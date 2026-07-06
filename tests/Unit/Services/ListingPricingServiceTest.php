<?php

namespace Tests\Unit\Services;

use App\Services\ListingPricingService;
use Tests\TestCase;

class ListingPricingServiceTest extends TestCase
{
    private ListingPricingService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ListingPricingService();
    }

    public function test_valid_price_is_accepted(): void
    {
        $this->assertTrue($this->service->isValid(15000, null, false, false));
    }

    public function test_price_on_request_is_valid(): void
    {
        $this->assertTrue($this->service->isValid(null, null, false, true));
    }

    public function test_price_negotiable_is_valid(): void
    {
        $this->assertTrue($this->service->isValid(null, null, true, false));
    }

    public function test_price_one_is_invalid_by_default(): void
    {
        $this->assertFalse($this->service->isValid(1, null, false, false));
    }

    public function test_price_one_can_be_explicitly_allowed(): void
    {
        $this->assertTrue($this->service->isValid(1, null, false, false, true));
    }

    public function test_no_price_strategy_is_invalid(): void
    {
        $this->assertFalse($this->service->isValid(null, null, false, false));
    }
}
