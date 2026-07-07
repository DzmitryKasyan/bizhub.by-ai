<?php

namespace Tests\Feature\Pages;

use Tests\TestCase;

class LegalPageTest extends TestCase
{
    public function test_legal_page_is_accessible(): void
    {
        $response = $this->get(route('legal'));
        $response->assertOk();
        $response->assertSee(config('bizhub.platform_contacts.legal_name'));
    }

    public function test_contacts_page_shows_platform_email(): void
    {
        $response = $this->get(route('contacts'));
        $response->assertOk();
        $response->assertSee(config('bizhub.platform_contacts.email'));
    }
}
