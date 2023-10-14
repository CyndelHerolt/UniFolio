<?php

$year = date('Y');

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__)
    ->exclude('vendor');

$header = <<<EOF
Copyright (c) $year. | Cyndel Herolt | IUT de Troyes  - All Rights Reserved
@author cyndelherolt
@project UniFolio
EOF;

return (new PhpCsFixer\Config())
    ->setRules([
        'header_comment' => [
            'header' => $header,
            'comment_type' => 'comment',
            'separate' => 'none',
        ],
    ])
    ->setFinder($finder);