<?php
\error_reporting(-1);
if ((!empty($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] === '127.0.0.1') || PHP_SAPI === 'cli') {
    \ini_set('display_errors', '1');
} else {
    \ini_set('display_errors', '0');
}

$masterDocumentRoot = $masterDocumentRoot ?? (PHP_SAPI !== 'cli' ? \rtrim(\dirname($_SERVER['SCRIPT_FILENAME']), '\/') : \getcwd());
$documentRoot = $documentRoot ?? $masterDocumentRoot;
$latestVersion = $latestVersion ?? '1.0';

if (!require __DIR__ . '/parts/calculator-skeleton/solve_version.php') {
    require_once __DIR__ . '/parts/calculator-skeleton/safe_autoload.php';

    $dirs = !empty($dirs)
        ? new \DrdPlus\CalculatorSkeleton\Dirs($dirs->getMasterDocumentRoot(), $dirs->getDocumentRoot())
        : new \DrdPlus\CalculatorSkeleton\Dirs($masterDocumentRoot, $documentRoot);
    $controller = $controller ?? new \DrdPlus\CalculatorSkeleton\CalculatorController(
            $googleAnalyticsId ?? 'UA-121206931-1',
            \DrdPlus\FrontendSkeleton\HtmlHelper::createFromGlobals($dirs),
            $dirs,
            'https://github.com/jaroslavtyc/drd-plus-calculator-skeleton',
            new \DrdPlus\FrontendSkeleton\CookiesService(),
            \basename($dirs->getMasterDocumentRoot())
        );
    if (!\is_a($controller, \DrdPlus\CalculatorSkeleton\CalculatorController::class)) {
        throw new \LogicException('Invalid controller class, expected ' . \DrdPlus\CalculatorSkeleton\CalculatorController::class
            . ' or descendant, got ' . \get_class($controller)
        );
    }
    if (!empty($hasContactsFixed)) {
        $controller->setContactsFixed();
    }
    if (!empty($hasHiddenHomeButton)) {
        $controller->hideHomeButton();
    }

    /** @noinspection PhpIncludeInspection */
    require $dirs->getVendorRoot() . '/drd-plus/frontend-skeleton/index.php';
}