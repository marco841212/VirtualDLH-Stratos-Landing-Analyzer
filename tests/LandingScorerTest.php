<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding\Tests;

use PHPUnit\Framework\TestCase;
use VirtualDLH\StratosLanding\Scoring\LandingScorer;

final class LandingScorerTest extends TestCase
{
    public function testScoresCompleteSyntheticReport(): void
    {
        $scorer = new LandingScorer();

        $result = $scorer->score([
            'touchdown' => [
                'landingRateFpm' => -180,
                'gForce' => 1.12,
            ],
            'bounces' => [],
            'stabilityGate' => [
                'evaluated' => true,
                'passed' => true,
                'failures' => [],
            ],
            'runway' => [
                'touchdownFromThresholdFt' => 1200,
                'lengthFt' => 10000,
            ],
            'fuel' => [
                'atLandingLbs' => 12000,
                'burnRateLbsHr' => 12000,
            ],
        ]);

        self::assertSame('virtualdlh-default-v1', $result['model']);
        self::assertSame(100.0, $result['categories']['landingRate']['score']);
        self::assertSame(100.0, $result['categories']['gForce']['score']);
        self::assertSame(100.0, $result['categories']['bounce']['score']);
        self::assertSame(100.0, $result['categories']['approach']['score']);
        self::assertNotNull($result['compositeScore']);
        self::assertNotNull($result['grade']);
    }

    public function testUnavailableCategoriesAreSkipped(): void
    {
        $scorer = new LandingScorer();

        $result = $scorer->score([
            'touchdown' => [
                'landingRateFpm' => -250,
            ],
            'bounces' => [],
        ]);

        self::assertTrue($result['categories']['gForce']['skipped']);
        self::assertTrue($result['categories']['approach']['skipped']);
        self::assertTrue($result['categories']['fuel']['skipped']);
        self::assertNotNull($result['compositeScore']);
    }

    public function testCanAttachAnalysisWithoutOverwritingStratosFields(): void
    {
        $scorer = new LandingScorer();

        $input = [
            'compositeScore' => 81.67,
            'grade' => 'B+',
            'touchdown' => [
                'landingRateFpm' => -220,
                'gForce' => 1.18,
            ],
            'bounces' => [],
        ];

        $output = $scorer->attach($input);

        self::assertSame(81.67, $output['compositeScore']);
        self::assertSame('B+', $output['grade']);
        self::assertArrayHasKey('virtualdlh_open_source', $output);
    }
}
