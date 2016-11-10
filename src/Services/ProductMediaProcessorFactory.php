<?php

/**
 * TechDivision\Import\Product\Media\Services\ProductMediaProcessorFactory
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

namespace TechDivision\Import\Product\Media\Services;

use TechDivision\Import\ConfigurationInterface;
use TechDivision\Import\Product\Media\Actions\Processors\ProductMediaGalleryPersistProcessor;
use TechDivision\Import\Product\Media\Actions\Processors\ProductMediaGalleryValuePersistProcessor;
use TechDivision\Import\Product\Media\Actions\Processors\ProductMediaGalleryValueVideoPersistProcessor;
use TechDivision\Import\Product\Media\Actions\Processors\ProductMediaGalleryValueToEntityPersistProcessor;
use TechDivision\Import\Product\Media\Actions\ProductMediaGalleryAction;
use TechDivision\Import\Product\Media\Actions\ProductMediaGalleryValueAction;
use TechDivision\Import\Product\Media\Actions\ProductMediaGalleryValueVideoAction;
use TechDivision\Import\Product\Media\Actions\ProductMediaGalleryValueToEntityAction;

/**
 * A SLSB providing methods to load product data using a PDO connection.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class ProductMediaProcessorFactory
{

    /**
     * Factory method to create a new product media processor instance.
     *
     * @param \PDO                                       $connection    The PDO connection to use
     * @param TechDivision\Import\ConfigurationInterface $configuration The subject configuration
     *
     * @return \TechDivision\Import\Product\Media\Services\ProductMediaProcessor The processor instance
     */
    public function factory(\PDO $connection, ConfigurationInterface $configuration)
    {

        // extract Magento edition/version
        $magentoEdition = $configuration->getMagentoEdition();
        $magentoVersion = $configuration->getMagentoVersion();

        // initialize the action that provides product media gallery CRUD functionality
        $productMediaGalleryPersistProcessor = new ProductMediaGalleryPersistProcessor();
        $productMediaGalleryPersistProcessor->setMagentoEdition($magentoEdition);
        $productMediaGalleryPersistProcessor->setMagentoVersion($magentoVersion);
        $productMediaGalleryPersistProcessor->setConnection($connection);
        $productMediaGalleryPersistProcessor->init();
        $productMediaGalleryAction = new ProductMediaGalleryAction();
        $productMediaGalleryAction->setPersistProcessor($productMediaGalleryPersistProcessor);

        // initialize the action that provides product media gallery value CRUD functionality
        $productMediaGalleryValuePersistProcessor = new ProductMediaGalleryValuePersistProcessor();
        $productMediaGalleryValuePersistProcessor->setMagentoEdition($magentoEdition);
        $productMediaGalleryValuePersistProcessor->setMagentoVersion($magentoVersion);
        $productMediaGalleryValuePersistProcessor->setConnection($connection);
        $productMediaGalleryValuePersistProcessor->init();
        $productMediaGalleryValueAction = new ProductMediaGalleryValueAction();
        $productMediaGalleryValueAction->setPersistProcessor($productMediaGalleryValuePersistProcessor);

        // initialize the action that provides product media gallery value to entity CRUD functionality
        $productMediaGalleryValueToEntityPersistProcessor = new ProductMediaGalleryValueToEntityPersistProcessor();
        $productMediaGalleryValueToEntityPersistProcessor->setMagentoEdition($magentoEdition);
        $productMediaGalleryValueToEntityPersistProcessor->setMagentoVersion($magentoVersion);
        $productMediaGalleryValueToEntityPersistProcessor->setConnection($connection);
        $productMediaGalleryValueToEntityPersistProcessor->init();
        $productMediaGalleryValueToEntityAction = new ProductMediaGalleryValueToEntityAction();
        $productMediaGalleryValueToEntityAction->setPersistProcessor($productMediaGalleryValueToEntityPersistProcessor);

        // initialize the action that provides product media gallery value video CRUD functionality
        $productMediaGalleryValueVideoPersistProcessor = new ProductMediaGalleryValueVideoPersistProcessor();
        $productMediaGalleryValueVideoPersistProcessor->setMagentoEdition($magentoEdition);
        $productMediaGalleryValueVideoPersistProcessor->setMagentoVersion($magentoVersion);
        $productMediaGalleryValueVideoPersistProcessor->setConnection($connection);
        $productMediaGalleryValueVideoPersistProcessor->init();
        $productMediaGalleryValueVideoAction = new ProductMediaGalleryValueVideoAction();
        $productMediaGalleryValueVideoAction->setPersistProcessor($productMediaGalleryValueVideoPersistProcessor);

        // initialize the product media processor
        $productMediaProcessor = new ProductMediaProcessor();
        $productMediaProcessor->setConnection($connection);
        $productMediaProcessor->setProductMediaGalleryAction($productMediaGalleryAction);
        $productMediaProcessor->setProductMediaGalleryValueAction($productMediaGalleryValueAction);
        $productMediaProcessor->setProductMediaGalleryValueToEntityAction($productMediaGalleryValueToEntityAction);
        $productMediaProcessor->setProductMediaGalleryValueVideoAction($productMediaGalleryValueVideoAction);

        // return the instance
        return $productMediaProcessor;
    }
}
