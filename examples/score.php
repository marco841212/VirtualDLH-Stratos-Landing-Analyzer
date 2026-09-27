<?php

declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use VirtualDLH\StratosLanding\Scoring\LandingScorer;

$report = [
    'touchdown' => [
        'landingRateFpm' => -205,
        'gForce' => 1.16,
    ],
    'bounces' => [],
    'stabilityGate' => [
        'evaluated' => true,
        'passed' => true,
        'failures' => [],
    ],
    'runway' => [
        'touchdownFromThresholdFt' => 1350,
        'lengthFt' => 11000,
    ],
    'fuel' => [
        'atLandingLbs' => 15000,
        'burnRateLbsHr' => 12000,
    ],
];

$result = (new LandingScorer())->score($report);

echo json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) . PHP_EOL;
