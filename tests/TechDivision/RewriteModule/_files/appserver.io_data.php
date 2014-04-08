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

/**
 * This list contains rewrite rules as they would be used within our server configuration.
 * To make tests independent from config parsing we have to provide them already split up.
 *
 * @var array $rules The rewrite rules this test is based on
 */
$rules = array(
    array(
        'condition' => '^/index([/\?]*.*)',
        'target' => '/index.do$1',
        'flag' => 'L'),
    array(
        'condition' => 'downloads([/\?]*.*)',
        'target' => '/downloads.do/downloads$1',
        'flag' => 'L'),
    array(
        'condition' => '^/dl([/\?]*.*)',
        'target' => '/dl.do$1',
        'flag' => 'L'),
    array(
        'condition' => '^(/\?*.*)',
        'target' => '/index.do$1',
        'flag' => 'L')
);

/**
 * This map contains URI pairs of the sort "incoming URI" => "expected URI after rewrite"
 *
 * @var array $map The map of URIs to rewrite
 */
$map = array(
    '/dl/API' => '/dl.do/API',
    '/index/test' => '/index.do/test',
    '/imprint' => '/index.do/imprint',
    '/index?q=dfgdsfgs&p=fsdgdfg' => '/index.do?q=dfgdsfgs&p=fsdgdfg'
);