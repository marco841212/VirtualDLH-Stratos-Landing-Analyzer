<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding\Scoring;

final class DefaultScoringConfig
{
    public static function all(): array
    {
        return [
            'model' => 'virtualdlh-default-v1',
            'weights' => [
                'landingRate' => 30,
                'gForce' => 20,
                'bounce' => 15,
                'approach' => 20,
                'rollout' => 10,
                'fuel' => 5,
            ],
        ];
    }
}
