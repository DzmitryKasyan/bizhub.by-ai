<?php

declare(strict_types=1);

$seederPath = __DIR__ . '/../database/seeders/ArticleSeeder.php';
$jsonPath = $argv[1] ?? null;

if (!$jsonPath || !file_exists($jsonPath)) {
    fwrite(STDERR, "Usage: php scripts/update_articles.php <json-file>\n");
    exit(1);
}

$updates = json_decode(file_get_contents($jsonPath), true);
if (!is_array($updates)) {
    fwrite(STDERR, "Invalid JSON\n");
    exit(1);
}

$content = file_get_contents($seederPath);

foreach ($updates as $slug => $newContent) {
    // Find the article block by slug
    $pattern = '/(\[\s*\'slug\'\s*=>\s*\'' . preg_quote($slug, '/') . '\'\s*,\s*\'article_category_id\'\s*=>\s*\$categoryIds\[[^\]]+\]\s*,\s*\'content\'\s*=>\s*\')(.*?)(\',\s*\'meta_description\'\s*=>)/s';
    
    if (!preg_match($pattern, $content, $matches)) {
        fwrite(STDERR, "Could not find article: $slug\n");
        continue;
    }
    
    $escaped = str_replace(['\\', '\''], ['\\\\', '\\\''], $newContent);
    $replacement = $matches[1] . $escaped . $matches[3];
    $content = preg_replace($pattern, $replacement, $content, 1);
    echo "Updated: $slug\n";
}

file_put_contents($seederPath, $content);
echo "Done\n";
