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
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */

namespace TechDivision\Import\Product\Media\Subjects;

use TechDivision\Import\Subjects\AbstractSubject;
use TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface;

/**
 * A SLSB that handles the process to import product variants.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class MediaSubject extends AbstractSubject
{

    /**
     * The processor to write the necessary product media data.
     *
     * @var \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface
     */
    protected $productProcessor;

    /**
     * Set's the product media processor instance.
     *
     * @param \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface $productProcessor The product media processor instance
     *
     * @return void
     */
    public function setProductProcessor(ProductMediaProcessorInterface $productProcessor)
    {
        $this->productProcessor = $productProcessor;
    }

    /**
     * Return's the product media processor instance.
     *
     * @return \TechDivision\Import\Product\Media\Services\ProductMediaProcessorInterface The product media processor instance
     */
    public function getProductProcessor()
    {
        return $this->productProcessor;
    }
}
