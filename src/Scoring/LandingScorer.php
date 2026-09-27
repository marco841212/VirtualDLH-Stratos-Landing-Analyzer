<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding\Scoring;

final class LandingScorer
{
    public function __construct(
        private readonly array $config = []
    ) {
    }

    public function score(array $report): array
    {
        $config = array_replace_recursive(DefaultScoringConfig::all(), $this->config);

        $touchdown = is_array($report['touchdown'] ?? null) ? $report['touchdown'] : [];
        $stability = is_array($report['stabilityGate'] ?? null) ? $report['stabilityGate'] : [];
        $runway = is_array($report['runway'] ?? null) ? $report['runway'] : [];
        $fuel = is_array($report['fuel'] ?? null) ? $report['fuel'] : [];
        $bounces = is_array($report['bounces'] ?? null) ? $report['bounces'] : [];

        $landingRate = $report['landing_rate_fpm']
            ?? $touchdown['landingRateFpm']
            ?? $touchdown['landingRate']
            ?? null;

        $gForce = $report['g_force'] ?? $touchdown['gForce'] ?? null;
        $bounceCount = isset($report['bounce_count'])
            ? (int) $report['bounce_count']
            : count($bounces);

        $runwayUsedPercent = $report['runwayUsedPercent'] ?? null;
        if (!is_numeric($runwayUsedPercent)) {
            $distance = $runway['touchdownFromThresholdFt'] ?? null;
            $length = $runway['lengthFt'] ?? null;
            if (is_numeric($distance) && is_numeric($length) && (float) $length > 0) {
                $runwayUsedPercent = min(100.0, max(0.0, ((float) $distance / (float) $length) * 100.0));
            }
        }

        $categories = [
            'landingRate' => $this->scoreLandingRate($landingRate),
            'gForce' => $this->scoreGForce($gForce),
            'bounce' => $this->scoreBounces($bounceCount),
            'approach' => $this->scoreApproach($stability),
            'rollout' => $this->scoreRollout($runwayUsedPercent),
            'fuel' => $this->scoreFuel($fuel),
        ];

        [$composite, $activeWeight] = $this->weightedComposite($categories, $config['weights']);

        return [
            'model' => (string) $config['model'],
            'compositeScore' => $composite,
            'grade' => $this->grade($composite),
            'landingVerdict' => $this->verdict($composite),
            'categories' => $categories,
            'activeWeight' => $activeWeight,
            'disclaimer' => 'Simulation scoring model only; not a real-world aviation safety standard.',
        ];
    }

    public function attach(array $report): array
    {
        $report['virtualdlh_open_source'] = $this->score($report);
        return $report;
    }

    private function scoreLandingRate(mixed $value): array
    {
        if (!is_numeric($value)) {
            return $this->skipped('Landing rate unavailable');
        }

        $rate = abs((float) $value);

        if ($rate < 60) {
            return $this->category(85, 'Very soft');
        }
        if ($rate <= 180) {
            return $this->category(100, 'Excellent');
        }
        if ($rate <= 260) {
            return $this->category(90, 'Good');
        }
        if ($rate <= 360) {
            return $this->category(75, 'Fair');
        }
        if ($rate <= 500) {
            return $this->category(55, 'Marginal');
        }
        if ($rate <= 700) {
            return $this->category(30, 'Hard');
        }

        return $this->category(10, 'Very hard');
    }

    private function scoreGForce(mixed $value): array
    {
        if (!is_numeric($value)) {
            return $this->skipped('Touchdown G-force unavailable');
        }

        $g = (float) $value;

        if ($g < 0.95) {
            return $this->category(80, 'Low');
        }
        if ($g <= 1.20) {
            return $this->category(100, 'Excellent');
        }
        if ($g <= 1.30) {
            return $this->category(90, 'Good');
        }
        if ($g <= 1.40) {
            return $this->category(75, 'Fair');
        }
        if ($g <= 1.60) {
            return $this->category(50, 'High');
        }

        return $this->category(25, 'Very high');
    }

    private function scoreBounces(int $count): array
    {
        return match (true) {
            $count <= 0 => $this->category(100, 'No bounce'),
            $count === 1 => $this->category(75, 'Single bounce'),
            $count === 2 => $this->category(45, 'Multiple bounces'),
            default => $this->category(20, 'Repeated bounces'),
        };
    }

    private function scoreApproach(array $stability): array
    {
        if (empty($stability) || empty($stability['evaluated'])) {
            return $this->skipped('Approach stability not evaluated');
        }

        if (!empty($stability['passed'])) {
            return $this->category(100, 'Stable');
        }

        $failures = is_array($stability['failures'] ?? null)
            ? count($stability['failures'])
            : 1;

        $score = max(20, 60 - (max(1, $failures) - 1) * 10);

        return $this->category($score, 'Unstable');
    }

    private function scoreRollout(mixed $value): array
    {
        if (!is_numeric($value)) {
            return $this->skipped('Runway-use metric unavailable');
        }

        $percent = max(0.0, min(100.0, (float) $value));

        if ($percent <= 50) {
            return $this->category(100, 'Efficient');
        }
        if ($percent <= 65) {
            return $this->category(90, 'Good');
        }
        if ($percent <= 80) {
            return $this->category(75, 'Fair');
        }
        if ($percent <= 90) {
            return $this->category(60, 'Long');
        }

        return $this->category(40, 'Very long');
    }

    private function scoreFuel(array $fuel): array
    {
        $landingFuel = $fuel['atLandingLbs'] ?? null;
        $burnRate = $fuel['burnRateLbsHr'] ?? null;

        if (!is_numeric($landingFuel) || !is_numeric($burnRate) || (float) $burnRate <= 0) {
            return $this->skipped('Fuel reserve metric unavailable');
        }

        $reserveMinutes = ((float) $landingFuel / (float) $burnRate) * 60.0;

        if ($reserveMinutes >= 45) {
            return $this->category(100, 'Excellent reserve', ['reserveMinutes' => round($reserveMinutes, 1)]);
        }
        if ($reserveMinutes >= 30) {
            return $this->category(90, 'Good reserve', ['reserveMinutes' => round($reserveMinutes, 1)]);
        }
        if ($reserveMinutes >= 20) {
            return $this->category(75, 'Fair reserve', ['reserveMinutes' => round($reserveMinutes, 1)]);
        }
        if ($reserveMinutes >= 10) {
            return $this->category(60, 'Marginal reserve', ['reserveMinutes' => round($reserveMinutes, 1)]);
        }

        return $this->category(40, 'Low reserve', ['reserveMinutes' => round($reserveMinutes, 1)]);
    }

    private function weightedComposite(array $categories, array $weights): array
    {
        $weighted = 0.0;
        $activeWeight = 0.0;

        foreach ($categories as $name => $category) {
            if (!empty($category['skipped'])) {
                continue;
            }

            $weight = (float) ($weights[$name] ?? 0);
            if ($weight <= 0) {
                continue;
            }

            $weighted += ((float) $category['score']) * $weight;
            $activeWeight += $weight;
        }

        if ($activeWeight <= 0) {
            return [null, 0.0];
        }

        return [round($weighted / $activeWeight, 2), $activeWeight];
    }

    private function grade(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        return match (true) {
            $score >= 97 => 'A+',
            $score >= 93 => 'A',
            $score >= 90 => 'A-',
            $score >= 87 => 'B+',
            $score >= 83 => 'B',
            $score >= 80 => 'B-',
            $score >= 77 => 'C+',
            $score >= 73 => 'C',
            $score >= 70 => 'C-',
            $score >= 67 => 'D+',
            $score >= 63 => 'D',
            $score >= 60 => 'D-',
            default => 'F',
        };
    }

    private function verdict(?float $score): ?string
    {
        if ($score === null) {
            return null;
        }

        return match (true) {
            $score >= 90 => 'Excellent',
            $score >= 80 => 'Good',
            $score >= 70 => 'Fair',
            $score >= 60 => 'Marginal',
            default => 'Poor',
        };
    }

    private function category(float $score, string $verdict, array $extra = []): array
    {
        return array_merge([
            'score' => round(max(0.0, min(100.0, $score)), 2),
            'verdict' => $verdict,
            'skipped' => false,
        ], $extra);
    }

    private function skipped(string $reason): array
    {
        return [
            'score' => null,
            'verdict' => null,
            'skipped' => true,
            'reason' => $reason,
        ];
    }
}
