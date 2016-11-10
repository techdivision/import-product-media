<?php

/**
 * TechDivision\Import\Product\Media\Actions\Processors\AbstractProductMediaPersistProcessor
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

namespace TechDivision\Import\Product\Media\Actions\Processors;

use TechDivision\Import\Product\Media\Utils\SqlStatements;
use TechDivision\Import\Actions\Processors\AbstractPersistProcessor;

/**
 * The product media persist processor implementation.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
abstract class AbstractProductMediaPersistProcessor extends AbstractPersistProcessor
{

    /**
     * Return's the passed statement from the Magento specific
     * utility class.
     *
     * @return string The utility class name
     */
    protected function getUtilityClassName()
    {
        return SqlStatements::getUtilityClassName($this->getMagentoEdition(), $this->getMagentoVersion());
    }
}
