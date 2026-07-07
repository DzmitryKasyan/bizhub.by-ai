<?php

namespace Tests\Unit\Config;

use Tests\TestCase;

class BizhubConfigTest extends TestCase
{
    public function test_platform_contacts_config_exists(): void
    {
        $this->assertNotNull(config('bizhub.platform_contacts.legal_name'));
        $this->assertNotNull(config('bizhub.platform_contacts.email'));
        $this->assertSame('info@bizhub.by', config('bizhub.platform_contacts.email'));
        $this->assertSame('Индивидуальный предприниматель / ООО «BizHub»', config('bizhub.platform_contacts.legal_name'));
    }

    public function test_prohibited_patterns_config_exists(): void
    {
        $patterns = config('bizhub.prohibited_patterns');
        $this->assertIsArray($patterns);
        $this->assertNotEmpty($patterns);
        $this->assertContains('займ', $patterns);
        $this->assertContains('кредит', $patterns);
        $this->assertContains('форекс', $patterns);
    }

    public function test_representative_default_note_config_exists(): void
    {
        $note = config('bizhub.representative_default_note');
        $this->assertIsString($note);
        $this->assertNotEmpty($note);
    }

    public function test_prohibited_patterns_are_case_insensitive_substrings(): void
    {
        $patterns = config('bizhub.prohibited_patterns');
        $text = 'Предлагаю ЗАЙМ под залог';
        $found = false;
        foreach ($patterns as $pattern) {
            if (mb_stripos(mb_strtolower($text), mb_strtolower($pattern)) !== false) {
                $found = true;
                break;
            }
        }
        $this->assertTrue($found);
    }
}
