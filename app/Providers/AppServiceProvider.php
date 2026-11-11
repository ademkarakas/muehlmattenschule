<?php

declare(strict_types=1);

namespace App\Providers;

use App\Support\ImageExifStripper;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Illuminate\Validation\Rules\Password;
use Spatie\MediaLibrary\MediaCollections\Events\MediaHasBeenAddedEvent;
use Symfony\Component\Mime\MimeTypeGuesserInterface;
use Symfony\Component\Mime\MimeTypes;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Model::shouldBeStrict(! $this->app->isProduction());

        if ($this->app->isProduction()) {
            URL::forceScheme('https');
        }

        Password::defaults(function (): Password {
            return Password::min(12)
                ->letters()
                ->mixedCase()
                ->numbers()
                ->symbols();
        });

        RateLimiter::for('public-web', function (Request $request): Limit {
            return Limit::perMinute(120)->by((string) $request->ip());
        });

        Event::listen(MediaHasBeenAddedEvent::class, ImageExifStripper::class);

        if (PHP_OS_FAMILY === 'Windows') {
            MimeTypes::getDefault()->registerGuesser(new class implements MimeTypeGuesserInterface
            {
                public function isGuesserSupported(): bool
                {
                    return function_exists('mime_content_type');
                }

                public function guessMimeType(string $path): ?string
                {
                    if (! is_file($path)) {
                        return null;
                    }

                    $mime = @mime_content_type($path);

                    return is_string($mime) && $mime !== '' ? $mime : null;
                }
            });
        }
    }
}
