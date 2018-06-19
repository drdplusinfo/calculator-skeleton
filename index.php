<?php
\error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    \ini_set('display_errors', '1');
} else {
    \ini_set('display_errors', '0');
}
$documentRoot = $documentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());
$vendorRoot = $vendorRoot ?? $documentRoot . '/vendor';
/** @noinspection PhpIncludeInspection */
require_once $vendorRoot . '/autoload.php';

$controller = $controller ?? new \DrdPlus\CalculatorSkeleton\CalculatorController(
        \DrdPlus\FrontendSkeleton\HtmlHelper::createFromGlobals($documentRoot),
        'https://github.com/jaroslavtyc/drd-plus-calculator-skeleton',
        \basename($documentRoot),
        $documentRoot,
        $vendorRoot
    );

/** @noinspection PhpIncludeInspection */
require $vendorRoot . '/drd-plus/frontend-skeleton/index.php';
