<?php

$loader = require '${php-target.dir}/vendor/autoload.php';
$loader->add('TechDivision\\RewriteModule\\', '${php-target.dir}/vendor/techdivision/rewritemodule/src');
$loader->add('TechDivision\\Http\\', '${php-target.dir}/vendor/techdivision/http/src');
$loader->add('TechDivision\\Server\\', '${php-target.dir}/vendor/techdivision/server/src');
$loader->add('TechDivision\\WebServer\\', '${php-target.dir}/vendor/techdivision/webserver/src');

// We have to create a symbolic link for our tests here as we cannot copy them via ant
symlink(
    '${php-target.dir}/vendor/techdivision/rewritemodule/src/TechDivision/RewriteModule/_files/html/index.html',
    '${php-target.dir}/vendor/techdivision/rewritemodule/src/TechDivision/RewriteModule/_files/html/symlink.html'
);