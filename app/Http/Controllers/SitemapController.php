<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Album;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    /**
     * Erzeugt eine dynamische XML-Sitemap aller veröffentlichten Seiten, Beiträge und Fotoalben.
     */
    public function __invoke(): Response
    {
        $urls = [
            url('/'),
            url('/aktuelles'),
            url('/termine'),
            url('/galerie'),
            url('/downloads'),
            url('/kontakt'),
            url('/impressum'),
            url('/datenschutz'),
        ];

        foreach (Page::query()->published()->get(['section', 'slug']) as $page) {
            $urls[] = url("/{$page->section}/{$page->slug}");
        }

        foreach (Post::query()->published()->get(['slug']) as $post) {
            $urls[] = url("/aktuelles/{$post->slug}");
        }

        foreach (Album::query()->published()->get(['slug']) as $album) {
            $urls[] = url("/galerie/{$album->slug}");
        }

        $xml = '<?xml version="1.0" encoding="UTF-8"?>'."\n";
        $xml .= '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">'."\n";

        foreach ($urls as $loc) {
            $escaped = htmlspecialchars($loc, ENT_XML1);
            $xml .= "  <url><loc>{$escaped}</loc></url>\n";
        }

        $xml .= '</urlset>';

        return response($xml, 200, [
            'Content-Type' => 'application/xml; charset=UTF-8',
        ]);
    }
}
