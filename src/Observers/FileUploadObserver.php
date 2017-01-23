<?php

/**
 * TechDivision\Import\Product\Media\Observers\FileUploadObserver
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
use TechDivision\Import\Product\Observers\AbstractProductImportObserver;

/**
 * Observer that uploads the file specified in a CSV file's column 'image_path' to a
 * configurable directoy.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class FileUploadObserver extends AbstractProductImportObserver
{

    /**
     * Process the observer's business logic.
     *
     * @return array The processed row
     */
    protected function process()
    {

        // query whether or not, the image changed
        if ($this->isParentImage($image = $this->getValue(ColumnKeys::IMAGE_PATH))) {
            return;
        }

        // initialize the image path
        $imagePath = $this->getValue(ColumnKeys::IMAGE_PATH);

        // query whether or not we've to upload the image files
        if ($this->hasCopyImages()) {
            // upload the file and set the new image path
            $imagePath = $this->uploadFile($image);
        }

        // temoprarily persist the image path
        $this->setValue(ColumnKeys::IMAGE_PATH_NEW, $imagePath);
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
    protected function uploadFile($filename)
    {
        return $this->getSubject()->uploadFile($filename);
    }

    /**
     * Return's the name of the created image.
     *
     * @return string The name of the created image
     */
    protected function getParentImage()
    {
        return $this->getSubject()->getParentImage();
    }

    /**
     * Return's TRUE if the passed image is the parent one.
     *
     * @param string $image The imageD to check
     *
     * @return boolean TRUE if the passed image is the parent one
     */
    protected function isParentImage($image)
    {
        return $this->getParentImage() === $image;
    }

    /**
     * Return's the flag to copy images or not.
     *
     * @return booleas The flag
     */
    protected function hasCopyImages()
    {
        return $this->getSubject()->hasCopyImages();
    }
}
