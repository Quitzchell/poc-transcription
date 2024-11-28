#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use App\Command\ParseXmlCommand;
use Symfony\Component\Console\Application;

$application = new Application();

// Register your command
$application->add(new ParseXmlCommand());

$application->run();
