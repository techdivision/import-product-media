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

use TechDivision\Import\Utils\FileUploadConfigurationKeys;
use TechDivision\Import\Utils\RegistryKeys;
use TechDivision\Import\Subjects\FileUploadTrait;
use TechDivision\Import\Subjects\ExportableTrait;
use TechDivision\Import\Subjects\FileUploadSubjectInterface;
use TechDivision\Import\Subjects\ExportableSubjectInterface;
use TechDivision\Import\Subjects\CleanUpColumnsSubjectInterface;
use TechDivision\Import\Product\Media\Utils\ConfigurationKeys;
use TechDivision\Import\Product\Subjects\AbstractProductSubject;

/**
 * The subject implementation for the product media handling.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MediaSubject extends AbstractProductSubject implements FileUploadSubjectInterface, CleanUpColumnsSubjectInterface, ExportableSubjectInterface
{

    /**
     * The trait that provides file upload functionality.
     *
     * @var \TechDivision\Import\Subjects\FileUploadTrait
     */
    use FileUploadTrait;

    /**
     * The trait that provides media import functionality.
     *
     * @var \TechDivision\Import\Product\Media\Subjects\MediaSubjectTrait
     */
    use MediaSubjectTrait;

    /**
     * The trait with the exportable functionality.
     *
     * @var \TechDivision\Import\Subjects\ExportableTrait
     */
    use ExportableTrait;

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
        $this->skuEntityIdMapping = isset($status[RegistryKeys::SKU_ENTITY_ID_MAPPING]) ? $status[RegistryKeys::SKU_ENTITY_ID_MAPPING] : array();

        // initialize media directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::MEDIA_DIRECTORY)) {
            try {
                $this->setMediaDir($this->resolvePath($this->getConfiguration()->getParam(FileUploadConfigurationKeys::MEDIA_DIRECTORY)));
            } catch (\InvalidArgumentException $iae) {
                // only if we wanna copy images we need directories
                if ($this->hasCopyImages()) {
                    $this->getSystemLogger()->warning($iae->getMessage());
                }
            }
        }

        // initialize images directory => can be absolute or relative
        if ($this->getConfiguration()->hasParam(FileUploadConfigurationKeys::IMAGES_FILE_DIRECTORY)) {
            try {
                $this->setImagesFileDir($this->resolvePath($this->getConfiguration()->getParam(FileUploadConfigurationKeys::IMAGES_FILE_DIRECTORY)));
            } catch (\InvalidArgumentException $iae) {
                // only if we wanna copy images we need directories
                if ($this->hasCopyImages()) {
                    $this->getSystemLogger()->warning($iae->getMessage());
                }
            }
        }
    }

    /**
     * Merge the columns from the configuration with all image type columns to define which
     * columns should be cleaned-up.
     *
     * @return array The columns that has to be cleaned-up
     */
    public function getCleanUpColumns()
    {

        // initialize the array for the columns that has to be cleaned-up
        $cleanUpColumns = array();

        // query whether or not an array has been specified in the configuration
        if ($this->getConfiguration()->hasParam(ConfigurationKeys::CLEAN_UP_EMPTY_COLUMNS)) {
            $cleanUpColumns = $this->getConfiguration()->getParam(ConfigurationKeys::CLEAN_UP_EMPTY_COLUMNS);
        }

        // return the array with the column names
        return $cleanUpColumns;
    }
}
