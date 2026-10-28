# HANDOFF – Yeni oturuma başlarken ilk okunacak dosya

## Başlangıç talimatı (her yeni yapay zeka oturumu için)
1. Bu dosyayı oku.
2. [`docs/ARCHITECTURE.md`](ARCHITECTURE.md) – teknik kararlar ve kurallar (bağlayıcı).
3. [`docs/ROADMAP.md`](ROADMAP.md) – ilk açık `[ ]` maddeyi bul ve oradan devam et.
4. `AGENTS.md` – Laravel Boost yönergeleri (Laravel/Filament/Inertia kuralları).
5. Bir madde bitince: ROADMAP'te `[x]` yap, testleri çalıştır, Conventional Commit ile commit at,
   aşağıdaki **Oturum günlüğü**ne kısa kayıt ekle.

## Ortam notları (Windows geliştirme makinesi)
- PHP 8.3 (winget, `intl` dahil): `C:\Users\Student\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe`
  – kullanıcı PATH'inde en başta. `php.ini` bu klasörde.
- Composer: `C:\Users\Student\.config\herd-lite\bin\composer.bat` (php.new ile kuruldu).
  > Uyarı: `herd-lite\bin\php.exe` **intl içermez**; PATH'te winget PHP'si önce gelmeli.
- Yeni açılan terminallerde PATH otomatik doğru. Eski terminali yeniden başlat.
- Node.js 22, npm, Git, GitHub CLI (`gh`) kurulu.
- Proje yolu: `E:\source\repos\Mühlmattenschule` (ü karakterine dikkat).

## Açık kararlar / bekleyen bilgiler
- Gerçek logo okul tarafından verilecek (şimdilik `public/images/logo.svg` geçici).
- IONOS paketi henüz satın alınmadı (Faz 7).
- GitHub hesabı: `ademkarakas` (geçici, ileride okula devredilebilir).

## Oturum günlüğü
| Tarih | Ajan / Kişi | Yapılanlar | Sonraki adım |
|---|---|---|---|
| 2026-10-28 | Antigravity | Faz 0 & Faz 1 tamamlandı: PHP 8.3 + Composer kurulumu, Laravel 13 + Filament + Inertia + React 19 + TS + Tailwind 4 (turkuaz tema), Almanca dil dosyaları, geçici logo, dokümantasyon, testler ve GitHub (`ademkarakas/muehlmattenschule`) push | Faz 2 |
| 2026-10-28 | Antigravity | Faz 2 tamamlandı: Enums (`UserRole`, `PublishStatus`, `AnnouncementLevel`, `PersonGroup`), tüm migration'lar + Spatie Media Library, modeller, DSGVO `consent_confirmed` zorunluluğu, Factory + Almanca `DatabaseSeeder` ve Feature testleri | Faz 3 (Filament `/admin` paneli ve tüm Resource'lar) |
| 2026-10-28 | Antigravity | Faz 3 tamamlandı: Filament 5 `/admin` paneli (Teal/turkuaz tema, Almanca navigasyon), `Lehrkraft` (öğretmen) rolü ve sınıf bazlı aktivite/albüm paylaşımı (`class_name`), 9 Filament Resource (`Post`, `Album`, `Event`, `Announcement`, `Page`, `Person`, `Download`, `Category`, `User`), `ManageSchoolSettings` sayfası, Dashboard widget'ları, tüm Policy sınıfları ve 11 Feature testi | Faz 4 |
| 2026-10-28 | Antigravity | Faz 4 tamamlandı: EduPage tarzı turkuaz React 19 + TypeScript + Inertia.js arayüzü (`SiteLayout`, `HomePage`, `PostsIndexPage` sınıf ve kategori filtreli, `PostShowPage`, `EventsIndexPage`, `AlbumsIndexPage`, Lightbox'lı `AlbumShowPage`, blok tabanlı `PageShow`, `DownloadsIndexPage`, `ContactPage`, `ImpressumPage`, `DatenschutzPage`, Almanca 404/500 sayfaları) ve 12 Feature testi | Faz 5 |
| 2026-10-28 | Antigravity | Faz 5 tamamlandı: Dinamik `SitemapController` (`/sitemap.xml`), `robots.txt`, erişilebilirlik (Skip-to-content linki), Laravel Pint kod standartları, `.github/workflows/ci.yml` ve tüm testler | Faz 6 (içerik zenginleştirme), Faz 7 (IONOS Deploy Now `.deploy-now/config.yaml`) ve Faz 8 (`docs/ANLEITUNG-ADMIN.md`) |

