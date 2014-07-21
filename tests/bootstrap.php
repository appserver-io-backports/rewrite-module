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
$loader = require '${php-target.dir}/vendor/autoload.php';
$loader->add('TechDivision\\RewriteModule\\', array(
        '${php-target.dir}/vendor/techdivision/rewritemodule/src',
        '${php-target.dir}/vendor/techdivision/rewritemodule/tests'
    ));
$loader->add('TechDivision\\Http\\', '${php-target.dir}/vendor/techdivision/http/src');
$loader->add('TechDivision\\Server\\', '${php-target.dir}/vendor/techdivision/server/src');
$loader->add('TechDivision\\WebServer\\', '${php-target.dir}/vendor/techdivision/webserver/src');

// We have to create a symbolic link for our tests here as we cannot copy them via ant
symlink(
    '${php-target.dir}/vendor/techdivision/rewritemodule/src/TechDivision/RewriteModule/_files/html/index.html',
    '${php-target.dir}/vendor/techdivision/rewritemodule/src/TechDivision/RewriteModule/_files/html/symlink.html'
);
