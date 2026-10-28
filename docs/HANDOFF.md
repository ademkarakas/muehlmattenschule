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
| 2026-10-28 | Antigravity | Faz 0 & Faz 1: PHP 8.3 + Composer kurulumu, Laravel 13 + Filament + Inertia + React 19 + TS + Tailwind 4 (turkuaz tema), Almanca dil dosyaları, geçici logo, dokümantasyon, testler ve ilk git commit | Faz 2 (Veri modeli: migration + modeller) ve 0.6 GitHub push |

