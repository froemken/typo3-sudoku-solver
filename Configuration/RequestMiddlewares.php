<?php
return [
    'frontend' => [
        'stefanfroemken/sudoku-solver/get-hint' => [
            'target' => \StefanFroemken\Typo3SudokuSolver\Middleware\GetHintMiddleware::class,
            'after' => [
                'typo3/cms-frontend/tsfe',
            ],
            'before' => [
                'typo3/cms-frontend/prepare-tsfe-rendering'
            ]
        ]
    ]
];
