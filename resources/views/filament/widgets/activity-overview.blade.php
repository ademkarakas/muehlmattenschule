<x-filament-widgets::widget>
    <style>
        .mms-feed { list-style: none; margin: 0; padding: 0; display: flex; flex-direction: column; gap: 0.5rem; }
        .mms-feed-item {
            display: flex; align-items: center; gap: 1rem;
            padding: 0.8rem 1rem; border: 1px solid #e2e8f0; border-radius: 0.85rem;
            background: #fff; text-decoration: none; color: inherit;
            transition: border-color .15s, box-shadow .15s, transform .15s;
        }
        a.mms-feed-item:hover { border-color: #00838f; box-shadow: 0 4px 14px rgba(0, 105, 120, .12); transform: translateY(-1px); }
        .mms-feed-icon {
            flex-shrink: 0; width: 2.5rem; height: 2.5rem; border-radius: 0.7rem;
            display: grid; place-items: center; background: #e0f4f6; color: #00838f;
        }
        .mms-feed-icon.is-draft { background: #f1f5f9; color: #64748b; }
        .mms-feed-icon svg { width: 1.25rem; height: 1.25rem; }
        .mms-feed-body { min-width: 0; flex: 1; }
        .mms-feed-title { display: block; font-size: 0.925rem; font-weight: 600; color: #0f172a; white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }
        .mms-feed-meta { display: flex; flex-wrap: wrap; align-items: center; gap: 0.5rem; margin-top: 0.15rem; font-size: 0.775rem; color: #64748b; }
        .mms-feed-chip { padding: 0.05rem 0.55rem; border-radius: 999px; background: #f1f5f9; color: #475569; font-weight: 500; }
        .mms-feed-chevron { flex-shrink: 0; width: 1.1rem; height: 1.1rem; color: #cbd5e1; }
        a.mms-feed-item:hover .mms-feed-chevron { color: #00838f; }
        .mms-feed-all { font-size: 0.85rem; font-weight: 600; color: #00838f; text-decoration: none; }
        .mms-feed-all:hover { text-decoration: underline; }
        .dark .mms-feed-item { background: rgba(255, 255, 255, .03); border-color: rgba(255, 255, 255, .08); }
        .dark .mms-feed-title { color: #f8fafc; }
        .dark .mms-feed-chip { background: rgba(255, 255, 255, .08); color: #cbd5e1; }
        .dark .mms-feed-icon { background: rgba(0, 180, 216, .15); color: #67e8f9; }
    </style>

    <x-filament::section heading="Neueste Beiträge">
        <x-slot name="afterHeader">
            <a href="{{ $postsUrl }}" class="mms-feed-all">Alle Beiträge →</a>
        </x-slot>

        <ul class="mms-feed">
            @forelse ($posts as $post)
                <li>
                    @if ($post['url'])
                        <a href="{{ $post['url'] }}" class="mms-feed-item">
                    @else
                        <div class="mms-feed-item">
                    @endif
                        <span class="mms-feed-icon {{ $post['published'] ? '' : 'is-draft' }}">
                            <x-filament::icon icon="heroicon-o-newspaper" />
                        </span>
                        <span class="mms-feed-body">
                            <span class="mms-feed-title">{{ $post['title'] }}</span>
                            <span class="mms-feed-meta">
                                @if ($post['date'])
                                    <span>{{ $post['date'] }}</span>
                                @endif
                                <span class="mms-feed-chip">{{ $post['class'] }}</span>
                            </span>
                        </span>
                        <x-filament::badge :color="$post['published'] ? 'success' : 'gray'">
                            {{ $post['published'] ? 'Veröffentlicht' : 'Entwurf' }}
                        </x-filament::badge>
                        @if ($post['url'])
                            <x-filament::icon icon="heroicon-m-chevron-right" class="mms-feed-chevron" />
                        @endif
                    @if ($post['url'])
                        </a>
                    @else
                        </div>
                    @endif
                </li>
            @empty
                <li style="padding:1rem;color:#64748b;font-size:0.9rem;">Noch keine Beiträge.</li>
            @endforelse
        </ul>
    </x-filament::section>
</x-filament-widgets::widget>
