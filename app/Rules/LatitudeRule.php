<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

/**
 * Class LatitudeRule
 * @package App\Rules
 */
class LatitudeRule implements Rule
{
    const REGEX = '/^(\+|-)?(?:90(?:(?:\.0{1,6})?)|(?:[0-9]|[1-8][0-9])(?:(?:\.[0-9]{1,6})?))$/';

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
