import type { SharedInertiaProps } from '@/types/school';
import { Head, usePage } from '@inertiajs/react';
import type { ReactElement } from 'react';

export interface SeoHeadProps {
    title?: string;
    description?: string;
}

/**
 * Synchronisiert sämtliche SEO-, Open-Graph-, Twitter-Card-, Canonical- und Schema.org-JSON-LD-Tags
 * bei clientseitiger Inertia-Navigation mit den serverseitig vorgerenderten Head-Tags.
 */
export function SeoHead({ title, description }: SeoHeadProps): ReactElement {
    const { seo } = usePage<SharedInertiaProps>().props;

    const effectiveTitle = title ? `${title} – Mühlmattenschule` : seo.fullTitle;
    const effectiveDescription = description || seo.description;

    return (
        <Head title={effectiveTitle}>
            <meta head-key="description" name="description" content={effectiveDescription} />
            <meta head-key="robots" name="robots" content={seo.robots} />
            <link head-key="canonical" rel="canonical" href={seo.canonicalUrl} />
            {seo.prevUrl && <link head-key="prev" rel="prev" href={seo.prevUrl} />}
            {seo.nextUrl && <link head-key="next" rel="next" href={seo.nextUrl} />}

            <meta head-key="og:locale" property="og:locale" content={seo.locale} />
            <meta head-key="og:site_name" property="og:site_name" content={seo.siteName} />
            <meta head-key="og:type" property="og:type" content={seo.ogType} />
            <meta head-key="og:title" property="og:title" content={effectiveTitle} />
            <meta head-key="og:description" property="og:description" content={effectiveDescription} />
            <meta head-key="og:url" property="og:url" content={seo.canonicalUrl} />
            <meta head-key="og:image" property="og:image" content={seo.ogImage} />
            <meta head-key="og:image:alt" property="og:image:alt" content={seo.ogImageAlt} />

            {seo.ogType === 'article' && seo.publishedTime && (
                <meta
                    head-key="article:published_time"
                    property="article:published_time"
                    content={seo.publishedTime}
                />
            )}
            {seo.ogType === 'article' && seo.modifiedTime && (
                <meta
                    head-key="article:modified_time"
                    property="article:modified_time"
                    content={seo.modifiedTime}
                />
            )}
            {seo.ogType === 'article' && seo.section && (
                <meta head-key="article:section" property="article:section" content={seo.section} />
            )}

            <meta head-key="twitter:card" name="twitter:card" content={seo.twitterCard} />
            <meta head-key="twitter:title" name="twitter:title" content={effectiveTitle} />
            <meta head-key="twitter:description" name="twitter:description" content={effectiveDescription} />
            <meta head-key="twitter:image" name="twitter:image" content={seo.ogImage} />
            <meta head-key="twitter:image:alt" name="twitter:image:alt" content={seo.ogImageAlt} />

            {seo.jsonLd && (
                <script
                    head-key="json-ld"
                    type="application/ld+json"
                    dangerouslySetInnerHTML={{ __html: seo.jsonLd }}
                />
            )}
        </Head>
    );
}
