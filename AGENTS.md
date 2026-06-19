# Agent Notes

## Security Checklist

### File uploads / external image downloads
Always verify that any code that saves files to `storage/app/public` (or any web-accessible path) does **not** rely on the original filename or extension.

Applies to:
- User-uploaded images/documents.
- Images downloaded from external URLs (parsers, importers, integrations).

Required pattern (same as CVE fix `5cb2058`):
1. Validate file content (e.g. `App\Rules\ValidImageContent` or `Intervention\Image`).
2. Re-encode images to WebP (or another safe format).
3. Generate a UUID filename and force the safe extension.
4. Do not preserve the original extension or basename.
5. Limit download size and redirects for external URLs.

Example for user uploads:
```php
$manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
$image = $manager->read($file->getPathname());
$filename = \Illuminate\Support\Str::uuid() . '.webp';
$path = 'listings/' . $filename;
\Illuminate\Support\Facades\Storage::disk('public')->put($path, $image->toWebp(80));
```

Example for external images:
```php
$img = @file_get_contents($url, false, stream_context_create([
    'http' => ['timeout' => 10, 'max_redirects' => 2],
]));
if ($img && strlen($img) > 2000 && strlen($img) < 10_000_000) {
    $tempPath = tempnam(sys_get_temp_dir(), 'external_');
    file_put_contents($tempPath, $img);
    $manager = new \Intervention\Image\ImageManager(new \Intervention\Image\Drivers\Gd\Driver());
    $image = $manager->read($tempPath);
    $dest = 'listings/' . \Illuminate\Support\Str::uuid() . '.webp';
    \Illuminate\Support\Facades\Storage::disk('public')->put($dest, $image->toWebp(80));
    unlink($tempPath);
}
```

Before finishing any task involving file writes, ask: **"Could an attacker control the filename, extension, or content here?"**
