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

use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;

/**
 * A SLSB that handles the process to import product variants.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaSubject extends AbstractProductSubject
{

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
     * The name of the craeted image.
     *
     * @var integer
     */
    protected $parentImage;

    /**
     * The Magento installation directory.
     *
     * @var string
     */
    protected $installationDir;

    /**
     * The directory with the Magento media files => target directory for images (relative to the root directory).
     *
     * @var string
     */
    protected $mediaDir;

    /**
     * The directory with the images that have to be imported (relative to the root directory).
     *
     * @var string
     */
    protected $imagesFileDir;

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
     * @return void
     * @see \Importer\Csv\Actions\ProductImportAction::prepare()
     */
    public function setUp()
    {

        // invoke parent method
        parent::setUp();

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute($this->getSerial());

        // load the attribute set we've prepared intially
        $this->skuEntityIdMapping = $status[RegistryKeys::SKU_ENTITY_ID_MAPPING];

        // load the Magento installation directory
        $this->setInstallationDir($this->getConfiguration()->getConfiguration()->getInstallationDir());

        // initialize media/and images directory => can be absolute or relative
        $this->setMediaDir($this->resolvePath($this->getConfiguration()->getParam(ConfigurationKeys::MEDIA_DIRECTORY)));
        $this->setImagesFileDir($this->resolvePath($this->getConfiguration()->getParam(ConfigurationKeys::IMAGES_FILE__DIRECTORY)));
    }

    /**
     * Set's the Magento installation directory.
     *
     * @param string $installationDir The Magento installation directory
     *
     * @return void
     */
    public function setInstallationDir($installationDir)
    {
        $this->installationDir = $installationDir;
    }

    /**
     * Return's the Magento installation directory.
     *
     * @return string The Magento installation directory
     */
    public function getInstallationDir()
    {
        return $this->installationDir;
    }

    /**
     * Set's directory with the Magento media files => target directory for images.
     *
     * @param string $mediaDir The directory with the Magento media files => target directory for images
     *
     * @return void
     */
    public function setMediaDir($mediaDir)
    {
        $this->mediaDir = $mediaDir;
    }

    /**
     * Return's the directory with the Magento media files => target directory for images.
     *
     * @return string The directory with the Magento media files => target directory for images
     */
    public function getMediaDir()
    {
        return $this->mediaDir;
    }

    /**
     * Set's directory with the images that have to be imported.
     *
     * @param string $imagesFileDir The directory with the images that have to be imported
     *
     * @return void
     */
    public function setImagesFileDir($imagesFileDir)
    {
        $this->imagesFileDir = $imagesFileDir;
    }

    /**
     * Return's the directory with the images that have to be imported.
     *
     * @return string The directory with the images that have to be imported
     */
    public function getImagesFileDir()
    {
        return $this->imagesFileDir;
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
     * Set's the name of the created image.
     *
     * @param string $parentImage The name of the created image
     *
     * @return void
     */
    public function setParentImage($parentImage)
    {
        $this->parentImage = $parentImage;
    }

    /**
     * Return's the name of the created image.
     *
     * @return string The name of the created image
     */
    public function getParentImage()
    {
        return $this->parentImage;
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
     * @throws \Exception Is thrown if the SKU is not mapped yet
     */
    public function mapSkuToEntityId($sku)
    {

        // query weather or not the SKU has been mapped
        if (isset($this->skuEntityIdMapping[$sku])) {
            return $this->skuEntityIdMapping[$sku];
        }

        // throw an exception if the SKU has not been mapped yet
        throw new \Exception(sprintf('Found not mapped SKU %s', $sku));
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
        throw new \Exception(sprintf('Found invalid store code %s', $storeCode));
    }

    /**
     * Upload's the file with the passed name to the Magento
     * media directory. If the file already exists, the will
     * be given a new name that will be returned.
     *
     * @param string $filename The name of the file to be uploaded
     *
     * @return string The name of the uploaded file
     */
    public function uploadFile($filename)
    {

        // prepare source/target filename
        $sourceFilename = sprintf('%s%s', $this->getImagesFileDir(), $filename);
        $targetFilename = sprintf('%s%s', $this->getMediaDir(), $filename);

        // query whether or not the image file to be imported is available
        if (!$this->getFilesystem()->has($sourceFilename)) {
            $this->getSystemLogger()->info(sprintf('Media file %s not available', $sourceFilename));
            return;
        }

        // prepare the target filename, if necessary
        $newTargetFilename = $this->getNewFileName($targetFilename);
        $targetFilename = str_replace(basename($targetFilename), $newTargetFilename, $targetFilename);

        // copy the image to the target directory
        $this->getFilesystem()->copy($sourceFilename, $targetFilename);

        // return the new target filename
        return str_replace($this->getMediaDir(), '', $targetFilename);
    }

    /**
     * Get new file name if the same is already exists.
     *
     * @param string $targetFilename The name of the exisising files
     *
     * @return string The new filename
     */
    public function getNewFileName($targetFilename)
    {

        // load the file information
        $fileInfo = pathinfo($targetFilename);

        // query whether or not, the file exists
        if ($this->getFilesystem()->has($targetFilename)) {
            // initialize the incex and the basename
            $index = 1;
            $baseName = $fileInfo['filename'] . '.' . $fileInfo['extension'];

            // prepare the new filename by raising the index
            while ($this->getFilesystem()->has($fileInfo['dirname'] . '/' . $baseName)) {
                $baseName = $fileInfo['filename'] . '_' . $index . '.' . $fileInfo['extension'];
                $index++;
            }

            // set the new filename
            $targetFilename = $baseName;

        } else {
            // if not, simply return the filename
            return $fileInfo['basename'];
        }

        // return the new filename
        return $targetFilename;
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
