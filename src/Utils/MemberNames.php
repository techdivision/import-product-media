<?php

/**
 * TechDivision\Import\Product\Media\Utils\MemberNames
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

namespace TechDivision\Import\Product\Media\Utils;

/**
 * Utility class containing the entities member names.
 *
 * @author    Tim Wagner <t.wagner@techdivision.com>
 * @copyright 2016 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/import-product-media
 * @link      http://www.techdivision.com
 */
class MemberNames extends \TechDivision\Import\Product\Utils\MemberNames
{

    /**
     * Name for the member 'media_type'.
     *
     * @var string
     */
    const MEDIA_TYPE = 'media_type';

    /**
     * Name for the member 'parent_id'.
     *
     * @var string
     */
    const PARENT_ID = 'parent_id';

    /**
     * Name for the member 'disabled'.
     *
     * @var string
     */
    const DISABLED = 'disabled';

    /**
     * Name for the member 'label'.
     *
     * @var string
     */
    const LABEL = 'label';
}
