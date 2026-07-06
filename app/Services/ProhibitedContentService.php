<?php

declare(strict_types=1);

namespace App\Services;

class ProhibitedContentService
{
    /**
     * @return string[]
     */
    public function patterns(): array
    {
        return config('bizhub.prohibited_patterns', []);
    }

    public function contains(string $text): bool
    {
        foreach ($this->patterns() as $pattern) {
            if (mb_stripos($text, $pattern, 0, 'UTF-8') !== false) {
                return true;
            }
        }

        return false;
    }

    /**
     * @return string[]
     */
    public function detect(string $text): array
    {
        $found = [];

        foreach ($this->patterns() as $pattern) {
            if (mb_stripos($text, $pattern, 0, 'UTF-8') !== false) {
                $found[] = $pattern;
            }
        }

        return array_values(array_unique($found));
    }
}
