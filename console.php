#!/usr/bin/env php
<?php

require __DIR__ . '/vendor/autoload.php';

use Command\TranscribeAction;
use Symfony\Component\Console\Application;

$application = new Application();

// Register your command
$application->add(new TranscribeAction());

$application->run();
