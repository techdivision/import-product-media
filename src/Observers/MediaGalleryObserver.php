<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryObserver
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Observers\StateDetectorInterface;

/**
 * Observer that creates/updates the product's media gallery information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryObserver extends AbstractProductImportObserver
{

    /**
     * The media gallery attribute code.
     *
     * @var string
     */
    const ATTRIBUTE_CODE = 'media_gallery';

    /**
     * The ID of the parent product the media is related to.
     *
     * @var integer
     */
    protected $parentId;

    /**
     * The ID of the persisted media gallery entity.
     *
     * @var integer
     */
    protected $valueId;

    /**
     * The product media processor instance.
     *
     * @var \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
     */
    protected $productMediaProcessor;

    /**
     * Initialize the observer with the passed product media processor instance.
     *
     * @param \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface $productMediaProcessor The product media processor instance
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null                 $stateDetector         The state detector instance to use
     */
    public function __construct(ProductMediaProcessorInterface $productMediaProcessor, StateDetectorInterface $stateDetector = null)
    {

        // initialize the media processor instance
        $this->productMediaProcessor = $productMediaProcessor;

        // pass the state detector to the parent method
        parent::__construct($stateDetector);
    }

    /**
     * Return's the product media processor instance.
     *
     * @return \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface The product media processor instance
     */
    protected function getProductMediaProcessor()
    {
        return $this->productMediaProcessor;
    }

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // try to load the product SKU and map it the entity ID and
        $this->parentId = $this->getValue(ColumnKeys::IMAGE_PARENT_SKU, null, array($this, 'mapParentSku'));

        // reset the position counter, if either a new PK or store view code has been found
        if (!$this->isParentStoreViewCode($this->getValue(ColumnKeys::STORE_VIEW_CODE, $this->getDefaultStoreViewCode())) ||
            !$this->isParentId($this->parentId)
        ) {
            $this->resetPositionCounter();
        }

        // prepare the actual store view code
        $this->prepareStoreViewCode($this->getRow());

        // initialize and persist the product media gallery
        if ($this->hasChanges($productMediaGallery = $this->initializeProductMediaGallery($this->prepareProductMediaGalleryAttributes()))) {
            // persist the media gallery data and temporarily persist value ID
            $this->setParentValueId($this->valueId = $this->persistProductMediaGallery($productMediaGallery));
            // persist the product media gallery to entity data
            if ($productMediaGalleryValueToEntity = $this->initializeProductMediaGalleryValueToEntity($this->prepareProductMediaGalleryValueToEntityAttributes())) {
                $this->persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity);
            }
        }

        // temporarily persist parent ID
        $this->setParentId($this->parentId);
    }

    /**
     * Prepare the product media gallery that has to be persisted.
     *
     * @return array The prepared product media gallery attributes
     */
    protected function prepareProductMediaGalleryAttributes()
    {

        // load the attribute ID of the media gallery EAV attribute
        $mediaGalleryAttribute = $this->getEavAttributeByAttributeCode(MediaGalleryObserver::ATTRIBUTE_CODE);
        $attributeId = $mediaGalleryAttribute[MemberNames::ATTRIBUTE_ID];

        // initialize the gallery data
        $disabled = 0;
        $mediaType = $this->getValue(ColumnKeys::MEDIA_TYPE, 'image');
        $image = $this->getValue(ColumnKeys::IMAGE_PATH_NEW);

        // initialize and return the entity
        return $this->initializeEntity(
            array(
                MemberNames::ATTRIBUTE_ID => $attributeId,
                MemberNames::VALUE        => $image,
                MemberNames::MEDIA_TYPE   => $mediaType,
                MemberNames::DISABLED     => $disabled
            )
        );
    }

    /**
     * Prepare the product media gallery value to entity that has to be persisted.
     *
     * @return array The prepared product media gallery value to entity attributes
     */
    protected function prepareProductMediaGalleryValueToEntityAttributes()
    {

        // initialize and return the entity
        return $this->initializeEntity(
            array(
                MemberNames::VALUE_ID  => $this->valueId,
                MemberNames::ENTITY_ID => $this->parentId
            )
        );
    }

    /**
     * Initialize the product media gallery with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery attributes
     *
     * @return array The initialized product media gallery
     */
    protected function initializeProductMediaGallery(array $attr)
    {
        return $attr;
    }

    /**
     * Initialize the product media gallery value to entity with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value to entity attributes
     *
     * @return array|null The initialized product media gallery value to entity, or NULL if the product media gallery value to entity already exists
     */
    protected function initializeProductMediaGalleryValueToEntity(array $attr)
    {
        return $attr;
    }

    /**
     * Map's the passed SKU of the parent product to it's PK.
     *
     * @param string $parentSku The SKU of the parent product
     *
     * @return integer The primary key used to create relations
     */
    protected function mapParentSku($parentSku)
    {
        return $this->mapSkuToEntityId($parentSku);
    }

    /**
     * Set's the value ID of the created media gallery entry.
     *
     * @param integer $parentValueId The ID of the created media gallery entry
     *
     * @return void
     */
    protected function setParentValueId($parentValueId)
    {
        $this->getSubject()->setParentValueId($parentValueId);
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    protected function mapSkuToEntityId($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Set's the ID of the parent product to relate the variant with.
     *
     * @param integer $parentId The ID of the parent product
     *
     * @return void
     */
    protected function setParentId($parentId)
    {
        $this->getSubject()->setParentId($parentId);
    }

    /**
     * Return's the ID of the parent product to relate the variant with.
     *
     * @return integer The ID of the parent product
     */
    protected function getParentId()
    {
        return $this->getSubject()->getParentId();
    }

    /**
     * Query whether or not this is the parent ID.
     *
     * @param integer $parentId The PK of the parent image
     *
     * @return boolean TRUE if the PK euqals, else FALSE
     */
    protected function isParentId($parentId)
    {
        return $this->getParentId() === $parentId;
    }

    /**
     * Query whether or not this is the parent store view code.
     *
     * @param string $storeViewCode The actual store view code
     *
     * @return boolean TRUE if the store view code equals, else FALSE
     */
    protected function isParentStoreViewCode($storeViewCode)
    {
        return $this->getStoreViewCode() === $storeViewCode;
    }

    /**
     * Return's the default store view code.
     *
     * @return array The default store view code
     */
    protected function getDefaultStoreViewCode()
    {
        return $this->getSubject()->getDefaultStoreViewCode();
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    protected function resetPositionCounter()
    {
        $this->getSubject()->resetPositionCounter();
    }

    /**
     * Return's the EAV attribute with the passed attribute code.
     *
     * @param string $attributeCode The attribute code
     *
     * @return array The array with the EAV attribute
     * @throws \Exception Is thrown if the attribute with the passed code is not available
     */
    protected function getEavAttributeByAttributeCode($attributeCode)
    {
        return $this->getSubject()->getEavAttributeByAttributeCode($attributeCode);
    }

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array $productMediaGallery The product media gallery data to persist
     *
     * @return string The ID of the persisted entity
     */
    protected function persistProductMediaGallery($productMediaGallery)
    {
        return $this->getProductMediaProcessor()->persistProductMediaGallery($productMediaGallery);
    }

    /**
     * Persist's the passed product media gallery value to entity data.
     *
     * @param array $productMediaGalleryValueToEntity The product media gallery value to entity data to persist
     *
     * @return void
     */
    protected function persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity)
    {
        $this->getProductMediaProcessor()->persistProductMediaGalleryValueToEntity($productMediaGalleryValueToEntity);
    }
}
