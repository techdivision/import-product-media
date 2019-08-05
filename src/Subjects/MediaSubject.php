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
use TechDivision\Import\Subjects\FileUploadTrait;
use TechDivision\Import\Subjects\FileUploadSubjectInterface;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Media\Exceptions\MapSkuToEntityIdException;

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
     * Intializes the previously loaded global data for exactly one variants.
     *
     * @param string $serial The serial of the actual import
     *
     * @return void
     */
    public function setUp($serial)
    {

        // invoke parent method
        parent::setUp($serial);

        // load the entity manager and the registry processor
        $registryProcessor = $this->getRegistryProcessor();

        // load the status of the actual import process
        $status = $registryProcessor->getAttribute(RegistryKeys::STATUS);

        // load the SKU => entity ID mapping
        $this->skuEntityIdMapping = $status[RegistryKeys::SKU_ENTITY_ID_MAPPING];

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
        $this->positionCounter = 0;
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
}
