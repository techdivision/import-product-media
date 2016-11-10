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
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Media\Observers;

use TechDivision\Import\Utils\StoreViewCodes;
use TechDivision\Import\Product\Utils\MemberNames;
use TechDivision\Import\Product\Media\Utils\ColumnKeys;
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * A SLSB that handles the process to import product media.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class MediaGalleryValueObserver extends AbstractProductImportObserver
{

    /**
     * {@inheritDoc}
     * @see \Importer\Csv\Actions\Listeners\Row\ListenerInterface::handle()
     */
    public function handle(array $row)
    {

        // load the header information
        $headers = $this->getHeaders();

        // load the product SKU
        $parentSku = $row[$headers[ColumnKeys::IMAGE_PARENT_SKU]];

        // load parent/option ID
        $parentId = $this->mapSkuToEntityId($parentSku);

        // initialize the store view code
        $storeViewCode = $row[$headers[ColumnKeys::STORE_VIEW_CODE]] ?: StoreViewCodes::ADMIN;

        // load the store ID
        $store = $this->getStoreByStoreCode($storeViewCode);
        $storeId = $store[MemberNames::STORE_ID];

        // load the value ID and the position counter
        $valueId = $this->getParentValueId();
        $position = $this->raisePositionCounter();

        // load the image label
        $imageLabel = $row[$headers[ColumnKeys::IMAGE_LABEL]];

        // initialize the disabled flag
        $disabled = 0;

        // prepare the media gallery value
        $productMediaGalleryValue = array(
            $valueId,
            $storeId,
            $parentId,
            $imageLabel,
            $position,
            $disabled
        );

        // persist the product media gallery value
        $this->persistProductMediaGalleryValue($productMediaGalleryValue);

        // returns the row
        return $row;
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToEntityId($sku)
    {
        return $this->getSubject()->mapSkuToEntityId($sku);
    }

    /**
     * Return's the value ID of the created media gallery entry.
     *
     * @return integer The ID of the created media gallery entry
     */
    public function getParentValueId()
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
    public function getStoreByStoreCode($storeCode)
    {
        return $this->getSubject()->getStoreByStoreCode($storeCode);
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    public function raisePositionCounter()
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
    public function persistProductMediaGalleryValue($productMediaGalleryValue)
    {
        $this->getSubject()->persistProductMediaGalleryValue($productMediaGalleryValue);
    }
}
