<?php

declare(strict_types=1);

namespace App\Filament\Concerns;

use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Support\Enums\IconSize;
use Filament\Support\Icons\Heroicon;
use Illuminate\Contracts\Support\Htmlable;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

use function Filament\Support\generate_icon_html;

/**
 * Einheitlicher Listen-Kopfbereich (.page-header) exakt nach Vorbild von SupportCallSystem.Frontend:
 * - Links: Ressourcen-Icon (1.75rem) + Seitentitel (1.5rem) in einer Zeile, darunter eingerückter Untertitel
 * - Rechts: Kompakte „+ Neu“-Schaltfläche (6px Radius)
 */
trait HasSupportCallListHeader
{
    /**
     * Keine Breadcrumbs über dem Seitentitel.
     *
     * @return array<string, string>
     */
    public function getBreadcrumbs(): array
    {
        return [];
    }

    /**
     * Rendert Icon + Seitentitel analog zu .title-row in SupportCallSystem.Frontend.
     */
    public function getHeading(): string|Htmlable
    {
        /** @var class-string<\Filament\Resources\Resource> $resource */
        $resource = static::getResource();

        $iconHtml = generate_icon_html(
            $resource::getNavigationIcon(),
            attributes: new ComponentAttributeBag(['class' => 'scs-title-icon']),
            size: IconSize::Large,
        )?->toHtml() ?? '';

        $title = e($this->getTitle());

        return new HtmlString(
            '<span class="scs-title-row">'
            .$iconHtml
            .'<span>'.$title.'</span>'
            .'</span>'
        );
    }

    /**
     * Kompakte „+ Neu“-Schaltfläche oben rechts.
     *
     * @return array<Action>
     */
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()
                ->label('Neu')
                ->icon(Heroicon::OutlinedPlus),
        ];
    }
}
