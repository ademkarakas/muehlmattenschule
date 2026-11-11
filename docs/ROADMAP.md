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
- [x] 5.1 Feature testleri: genel sayfalar, yayın mantığı
- [x] 5.2 Testler: admin rolleri / Policy'ler (`AdminAccessAndPoliciesTest`)
- [x] 5.3 SEO: sayfa başına sunucu + istemci senkronlu title/meta (`SeoData`, `<SeoHead>`), Open Graph & Twitter Cards, Canonical + `rel="prev/next"`, Schema.org JSON-LD (`ElementarySchool`, `WebSite`, `BreadcrumbList`, `NewsArticle`, `Event`, `ImageGallery`, `ContactPage`), semantik `<Breadcrumbs>`, `<lastmod>/<changefreq>/<priority>` destekli dinamik `sitemap.xml` (`SitemapController`), `robots.txt` ve `site.webmanifest`
- [x] 5.4 Erişilebilirlik (WCAG kontrast, klavye „Zum Hauptinhalt springen“, alt metinler)
- [x] 5.5 Performans (lazy loading, WebP dönüşümleri, Vite code-splitting)
- [x] 5.6 Lint: Laravel Pint (PHP) + `tsc --noEmit` (strict TypeScript)
- [x] 5.7 GitHub Actions CI (`.github/workflows/ci.yml`: test + lint + build)

## Faz 6 – İçerik
- [x] 6.1 Mevcut siteden içerik taşıma (`muehlmattenschule.de` & `muehlmattenschule.0x6e.net`: 71 gerçek fotoğraf `public/images/school/`, 5 orijinal PDF `public/files/school/`, 22 kişilik gerçek öğretmen/kadro/sosyal hizmet listesi, 17 gerçek haber ve sınıf aktivitesi, 6 zengin fotoğraf albümü ve 8 tam içerikli sayfa)
- [x] 6.2 Gerçek logoyu ekle (muehlmattenschule.de orijinal 2023 amblemi `logo-mms-2023.png`, `logo.png` ve `logo.svg` entegrasyonu + `LogoFoeVe-webGrau.png` Förderverein logosu)
- [x] 6.3 Impressum/Datenschutz ve Kontakt metinlerini resmi `muehlmattenschule.de` verileriyle güncelle
- [x] 6.4 Kalan tüm içeriklerin eksiksiz aktarımı: 28 yeni görsel/harita/PDF (`public/images/school/` ve `public/files/school/`), 5 otantik tematik yazı (Chorvereinigung, Schulwegeplan, Johannes Stang Sonderpreis, Inklusive Außenklasse, 25 Jahre Jubiläum), 4 yeni bağımsız sayfa (`foerderverein`, `crosslauf`, `hochschulpartnerschule`, `schulwegeplan`), 4 yeni resmi PDF indirme belgesi (Schulwegeplan Karte & Regeln, Gesamtelternbeiratssitzung Leitfaden & Kurzprotokoll) ve zenginleştirilmiş 8 AG görseli + Leitbild şeması

## Faz 7 – Yayına alma (IONOS Deploy Now)
- [ ] 7.1 IONOS „Mitgliedschaft + PHP-Projekt“ (okul adına) satın al
- [ ] 7.2 GitHub reposunu Deploy Now'a bağla
- [ ] 7.3 `.deploy-now/<proje>/config.yaml`: build, `storage/` korumaya al, deploy sonrası (`migrate --force`, `storage:link`, cache)
- [ ] 7.4 Deploy Now'da `.env` şablonu (MariaDB bilgileri)
- [ ] 7.5 `muehlmattenschule.de` domainini bağla, HTTPS
- [ ] 7.6 Yedekleme stratejisi (DB + `storage/`)

## Faz 8 – Teslim
- [ ] 8.1 Müdür ve sekreter için kullanıcı hesapları
- [x] 8.2 Almanca kullanım kılavuzu `docs/ANLEITUNG-ADMIN.md`
- [ ] 8.3 Eğitim

---

## Faz 9 – Gerçek Logo Turkuazı (`#029786`) Kurumsal Kimlik Uyumu
- [x] 9.1 `docs/ROADMAP.md` ve `docs/ARCHITECTURE.md` dosyalarını yeni aşamalar ve `#029786` logo turkuazı paletiyle güncelle
- [x] 9.2 `resources/css/app.css` içindeki `@theme` (`brand-50` … `brand-900`) renklerini `logo-mms-2023.png` içindeki gerçek turkuaz (`#029786` ana renk, `#007f70` / `#00695c` koyu, `#003d36` derin) ve `data-font-scale` kurallarıyla güncelle
- [x] 9.3 `app/Providers/Filament/AdminPanelProvider.php`, `resources/views/filament/admin-theme.blade.php` ve `LocalInitialsAvatarProvider.php` dosyalarını logo turkuazı (`#029786` → `#007f70` → `#00695c`) ile birebir uyumlu hale getir
- [x] 9.4 Footer (`site-layout.tsx`), Admin logo (`brand-logo.blade.php`) ve hata sayfalarında (`404.blade.php`, `500.blade.php`) gerçek okul logosunu (`/images/logo.png`) kullan ve `public/images/logo.svg` dosyasını da `#029786` rengiyle güncelle

## Faz 10 – Aydınlatıcı ve Şık Bakım Modu (Wartungsmodus – Admin & HTTP 503)
- [x] 10.1 `ManageSchoolSettings` (`/admin/manage-school-settings`) sayfasına **„Wartungsmodus (Website vorübergehend in Wartung nehmen)“** bölümü ekle (`maintenance_active`, `maintenance_title`, `maintenance_message`, `maintenance_until`, `maintenance_show_contact`)
- [x] 10.2 `App\Http\Middleware\CheckSchoolMaintenanceMode` middleware oluştur ve `bootstrap/app.php` içine kaydet (Ziyaretçilere HTTP 503 + `Retry-After`, oturum açmış yöneticilere üst bilgi bandıyla canlı önizleme imkanı, `/admin*` ve `/up` her zaman açık)
- [x] 10.3 Admin Topbar (`resources/views/filament/topbar-actions.blade.php`) üzerinde bakım modu aktifken dikkat çekici **„Wartungsmodus AKTIV“** rozeti ve hızlı geçiş bağlantısı göster
- [x] 10.4 `resources/views/errors/503.blade.php` şablonunu oluştur (Okul logosu, `#029786` turkuaz tasarım, özel bakım mesajı, tahmini açılış süresi, **Sekretariat & Krankmeldung** ve **Caritas Schulkindbetreuung** tek tıkla arama/e-posta kartları, `/admin` giriş linki)
- [x] 10.5 Bakım modu için kapsamlı Feature testleri (`tests/Feature/MaintenanceModeTest.php`) yaz

## Faz 11 – Eski Sitedeki (`muehlmattenschule.de`) TÜM Fotoğrafların (~320 Adet), TÜM Yazıların ve TÜM Alt Sayfaların %100 Aktarımı
- [x] 11.1 Eski sitedeki tüm yazı galerilerinde ve sayfalarda bulunan **eksik ~250 fotoğrafı** (88 Crosslauf, 52 Adventsbasteln, 52 Spielmobil, 44 Slow-Mobil, 40 Gesundes Frühstück, 24 Weihnachtliche MMS, 12 Tanzen, 5 Plätzchenverkauf, 2 Diana Blum Jubiläum, 2 Zeitung in der Schule 4a/4b) ve `wp-content/Schulsozialarbeit/` mikro sitesindeki **6 görseli** (`MMS Schulsoz HP2.jpg`, `Wortwolke.jpeg`, `Klassen und Gruppen.png`, `Bild3.png` vb.) `public/images/school/` altına indir ve EXIF temizliğini yap
- [x] 11.2 `AlbumResource` fotoğraf yükleme sınırını (`maxFiles`) 120'ye çıkar ve tüm fotoğraf albümlerini (`Crosslauf` 88 foto, `Adventszeit & Basteln` 81 foto, `Bewegungs- & Gesundheitstage` 108 foto, `Gesunde Ernährung 2. Klassen` 46 foto, `Schulgebäude & Campus`, `Patenschaft Rosedale`) eksiksiz fotoğraflarıyla `DatabaseSeeder.php` içine bağla
- [x] 11.3 Eski sitedeki tüm yazıların (`Post`) metinlerini kısaltılmamış **1:1 orijinal tam metinleriyle** (3.326 karakterlik Mundenhof öğrenci yazıları, tam BiSS-Transfer, Chorvereinigung, Förderverein, Elterninfo metinleri ve yazı içi fotoğraf galerileri dahil) güncelle ve eksik okul yaşamı yazılarını (*Zeitung in der Schule 4a & 4b*, *40-jähriges Dienstjubiläum Edith Jakob*, *1. Mühlmattentreffen* vb.) ekle
- [x] 11.4 Eski sitedeki Navbar'da (`Hauptmenu`) yer alan **tüm sayfaları ve alt sayfaları** (22 zengin sayfa: `schulportrait`, `schulleitung-und-verwaltung`, `kollegium`, `unsere-klassen`, `schulsozialarbeit`, `elternbeirat`, `aufgaben-des-elternbeirats`, `aktivitaeten-des-elternbeirats`, `foerderverein`, `crosslauf`, `hochschulpartnerschule`, `leitbild`, `so-ist-es-bei-uns`, `klassenrat`, `patenschaft-simbabwe`, `biss-transfer-und-inklusion`, `arbeitsgemeinschaften`, `lesepatenschaften`, `bibfit`, `kooperationen`, `schulkindbetreuung`, `schulwegeplan`) kısaltılmamış tam metinleriyle müstakil sayfalar olarak oluştur

## Faz 12 – Eski Sitedeki Navbar Yapısı + `ghsfreiburg.edupage.org` Mega-Menü, „Beiträge nach Rubriken“, Erişilebilirlik & Arama
- [x] 12.1 `Page` modeline `menu_group` alanı ekle ve `routes/web.php` + `HandleInertiaRequests` içinde `unsere-schule`, `konzepte-projekte`, `angebote` (ve `schulleben`) bölümlerini destekle
- [x] 12.2 `SiteLayout` (`resources/js/layouts/site-layout.tsx`) üst menüsünü eski sitedeki **tüm başlıkları** (`Startseite`, `Aktuelles`, `Unsere Schule ▾`, `Konzepte & Projekte ▾`, `Angebote & Betreuung ▾`, `Termine`, `Fotogalerie`, `Downloads`, `Kontakt`) içerecek şekilde `ghsfreiburg.edupage.org` tarzı gruplandırılmış açılır menüyle güncelle
- [x] 12.3 Eski sitedeki **„Beiträge nach Rubriken“** (`Alle Beiträge`, `Elterninfos`, `Ferienkalender`, `Klasse 4a`, `Klasse 4b`, `Konzept & Didaktik`, `Schulfeste & Feiern`, `Schulleben`, `Sport & Wettbewerbe`, `Inklusive Beschulung`) kutusunu yazı sayılarıyla (`(count)`) birlikte hem Ana Sayfa sağ sütununa hem de `/aktuelles` sayfasına ekle
- [x] 12.4 `ghsfreiburg.edupage.org` üst barındaki gibi **Barrierefreie Einstellungen (Yazı Boyutu `A- / A / A+`)** ve **Site İçi Arama (`GET /suche?q=...`)** özelliklerini ekle

## Faz 13 – `ghsfreiburg.edupage.org` Ana Sayfa & Takvim Özellikleri (Pausen Widget, Sınıf Etkinlikleri, iCal, Haber Sayfalama)
- [x] 13.1 Ana sayfa (`resources/js/pages/home.tsx`) sağ sütununa `ghsfreiburg.edupage.org` stilinde:
  1. **„Unterrichts- & Pausenzeiten“ (`Pausen`) Widget'ı** (Bugünün tarihi + ders/teneffüs saatleri)
  2. **Gelişmiş „Kalender“ Widget'ı** (Tarih + başlık + sınıf rozeti + tür + `.ics` takvime ekle)
  3. **„Beiträge nach Rubriken“ Widget'ı** (Yazı sayılarıyla kategoriler ve sınıflar)
  4. **„Sekretariat & Krankmeldung“ Widget'ı**
- [x] 13.2 Ana sayfadaki haber akışına `ghsfreiburg.edupage.org` gibi sayfalama (`1, 2, 3 … Weiter`) ve bağlı albüm fotoğraf sayısı rozeti (`📷 88 Fotos`) ekle
- [x] 13.3 `Event` modeline `class_name` ve `category` alanlarını ekle ve `/termine/{event}/ical` + `/termine.ics` ile **iCal (`.ics`) takvim indirme** desteği sun

## Faz 14 – Admin Paneli (`/admin`) Eksiklerinin Giderilmesi ve Editör Deneyimi (UX) İyileştirmeleri
- [x] 14.1 `ManageSchoolSettings` sayfasını sekmeli (`Tabs`) yapıya kavuştur (*Stammdaten & Kontakt*, *Startseite & Hero-Bilder*, *Unterrichts- & Pausenzeiten*, *Wartungsmodus*)
- [x] 14.2 `PageResource` sayfa oluşturucu (`Builder`) bloklarına doğrudan **görsel yükleme (`FileUpload`)**, **`accordion` (SSS)** ve **`downloads_box`** bloklarını ekle
- [x] 14.3 `PostResource`, `AlbumResource` ve `PageResource` içine **„Auf Website ansehen ↗“** hızlı önizleme butonu ekle

## Faz 15 – Kalite Kontrol, Testler, Build ve Dokümantasyon
- [x] 15.1 Veritabanı migration ve seeder'larını çalıştır (`php artisan migrate --seed`)
- [x] 15.2 Yeni özellikler için Feature testlerini yaz/güncelle (`MaintenanceModeTest`, `EdupageFeaturesTest`)
- [x] 15.3 Laravel Pint, TypeScript (`tsc --noEmit`), Vite Build (`npm run build`) ve tüm PHPUnit testlerini (`php artisan test`) çalıştırıp %100 doğrula
- [x] 15.4 `docs/ANLEITUNG-ADMIN.md` (Almanca Admin Kullanım Kılavuzu) oluştur ve `docs/ROADMAP.md` + `docs/HANDOFF.md` dosyalarını güncelle
