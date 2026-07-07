<?php

declare(strict_types=1);

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Http\UploadedFile;
use Intervention\Image\ImageManager;
use Intervention\Image\Drivers\Gd\Driver;

class ValidImageContent implements ValidationRule
{
    private const ALLOWED_MIMES = [
        'image/jpeg',
        'image/png',
        'image/gif',
        'image/webp',
    ];

    private const MAX_WIDTH = 5000;
    private const MAX_HEIGHT = 5000;

    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $value instanceof UploadedFile) {
            return;
        }

        if (! $value->isValid()) {
            $fail('Файл «' . $attribute . '» не был корректно загружен.');
            return;
        }

        $mime = $value->getMimeType();
        if (! in_array($mime, self::ALLOWED_MIMES, true)) {
            $fail('Файл «' . $attribute . '» имеет недопустимый формат.');
            return;
        }

        try {
            $manager = new ImageManager(new Driver());
            $image = $manager->read($value->getPathname());

            $width = $image->width();
            $height = $image->height();

            if ($width > self::MAX_WIDTH || $height > self::MAX_HEIGHT) {
                $fail('Файл «' . $attribute . '» слишком большой (максимум ' . self::MAX_WIDTH . '×' . self::MAX_HEIGHT . ' px).');
                return;
            }

            // Try to re-encode to webp to ensure it is a real image
            $image->toWebp(80);
        } catch (\Exception $e) {
            $fail('Файл «' . $attribute . '» не является корректным изображением.');
        }
    }
}
