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

use TechDivision\Server\Contexts\ServerContext;

/**
 * TechDivision\RewriteModule\Mock\MockServerContext
 *
 * Mocks the ServerContext class to test exception catching
 *
 * @category   WebServer
 * @package    TechDivision_RewriteModule
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class MockServerContext extends ServerContext
{
    /**
     * Overridden method to test exception handling
     *
     * @return void
     * @throws \Exception
     */
    public function getServerConfig()
    {
        throw new \Exception();
    }
}
