<?php

namespace Tests\Unit\Models;

use App\Enums\ListingFormat;
use App\Models\Listing;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ListingKpisTest extends TestCase
{
    #[Test]
    public function it_calculates_payback_years_from_monthly_profit_and_price(): void
    {
        $listing = new Listing([
            'price' => 120_000,
            'monthly_profit' => 10_000,
        ]);

        $this->assertSame(1.0, $listing->payback_years);
    }

    #[Test]
    public function it_falls_back_to_payback_months(): void
    {
        $listing = new Listing(['payback_months' => 18]);

        $this->assertSame(1.5, $listing->payback_years);
    }

    #[Test]
    public function it_calculates_margin_percent(): void
    {
        $listing = new Listing([
            'monthly_revenue' => 100_000,
            'monthly_profit' => 25_000,
        ]);

        $this->assertSame(25.0, $listing->margin_percent);
    }

    #[Test]
    public function it_calculates_roi_estimate(): void
    {
        $listing = new Listing([
            'price' => 120_000,
            'monthly_profit' => 10_000,
        ]);

        $this->assertSame(100.0, $listing->roi_estimate_percent);
    }

    #[Test]
    public function it_returns_kpis_array(): void
    {
        $listing = new Listing([
            'price' => 120_000,
            'monthly_revenue' => 100_000,
            'monthly_profit' => 10_000,
            'payback_months' => 12,
            'employees_count' => 5,
        ]);

        $kpis = $listing->kpis;

        $this->assertCount(5, $kpis);
        $this->assertSame('Прибыль/мес', $kpis[0]['label']);
        $this->assertSame('Окупаемость', $kpis[1]['label']);
        $this->assertSame('Маржа', $kpis[2]['label']);
        $this->assertSame('ROI/год', $kpis[3]['label']);
        $this->assertSame('Сотрудников', $kpis[4]['label']);
    }

    #[Test]
    public function listing_format_is_casted(): void
    {
        $listing = new Listing(['listing_format' => 'asset_sale']);

        $this->assertInstanceOf(ListingFormat::class, $listing->listing_format);
        $this->assertSame(ListingFormat::AssetSale, $listing->listing_format);
    }
}
