<?php

namespace Command;

use Symfony\Component\Console\Command\Command;

abstract class BaseCommand extends Command
{
    protected const string INPUT_DIR = __DIR__ . '/../../input/';
    protected const string OUTPUT_DIR = __DIR__ . '/../../output/';
}
