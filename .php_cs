<?php

$excluded_folders = [
  'node_modules',
  'storage',
  'vendor',
  'bootstrap/cache'
];

return PhpCsFixer\Config::create()
  ->setFinder(
    PhpCsFixer\Finder::create()
      ->in(__DIR__)
      ->exclude($excluded_folders)
      ->notName('README.md')
      ->notName('*.xml')
      ->notName('*.yml')
      ->notName('_ide_helper.php')
  )
  ->setRiskyAllowed(true)
  ->setRules(array(
    '@Symfony' => true,
    'binary_operator_spaces' => ['align_double_arrow' => false, 'align_equals' => false],
    'array_syntax' => ['syntax' => 'short'],
    'linebreak_after_opening_tag' => true,
    'not_operator_with_successor_space' => false,
    'ordered_imports' => true,
    'phpdoc_order' => true,
    'logical_operators' => true,
    'modernize_types_casting' => true,
    'yoda_style' => false,
  ));
