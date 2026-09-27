<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding;

final class StratosReportNormalizer
{
    public static function normalize(array $report): array
    {
        $touchdown = is_array($report['touchdown'] ?? null) ? $report['touchdown'] : [];
        $bounces = is_array($report['bounces'] ?? null) ? $report['bounces'] : [];

        $score = $report['composite_score'] ?? $report['compositeScore'] ?? null;
        $landingRate = $report['landing_rate_fpm']
            ?? $touchdown['landingRateFpm']
            ?? $touchdown['landingRate']
            ?? null;

        $gForce = $report['g_force'] ?? $touchdown['gForce'] ?? null;

        $report['composite_score'] = is_numeric($score) ? (float) $score : null;
        $report['landing_rate_fpm'] = is_numeric($landingRate) ? (float) $landingRate : null;
        $report['g_force'] = is_numeric($gForce) ? (float) $gForce : null;
        $report['bounce_count'] = isset($report['bounce_count'])
            ? (int) $report['bounce_count']
            : count($bounces);

        if (!array_key_exists('analysis_available', $report)) {
            $report['analysis_available'] = self::hasCompletedAnalysis($report);
        }

        $report['analysis_status'] = $report['analysis_available'] ? 'available' : 'unavailable';

        return $report;
    }

    private static function hasCompletedAnalysis(array $report): bool
    {
        return isset($report['compositeScore'])
            || isset($report['composite_score'])
            || isset($report['grade'])
            || isset($report['landingVerdict'])
            || isset($report['stabilityGate']);
    }
}
