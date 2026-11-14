<x-filament-widgets::widget>
    <div class="scs-activity-card">
        <div class="scs-activity-header">
            <h3>Neueste Aktivitäten</h3>
            <a href="{{ $postsUrl }}" class="scs-btn-link">
                <span>Alle Beiträge</span>
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M3 10a.75.75 0 0 1 .75-.75h10.638L10.23 5.29a.75.75 0 1 1 1.04-1.08l5.5 5.25a.75.75 0 0 1 0 1.08l-5.5 5.25a.75.75 0 1 1-1.04-1.08l4.158-3.96H3.75A.75.75 0 0 1 3 10Z" clip-rule="evenodd" />
                </svg>
            </a>
        </div>

        @if (count($posts) > 0)
            <div class="scs-table-container">
                <table class="scs-data-table">
                    <thead>
                        <tr>
                            <th>Datum</th>
                            <th>Titel</th>
                            <th>Verfasser</th>
                            <th>Rubrik / Klasse</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($posts as $post)
                            <tr
                                @if ($post['url'])
                                    class="is-clickable"
                                    onclick="window.location.href='{{ $post['url'] }}'"
                                @endif
                            >
                                <td class="scs-cell-date">{{ $post['date'] ?? '—' }}</td>
                                <td class="scs-cell-main">
                                    @if ($post['url'])
                                        <a href="{{ $post['url'] }}" class="scs-row-link">
                                            {{ $post['title'] }}
                                        </a>
                                    @else
                                        {{ $post['title'] }}
                                    @endif
                                </td>
                                <td>
                                    <span class="scs-customer-cell">
                                        <span class="scs-name-avatar {{ $post['avatarTone'] }}">
                                            {{ $post['initials'] }}
                                        </span>
                                        <span>{{ $post['author'] }}</span>
                                    </span>
                                </td>
                                <td>
                                    <span class="scs-badge scs-badge-muted">{{ $post['class'] }}</span>
                                </td>
                                <td>
                                    @if ($post['published'])
                                        <span class="scs-badge scs-badge-success">Veröffentlicht</span>
                                    @else
                                        <span class="scs-badge scs-badge-muted">Entwurf</span>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="scs-empty-state">
                <p>Keine aktuellen Aktivitäten vorhanden.</p>
            </div>
        @endif
    </div>
</x-filament-widgets::widget>
