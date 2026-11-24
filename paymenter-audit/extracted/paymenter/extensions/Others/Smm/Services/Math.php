<?php

namespace Paymenter\Extensions\Others\Smm\Services;

class Math
{
    public static function add(string $a, string $b): string
    {
        return bcadd($a, $b, 8);
    }

    public static function sub(string $a, string $b): string
    {
        return bcsub($a, $b, 8);
    }

    public static function mul(string $a, string $b): string
    {
        return bcmul($a, $b, 8);
    }

    public static function div(string $a, string $b, int $scale = 8): string
    {
        return bcdiv($a, $b, $scale);
    }

    public static function comp(string $a, string $b): int
    {
        return bccomp($a, $b, 8);
    }

    public static function round(string $value, int $scale = 2): string
    {
        return bcdiv(bcmul($value, '1'.str_repeat('0', $scale), 0), '1'.str_repeat('0', $scale), $scale);
    }

    public static function toDecimal(mixed $value): string
    {
        return (string) bcadd((string) $value, '0', 8);
    }
}
