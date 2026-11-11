/**
 * Gemeinsame TypeScript-Typen für alle öffentlichen Seiten der Mühlmattenschule.
 */

export interface BellScheduleItem {
    label: string;
    time: string;
    type?: string;
    is_break?: boolean;
}

export interface HeroSlideItem {
    url: string;
    alt: string;
}

export interface QuickLinkItem {
    title: string;
    subtitle: string;
    url: string;
    icon: string;
}

export interface SchoolSettings {
    name: string;
    subtitle: string;
    street: string;
    zip_city: string;
    phone: string;
    email: string;
    office_hours: string;
    welcome_title?: string;
    welcome_text?: string;
    care_phone?: string;
    care_mobile?: string;
    care_email?: string;
    care_hours?: string;
    bell_schedule?: BellScheduleItem[];
    hero_slides?: HeroSlideItem[];
    quick_links?: QuickLinkItem[];
    maintenance_active?: boolean;
    maintenance_until?: string;
}

export interface AnnouncementItem {
    id: number;
    text: string;
    level: 'info' | 'important';
    linkUrl: string | null;
    linkLabel: string | null;
}

export interface NavigationPageLink {
    title: string;
    slug: string;
    section?: string;
    menuGroup?: string | null;
}

export interface RubrikCategoryItem {
    name: string;
    slug: string;
    color: string;
    count: number;
}

export interface RubrikClassItem {
    name: string;
    count: number;
}

export interface RubrikenData {
    totalPosts: number;
    categories: RubrikCategoryItem[];
    classes: RubrikClassItem[];
}

export interface BreadcrumbItem {
    label: string;
    href: string | null;
}

export interface SeoMetadata {
    title: string;
    fullTitle: string;
    siteName: string;
    description: string;
    canonicalUrl: string;
    robots: string;
    locale: string;
    ogType: 'website' | 'article';
    ogImage: string;
    ogImageAlt: string;
    twitterCard: 'summary_large_image' | 'summary';
    publishedTime: string | null;
    modifiedTime: string | null;
    section: string | null;
    prevUrl: string | null;
    nextUrl: string | null;
    breadcrumbs: BreadcrumbItem[];
    jsonLd: string;
}

export interface SharedInertiaProps {
    school: SchoolSettings;
    maintenancePreview?: boolean;
    announcements: AnnouncementItem[];
    navigationPages: {
        unsereSchule: NavigationPageLink[];
        konzepteProjekte?: NavigationPageLink[];
        angebote?: NavigationPageLink[];
        schulleben: NavigationPageLink[];
    };
    rubriken?: RubrikenData;
    seo: SeoMetadata;
    [key: string]: unknown;
}

export interface CategorySummary {
    name: string;
    slug: string;
    color: string;
}

export interface PostCardItem {
    id: number;
    title: string;
    slug: string;
    excerpt: string | null;
    className: string | null;
    isFeatured: boolean;
    publishedAt: string | null;
    publishedAtIso?: string | null;
    coverUrl: string | null;
    photosCount?: number;
    category: CategorySummary | null;
}

export interface UpcomingEventItem {
    id: number;
    title: string;
    startsAtIso?: string;
    monthHeading?: string;
    day: string;
    weekday?: string;
    monthShort?: string;
    dateFormatted: string;
    endDateFormatted?: string | null;
    timeFormatted: string;
    location: string | null;
    className?: string | null;
    category?: string | null;
    description?: string | null;
    icalUrl?: string;
}

export interface AlbumCardItem {
    id: number;
    title: string;
    slug: string;
    className: string | null;
    description?: string | null;
    eventDate: string | null;
    eventDateIso?: string | null;
    photosCount: number;
    coverUrl: string | null;
}

export interface GalleryPhotoItem {
    id: number;
    thumbUrl: string;
    largeUrl: string;
    alt: string;
}

export interface PersonItem {
    id: number;
    name: string;
    roleTitle: string;
    className: string | null;
    email: string | null;
    group: string;
    groupLabel: string;
    avatarUrl: string | null;
}
