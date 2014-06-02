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
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace TechDivision\RewriteModule\Mock;

use TechDivision\Server\Configuration\ServerXmlConfiguration;

/**
 * TechDivision\RewriteModule\Mock\MockServerConfig
 *
 * Mock class for a server configuration
 *
 * @category   Appserver
 * @package    TechDivision_RewriteModule
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class MockServerConfig extends ServerXmlConfiguration
{
    /**
     * Default constructor to eliminate the need for a node.
     */
    public function __construct()
    {
    }

    /**
     * Only functionality we need overwritten is the getRewrites method.
     * It will always return an empty array.
     *
     * @return array
     */
    public function getRewrites()
    {
        return array();
    }
}
