<?php

declare(strict_types=1);

namespace VirtualDLH\StratosLanding;

final class StratosReportValidator
{
    public static function errors(array $report): array
    {
        $errors = [];

        if (!is_array($report['touchdown'] ?? [])) {
            $errors[] = 'touchdown must be an object/associative array';
        }

        if (!is_array($report['bounces'] ?? [])) {
            $errors[] = 'bounces must be an array';
        }

        foreach (['compositeScore', 'composite_score'] as $field) {
            if (array_key_exists($field, $report) && $report[$field] !== null && !is_numeric($report[$field])) {
                $errors[] = $field . ' must be numeric or null';
            }
        }

        if (isset($report['grade']) && !is_string($report['grade'])) {
            $errors[] = 'grade must be a string';
        }

        return $errors;
    }

    public static function isValid(array $report): bool
    {
        return self::errors($report) === [];
    }
}
