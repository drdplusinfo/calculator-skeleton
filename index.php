<?php
$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());
$vendorRoot = $vendorRoot ?? $documentRoot . '/vendor';
/** @noinspection PhpUnusedLocalVariableInspection */
$partsRoot = \file_exists($documentRoot . '/parts')
    ? ($documentRoot . '/parts')
    : ($vendorRoot . '/drd-plus/calculator-skeleton/parts');
/** @noinspection PhpUnusedLocalVariableInspection */
$genericPartsRoot = $genericPartsRoot ?? __DIR__ . '/parts/calculator-skeleton';

$controller = $controller ?? include __DIR__ . '/parts/mocked-controller.php';

/** @noinspection PhpIncludeInspection */
require $vendorRoot . '/drd-plus/frontend-skeleton/index.php';
