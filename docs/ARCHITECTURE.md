# Mimari ve Kurallar (Architecture & Conventions)

Bu dosya, projeye katılan her geliştirici / yapay zeka ajanı için **bağlayıcı** teknik kararları içerir.
Bir kararı değiştirmeden önce burada güncelle ve `docs/HANDOFF.md` içine gerekçesini yaz.

## 1. Amaç ve kapsam
- Mühlmattenschule Freiburg-Hochdorf (Grundschule) için **dinamik okul web sitesi**.
- Örnek alınan mantık: EduPage (ghsfreiburg.edupage.org) – haber akışı, galeri, takvim.
- **Kapsam dışı:** yoklama, not sistemi, veli/öğrenci girişi. Giriş sadece personel için (`/admin`).
- Site içeriği ve admin arayüzü **tamamen Almanca**. Kod (değişken, sınıf, tablo adları) **İngilizce**.

## 2. Teknoloji yığını
| Katman | Teknoloji |
|---|---|
| Dil (backend) | PHP 8.3 (`composer.json` → `config.platform.php = 8.3`) |
| Framework | Laravel 13 |
| Admin paneli | Filament (`/admin`) |
| Frontend | React 19 + TypeScript (strict) + Inertia.js 2 |
| CSS | Tailwind CSS 4 (tema `resources/css/app.css` içinde `@theme`) |
| Build | Vite |
| Medya | spatie/laravel-medialibrary (+ Filament eklentisi) |
| Veritabanı | Lokal: SQLite · Prod: MariaDB (IONOS Deploy Now) |
| Hosting | IONOS Deploy Now – PHP projesi (GitHub push → otomatik deploy) |

> Prod ortamında Node.js **yok**. Bu yüzden Inertia SSR kullanılmaz; frontend `npm run build` ile
> derlenip `public/build` altında statik olarak sunulur.

## 3. Klasör yapısı (hedef)
```
app/
  Enums/                  UserRole, PublishStatus ...
  Filament/Resources/     Admin paneli kaynakları
  Http/Controllers/       Genel (public) sayfa controller'ları – Inertia::render
  Models/
  Policies/
resources/
  css/app.css             Tailwind + tema (turkuaz)
  js/
    app.tsx               Inertia giriş noktası
    components/           Tekrar kullanılan UI bileşenleri (kebab-case dosya adları)
    layouts/              site-layout.tsx
    pages/                Inertia sayfaları (ör. pages/home.tsx, pages/posts/index.tsx)
    types/                Paylaşılan TS tipleri (Inertia props)
  views/app.blade.php     Inertia root şablonu
docs/                     ROADMAP, HANDOFF, ARCHITECTURE
```

## 4. Veri modeli (özet)
| Model | Tablo | Önemli alanlar |
|---|---|---|
| `Category` | categories | name, slug, color |
| `Post` | posts | title, slug, excerpt, body (HTML), category_id, album_id?, status, published_at, author_id |
| `Event` | events | title, starts_at, ends_at?, all_day, location?, description? |
| `Album` | albums | title, slug, date, description?, consent_confirmed (bool, zorunlu true), status, published_at; medya koleksiyonu `photos` |
| `Announcement` | announcements | text, level (info/important), visible_from?, visible_until? |
| `Page` | pages | title, slug, parent_id?, menu_order, content (JSON blokları), status |
| `Person` | people | name, role_title, group (Schulleitung/Kollegium/Verwaltung/...), sort, photo (medya) |
| `Download` | downloads | title, category, file (medya) |
| `Setting` | settings | key, value (JSON) |
| `User` | users | + role (admin/schulleitung/sekretariat) |

## 5. Roller
| Rol | Yetki |
|---|---|
| `admin` | Her şey |
| `schulleitung` | Tüm içerik + sekretariat ve lehrkraft kullanıcılarını yönetme + okul ayarları + sabit sayfalar |
| `sekretariat` | Beiträge, Termine, Fotoalben, Ankündigungen, Downloads, Kategorien, Kollegium |
| `lehrkraft` | Kendi sınıfı (`class_name`) veya okul için Beiträge ve Fotoalben oluşturma/düzenleme/silme (yalnızca kendi oluşturduklarını değiştirebilir) + Termine ekleme |

## 6. Kodlama kuralları
- PHP: PSR-12 / Laravel Pint, strict tipler, `declare(strict_types=1);` yeni dosyalarda.
- PHP: tüm public metotlarda PHPDoc; Form Request ile doğrulama; N+1 yok (`with()`); listelerde sayfalama.
- TS: `strict: true`, `any` yasak, dosya adları kebab-case (`news-card.tsx`), bileşenler PascalCase.
- TS: her export edilen fonksiyon/bileşen için JSDoc.
- Commit mesajları: Conventional Commits (`feat(admin): ...`, `fix(frontend): ...`).
- Branch: `main` = yayındaki kod. Özellikler `feature/<isim>` dalında.
- Gizli bilgiler **asla** repoya girmez (`.env` git'te yok, `.env.example` var).

## 7. Tasarım
- Ana renk: Okulun resmi logosundaki (`public/images/school/logo-mms-2023.png`) gerçek **Logo Turkuazı (`#029786`)**.
- Tailwind tema değişkenleri (`resources/css/app.css`): `brand-50 … brand-900` (ana `brand-500 = #029786`, koyu `brand-600 = #007f70`, `brand-700 = #00695c`, `brand-800 = #00544a`, `brand-900 = #003d36`), vurgu `accent = #F59E0B`.
- Font: sistem fontu / yerel barındırılan font (Google Fonts **yok** – DSGVO).
- Mobil öncelikli, WCAG AA kontrast, `A- / A / A+` erişilebilirlik yazı boyutu ölçekleme desteği.
- **Admin paneli (`/admin`) form standardı:** Kayıt oluşturma ve düzenleme işlemleri popup/modal içinde **değil**, her zaman müstakil sayfalarda (`List*`, `Create*`, `Edit*`) açılır; yalnızca silme onayı küçük bir modal olarak kalır. Tüm oluşturma/düzenleme ve ayar (`ManageSchoolSettings`) sayfaları `resources/views/filament/admin-theme.blade.php` (Bölüm 10) içindeki açık turkuaz üst başlık (`#e6f7f5` → `#bfeae3` + `#029786` alt çizgi) ve yuvarlatılmış modern kart tasarımını paylaşır.

## 8. Yasal (DSGVO)
- Albüm yayınlamak için `consent_confirmed = true` zorunlu.
- Yüklenen görsellerden EXIF/GPS silinir.
- Harici izleme / CDN / Google servisleri yok → çerez banner'ı gerekmez.

## 9. Lokal geliştirme
```powershell
composer install
npm install
copy .env.example .env   # ilk kez
php artisan key:generate
php artisan migrate --seed
composer run dev          # Laravel + Vite birlikte
```
Admin: http://127.0.0.1:8000/admin

