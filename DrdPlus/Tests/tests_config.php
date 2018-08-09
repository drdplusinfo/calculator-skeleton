<?php
global $testsConfiguration;
$testsConfiguration = new \DrdPlus\Tests\FrontendSkeleton\TestsConfiguration('http://kalkulator.drdplus.loc:88');
$testsConfiguration->disableHasCustomBodyContent();
$testsConfiguration->disableHasTables();
$testsConfiguration->disableHasNotes();
$testsConfiguration->disableHasExternalAnchorsWithHashes();
$testsConfiguration->disableHasLinksToAltar();
$testsConfiguration->setExpectedWebName('HTML kostra pro DrD+ kalkulátory');
$testsConfiguration->setExpectedPageTitle('HTML kostra pro DrD+ kalkulátory');
$testsConfiguration->disableHasLinksToAltar();