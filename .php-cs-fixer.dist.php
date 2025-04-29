<?php
// Add all plugin folders
$finder = PhpCsFixer\Finder::create()
    ->in(
        [
            __DIR__ . '/src',
            __DIR__ . '/services',
        ]
    );

$config = new PhpCsFixer\Config();
$config
    ->setParallelConfig(PhpCsFixer\Runner\Parallel\ParallelConfigFactory::detect())
    ->setRiskyAllowed(true)
    ->setHideProgress(false)
    ->setUsingCache(false)
    ->setRules(
        [
            // Basic ruleset is PSR 12
            '@PSR12'                                           => true,
            // Short array syntax
            'array_syntax'                                     => ['syntax' => 'short'],
            // List of values separated by a comma is contained on a single line should not have a trailing comma like [$foo, $bar,] = ...
            'no_trailing_comma_in_singleline'                  => true,
            // Arrays on multiline should have a trailing comma
            'trailing_comma_in_multiline'                      => ['elements' => ['arrays']],
            // Align elements in multiline array and variable declarations on new lines below each other
            'binary_operator_spaces'                           => ['operators' => ['=>' => 'align_single_space_minimal', '=' => 'align', '??=' => 'align']],
            // The "No break" comment in switch statements
            'no_break_comment'                                 => ['comment_text' => 'No break'],
            // Remove unused imports
            'no_unused_imports'                                => true,
            // Classes from the global namespace should not be imported
            'global_namespace_import'                          => ['import_classes' => false, 'import_constants' => false, 'import_functions' => false],
            // Alpha order imports
            'ordered_imports'                                  => ['imports_order' => ['class', 'function', 'const'], 'sort_algorithm' => 'alpha'],
            // There should not be useless else cases
            'no_useless_else'                                  => true,
            // Native function invocation
            'native_function_invocation'                       => ['include' => ['@compiler_optimized']],
            // Adds null to type declarations when parameter have a default null value
            'nullable_type_declaration_for_default_null_value' => true,
            // Removes unneeded parentheses around control statements
            'no_unneeded_control_parentheses'                  => true,
            // Using isset($var) && multiple times should be done in one call.
            'combine_consecutive_issets'                       => true,
            // Calling unset on multiple items should be done in one call
            'combine_consecutive_unsets'                       => true,
            // There must be no sprintf calls with only the first argument
            'no_useless_sprintf'                               => true,
        ]
    )
    ->setFinder($finder);

return $config;