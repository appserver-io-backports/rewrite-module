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
 * @category  WebServer
 * @package   TechDivision_RewriteModule
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH - <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php
 *            Open Software License (OSL 3.0)
 * @link      http://www.techdivision.com/
 */

namespace TechDivision\RewriteModule;

use TechDivision\Http\HttpRequest;
use TechDivision\Http\HttpResponse;
use TechDivision\RewriteModule\Mock\MockServerConfig;
use TechDivision\RewriteModule\Mock\MockServerContext;
use TechDivision\WebServer\Dictionaries\ModuleVars;
use TechDivision\WebServer\Dictionaries\ServerVars;

/**
 * TechDivision\RewriteModule\RewriteModuleTest
 *
 * Basic test class for the RewriteModule class.
 *
 * @category  WebServer
 * @package   TechDivision_RewriteModule
 * @author    Bernhard Wick <b.wick@techdivision.com>
 * @copyright 2014 TechDivision GmbH - <info@techdivision.com>
 * @license   http://opensource.org/licenses/osl-3.0.php
 *            Open Software License (OSL 3.0)
 * @link      http://www.techdivision.com/
 */
class RewriteModuleTest extends \PHPUnit_Framework_TestCase
{

    /**
     * The rewrite module instance to test.
     *
     * @var \TechDivision\RewriteModule\RewriteModule
     */
    protected $rewriteModule;

    /**
     * Nested array of datasets we will tests one after another
     *
     * @var array $rewriteDataSets
     */
    protected $rewriteDataSets = array();

    /**
     * Nested array of datasets we will tests one after another.
     * Theses datasets contain redirects which have to be tested differently
     *
     * @var array $redirectDataSets
     */
    protected $redirectDataSets = array();

    /**
     * The mock server context we use in this test
     *
     * @var \TechDivision\RewriteModule\Mock\MockServerContext $mockServerContext
     */
    protected $mockServerContext;

    /**
     * List of files which provide vital test data we will run through
     *
     * @var array $dataFiles
     */
    protected $dataFiles = array('magento_data', 'appserver.io_data');

    /**
     * @var \TechDivision\Http\HttpRequest $request The request we need for processing
     */
    protected $request;

    /**
     * @var \TechDivision\Http\HttpResponse $response The response we need for processing
     */
    protected $response;

    /**
     * Initializes the rewrite module to test.
     * Will also build up needed mock objects and provide data for the actual rewrite tests.
     *
     * @return void
     */
    public function setUp()
    {
        // Get an instance of the module we can test with
        $this->rewriteModule = new RewriteModule();

        // We need a mock server context to init our module, otherwise we cannot use it
        $this->mockServerContext = new MockServerContext(new MockServerConfig());

        // The module has to be inited
        $this->rewriteModule->init($this->mockServerContext);

        // Iterate over all data files and collect the sets of test data
        foreach ($this->dataFiles as $dataFile) {

            // Require the different files and collect the data
            require __DIR__ . DIRECTORY_SEPARATOR . '_files' . DIRECTORY_SEPARATOR . $dataFile . '.php';

            // We have to filter redirects as they (instead of rewrites) have to be tested completely different!
            // So if a rule has the R-flag set we have to pipe all corresponding data into the $redirectDataSets array
            $containsRedirect = false;
            foreach ($rules as $key => $rule) {

                if (strpos($rule['flag'], 'R') !== false) {

                    // Flag the dataset as redirect containing and remove the flag from the set
                    $containsRedirect = true;
                    $rules[$key]['flag'] = str_replace('R', '', $rule['flag']);
                }
            }

            // If the dataset has a redirect in it we have to test it separately for these
            if ($containsRedirect) {

                $this->redirectDataSets[] = array(
                    'rules' => $rules,
                    'map' => $map
                );
            }

            // Per convention we got the variables $rules, and $map within a file
            $this->rewriteDataSets[] = array(
                'rules' => $rules,
                'map' => $map
            );
        }

        // Create a request and response object we can use for our processing
        $this->request = new HttpRequest();
        $this->response = new HttpResponse();

    }

    /**
     * Test if the constructor created an instance of the rewrite module.
     *
     * @return void
     */
    public function testInstanceOf()
    {
        $this->assertInstanceOf('\TechDivision\RewriteModule\RewriteModule', $this->rewriteModule);
    }

    /**
     * Basic test of the module name
     *
     * @return void
     */
    public function testModuleName()
    {
        $module = $this->rewriteModule;
        $this->assertSame('rewrite', $module::MODULE_NAME);
    }

    /*
     * Iterate over all sets of data and test the rewriting
     *
     * @return void
     */
    public function testRewriting()
    {
        // Iterate over the data sets and test them
        foreach ($this->rewriteDataSets as $dataSet) {

            // We will get the rules into our module by ways of the volatile rewrites
            $this->mockServerContext->setModuleVar(ModuleVars::VOLATILE_REWRITES, $dataSet['rules']);

            // No iterate over the map which is combined with the rules in the dataset
            foreach ($dataSet['map'] as $input => $desiredOutput) {

                // We will provide the crucial information by way of server vars
                $this->mockServerContext->setServerVar(ServerVars::X_REQUEST_URI, $input);

                // Start the processing
                $this->rewriteModule->process($this->request, $this->response);

                // Now check if we got the same thing here
                $this->assertSame($desiredOutput, $this->mockServerContext->getServerVar(ServerVars::X_REQUEST_URI));
            }
        }
    }
}
