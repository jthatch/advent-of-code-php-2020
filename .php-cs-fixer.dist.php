<?php
// have it fix our source code and tests
$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests')
    ->append([
        __DIR__ . '/run.php'
    ]);
;

$config = new PhpCsFixer\Config();
$config
    ->setRiskyAllowed(true)
    ->setRules(array(
        '@Symfony' => true,
        '@PSR12' => true,
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'ordered_imports' => true,
        'array_syntax' => ['syntax' => 'short'],
        'declare_strict_types' => true,
        'void_return' => true,
        'no_superfluous_phpdoc_tags' => false,
        'php_unit_method_casing' => false,
        'protected_to_private' => false,
    ))
    ->setFinder($finder)
    ->setUsingCache(true)
;

return $config;