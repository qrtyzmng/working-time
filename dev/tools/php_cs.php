<?php

return (new \PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => [
            'syntax' => 'short', // PHP arrays should use the PHP 5.4 short-syntax.
        ],
        'blank_line_after_opening_tag' => true, // Ensure there is no code on the same line as the PHP open tag and it is followed by a blankline.
        'blank_line_before_statement' => true,
        'concat_space' => [
            'spacing' => 'one', // Concatenation should be used with at least one whitespace around.
        ],
        'new_with_braces' => true, // All instances created with new keyword must be followed by braces.
        'no_extra_blank_lines' => [
            'tokens' => [
                'extra', // Removes extra empty lines.
                'use',   // Removes extra empty lines between uses.
            ]
        ],
        'no_trailing_comma_in_singleline_array' => true, // PHP single-line arrays should not have trailing comma.
        'no_unused_imports' => true, // Unused use statements must be removed.
        'no_useless_else' => true, // No useless else
        'no_whitespace_in_blank_line' => true, // Remove trailing whitespace at the end of blank lines.
        'not_operator_with_successor_space' => true, // Logical NOT operators (!) should have one trailing whitespace.
        'ordered_imports' => true, // Ordering use statements.
        'phpdoc_align' => true, // All items of the @param, @throws, @return, @var, and @type phpdoc tags must be aligned vertically.
        'phpdoc_order' => true, // Annotations in phpdocs should be ordered so that param annotations come first, then throws annotations, then return annotations.
        'phpdoc_separation' => true, // Annotations in phpdocs should be grouped together so that annotations of the same type immediately follow each other, and annotations of a different type are separated by a single blank line.
        'single_quote' => true, // Convert double quotes to single quotes for simple strings.
        'standardize_not_equals' => true, // Replace all <> with !=.
        'trailing_comma_in_multiline' => true,
        'blank_lines_before_namespace' => true, // There should be exactly one blank line before a namespace declaration.
        'native_function_invocation' => ['include' => ['@all']], // Add leading \ before constant invocation of internal constant to speed up resolving. Constant name match is case-sensitive, except for null, false and true.
        'multiline_whitespace_before_semicolons' => true, // no semicolons on their own line
        'single_line_throw' => false,
        'php_unit_method_casing' => ['case' => 'snake_case'],
    ])
    ->setFinder(
        \PhpCsFixer\Finder::create()
            ->in([
                \getcwd() . '/src',
                \getcwd() . '/tests/unit',
            ])
            ->exclude([
                'Migrations',
            ])
    );
