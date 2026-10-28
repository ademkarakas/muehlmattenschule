/**
 * Gemeinsame TypeScript-Typen für alle öffentlichen Seiten der Mühlmattenschule.
 */

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
}

export interface SharedInertiaProps {
    school: SchoolSettings;
    announcements: AnnouncementItem[];
    navigationPages: {
        unsereSchule: NavigationPageLink[];
        schulleben: NavigationPageLink[];
    };
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
    coverUrl: string | null;
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
    description?: string | null;
}

export interface AlbumCardItem {
    id: number;
    title: string;
    slug: string;
    className: string | null;
    description?: string | null;
    eventDate: string | null;
    photosCount: number;
    coverUrl: string | null;
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

