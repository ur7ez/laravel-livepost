<?php

namespace App\Helpers\Math;

class ArithmeticHelper
{
    public static function add(...$nums)
    {
        if (count($nums) < 1) {
            throw new \InvalidArgumentException('Must have at least 1 argument');
        }
        $sum = 0;
        foreach ($nums as $num) {
            if (!self::_isNumeric($num)) {
                throw new \InvalidArgumentException('Argument can only be numeric');
            }
            $sum += $num;
        }
        return $sum;
    }

    private static function _isNumeric($number): bool
    {
        return (is_float($number) || is_int($number));
    }

    public static function minus(...$nums)
    {
        if (count($nums) < 1) {
            throw new \InvalidArgumentException("Must have at least 1 argument");
        }
        $subtraction = $nums[0];
        throw_if(!self::_isNumeric($subtraction), \InvalidArgumentException::class);
        foreach (array_slice($nums, 1) as $num) {
            if (!self::_isNumeric($num)) {
                throw new \InvalidArgumentException("Argument can only be numeric");
            }
            $subtraction -= $num;
        }
        return $subtraction;
    }
}
