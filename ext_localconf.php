<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

call_user_func(static function () {
    \TYPO3\CMS\Extbase\Utility\ExtensionUtility::configurePlugin(
        'sudoku_solver',
        'Sudoku',
        [
            \StefanFroemken\Typo3SudokuSolver\Controller\SudokuController::class => 'show',
        ],
        // non-cacheable actions
        []
    );
});
