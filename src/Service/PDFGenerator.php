<?php

declare(strict_types=1);

namespace App\Service;

use GuzzleHttp\Client as GuzzleClient;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class PDFGenerator
{
    /**
     * @var GuzzleClient
     */
    private $client;

    /**
     * @var string
     */
    private $pdfMicroserviceHost;

    /**
     * @param string $pdfMicroserviceHost
     */
    public function __construct($pdfMicroserviceHost)
    {
        $this->client = new GuzzleClient(['base_uri' => $pdfMicroserviceHost, 'http_errors' => false]);
    }

    /**
     * generetePaymentReceipt.
     *
     * @param array $jsonPaymentReceipt
     */
    public function generetePaymentReceipt($jsonPaymentReceipt)
    {
        try {
            $options = [
                'body' => \json_encode($jsonPaymentReceipt),
                'headers' => [
                    'User-Agent' => 'U-Centric API',
                    'Content-Type' => 'application/json',
                ],
            ];
            $response = $this->client->post('api/v1/converter/pdf/payment-receipt', $options);
            $paymentReceiptResult = $response->getBody()->getContents();

            if (200 !== $response->getStatusCode()) {
                throw new BadRequestHttpException('Unable to convert into PDF');
            }

            return $paymentReceiptResult;
        } catch (\Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }
}
