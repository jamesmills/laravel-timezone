<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
;

return PhpCsFixer\Config::create()
    ->setUsingCache(false)
    ->setRules([
        '@PSR2' => true,
        'concat_space' => ['spacing' => 'one'],
        'cast_spaces' => ['space' => 'single'],
        'no_unused_imports' => true,
        'blank_line_before_statement' => true,
        'trailing_comma_in_multiline_array' => true,
        'single_quote' => true
    ])
    ->setFinder($finder)
;
