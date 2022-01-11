<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiProperty;
use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\DBAL\Types\ProductBlobType;

/**
 * The country.
 *
 * @ORM\Entity
 * @ApiResource(attributes={
 *     "normalization_context"={"groups"={"product_read"}},
 *     "denormalization_context"={"groups"={"product_write"}}
 * })
 */
class Product
{
    use Traits\BlameableTrait,
        Traits\TimestampableTrait;

    /**
     * @var int|null
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    
    /**
     * @var string product name
     *
     * @ORM\Column(type="string", length=50, nullable=false)
     * @ApiProperty()
     */
    private $name;
    
    /**
     * @var string product price
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     * @ApiProperty()
     */
    private $price;
    
    /**
     * @var string universal price code
     *
     * @ORM\Column(type="string", length=30, unique=true, nullable=false)
     * @ApiProperty()
     */
    private $upc;

    /**
     * @var string product status
     *
     * @ORM\Column(type="string", length=30, nullable=false)
     * @ApiProperty()
     */
    private $status;
    
    /**
     * @var string product image
     *
     * @ORM\Column(type="BLOB", nullable=true)
     * @ApiProperty()
     */
    private $prodimage;

    /**
     * Gets id.
     *
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

   /**
     * Gets name 
     *
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * Sets name.
     *
     * @param string $name
     *
     * @return self
     */
    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }
    
    /**
     * Gets price.
     *
     * @return string
     */
    public function getPrice(): ?string
    {
        return $this->price;
    }

    /**
     * Sets price.
     *
     * @param string $price
     *
     * @return self
     */
    public function setPrice(?string $price): self
    {
        $this->price = $price;

        return $this;
    }
    
    /**
     * Gets upc.
     *
     * @return string
     */
    public function getUpc(): ?string
    {
        return $this->price;
    }

    /**
     * Sets upc.
     *
     * @param string $upc
     *
     * @return self
     */
    public function setUpc(?string $upc): self
    {
        $this->upc = $upc;

        return $this;
    }
    
    /**
     * Gets status.
     *
     * @return string
     */
    public function getStatus(): ?string
    {
        return $this->status;
    }

    /**
     * Sets stats.
     *
     * @param string $status
     *
     * @return self
     */
    public function setStatus(?string $status): self
    {
        $this->status = $status;

        return $this;
    }

    /**
    * Set prodimage
    *
    * @param /post/blob $prodimage
    *
    * @return Post
    */
    public function setProdimage($prodimage)
    {
        $this->prodimage = $prodimage;

        return $this;
    }

    /**
     * Get prodimage
     *
     * @return post/blob
     */
    public function getimage()
    {
        return $this->prodimage;
    }
}
