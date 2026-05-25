<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Article;
use App\Models\BlogPost;
use App\Models\Listing;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = collect();

        $urls->push($this->u(url('/'), '1.0', 'daily'));
        $urls->push($this->u(url('/listings'), '0.9', 'daily'));
        $urls->push($this->u(url('/contacts'), '0.6', 'monthly'));
        $urls->push($this->u(url('/rates'), '0.7', 'hourly'));

        foreach (['sell-business', 'buy-business', 'investments', 'franchises', 'trust-management'] as $slug) {
            $urls->push($this->u(url("/{$slug}"), '0.8', 'daily'));
        }

        foreach (Article::where('is_published', true)->pluck('slug') as $slug) {
            $urls->push($this->u(url("/article/{$slug}"), '0.5', 'monthly'));
        }

        if (BlogPost::where('is_published', true)->exists()) {
            $urls->push($this->u(url('/blog'), '0.6', 'weekly'));
        }
        foreach (BlogPost::where('is_published', true)->pluck('slug') as $slug) {
            $urls->push($this->u(url("/blog/{$slug}"), '0.7', 'weekly'));
        }

        foreach (Listing::active()->pluck('slug') as $slug) {
            $urls->push($this->u(url("/listings/{$slug}"), '0.7', 'daily'));
        }

        $xml = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n<urlset xmlns=\"http://www.sitemaps.org/schemas/sitemap/0.9\">\n";
        foreach ($urls as $u) {
            $xml .= "  <url>\n";
            $xml .= '    <loc>' . htmlspecialchars($u['loc'], ENT_XML1) . "</loc>\n";
            $xml .= '    <changefreq>' . $u['changefreq'] . "</changefreq>\n";
            $xml .= '    <priority>' . $u['priority'] . "</priority>\n";
            $xml .= "  </url>\n";
        }
        $xml .= "</urlset>\n";

        return response($xml, 200, ['Content-Type' => 'application/xml']);
    }

    private function u(string $loc, string $priority, string $changefreq): array
    {
        return compact('loc', 'priority', 'changefreq');
    }
}
