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
 * @category   WebServer
 * @package    TechDivision_RewriteModule
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace TechDivision\RewriteModule\Mock;

use TechDivision\RewriteModule\Entities\Rule;

/**
 * TechDivision\RewriteModule\Mock\MockRule
 *
 * Mocks the Rule class to expose additional and hidden functionality
 *
 * @category   WebServer
 * @package    TechDivision_RewriteModule
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class MockRule extends Rule
{
    /**
     * Used to open up the parent's sortFlags() method for testing
     *
     * @param string $flagString The unsorted string of flags
     *
     * @return array
     */
    public function sortFlags($flagString)
    {
        return parent::sortFlags($flagString);
    }

    /**
     * Getter function for the protected $type member
     *
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Getter function for the protected $sortedFlags member
     *
     * @return array
     */
    public function getSortedFlags()
    {
        return $this->sortedFlags;
    }
}
