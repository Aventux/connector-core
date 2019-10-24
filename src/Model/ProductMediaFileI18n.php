<?php
/**
 * @copyright 2010-2015 JTL-Software GmbH
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 */

namespace Jtl\Connector\Core\Model;

use InvalidArgumentException;
use JMS\Serializer\Annotation as Serializer;

/**
 * Locale specific mediafile name + description.
 *
 * @access public
 * @package Jtl\Connector\Core\Model
 * @subpackage Product
 * @Serializer\AccessType("public_method")
 */
class ProductMediaFileI18n extends AbstractI18n
{
    /**
     * @var Identity Reference to mediaFile
     * @Serializer\Type("Jtl\Connector\Core\Model\Identity")
     * @Serializer\SerializedName("productMediaFileId")
     * @Serializer\Accessor(getter="getProductMediaFileId",setter="setProductMediaFileId")
     */
    protected $productMediaFileId = null;
    
    /**
     * @var string Locale specific description
     * @Serializer\Type("string")
     * @Serializer\SerializedName("description")
     * @Serializer\Accessor(getter="getDescription",setter="setDescription")
     */
    protected $description = '';

    /**
     * @var string Locale specific name
     * @Serializer\Type("string")
     * @Serializer\SerializedName("name")
     * @Serializer\Accessor(getter="getName",setter="setName")
     */
    protected $name = '';
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->productMediaFileId = new Identity();
    }
    
    /**
     * @param Identity $productMediaFileId Reference to mediaFile
     * @return ProductMediaFileI18n
     * @throws InvalidArgumentException if the provided argument is not of type 'Identity'.
     */
    public function setProductMediaFileId(Identity $productMediaFileId): ProductMediaFileI18n
    {
        $this->productMediaFileId = $productMediaFileId;
        
        return $this;
    }
    
    /**
     * @return Identity Reference to mediaFile
     */
    public function getProductMediaFileId(): Identity
    {
        return $this->productMediaFileId;
    }
    
    /**
     * @param string $description Locale specific description
     * @return ProductMediaFileI18n
     */
    public function setDescription(string $description): ProductMediaFileI18n
    {
        $this->description = $description;
        
        return $this;
    }
    
    /**
     * @return string Locale specific description
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @param string $name Locale specific name
     * @return ProductMediaFileI18n
     */
    public function setName(string $name): ProductMediaFileI18n
    {
        $this->name = $name;
        
        return $this;
    }
    
    /**
     * @return string Locale specific name
     */
    public function getName(): string
    {
        return $this->name;
    }
}
