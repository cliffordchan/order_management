<?php

namespace Tests\Rules;

use App\Rules\LongitudeRule;
use Tests\TestCase;

/**
 * Class LongitudeTest
 * @package Tests\Rules
 */
class LongitudeTest extends TestCase
{
    /**
     * @var LongitudeRule
     */
    protected $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new LongitudeRule();
    }

    /**
     * @dataProvider longitudeProvider
     * @param float|string|int $latitude
     * @param bool $expected
     */
    public function testLatitude($latitude, $expected)
    {
        $this->assertSame($this->rule->passes('test', $latitude), $expected);
    }

    /**
     * @return array
     */
    public function longitudeProvider()
    {
        return [
            [40.20361, true],
            [40.74229, true],
            [-185.00584, false],
            [-2, true],
            [-20.1, true],
            [-2.233, true],
            [0, true],
            [2, true],
            [-179.00584, true],
            [20.1, true],
            [2.233, true],
        ];
    }
}
