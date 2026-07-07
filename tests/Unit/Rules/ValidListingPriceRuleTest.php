<?php

namespace Tests\Unit\Rules;

use App\Rules\ValidListingPrice;
use Illuminate\Support\Facades\Validator;
use Tests\TestCase;

class ValidListingPriceRuleTest extends TestCase
{
    public function test_rule_accepts_price_on_request(): void
    {
        $validator = Validator::make(
            ['price_strategy' => null],
            ['price_strategy' => [new ValidListingPrice(['price_on_request' => 'true'])]]
        );
        $this->assertFalse($validator->fails());
    }

    public function test_rule_rejects_false_string_as_negotiable(): void
    {
        $validator = Validator::make(
            ['price_strategy' => null],
            ['price_strategy' => [new ValidListingPrice(['price_negotiable' => 'false', 'price' => null])]]
        );
        $this->assertTrue($validator->fails());
    }

    public function test_rule_accepts_valid_price(): void
    {
        $validator = Validator::make(
            ['price_strategy' => null],
            ['price_strategy' => [new ValidListingPrice(['price' => 15000])]]
        );
        $this->assertFalse($validator->fails());
    }

    public function test_rule_rejects_price_one(): void
    {
        $validator = Validator::make(
            ['price_strategy' => null],
            ['price_strategy' => [new ValidListingPrice(['price' => 1])]]
        );
        $this->assertTrue($validator->fails());
    }
}
