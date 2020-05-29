<?php

/**
 * TechDivision\Import\Product\Media\Observers\MediaGalleryValueObserver
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

use TechDivision\Import\Utils\EntityStatus;
use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Utils\BackendTypeKeys;
use TechDivision\Import\Observers\StateDetectorInterface;
use TechDivision\Import\Observers\AttributeLoaderInterface;
use TechDivision\Import\Observers\DynamicAttributeObserverInterface;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;
use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Media\Utils\MemberNames;
use TechDivision\Import\Product\Media\Utils\EntityTypeCodes;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;
use TechDivision\Import\Observers\EntityMergers\EntityMergerInterface;

/**
 * Observer that creates/updates the product's media gallery value information.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaGalleryValueObserver extends AbstractProductImportObserver implements DynamicAttributeObserverInterface
{

    /**
     * The product media processor instance.
     *
     * @var \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
     */
    protected $productMediaProcessor;

    /**
     * The attribute loader instance.
     *
     * @var \TechDivision\Import\Observers\AttributeLoaderInterface
     */
    protected $attributeLoader;

    /**
     * The entity merger instance.
     *
     * @var \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface
     */
    protected $entityMerger;

    /**
     * Initialize the "dymanmic" columns.
     *
     * @var array
     */
    protected $columns = array(
        MemberNames::DISABLED => array(ColumnKeys::HIDE_FROM_PRODUCT_PAGE, BackendTypeKeys::BACKEND_TYPE_INT)
    );

    /**
     * Initialize the observer with the passed product media processor instance.
     *
     * @param \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface $productMediaProcessor The product media processor instance
     * @param \TechDivision\Import\Observers\AttributeLoaderInterface|null               $attributeLoader       The attribute loader instance
     * @param \TechDivision\Import\Observers\EntityMergers\EntityMergerInterface|null    $entityMerger          The entity merger instance
     * @param \TechDivision\Import\Observers\StateDetectorInterface|null                 $stateDetector         The state detector instance to use
     */
    public function __construct(
        ProductMediaProcessorInterface $productMediaProcessor,
        AttributeLoaderInterface $attributeLoader = null,
        EntityMergerInterface $entityMerger = null,
        StateDetectorInterface $stateDetector = null
    ) {

        // initialize the media processor and the dynamic attribute loader instance
        $this->productMediaProcessor = $productMediaProcessor;
        $this->attributeLoader = $attributeLoader;
        $this->entityMerger = $entityMerger;

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

        // initialize and persist the product media gallery value
        if ($this->hasChanges($productMediaGalleryValue = $this->initializeProductMediaGalleryValue($this->prepareDynamicAttributes()))) {
            $this->persistProductMediaGalleryValue($productMediaGalleryValue);
        }
    }

    /**
     * Merge's and return's the entity with the passed attributes and set's the
     * passed status.
     *
     * @param array       $entity        The entity to merge the attributes into
     * @param array       $attr          The attributes to be merged
     * @param string|null $changeSetName The change set name to use
     *
     * @return array The merged entity
     * @todo https://github.com/techdivision/import/issues/179
     */
    protected function mergeEntity(array $entity, array $attr, $changeSetName = null)
    {
        return array_merge(
            $entity,
            $this->entityMerger ? $this->entityMerger->merge($this, $entity, $attr) : $attr,
            array(EntityStatus::MEMBER_NAME => $this->detectState($entity, $attr, $changeSetName))
        );
    }

    /**
     * Appends the dynamic to the static attributes for the media type
     * gallery attributes and returns them.
     *
     * @return array The array with all available attributes
     */
    protected function prepareDynamicAttributes()
    {
        return array_merge($this->prepareAttributes(), $this->attributeLoader ? $this->attributeLoader->load($this, $this->columns) : array());
    }

    /**
     * Prepare the product media gallery value that has to be persisted.
     *
     * @return array The prepared product media gallery value attributes
     */
    protected function prepareAttributes()
    {

        try {
            // try to load the product SKU and map it the entity ID
            $parentId = $this->getValue(ColumnKeys::IMAGE_PARENT_SKU, null, array($this, 'mapParentSku'));
        } catch (\Exception $e) {
            throw $this->wrapException(array(ColumnKeys::IMAGE_PARENT_SKU), $e);
        }

        // load the store ID
        $storeId = $this->getRowStoreId(StoreViewCodes::ADMIN);

        // load the value ID
        $valueId = $this->getParentValueId();

        // load the image label
        $imageLabel = $this->getValue(ColumnKeys::IMAGE_LABEL);

        // load the position
        $position = (int) $this->getValue(ColumnKeys::IMAGE_POSITION, 0);

        // prepare the media gallery value
        return $this->initializeEntity(
            $this->loadRawEntity(
                array(
                    MemberNames::VALUE_ID    => $valueId,
                    MemberNames::STORE_ID    => $storeId,
                    MemberNames::ENTITY_ID   => $parentId,
                    MemberNames::LABEL       => $imageLabel,
                    MemberNames::POSITION    => $position
                )
            )
        );
    }

    /**
     * Load's and return's a raw customer entity without primary key but the mandatory members only and nulled values.
     *
     * @param array $data An array with data that will be used to initialize the raw entity with
     *
     * @return array The initialized entity
     */
    protected function loadRawEntity(array $data = array())
    {
        return $this->getProductMediaProcessor()->loadRawEntity(EntityTypeCodes::CATALOG_PRODUCT_MEDIA_GALLERY_VALUE, $data);
    }

    /**
     * Initialize the product media gallery value with the passed attributes and returns an instance.
     *
     * @param array $attr The product media gallery value attributes
     *
     * @return array The initialized product media gallery value
     */
    protected function initializeProductMediaGalleryValue(array $attr)
    {
        return $attr;
    }

    /**
     * Return's the store ID of the actual row, or of the default store
     * if no store view code is set in the CSV file.
     *
     * @param string|null $default The default store view code to use, if no store view code is set in the CSV file
     *
     * @return integer The ID of the actual store
     * @throws \Exception Is thrown, if the store with the actual code is not available
     */
    protected function getRowStoreId($default = null)
    {
        return $this->getSubject()->getRowStoreId($default);
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
     * Return's the value ID of the created media gallery entry.
     *
     * @return integer The ID of the created media gallery entry
     */
    protected function getParentValueId()
    {
        return $this->getSubject()->getParentValueId();
    }

    /**
     * Return's the store for the passed store code.
     *
     * @param string $storeCode The store code to return the store for
     *
     * @return array The requested store
     * @throws \Exception Is thrown, if the requested store is not available
     */
    protected function getStoreByStoreCode($storeCode)
    {
        return $this->getSubject()->getStoreByStoreCode($storeCode);
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     * @deprecated Since 23.0.0
     */
    protected function raisePositionCounter()
    {
        return $this->getSubject()->raisePositionCounter();
    }

    /**
     * Persist's the passed product media gallery value data.
     *
     * @param array $productMediaGalleryValue The product media gallery value data to persist
     *
     * @return void
     */
    protected function persistProductMediaGalleryValue($productMediaGalleryValue)
    {
        $this->getProductMediaProcessor()->persistProductMediaGalleryValue($productMediaGalleryValue);
    }
}
