<?php

$EM_CONF[$_EXTKEY] = [
    'title' => 'Sudoku Solver',
    'description' => 'Sudoku Solver extension for TYPO3 CMS',
    'category' => 'plugin',
    'author' => 'Stefan Froemken',
    'author_email' => 'froemken@gmail.com',
    'state' => 'stable',
    'version' => '1.0.0',
    'constraints' => [
        'depends' => [
            'typo3' => '11.5.0-11.5.99',
        ],
        'conflicts' => [
        ],
        'suggests' => [
        ],
    ],
];
