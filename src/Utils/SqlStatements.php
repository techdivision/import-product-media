<?php

/**
 * TechDivision\Import\Product\Media\Utils\SqlStatements
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

namespace TechDivision\Import\Product\Media\Utils;

use TechDivision\Import\Utils\SqlStatementsUtil;

/**
 * A SSB providing process registry functionality.
 *
 * @author    Tim Wagner <tw@appserver.io>
 * @copyright 2015 TechDivision GmbH <info@appserver.io>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/wagnert/csv-import
 * @link      http://www.appserver.io
 */
class SqlStatements
{

    /**
     * This is a utility class, so protect it against direct
     * instantiation.
     */
    private function __construct()
    {
    }

    /**
     * This is a utility class, so protect it against cloning.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Return's the Magento edition/version specific utility class containing
     * the SQL statements to use.
     *
     * @param string $magentoEdition The Magento edition to use, EE or CE
     * @param string $magentoVersion The Magento version to use, e. g. 2.1.0
     *
     * @return string The fully qualified utility class name
     */
    public static function getUtilityClassName($magentoEdition, $magentoVersion)
    {

        // format Magento edition/version to build a valid PHP namespace
        $magentoEdition = SqlStatementsUtil::formatMagentoEdition($magentoEdition);
        $magentoVersion = SqlStatementsUtil::formatMagentoVersion($magentoVersion);

        // prepare the Magento edition/version specific utility classname
        $utilClassName = sprintf('TechDivision\Import\Product\Media\Utils\%s\V%s\SqlStatements', $magentoEdition, $magentoVersion);

        // if NOT available, use the default utility class name
        if (!class_exists($utilClassName)) {
            // prepare the Magento edition/version specific utility classname
            if (!class_exists($utilClassName = sprintf('TechDivision\Import\Product\Media\Utils\%s\SqlStatements', $magentoEdition))) {
                $utilClassName = __CLASS__;
            }
        }

        // return the utility class name
        return $utilClassName;
    }

    /**
     * The SQL statement to create a new product media gallery entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY = 'INSERT
                                            INTO catalog_product_entity_media_gallery (
                                                     attribute_id,
                                                     value,
                                                     media_type,
                                                     disabled
                                                   )
                                            VALUES (?, ?, ?, ?)';

    /**
     * The SQL statement to create a new product media gallery value entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE = 'INSERT
                                                  INTO catalog_product_entity_media_gallery_value (
                                                           value_id,
                                                           store_id,
                                                           entity_id,
                                                           label,
                                                           position,
                                                           disabled
                                                       )
                                                VALUES (?, ?, ?, ?, ?, ?)';

    /**
     * The SQL statement to create a new product media gallery value to entity entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE_TO_ENTITY = 'INSERT
                                                            INTO catalog_product_entity_media_gallery_value_to_entity (
                                                                   value_id,
                                                                   entity_id
                                                                 )
                                                          VALUES (?, ?)';

    /**
     * The SQL statement to create a new product media gallery value vidoe entry.
     *
     * @var string
     */
    const CREATE_PRODUCT_MEDIA_GALLERY_VALUE_VIDEO = 'INSERT
                                                        INTO catalog_product_entity_media_gallery_value_video (
                                                                 value_id,
                                                                 store_id,
                                                                 provider,
                                                                 url,
                                                                 title,
                                                                 description,
                                                                 metadata
                                                             )
                                                      VALUES (?, ?, ?, ?, ?, ?, ?)';
}
