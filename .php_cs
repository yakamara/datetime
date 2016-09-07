<?php

$header = <<<'HEADER'
This file is part of the datetime package.

(c) Yakamara Media GmbH & Co. KG

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
HEADER;


$finder = PhpCsFixer\Finder::create()
    ->exclude('propel')
    ->exclude('var')
    ->exclude('web')
    ->exclude('Resources')
    ->notPath('Model/Base')
    ->notPath('Model/Map')
    ->in(__DIR__)
;

return PhpCsFixer\Config::create()
    ->setUsingCache(true)
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'blank_line_after_opening_tag' => false,
        'blank_line_before_return' => false,
        'combine_consecutive_unsets' => true,
        'declare_strict_types' => true,
        'header_comment' => ['header' => $header],
        'no_useless_else' => true,
        'ordered_imports' => true,
        'short_array_syntax' => true,
    ])
    ->finder($finder)
;
