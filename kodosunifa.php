#!/usr/bin/env php
<?php

use Kodosunifa\SniffCodesCommand;
use Symfony\Component\Console\Application;

require 'vendor/autoload.php';

$application = new Application();
$application->add(new SniffCodesCommand());
$application->run();
