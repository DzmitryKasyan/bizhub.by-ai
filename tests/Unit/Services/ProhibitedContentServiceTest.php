<?php

namespace Tests\Unit\Services;

use App\Services\ProhibitedContentService;
use Tests\TestCase;

class ProhibitedContentServiceTest extends TestCase
{
    private ProhibitedContentService $service;

    protected function setUp(): void
    {
        parent::setUp();
        $this->service = new ProhibitedContentService();
    }

    public function test_detects_prohibited_content(): void
    {
        $this->assertTrue($this->service->contains('Предлагаю займ под залог авто'));
        $this->assertTrue($this->service->contains('Кредит на любые цели'));
    }

    public function test_allows_clean_content(): void
    {
        $this->assertFalse($this->service->contains('Продаю готовый магазин одежды'));
    }

    public function test_returns_detected_patterns(): void
    {
        $patterns = $this->service->detect('Займ и кредит без справок');
        $this->assertContains('займ', $patterns);
        $this->assertContains('кредит', $patterns);
    }

    public function test_detects_cyrillic_case_insensitive(): void
    {
        $this->assertTrue($this->service->contains('Предлагаю ЗАЙМ без залога'));
    }
}
