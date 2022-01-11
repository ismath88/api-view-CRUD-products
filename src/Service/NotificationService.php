<?php

declare(strict_types=1);

namespace App\Service;

use App\Entity\BillPrintDocument;
use GuzzleHttp\Client;
use Psr\Log\LoggerInterface;

class NotificationService
{
    /**
     * @var LoggerInterface
     */
    private $logger;

    /**
     * @param LoggerInterface $logger
     */
    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }

    /**
     * sendBillNotification.
     *
     * @param BillPrintDocument|null $billPrintDocument
     * @param string                 $notificationType
     * @param array|null             $paymentDocument
     */
    public function sendNotification($billPrintDocument, $notificationType, $paymentDocument)
    {
        try {
            $cmsHost = \getenv('CMS_HOST');
            $authenticationApi = \getenv('CMS_AUTH_API');
            $billingApi = \getenv('CMS_BILLING_API');
            $requestBody['client_id'] = \getenv('CLIENT_ID');
            $requestBody['client_secret'] = \getenv('CLIENT_SECRET');
            $client = new Client(['base_uri' => $cmsHost]);
            $authOptions = [
                'body' => \json_encode($requestBody),
                'headers' => [
                    'Content-Type' => 'application/json',
                ],
            ];
            $response = $client->post($authenticationApi, $authOptions);
            $authResponse = \json_decode($response->getBody()->getContents(), true);
            if (200 === $response->getStatusCode()) {
                $noficationData['notification_type'] = $notificationType;
                if ($notificationType === \getenv('CMS_BILL_NOTIFICATION') && null !== $billPrintDocument) {
                    $noficationData['payload']['bill_no'] = $billPrintDocument->getDocumentNumber();
                    $noficationData['payload']['premise_id'] = $billPrintDocument->getPremise()->getId();
                    $noficationData['payload']['bill_print_document_id'] = $billPrintDocument->getId();
                    $noficationData['payload']['customer_id'] = $billPrintDocument->getCustomer()->getId();
                    $noficationData['payload']['unit_number'] = $billPrintDocument->getPremise()->getUnitNumber();
                    $noficationData['payload']['amount'] = $billPrintDocument->getCharges();
                    $noficationData['payload']['currency'] = (null !== $billPrintDocument->getBillingCurrency() ? $billPrintDocument->getBillingCurrency()->getCode() : 'USD');
                    $noficationData['payload']['due_date'] = (string) $billPrintDocument->getDocumentDate()->format('Ymd');
                } else {
                    $noficationData['payload']['payment_id'] = \substr($paymentDocument['@id'], 19);
                    $noficationData['payload']['document_number'] = $paymentDocument['documentNumber'];
                    $noficationData['payload']['customer_id'] = \substr($paymentDocument['customer']['@id'], 11);
                    $noficationData['payload']['currency'] = $paymentDocument['documentCurrency']['code'];
                    $noficationData['payload']['amount'] = $paymentDocument['amountInDocumentCurrency'];
                    $noficationData['payload']['payment_date'] = \strtok($paymentDocument['documentDate'], 'T');
                }
                $noficationOptions = [
                    'body' => \json_encode($noficationData),
                    'headers' => [
                        'X-Requested-With' => 'XMLHttpRequest',
                        'Content-Type' => 'application/json',
                        'Authorization' => $authResponse['token_type'].' '.$authResponse['access_token'],
                    ],
                ];
                $notificationResponse = $client->post($billingApi, $noficationOptions);
                $notificationStatus = $notificationResponse->getBody()->getContents();
            }
        } catch (\Exception $e) {
            $this->logger->error('NOTIFICATION_ERROR_'.\strtoupper($notificationType).' : '.\sprintf($e->getMessage()));
        }
    }
}
