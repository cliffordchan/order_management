<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class LongitudeRule
 * @package App\Rules
 */
class LongitudeRule implements Rule
{
    const REGEX = '/^(\+|-)?(?:180(?:(?:\.0{1,6})?)|(?:[0-9]|[1-9][0-9]|1[0-7][0-9])(?:(?:\.[0-9]{1,6})?))$/';

    /**
     * @param string $attribute
     * @param mixed $value
     * @return bool|false|int
     */
    public function passes($attribute, $value)
    {
        return 1 === preg_match(static::REGEX, $value, $matches);
    }

    /**
     * @return string
     */
    public function message()
    {
        return 'The :attribute do not match ISO 6709 standard.';
    }
}
