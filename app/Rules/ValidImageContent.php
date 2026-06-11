<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ValidImageContent implements ValidationRule
{
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (!$value instanceof \Illuminate\Http\UploadedFile) {
            return;
        }

        $ext = strtolower($value->getClientOriginalExtension());
        if ($ext === 'svg') {
            return;
        }

        try {
            $manager = new ImageManager(new Driver());
            $manager->read($value->getPathname());
        } catch (\Exception $e) {
            $fail('Файл «' . $attribute . '» не является корректным изображением.');
        }
    }
}
