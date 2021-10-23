<?php

use Kazuto\PhpCsPreset\Preset;

$finder = PhpCsFixer\Finder::create()
    ->in([
        __DIR__ . '/src',
        __DIR__ . '/tests',
    ]);

return Preset::apply($finder);