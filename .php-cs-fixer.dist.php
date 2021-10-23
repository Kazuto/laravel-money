<?php

use Kazuto\PhpCsPreset\Preset;

$classLoader = require __DIR__ . '/vendor/autoload.php';
$classLoader->register(true);

// Override rules to your liking
$rules = [
];

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
    ]);

return Preset::apply($finder, $rules);