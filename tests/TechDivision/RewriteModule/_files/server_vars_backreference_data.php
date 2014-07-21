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
 * @license   http://opensource.org/licenses/osl-3.0.php Open Software License (OSL 3.0)
 * @link      http://www.techdivision.com/
 */

/**
 * This list contains rewrite rules as they would be used within our server configuration.
 * To make tests independent from config parsing we have to provide them already split up.
 *
 * This map contains URI pairs of the sort "incoming URI" => "expected URI after rewrite"
 *
 * @var array $ruleSets The rewrite rule sets this test is based on
 */
$ruleSets = array(
    'serverVars' => array(
        'rules' => array(
            array(
                'condition' => '.*',
                'target' => '$REQUEST_URI@$SERVER_NAME',
                'flag' => 'L'
            )
        ),
        'map' => array(
            '/html' => '/html/index.html@unittest.local'
        )
    ),
    'varCondition' => array(
        'rules' => array(
            array(
                'condition' => '(unittest).+@$SERVER_NAME',
                'target' => '/$1',
                'flag' => 'L'
            )
        ),
        'map' => array(
            '/html' => '/unittest'
        )
    )
);
