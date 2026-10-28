# Yol Haritası & İlerleme – Mühlmattenschule Web Sitesi

> **Bu dosya proje ilerlemesinin tek doğru kaynağıdır.**
> Her yeni yapay zeka oturumu / geliştirici: önce [`docs/HANDOFF.md`](HANDOFF.md) dosyasını oku,
> sonra burada sıradaki açık görevi (`[ ]`) yap, bitince işaretle (`[x]`) ve
> `docs/HANDOFF.md` içindeki oturum günlüğüne kısa bir kayıt ekle.

İşaretler: `[x]` tamamlandı · `[~]` devam ediyor · `[ ]` açık · `[-]` bilinçli olarak iptal

---

## Faz 0 – Ortam ve proje temeli
- [x] 0.1 PHP 8.3 kur (`intl`, `gd`, `exif`, `zip`, `pdo_mysql`, `pdo_sqlite` eklentileri ile)
- [x] 0.2 Composer kur
- [x] 0.3 Node.js 22 + npm (zaten vardı)
- [x] 0.4 Laravel 13 projesini oluştur
- [x] 0.5 Git deposunu başlat, ilk commit
- [x] 0.6 GitHub reposu `ademkarakas/muehlmattenschule` oluştur ve push et
- [x] 0.7 Laravel Boost kur (yapay zeka yönergeleri)
- [x] 0.8 Proje dokümantasyonu (`README.md`, `docs/*`)

## Faz 1 – Backend iskeleti (B1)
- [x] 1.1 `.env`: `APP_NAME`, `APP_LOCALE=de`, `APP_FALLBACK_LOCALE=de`, saat dilimi `Europe/Berlin`
- [x] 1.2 Almanca dil dosyaları (`lang/de`): doğrulama, auth, sayfalama
- [x] 1.3 Inertia.js (sunucu tarafı) + `HandleInertiaRequests` middleware
- [x] 1.4 React 19 + TypeScript + `@inertiajs/react` + Vite React eklentisi
- [x] 1.5 Tailwind CSS 4 teması, turkuaz palet (`resources/css/app.css`)
- [x] 1.6 Geçici logo (`public/images/logo.svg`)
- [x] 1.7 Ana sayfa Inertia üzerinden render ediliyor

## Faz 2 – Veri modeli (B2)
- [x] 2.1 `Category` (Kategorien) migration + model
- [x] 2.2 `Post` (Beiträge) migration + model
- [x] 2.3 `Event` (Termine) migration + model
- [x] 2.4 `Album` (Fotoalben) migration + model + Media Library
- [x] 2.5 `Announcement` (Ankündigungen) migration + model
- [x] 2.6 `Page` (Seiten, blok içerik JSON) migration + model
- [x] 2.7 `Person` (Kollegium) migration + model
- [x] 2.8 `Download` migration + model
- [x] 2.9 Ayarlar (`settings` tablosu, anahtar/değer)
- [x] 2.10 `users.role` sütunu (Enum `UserRole`: admin, schulleitung, sekretariat)
- [x] 2.11 Factory + Seeder (Almanca demo içerik)

## Faz 3 – Filament admin paneli (B3–B6)
- [x] 3.1 `/admin` paneli, Almanca arayüz, turkuaz renk, logo
- [x] 3.2 Resource „Beiträge & Aktivitäten“ (okul ve sınıf aktiviteleri, `class_name` desteği)
- [x] 3.3 Resource „Termine“
- [x] 3.4 Resource „Fotoalben“ (çoklu yükleme, sıralama, zorunlu „Einwilligungen liegen vor“, sınıf etiketi)
- [x] 3.5 Resource „Ankündigungen“
- [x] 3.6 Resource „Seiten“ (Builder blokları: Textabschnitt, Infokasten, Personenliste)
- [x] 3.7 Resource „Personen“ (Kollegium & Team)
- [x] 3.8 Resource „Downloads & Elternbriefe“
- [x] 3.9 Resource „Kategorien“
- [x] 3.10 Ayarlar sayfası (`ManageSchoolSettings`: okul adı, adres, iletişim, karşılama metni)
- [x] 3.11 Kullanıcı yönetimi + rollere göre Policy'ler (`Admin`, `Schulleitung`, `Sekretariat` ve öğretmenler için `Lehrkraft`)
- [x] 3.12 Dashboard widget'ları (`SchoolOverviewStatsWidget`, `UpcomingEventsWidget`)
- [x] 3.13 Görsel işleme: dönüşümler (`thumb`/`card`/`large`/`hero`/`avatar`, WebP)
- [x] 3.14 Taslak/Yayında + zamanlı yayın, duyurularda bitiş tarihi

## Faz 4 – Frontend (F1–F5)
- [x] 4.1 UI bileşenleri (`PostCard`, paylaşılan TypeScript tipleri `resources/js/types/school.ts`)
- [x] 4.2 Layout (`SiteLayout`: turkuaz Header, açılır menü + mobil menü, duyuru bandı, Footer)
- [x] 4.3 Ana sayfa (`HomePage`: turkuaz Hero, Aktuelles akışı, Nächste Termine, galeri önizleme, hızlı linkler)
- [x] 4.4 Aktuelles: liste (sayfalama, kategori ve **sınıf (`Klasse`)** filtresi) + detay sayfası
- [x] 4.5 Termine: aylara göre gruplanmış etkinlik takvimi
- [x] 4.6 Galerie: albüm listesi + albüm detay ve Lightbox fotoğraf büyütme
- [x] 4.7 Sabit sayfalar (blok renderer: `rich_text`, `info_box`, `people_list`), Kollegium görünümü
- [x] 4.8 Downloads (Elternbriefe & Formulare), Kontakt (OpenStreetMap bağlantılı, çerezsiz)
- [x] 4.9 Impressum, Datenschutzerklärung (DSGVO uyumlu)
- [x] 4.10 Almanca 404/500 hata sayfaları (`resources/views/errors/404.blade.php`, `500.blade.php`)

## Faz 5 – Kalite (F6, B8)
- [ ] 5.1 Feature testleri: genel sayfalar, yayın mantığı
- [ ] 5.2 Testler: admin rolleri / Policy'ler
- [ ] 5.3 SEO: sayfa başına title/meta, `sitemap.xml`, `robots.txt`
- [ ] 5.4 Erişilebilirlik (kontrast, klavye, alt metinler)
- [ ] 5.5 Performans (lazy loading, önbellek, Lighthouse ≥ 90)
- [ ] 5.6 Lint: Pint (PHP), ESLint + Prettier + `tsc --noEmit` (TS)
- [ ] 5.7 GitHub Actions CI (test + lint)

## Faz 6 – İçerik
- [ ] 6.1 Mevcut siteden içerik taşıma (metinler, kadro, sayfa yapısı)
- [ ] 6.2 Gerçek logoyu ekle (okuldan gelecek)
- [ ] 6.3 Impressum/Datenschutz metinlerini okul ile netleştir

## Faz 7 – Yayına alma (IONOS Deploy Now)
- [ ] 7.1 IONOS „Mitgliedschaft + PHP-Projekt“ (okul adına) satın al
- [ ] 7.2 GitHub reposunu Deploy Now'a bağla
- [ ] 7.3 `.deploy-now/<proje>/config.yaml`: build, `storage/` korumaya al, deploy sonrası (`migrate --force`, `storage:link`, cache)
- [ ] 7.4 Deploy Now'da `.env` şablonu (MariaDB bilgileri)
- [ ] 7.5 `muehlmattenschule.de` domainini bağla, HTTPS
- [ ] 7.6 Yedekleme stratejisi (DB + `storage/`)

## Faz 8 – Teslim
- [ ] 8.1 Müdür ve sekreter için kullanıcı hesapları
- [ ] 8.2 Almanca kullanım kılavuzu (ekran görüntülü) `docs/ANLEITUNG-ADMIN.md`
- [ ] 8.3 Eğitim
