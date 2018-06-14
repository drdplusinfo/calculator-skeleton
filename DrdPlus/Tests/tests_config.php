<?php
global $testsConfiguration;
$testsConfiguration = new \DrdPlus\Tests\FrontendSkeleton\TestsConfiguration();
$testsConfiguration->disableHasCustomBodyContent();
$testsConfiguration->disableHasTables();
$testsConfiguration->disableHasNotes();
$testsConfiguration->disableHasExternalAnchorsWithHashes();
$testsConfiguration->disableHasLinksToAltar();
$testsConfiguration->setExpectedWebName('HTML kostra pro DrD+ kalkulátory');
$testsConfiguration->setExpectedPageTitle('HTML kostra pro DrD+ kalkulátory');
$testsConfiguration->disableHasMoreVersions();