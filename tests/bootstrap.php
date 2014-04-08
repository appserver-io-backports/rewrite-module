<?php

$loader = require '${php-target.dir}/vendor/autoload.php';
$loader->add('TechDivision\\RewriteModule\\', '${php-target.dir}/vendor/techdivision/rewritemodule/src');
$loader->add('TechDivision\\Http\\', '${php-target.dir}/vendor/techdivision/http/src');
$loader->add('TechDivision\\WebServer\\', '${php-target.dir}/vendor/techdivision/webserver/src');