<?php

namespace Tests\Rules;

use App\Rules\LatitudeRule;
use Tests\TestCase;

/**
 * Class LatitudeTest
 * @package Tests\Rules
 */
class LatitudeTest extends TestCase
{
    /**
     * @var LatitudeRule
     */
    protected $rule;

    public function setUp()
    {
        parent::setUp();

        $this->rule = new LatitudeRule();
    }

    /**
     * @dataProvider latitudeProvider
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
    public function latitudeProvider()
    {
        return [
            [40.20361, true],
            [40.74229, true],
            [-100, false],
            [-2, true],
            [-2.1, true],
            [-2.2, true],
            [0, true],
            [2, true],
            [100, false],
            [2.1, true],
            [2.2, true],
        ];
    }
}
