<?php

declare(strict_types=1);

namespace App\Doctrine\DBAL\Types;

use App\Enum\ActivityType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Type;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProductBlobType extends Bundle
{
    /**
     * Type name.
     */
    const NAME = 'product_blob_type';

   public function boot()
    {
        $em = $this->container->get('doctrine.orm.entity_manager');
        if (!Type::hasType('blob') 
        {
            Type::addType('blob', '..\BlobType');
            $em->getConnection()->getDatabasePlatform()->registerDoctrineTypeMapping('blob','blob');
        }        
    }
}
