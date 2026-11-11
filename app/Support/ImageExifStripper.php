<?php

declare(strict_types=1);

namespace App\Support;

use GdImage;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;

class ImageExifStripper
{
    /**
     * Event-Listener für Spatie Media Library: entfernt EXIF-/GPS-Metadaten sofort nach dem Upload.
     */
    public function handle(MediaHasBeenAddedEvent $event): void
    {
        $media = $event->media;
        $mimeType = strtolower((string) $media->mime_type);

        if (! in_array($mimeType, ['image/jpeg', 'image/jpg', 'image/png', 'image/webp'], true)) {
            return;
        }

        $path = $media->getPath();
        if (! is_string($path) || ! is_file($path) || ! self::isWritablePath($path)) {
            return;
        }

        if (self::stripFile($path, $mimeType)) {
            clearstatcache(true, $path);
            $newSize = filesize($path);
            if (is_int($newSize) && $newSize > 0 && $newSize !== $media->size) {
                $media->size = $newSize;
                $media->saveQuietly();
            }
        }
    }

    /**
     * Entfernt sämtliche EXIF-/GPS-Metadaten einer Bilddatei durch Neu-Kodierung über GD
     * unter Beibehaltung der korrekten Kamera-Ausrichtung (Orientation).
     */
    public static function stripFile(string $path, ?string $mimeType = null): bool
    {
        if (! is_file($path) || ! self::isWritablePath($path) || ! extension_loaded('gd')) {
            return false;
        }

        $detectedMime = $mimeType ?? (mime_content_type($path) ?: '');
        $detectedMime = strtolower($detectedMime);

        if (in_array($detectedMime, ['image/jpeg', 'image/jpg'], true)) {
            $image = @imagecreatefromjpeg($path);
            if (! $image instanceof GdImage) {
                return false;
            }

            $image = self::applyExifOrientation($image, $path);
            $result = @imagejpeg($image, $path, 90);
            imagedestroy($image);

            return $result;
        }

        if ($detectedMime === 'image/webp' && function_exists('imagecreatefromwebp')) {
            $image = @imagecreatefromwebp($path);
            if (! $image instanceof GdImage) {
                return false;
            }

            $result = @imagewebp($image, $path, 90);
            imagedestroy($image);

            return $result;
        }

        return false;
    }

    /**
     * Korrigiert die Bilddrehung anhand des EXIF-Orientation-Flags vor dem Entfernen der Metadaten.
     */
    private static function applyExifOrientation(GdImage $image, string $path): GdImage
    {
        if (! function_exists('exif_read_data')) {
            return $image;
        }

        $exif = @exif_read_data($path);
        if (! is_array($exif) || ! isset($exif['Orientation'])) {
            return $image;
        }

        $orientation = (int) $exif['Orientation'];
        $rotated = match ($orientation) {
            3 => @imagerotate($image, 180, 0),
            6 => @imagerotate($image, -90, 0),
            8 => @imagerotate($image, 90, 0),
            default => false,
        };

        if ($rotated instanceof GdImage) {
            imagedestroy($image);

            return $rotated;
        }

        return $image;
    }

    /**
     * Prüft die Schreibbarkeit einer Datei zuverlässig auch unter Windows (PHP-Bug #72004).
     */
    private static function isWritablePath(string $path): bool
    {
        if (is_writable($path)) {
            return true;
        }

        if (PHP_OS_FAMILY === 'Windows' && is_file($path)) {
            $handle = @fopen($path, 'r+');
            if ($handle !== false) {
                fclose($handle);

                return true;
            }
        }

        return false;
    }
}
