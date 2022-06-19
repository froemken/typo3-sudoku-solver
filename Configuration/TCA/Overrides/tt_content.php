<?php
if (!defined('TYPO3_MODE')) {
    die('Access denied.');
}

\TYPO3\CMS\Extbase\Utility\ExtensionUtility::registerPlugin(
    'sudoku_solver',
    'Sudoku',
    'LLL:EXT:glossary2/Resources/Private/Language/locallang_db.xlf:plugin.glossary.title'
);
