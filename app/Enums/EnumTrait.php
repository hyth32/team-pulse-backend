<?php

namespace App\Enums;

trait EnumTrait
{
    public static function values(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value();
        }

        return $values;
    }

    public static function labels(): array
    {
        $labels = [];
        foreach (self::cases() as $case) {
            $name = $case->name;
            $labels[] = $case->label();
        }

        return $labels;
    }

    public static function valueLabels(array $withoutLabels = null): array
    {
        $valueLabels = [];
        foreach (self::cases() as $case) {
            $valueLabels[$case->value()] = $case->label();
        }
        if (!empty($withoutLabels)) {
            $valueLabels = array_diff($valueLabels, $withoutLabels);
        }
        return $valueLabels;
    }

    public static function labelValues(): array {
        $labelValues = [];
        foreach (self::cases() as $case) {
            $labelValues[$case->label()] = $case->value();
        }

        return $labelValues;
    }

    public static function getLabelFromValue(int $value): string | null
    {
        $valueLabels = self::valueLabels();

        return $valueLabels[$value] ?? null;
    }

    public static function getValueFromLabel(string $label): int | null
    {
        $valueLabels = self::labelValues();

        return $valueLabels[$label] ?? null;
    }
}
