<?php

/**
 * TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
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

namespace TechDivision\Import\Product\Media\Services;

use TechDivision\Import\Product\Services\ProductProcessorInterface;

/**
 * Interface for product media processor implementations.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
interface ProductMediaProcessorInterface extends ProductProcessorInterface
{

    /**
     * Return's the raw entity loader instance.
     *
     * @return \TechDivision\Import\Loaders\LoaderInterface The raw entity loader instance
     */
    public function getRawEntityLoader();

    /**
     * Return's the repository to load product media gallery data.
     *
     * @return \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryRepositoryInterface The repository instance
     */
    public function getProductMediaGalleryRepository();

    /**
     * Return's the repository to load product media gallery value to entity data.
     *
     * @return \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueToEntityRepositoryInterface The repository instance
     */
    public function getProductMediaGalleryValueToEntityRepository();

    /**
     * Return's the repository to load product media gallery value data.
     *
     * @return \TechDivision\Import\Product\Media\Repositories\ProductMediaGalleryValueRepositoryInterface The repository instance
     */
    public function getProductMediaGalleryValueRepository();

    /**
     * Return's the action with the product media gallery CRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The action with the product media gallery CRUD methods
     */
    public function getProductMediaGalleryAction();

    /**
     * Return's the action with the product media gallery valueCRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface The action with the product media gallery value CRUD methods
     */
    public function getProductMediaGalleryValueAction();

    /**
     * Return's the action with the product media gallery value to entity CRUD methods.
     *
     * @return \TechDivision\Import\Dbal\Actions\ActionInterface $productMediaGalleryAction The action with the product media gallery value to entity CRUD methods
     */
    public function getProductMediaGalleryValueToEntityAction();

    /**
     * Load's and return's a raw entity without primary key but the mandatory members only and nulled values.
     *
     * @param string $entityTypeCode The entity type code to return the raw entity for
     * @param array  $data           An array with data that will be used to initialize the raw entity with
     *
     * @return array The initialized entity
     */
    public function loadRawEntity($entityTypeCode, array $data = array());

    /**
     * Load's the product media gallery with the passed attribute ID + value.
     *
     * @param integer $attributeId The attribute ID of the product media gallery to load
     * @param string  $value       The value of the product media gallery to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGallery($attributeId, $value);

    /**
     * Load's the product media gallery with the passed value/entity ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to entity to load
     * @param integer $entityId The entity ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGalleryValueToEntity($valueId, $entityId);

    /**
     * Load's the product media gallery value with the passed value/store/parent ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to load
     * @param string  $storeId  The store ID of the product media gallery value to load
     * @param string  $entityId The entity ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    public function loadProductMediaGalleryValue($valueId, $storeId, $entityId);

    /**
     * Load's the product media gallery entities with the passed SKU.
     *
     * @param string $sku The SKU to load the media gallery entities for
     *
     * @return array The product media gallery entities
     */
    public function getProductMediaGalleriesBySku($sku);

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array       $productMediaGallery The product media gallery data to persist
     * @param string|null $name                The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductMediaGallery($productMediaGallery, $name = null);

    /**
     * Persist's the passed product media gallery value data.
     *
     * @param array       $productMediaGalleryValue The product media gallery value data to persist
     * @param string|null $name                     The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductMediaGalleryValue($productMediaGalleryValue, $name = null);

    /**
     * Persist's the passed product media gallery value to entity data.
     *
     * @param array       $productMediaGalleryValuetoEntity The product media gallery value to entity data to persist
     * @param string|null $name                             The name of the prepared statement that has to be executed
     *
     * @return void
     */
    public function persistProductMediaGalleryValueToEntity($productMediaGalleryValuetoEntity, $name = null);

    /**
     * Delete's the passed product media gallery data.
     *
     * @param array       $row  The product media gallery data to be deleted
     * @param string|null $name The name of the prepared statement that has to be executed
     *
     * @return string The ID of the persisted entity
     */
    public function deleteProductMediaGallery(array $row, $name = null);
}
