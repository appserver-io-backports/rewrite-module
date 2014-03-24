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
     * Initializes the rewrite module to test.
     *
     * @return void
     */
    public function setUp()
    {
        $this->rewriteModule = new RewriteModule();
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
}
