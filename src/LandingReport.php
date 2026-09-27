<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding;

final class LandingReport
{
    public function __construct(private readonly array $data)
    {
    }

    public static function fromArray(array $data): self
    {
        return new self(StratosReportNormalizer::normalize($data));
    }

    public function toArray(): array
    {
        return $this->data;
    }

    public function score(): ?float
    {
        $value = $this->data['composite_score'] ?? null;
        return is_numeric($value) ? (float) $value : null;
    }

    public function grade(): ?string
    {
        $value = $this->data['grade'] ?? null;
        return is_string($value) && $value !== '' ? $value : null;
    }

    public function landingRateFpm(): ?float
    {
        $value = $this->data['landing_rate_fpm'] ?? null;
        return is_numeric($value) ? (float) $value : null;
    }

    public function bounceCount(): int
    {
        return (int) ($this->data['bounce_count'] ?? 0);
    }

    public function analysisAvailable(): bool
    {
        return (bool) ($this->data['analysis_available'] ?? false);
    }
}
