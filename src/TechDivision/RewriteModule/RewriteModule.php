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
 * @category  Webserver
 * @package   TechDivision_RewriteModule
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_WebServer
 */

namespace TechDivision\RewriteModule;

use TechDivision\Connection\ConnectionRequestInterface;
use TechDivision\Connection\ConnectionResponseInterface;
use TechDivision\Http\HttpProtocol;
use TechDivision\Server\Dictionaries\ModuleHooks;
use TechDivision\Server\Exceptions\ModuleException;
use TechDivision\Server\Dictionaries\ServerVars;
use TechDivision\Server\Dictionaries\EnvVars;
use TechDivision\Server\Interfaces\RequestContextInterface;
use TechDivision\Server\Interfaces\ServerContextInterface;
use TechDivision\Http\HttpRequestInterface;
use TechDivision\Http\HttpResponseInterface;
use TechDivision\Server\Interfaces\ModuleInterface;
use TechDivision\Server\Dictionaries\ModuleVars;
use TechDivision\RewriteModule\Entities\Rule;

/**
 * \TechDivision\RewriteModule\RewriteModule
 *
 * @category  Webserver
 * @package   TechDivision_RewriteModule
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      https://github.com/techdivision/TechDivision_WebServer
 *
 * TODO there currently is no possibility for internal subrequests
 */
class RewriteModule implements ModuleInterface
{
    /**
     * Server variables we support and need
     *
     * @var array $supportedServerVars
     */
    protected $supportedServerVars = array();

    /**
     * SSL environment variables we support and need
     *
     * @var array $supportedEnvVars
     */
    protected $supportedEnvVars = array();

    /**
     * This array will hold all locations (e.g. /example/websocket) we ever encountered in our live time.
     * It will provide a mapping to the $configs array, as several locations can share one config
     * (e.g. a "global" .htaccess or nginx config).
     *
     * @var array<string> $locations
     */
    protected $locations = array();

    /**
     * All rules we have to check (sorted by requested URL)
     *
     * @var array $rules
     */
    protected $rules = array();

    /**
     * The rules as we got it from our basic configuration
     *
     * @var array $configuredRules
     */
    protected $configuredRules = array();

    /**
     * Will hold all configs we have encountered to be used via the location mapping
     *
     * @var array<\TechDivision\WebServer\Modules\RewriteModule\Config> $configs
     */
    protected $configs = array();

    /**
     * The server's context instance which we preserve for later use
     *
     * @var \TechDivision\Server\Interfaces\ServerContextInterface $serverContext $serverContext
     */
    protected $serverContext;

    /**
     * The requests's context instance
     *
     * @var \TechDivision\Server\Interfaces\RequestContextInterface $requestContext The request's context instance
     */
    protected $requestContext;

    /**
     * This array will hold all values which one would suspect as part of the PHP $_SERVER array.
     * As it will be filled from different sources we better keep it as a flat array here so we can
     * easily search for any value we need.
     * Filling and refilling will take place in init() and process() as we need it.
     *
     * @var array $serverVars
     */
    protected $serverBackreferences = array();

    /**
     * @var array $dependencies The modules we depend on
     */
    protected $dependencies = array();

    /**
     * Defines the module name
     *
     * @var string
     */
    const MODULE_NAME = 'rewrite';

    /**
     * Defines the SCRIPT_URL constant's name we keep track of
     *
     * @var string
     */
    const SCRIPT_URL = 'SCRIPT_URL';

    /**
     * Defines the SCRIPT_URI constant's name we keep track of
     *
     * @var string
     */
    const SCRIPT_URI = 'SCRIPT_URI';

    /**
     * Initiates the module
     *
     * @param \TechDivision\Server\Interfaces\ServerContextInterface $serverContext The server's context instance
     *
     * @return bool
     * @throws \TechDivision\Server\Exceptions\ModuleException
     */
    public function init(ServerContextInterface $serverContext)
    {
        // We have to throw a ModuleException on failure, so surround the body with a try...catch block
        try {

            // Save the server context for later re-use
            $this->serverContext = $serverContext;

            // Register our dependencies
            $this->dependencies = array(
                'virtualHost'
            );

            $this->supportedServerVars = array(
                'headers' => array(
                    ServerVars::HTTP_USER_AGENT,
                    ServerVars::HTTP_REFERER,
                    ServerVars::HTTP_COOKIE,
                    ServerVars::HTTP_FORWARDED,
                    ServerVars::HTTP_HOST,
                    ServerVars::HTTP_PROXY_CONNECTION,
                    ServerVars::HTTP_ACCEPT
                )
            );

            $this->supportedEnvVars = array(
                EnvVars::HTTPS,
                EnvVars::SSL_PROTOCOL,
                EnvVars::SSL_SESSION_ID,
                EnvVars::SSL_CIPHER,
                EnvVars::SSL_CIPHER_EXPORT,
                EnvVars::SSL_CIPHER_USEKEYSIZE,
                EnvVars::SSL_CIPHER_ALGKEYSIZE,
                EnvVars::SSL_COMPRESS_METHOD,
                EnvVars::SSL_VERSION_INTERFACE,
                EnvVars::SSL_VERSION_LIBRARY,
                EnvVars::SSL_CLIENT_M_VERSION,
                EnvVars::SSL_CLIENT_M_SERIAL,
                EnvVars::SSL_CLIENT_S_DN,
                EnvVars::SSL_CLIENT_S_DN_X509,
                EnvVars::SSL_CLIENT_I_DN,
                EnvVars::SSL_CLIENT_I_DN_X509,
                EnvVars::SSL_CLIENT_V_START,
                EnvVars::SSL_CLIENT_V_END,
                EnvVars::SSL_CLIENT_V_REMAIN,
                EnvVars::SSL_CLIENT_A_SIG,
                EnvVars::SSL_CLIENT_A_KEY,
                EnvVars::SSL_CLIENT_CERT,
                EnvVars::SSL_CLIENT_CERT_CHAIN_N,
                EnvVars::SSL_CLIENT_VERIFY,
                EnvVars::SSL_SERVER_M_VERSION,
                EnvVars::SSL_SERVER_M_SERIAL,
                EnvVars::SSL_SERVER_S_DN,
                EnvVars::SSL_SERVER_S_DN_X509,
                EnvVars::SSL_SERVER_I_DN,
                EnvVars::SSL_SERVER_I_DN_X509,
                EnvVars::SSL_SERVER_V_START,
                EnvVars::SSL_SERVER_V_END,
                EnvVars::SSL_SERVER_A_SIG,
                EnvVars::SSL_SERVER_A_KEY,
                EnvVars::SSL_SERVER_CERT,
                EnvVars::SSL_TLS_SNI
            );

            // Get the rules as the array they are within the config
            // We might not even get anything, so prepare our rules accordingly
            $this->configuredRules = $this->serverContext->getServerConfig()->getRewrites();

        } catch (\Exception $e) {

            // Re-throw as a ModuleException
            throw new ModuleException($e);
        }
    }

    /**
     * Implement's module logic for given hook
     *
     * @param \TechDivision\Connection\ConnectionRequestInterface     $request        A request object
     * @param \TechDivision\Connection\ConnectionResponseInterface    $response       A response object
     * @param \TechDivision\Server\Interfaces\RequestContextInterface $requestContext A requests context instance
     * @param int                                                     $hook           The current hook to process logic for
     *
     * @return bool
     * @throws \TechDivision\Server\Exceptions\ModuleException
     */
    public function process(
        ConnectionRequestInterface $request,
        ConnectionResponseInterface $response,
        RequestContextInterface $requestContext,
        $hook
    ) {
        // In php an interface is, by definition, a fixed contract. It is immutable.
        // So we have to declair the right ones afterwards...
        /** @var $request \TechDivision\Http\HttpRequestInterface */
        /** @var $request \TechDivision\Http\HttpResponseInterface */

        // if false hook is coming do nothing
        if (ModuleHooks::REQUEST_POST !== $hook) {
            return;
        }

        // set member ref for request context
        $this->requestContext = $requestContext;

        // We have to throw a ModuleException on failure, so surround the body with a try...catch block
        try {

            $requestUrl = $requestContext->getServerVar(
                ServerVars::HTTP_HOST
            ) . $requestContext->getServerVar(ServerVars::X_REQUEST_URI);

            if (!isset($this->rules[$requestUrl])) {

                // Reset the $serverBackreferences array to avoid mixups of different requests
                $this->serverBackreferences = array();

                // Resolve all used backreferences which are NOT linked to the query string.
                // We will resolve query string related backreferences separately as we are not able to cache them
                // as easily as, say, the URI
                // We also have to resolve all the changes rules in front of us made, so build up the backreferences
                // IN the loop.
                // TODO switch to backreference request not prefill as it might be faster
                $this->fillContextBackreferences();
                $this->fillHeaderBackreferences($request);
                $this->fillSslEnvironmentBackreferences();

                // Get the rules as the array they are within the config.
                // We have to also collect any volatile rules which might be set on request base.
                // We might not even get anything, so prepare our rules accordingly
                $volatileRewrites = array();
                if ($requestContext->hasModuleVar(ModuleVars::VOLATILE_REWRITES)) {

                    $volatileRewrites = $requestContext->getModuleVar(ModuleVars::VOLATILE_REWRITES);
                }

                // Build up the complete ruleset, volatile rules up front
                $rules = array_merge(
                    $volatileRewrites,
                    $this->configuredRules
                );
                $this->rules[$requestUrl] = array();

                // Only act if we got something
                if (is_array($rules)) {

                    // Convert the rules to our internally used objects
                    foreach ($rules as $rule) {

                        // Add the rule as a Rule object
                        $rule = new Rule($rule['condition'], $rule['target'], $rule['flag']);
                        $rule->resolve($this->serverBackreferences);
                        $this->rules[$requestUrl][] = $rule;
                    }
                }
            }

            // Iterate over all rules, resolve vars and apply the rule (if needed)
            foreach ($this->rules[$requestUrl] as $rule) {

                // Check if the rule matches, and if, apply the rule
                if ($rule->matches()) {

                    // Apply the rule. If apply() returns false this means this was the last rule to process
                    if ($rule->apply($requestContext, $response, $this->serverBackreferences) === false) {

                        break;
                    }
                }
            }

        } catch (\Exception $e) {

            // Re-throw as a ModuleException
            throw new ModuleException($e);
        }
    }

    /**
     * Will fill the header variables into our pre-collected $serverVars array
     *
     * @param \TechDivision\Http\HttpRequestInterface $request The request instance
     *
     * @return void
     */
    protected function fillHeaderBackreferences(HttpRequestInterface $request)
    {
        $headerArray = $request->getHeaders();

        // Iterate over all header vars we know and add them to our serverBackreferences array
        foreach ($this->supportedServerVars['headers'] as $supportedServerVar) {

            // As we got them with another name, we have to rename them, so we will not have to do this on the fly
            $tmp = strtoupper(str_replace('HTTP', 'HEADER', $supportedServerVar));
            if (@isset($headerArray[constant("TechDivision\\Http\\HttpProtocol::$tmp")])) {
                $this->serverBackreferences['$' . $supportedServerVar] = $headerArray[constant(
                    "TechDivision\\Http\\HttpProtocol::$tmp"
                )];

                // Also create for the "dynamic" substitution syntax
                $this->serverBackreferences['$' . constant(
                    "TechDivision\\Http\\HttpProtocol::$tmp"
                )] = $headerArray[constant(
                    "TechDivision\\Http\\HttpProtocol::$tmp"
                )];
            }
        }
    }

    /**
     * Will fill the SSL environment variables into the backreferences.
     * These are empty as long as the SSL module is not loaded.
     *
     * @return void
     *
     * TODO Get this vars from the SSL module as soon as it exists
     */
    protected function fillSslEnvironmentBackreferences()
    {
        // Iterate over all SSL environment variables and fill them into our backreferences
        foreach ($this->supportedEnvVars as $supportedSslEnvironmentVar) {

            $this->serverBackreferences['$' . $supportedSslEnvironmentVar . ''] = '';
        }
    }

    /**
     * Something we might need within conditions or target definitions are server and environment variables.
     * So add them.
     *
     * @throws \TechDivision\Server\Exceptions\ModuleException
     *
     * @return void
     */
    protected function fillContextBackreferences()
    {
        // get local ref of request context
        $requestContext = $this->getRequestContext();

        // Iterate over all server variables and add them to the backreference array
        foreach ($requestContext->getServerVars() as $varName => $serverVar) {

            // Prefill the value
            $this->serverBackreferences['$' . $varName] = $serverVar;
        }

        // Do the same for environment variables
        foreach ($requestContext->getEnvVars() as $varName => $envVar) {

            // Prefill the value
            $this->serverBackreferences['$' . $varName] = $envVar;
        }
    }

    /**
     * Return's an array of module names which should be executed first
     *
     * @return array The array of module names
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }

    /**
     * Returns the module name
     *
     * @return string The module name
     */
    public function getModuleName()
    {
        return self::MODULE_NAME;
    }

    /**
     * Return's the request context instance
     *
     * @return \TechDivision\Server\Interfaces\RequestContextInterface
     */
    public function getRequestContext()
    {
        return $this->requestContext;
    }

    /**
     * Prepares the module for upcoming request in specific context
     *
     * @return bool
     * @throws \TechDivision\Server\Exceptions\ModuleException
     */
    public function prepare()
    {
        // nothing to prepare for this module
    }
}
