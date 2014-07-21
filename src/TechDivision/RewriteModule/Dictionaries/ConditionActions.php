<?php
/**
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Open Software License (OSL 3.0)
 * that is available through the world-wide-web at this URL:
 * http://opensource.org/licenses/osl-3.0.php
 *
 * PHP version 5
 *
 * @category   Appserver
 * @package    TechDivision_RewriteModule
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace TechDivision\RewriteModule\Dictionaries;

/**
 * TechDivision\RewriteModule\Dictionaries\ConditionActions
 *
 * A dictionary for actions a condition might use for testing if it matches.
 * Basic stuff used parallel to htaccess features
 *
 * @category   Appserver
 * @package    TechDivision_RewriteModule
 * @subpackage Dictionaries
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class ConditionActions
{
    /**
     * Lexical string comparison where $operand is less than the given second string
     *
     * @var string
     */
    const STR_LESS= '<';

    /**
     * Lexical string comparison where $operand is greater than the given second string
     *
     * @var string
     */
    const STR_GREATER = '>';

    /**
     * Lexical string comparison where $operand equals the given second string
     *
     * @var string
     */
    const STR_EQUAL = '=';

    /**
     * Tests if $operand is a valid directory
     *
     * @var string
     */
    const IS_DIR= '-d';

    /**
     * Tests if $operand is a real file
     *
     * @var string
     */
    const IS_FILE = '-f';

    /**
     * Tests if $operand is a real file with a size bigger than 0
     *
     * @var string
     */
    const IS_USED_FILE = '-s';

    /**
     * Tests if $operand is a symbolic link
     *
     * @var string
     */
    const IS_LINK = '-l';

    /**
     * Tests if $operand is a file system structure which is flagged as executable
     *
     * @var string
     */
    const IS_EXECUTABLE = '-x';

    /**
     * Tests for any PCRE regex
     *
     * @var string
     */
    const REGEX = '-r';
}
