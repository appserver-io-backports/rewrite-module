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

use TechDivision\RewriteModule\RewriteModule;
use TechDivision\Server\Interfaces\RequestContextInterface;
use TechDivision\Connection\ConnectionRequestInterface;

/**
 * TechDivision\RewriteModule\Mock\MockRewriteModule
 *
 * Mocks the RewriteModule class to expose additional and hidden functionality
 *
 * @category   WebServer
 * @package    TechDivision_RewriteModule
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */
class MockRewriteModule extends RewriteModule
{

    /**
     * Needed for simple tests the getRequestContext() method
     *
     * @param \TechDivision\Server\Interfaces\RequestContextInterface $requestContext The request context
     *
     * @return void
     */
    public function setRequestContext(RequestContextInterface $requestContext)
    {
        $this->requestContext = $requestContext;
    }

    /**
     * Used to read protected member $serverBackreferences
     *
     * @return array
     */
    public function getServerBackreferences()
    {
        return $this->serverBackreferences;
    }

    /**
     * Exposes the parent method
     *
     * @throws \TechDivision\Server\Exceptions\ModuleException
     *
     * @return void
     */
    public function fillContextBackreferences()
    {
        parent::fillContextBackreferences();
    }

    /**
     * Exposes the parent method
     *
     * @param \TechDivision\Connection\ConnectionRequestInterface $request The request instance
     *
     * @return void
     */
    public function fillHeaderBackreferences(ConnectionRequestInterface $request)
    {
        parent::fillHeaderBackreferences($request);
    }
}
