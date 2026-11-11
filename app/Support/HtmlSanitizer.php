<?php

declare(strict_types=1);

namespace App\Support;

use DOMDocument;
use DOMElement;
use DOMNode;

class HtmlSanitizer
{
    /**
     * Erlaubte HTML-Tags für Rich-Text-Inhalte (Beiträge & Schulseiten).
     *
     * @var array<int, string>
     */
    private const ALLOWED_TAGS = [
        'p', 'br', 'strong', 'b', 'em', 'i', 'u', 's', 'del', 'sub', 'sup',
        'h2', 'h3', 'h4', 'ul', 'ol', 'li', 'blockquote', 'pre', 'code',
        'a', 'hr', 'figure', 'figcaption', 'img', 'table', 'thead', 'tbody',
        'tr', 'th', 'td',
    ];

    /**
     * Tags, deren gesamter Inhalt (inklusive Kindknoten) vollständig entfernt wird.
     *
     * @var array<int, string>
     */
    private const DROP_WITH_CONTENT_TAGS = [
        'script', 'style', 'iframe', 'object', 'embed', 'applet',
        'svg', 'math', 'head', 'title', 'meta', 'link', 'base',
        'form', 'input', 'button', 'select', 'textarea', 'template',
    ];

    /**
     * Bereinigt HTML-Inhalte mittels einer strikten DOM-Whitelist gegen Stored-XSS.
     */
    public static function clean(?string $html): string
    {
        if ($html === null || trim($html) === '') {
            return '';
        }

        $dom = new DOMDocument('1.0', 'UTF-8');
        $previousErrorState = libxml_use_internal_errors(true);

        $wrappedHtml = '<!DOCTYPE html><html><head><meta charset="UTF-8"></head><body>'
            .$html
            .'</body></html>';

        $dom->loadHTML($wrappedHtml, LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD | LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previousErrorState);

        $body = $dom->getElementsByTagName('body')->item(0);
        if (! $body instanceof DOMElement) {
            return '';
        }

        self::sanitizeNode($body);

        $output = '';
        foreach ($body->childNodes as $child) {
            $output .= $dom->saveHTML($child);
        }

        return trim($output);
    }

    /**
     * Prüft und bereinigt eine URL gegen gefährliche Schemata (z. B. javascript:, data:, vbscript:).
     */
    public static function sanitizeUrl(?string $url, bool $allowMailtoAndTel = false): ?string
    {
        if ($url === null) {
            return null;
        }

        $trimmed = trim($url);
        if ($trimmed === '') {
            return null;
        }

        // Steuerzeichen und unsichtbare Whitespace-Tricks entfernen
        $normalized = preg_replace('/[\x00-\x20]+/', '', $trimmed) ?? '';

        if ($normalized === '') {
            return null;
        }

        // Interne Pfade (z. B. /aktuelles oder #anker) erlauben, aber keine protokoll-relativen URLs (//evil.example)
        if (str_starts_with($normalized, '/') && ! str_starts_with($normalized, '//')) {
            return $trimmed;
        }

        if (str_starts_with($normalized, '#')) {
            return $trimmed;
        }

        $scheme = strtolower((string) parse_url($normalized, PHP_URL_SCHEME));
        $allowedSchemes = ['http', 'https'];

        if ($allowMailtoAndTel) {
            $allowedSchemes[] = 'mailto';
            $allowedSchemes[] = 'tel';
        }

        if ($scheme !== '' && in_array($scheme, $allowedSchemes, true)) {
            return $trimmed;
        }

        return null;
    }

    /**
     * Durchläuft rekursiv alle DOM-Knoten und entfernt unerlaubte Elemente sowie Attribute.
     */
    private static function sanitizeNode(DOMNode $node): void
    {
        $children = iterator_to_array($node->childNodes);

        foreach ($children as $child) {
            if ($child->nodeType === XML_COMMENT_NODE) {
                $node->removeChild($child);

                continue;
            }

            if ($child instanceof DOMElement) {
                $tagName = strtolower($child->tagName);

                if (in_array($tagName, self::DROP_WITH_CONTENT_TAGS, true)) {
                    $node->removeChild($child);

                    continue;
                }

                if (! in_array($tagName, self::ALLOWED_TAGS, true)) {
                    self::sanitizeNode($child);
                    while ($child->firstChild !== null) {
                        $node->insertBefore($child->firstChild, $child);
                    }
                    $node->removeChild($child);

                    continue;
                }

                self::sanitizeAttributes($child, $tagName);
                self::sanitizeNode($child);
            }
        }
    }

    /**
     * Entfernt alle nicht ausdrücklich freigegebenen Attribute eines HTML-Elements.
     */
    private static function sanitizeAttributes(DOMElement $element, string $tagName): void
    {
        $attributeNames = [];
        foreach ($element->attributes as $attribute) {
            $attributeNames[] = $attribute->name;
        }

        foreach ($attributeNames as $attrName) {
            $lowerAttr = strtolower($attrName);

            if ($tagName === 'a' && in_array($lowerAttr, ['href', 'target', 'rel'], true)) {
                if ($lowerAttr === 'href') {
                    $safeHref = self::sanitizeUrl($element->getAttribute($attrName), true);
                    if ($safeHref === null) {
                        $element->removeAttribute($attrName);
                    } else {
                        $element->setAttribute('href', $safeHref);
                    }
                } elseif ($lowerAttr === 'target') {
                    $targetVal = strtolower($element->getAttribute($attrName));
                    if ($targetVal !== '_blank') {
                        $element->removeAttribute($attrName);
                    } else {
                        $element->setAttribute('target', '_blank');
                        $element->setAttribute('rel', 'noopener noreferrer');
                    }
                }

                continue;
            }

            if ($tagName === 'img' && in_array($lowerAttr, ['src', 'alt', 'width', 'height'], true)) {
                if ($lowerAttr === 'src') {
                    $safeSrc = self::sanitizeUrl($element->getAttribute($attrName), false);
                    if ($safeSrc === null) {
                        $element->removeAttribute($attrName);
                    } else {
                        $element->setAttribute('src', $safeSrc);
                    }
                }

                continue;
            }

            $element->removeAttribute($attrName);
        }

        if ($tagName === 'a' && $element->getAttribute('target') === '_blank') {
            $element->setAttribute('rel', 'noopener noreferrer');
        }
    }
}
