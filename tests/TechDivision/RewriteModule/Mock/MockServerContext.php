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
 * @subpackage Mock
 * @author     Bernhard Wick <b.wick@techdivision.com>
 * @copyright  2014 TechDivision GmbH - <info@techdivision.com>
 * @license    http://opensource.org/licenses/osl-3.0.php
 *             Open Software License (OSL 3.0)
 * @link       http://www.techdivision.com/
 */

namespace TechDivision\RewriteModule\Mock;

use TechDivision\Server\Interfaces\ServerConfigurationInterface;
use TechDivision\Server\ServerContext;

/**
 * TechDivision\RewriteModule\Mock\MockServerContext
 *
 * Mock class to be used to init the module
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
class MockServerContext extends ServerContext
{

    /**
     * Default constructor in which we set reasonable default values for our server vars
     * in order to avoid further mocking of configurations.
     */
    public function __construct(ServerConfigurationInterface $serverConfig)
    {
        // Set the server config
        $this->serverConfig = $serverConfig;

        // We need the envVars array, as we are not initing it correctly
        $this->envVars = array();

        // Presetting the server vars with some default values
        $this->serverVars = array (
            'DOCUMENT_ROOT' => realpath(__DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR .
                '_files'),
            'SERVER_ADMIN' => 'admin@appserver.io',
            'SERVER_NAME' => 'unittest.local',
            'SERVER_ADDR' => '0.0.0.0',
            'SERVER_PORT' => 9080,
            'GATEWAY_INTERFACE' => 'PHP/5.5.10',
            'SERVER_SOFTWARE' => 'appserver/0.6.0beta1 (linux) (PHP 5.5.10)',
            'SERVER_SIGNATURE' =>
                '<address>appserver/0.6.0beta1 (linux) (PHP 5.5.10) Server at 0.0.0.0 Port 9080</address>',
            'SERVER_HANDLER' => 'core',
            'SERVER_ERRORS_PAGE_TEMPLATE_PATH' => 'var/www/errors/error.phtml',
            'PATH' => '/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin',
            'HTTPS' => 'off',
            'REMOTE_ADDR' => '127.0.0.1',
            'REMOTE_PORT' => '57354',
            'REQUEST_TIME' => 1396882009,
            'SERVER_PROTOCOL' => 'HTTP/1.1',
            'HTTP_USER_AGENT' => 'Mozilla/5.0 (X11; Linux x86_64; rv:28.0) Gecko/20100101 Firefox/28.0',
            'HTTP_HOST' => 'unittest.local:9080',
            'HTTP_ACCEPT' => 'text/html,application/xhtml+xml,application/xml;q=0.9,*/*;q=0.8',
            'HTTP_ACCEPT_ENCODING' => 'gzip, deflate',
            'HTTP_ACCEPT_LANGUAGE' => 'de,en-US;q=0.7,en;q=0.3',
            'HTTP_CONNECTION' => 'keep-alive',
            'REQUEST_METHOD' => 'GET',
            'QUERY_STRING' => '',
            'REQUEST_URI' => '/html/index.html',
            'X_REQUEST_URI' => '/html/index.html',
        );
    }
}
