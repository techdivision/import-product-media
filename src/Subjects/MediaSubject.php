<?php

/**
 * TechDivision\Import\Product\Media\Subjects\MediaSubject
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

namespace TechDivision\Import\Product\Media\Subjects;

use League\Flysystem\Filesystem;
use League\Flysystem\Adapter\Local;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Subjects\FileUploadTrait;
use TechDivision\Import\Subjects\FileUploadSubjectInterface;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;
use TechDivision\Import\Product\Media\Observers\MapSkuToEntityIdException;

/**
 * The subject implementation for the product media handling.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaSubject extends AbstractProductSubject implements FileUploadSubjectInterface
{

    /**
     * The trait that provides file upload functionality.
     *
     * @var \TechDivision\Import\Subjects\FileUploadTrait
     */
    use FileUploadTrait;

    /**
     * The ID of the parent product to relate the variant with.
     *
     * @var integer
     */
    protected $parentId;

    /**
     * The value ID of the created media gallery entry.
     *
     * @var integer
     */
    protected $parentValueId;

    /**
     * The Magento installation directory.
     *
     * @var string
     */
    protected $installationDir;

    /**
     * The position counter, if no position for the product media gallery value has been specified.
     *
     * @var integer
     */
    protected $positionCounter = 1;

    /**
     * The available stores.
     *
     * @var array
     */
    protected $stores = array();

    /**
     * The mapping for the SKUs to the created entity IDs.
     *
     * @var array
     */
    protected $skuEntityIdMapping = array();

    /**
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     * @see \Importer\Csv\Actions\ProductImportAction::prepare()
     */
    public function setUp($serial)
    {

        // invoke parent method
        parent::setUp($serial);

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute($serial);

        // load the attribute set we've prepared intially
        $this->skuEntityIdMapping = $status[RegistryKeys::SKU_ENTITY_ID_MAPPING];

        // initialize the flag to decide copy images or not
        $this->setCopyImages($this->getConfiguration()->getParam(ConfigurationKeys::COPY_IMAGES));

        // initialize the filesystems root directory
        $this->setRootDir($this->getConfiguration()->getParam(ConfigurationKeys::ROOT_DIRECTORY, getcwd()));

        // initialize the filesystem
        $this->setFilesystem(new Filesystem(new Local($this->getRootDir())));

        // initialize media directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::MEDIA_DIRECTORY)) {
            $this->setMediaDir(
                $this->resolvePath(
                    $this->getConfiguration()->getParam(ConfigurationKeys::MEDIA_DIRECTORY)
                )
            );
        }

        // initialize images directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::IMAGES_FILE_DIRECTORY)) {
            $this->setImagesFileDir(
                $this->resolvePath(
                    $this->getConfiguration()->getParam(ConfigurationKeys::IMAGES_FILE_DIRECTORY)
                )
            );
        }
    }

    /**
     * Set's the ID of the parent product to relate the variant with.
     *
     * @param integer $parentId The ID of the parent product
     *
     * @return void
     */
    public function setParentId($parentId)
    {
        $this->parentId = $parentId;
    }

    /**
     * Return's the ID of the parent product to relate the variant with.
     *
     * @return integer The ID of the parent product
     */
    public function getParentId()
    {
        return $this->parentId;
    }

    /**
     * Set's the value ID of the created media gallery entry.
     *
     * @param integer $parentValueId The ID of the created media gallery entry
     *
     * @return void
     */
    public function setParentValueId($parentValueId)
    {
        $this->parentValueId  = $parentValueId;
    }

    /**
     * Return's the value ID of the created media gallery entry.
     *
     * @return integer The ID of the created media gallery entry
     */
    public function getParentValueId()
    {
        return $this->parentValueId;
    }

    /**
     * Reset the position counter to 1.
     *
     * @return void
     */
    public function resetPositionCounter()
    {
        $this->positionCounter = 1;
    }

    /**
     * Returns the acutal value of the position counter and raise's it by one.
     *
     * @return integer The actual value of the position counter
     */
    public function raisePositionCounter()
    {
        return $this->positionCounter++;
    }

    /**
     * Return the entity ID for the passed SKU.
     *
     * @param string $sku The SKU to return the entity ID for
     *
     * @return integer The mapped entity ID
     * @throws \TechDivision\Import\Product\Media\Exceptions\MapSkuToEntityIdException Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToEntityId($sku)
    {

        // query weather or not the SKU has been mapped
        if (isset($this->skuEntityIdMapping[$sku])) {
            return $this->skuEntityIdMapping[$sku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new MapSkuToEntityIdException(
            $this->appendExceptionSuffix(
                sprintf('Found not mapped entity ID for SKU %s', $sku)
            )
        );
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

        // query whether or not the store with the passed store code exists
        if (isset($this->stores[$storeCode])) {
            return $this->stores[$storeCode];
        }

        // throw an exception, if not
        throw new \Exception(
            $this->appendExceptionSuffix(
                sprintf('Found invalid store code %s', $storeCode)
            )
        );
    }

    /**
     * Load's the product media gallery with the passed attribute ID + value.
     *
     * @param integer $attributeId The attribute ID of the product media gallery to load
     * @param string  $value       The value of the product media gallery to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGallery($attributeId, $value)
    {
        return $this->getProductProcessor()->loadProductMediaGallery($attributeId, $value);
    }

    /**
     * Load's the product media gallery with the passed value/entity ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to entity to load
     * @param integer $entityId The entity ID of the product media gallery value to entity to load
     *
     * @return array The product media gallery
     */
    public function loadProductMediaGalleryValueToEntity($valueId, $entityId)
    {
        return $this->getProductProcessor()->loadProductMediaGalleryValueToEntity($valueId, $entityId);
    }

    /**
     * Load's the product media gallery value with the passed value/store/parent ID.
     *
     * @param integer $valueId  The value ID of the product media gallery value to load
     * @param string  $storeId  The store ID of the product media gallery value to load
     * @param string  $entityId The entity ID of the parent product of the product media gallery value to load
     *
     * @return array The product media gallery value
     */
    public function loadProductMediaGalleryValue($valueId, $storeId, $entityId)
    {
        $this->getProductProcessor()->loadProductMediaGalleryValue($valueId, $storeId, $entityId);
    }

    /**
     * Persist's the passed product media gallery data and return's the ID.
     *
     * @param array $productMediaGallery The product media gallery data to persist
     *
     * @return string The ID of the persisted entity
     */
    public function persistProductMediaGallery($productMediaGallery)
    {
        return $this->getProductProcessor()->persistProductMediaGallery($productMediaGallery);
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
        $this->getProductProcessor()->persistProductMediaGalleryValue($productMediaGalleryValue);
    }

    /**
     * Persist's the passed product media gallery value to entity data.
     *
     * @param array $productMediaGalleryValuetoEntity The product media gallery value to entity data to persist
     *
     * @return void
     */
    public function persistProductMediaGalleryValueToEntity($productMediaGalleryValuetoEntity)
    {
        $this->getProductProcessor()->persistProductMediaGalleryValueToEntity($productMediaGalleryValuetoEntity);
    }

    /**
     * Persist's the passed product media gallery value video data.
     *
     * @param array $productMediaGalleryValueVideo The product media gallery value video data to persist
     *
     * @return void
     */
    public function persistProductMediaGalleryValueVideo($productMediaGalleryValueVideo)
    {
        $this->getProductProcessor()->persistProductMediaGalleryValueVideo($productMediaGalleryValueVideo);
    }
}
