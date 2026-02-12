<?php

$finder = (new PhpCsFixer\Finder())
    ->in([
        'migrations',
        'src',
        'tests',
        'templates',
        'translations',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'declare_strict_types' => true,
        'global_namespace_import' => ['import_constants' => true, 'import_functions' => true, 'import_classes' => true],
        'is_null' => true,
        'yoda_style' => ['equal' => true, 'identical' =>false, 'less_and_greater' => null]
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
    ->setCacheFile(sprintf('%s/var/cache/php-cs-fixer.cache', __DIR__))
;
