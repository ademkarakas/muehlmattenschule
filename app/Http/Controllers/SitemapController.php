<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;
use Illuminate\Support\Carbon;

class SitemapController extends Controller
{
    /**
     * Erzeugt eine dynamische XML-Sitemap aller veröffentlichten Seiten, Beiträge und Fotoalben
     * inklusive <lastmod>, <changefreq> und <priority>.
     */
    public function __invoke(): Response
    {
        $latestPostAt = Post::query()->published()->max('updated_at');
        $latestAlbumAt = Album::query()->published()->max('updated_at');
        $todayIso = now()->toAtomString();

        /** @var list<array{loc: string, lastmod: string, changefreq: string, priority: string}> $entries */
        $entries = [
            [
                'loc' => url('/'),
                'lastmod' => $latestPostAt ? (string) Carbon::parse($latestPostAt)->toAtomString() : $todayIso,
                'changefreq' => 'daily',
                'priority' => '1.0',
            ],
            [
                'loc' => url('/aktuelles'),
                'lastmod' => $latestPostAt ? (string) Carbon::parse($latestPostAt)->toAtomString() : $todayIso,
                'changefreq' => 'daily',
                'priority' => '0.9',
            ],
            [
                'loc' => url('/termine'),
                'lastmod' => $todayIso,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/galerie'),
                'lastmod' => $latestAlbumAt ? (string) Carbon::parse($latestAlbumAt)->toAtomString() : $todayIso,
                'changefreq' => 'weekly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/downloads'),
                'lastmod' => $todayIso,
                'changefreq' => 'weekly',
                'priority' => '0.7',
            ],
            [
                'loc' => url('/kontakt'),
                'lastmod' => $todayIso,
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ],
            [
                'loc' => url('/impressum'),
                'lastmod' => $todayIso,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
            [
                'loc' => url('/datenschutz'),
                'lastmod' => $todayIso,
                'changefreq' => 'yearly',
                'priority' => '0.3',
            ],
        ];

        foreach (Page::query()->published()->orderBy('menu_order')->get(['section', 'slug', 'updated_at']) as $page) {
            $entries[] = [
                'loc' => url("/{$page->section}/{$page->slug}"),
                'lastmod' => ($page->updated_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.8',
            ];
        }

        foreach (Post::query()->published()->orderByDesc('published_at')->get(['slug', 'published_at', 'updated_at']) as $post) {
            $entries[] = [
                'loc' => url("/aktuelles/{$post->slug}"),
                'lastmod' => ($post->updated_at ?? $post->published_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.7',
            ];
        }

        foreach (Album::query()->published()->orderByDesc('event_date')->get(['slug', 'published_at', 'updated_at']) as $album) {
            $entries[] = [
                'loc' => url("/galerie/{$album->slug}"),
                'lastmod' => ($album->updated_at ?? $album->published_at ?? now())->toAtomString(),
                'changefreq' => 'monthly',
                'priority' => '0.6',
            ];
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($entries as $entry) {
            $loc = htmlspecialchars($entry['loc'], ENT_XML1);
            $lastmod = htmlspecialchars($entry['lastmod'], ENT_XML1);
            $changefreq = htmlspecialchars($entry['changefreq'], ENT_XML1);
            $priority = htmlspecialchars($entry['priority'], ENT_XML1);

            $xml .= "  <url>\n";
            $xml .= "    <loc>{$loc}</loc>\n";
            $xml .= "    <lastmod>{$lastmod}</lastmod>\n";
            $xml .= "    <changefreq>{$changefreq}</changefreq>\n";
            $xml .= "    <priority>{$priority}</priority>\n";
            $xml .= "  </url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
            'Cache-Control' => 'public, max-age=3600',
        ]);
    }
}
