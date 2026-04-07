#!/usr/bin/env php
<?php

use Chwnam\TRC\App;

if ('cli' !== php_sapi_name()) {
    die('This script can only be run from the command line.');
}

require __DIR__ . '/vendor/autoload.php';

const ROOT_DIR = __DIR__;

new App()
    ->run()
;