<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Cocina — TV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&family=Playfair+Display:wght@700&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css'])
    <style>
        html, body { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        body { font-family: 'Inter', sans-serif; background: #1A0F0A; color: #fff; }
        .tv-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(300px, 1fr)); gap: 10px; padding: 12px; height: calc(100vh - 72px); overflow-y: auto; }
        .tv-card { background: #2A1F1A; border-radius: 10px; border: 1px solid #3F3028; overflow: hidden; }
        .tv-card.urgent { border-color: #D06040; box-shadow: 0 0 12px rgba(208,96,64,0.15); }
        .tv-card.vip { border-color: #C8A45C; box-shadow: 0 0 12px rgba(200,164,92,0.15); }
        .tv-header { padding: 8px 12px; font-weight: 700; font-size: 1rem; display: flex; justify-content: space-between; align-items: center; }
        .tv-item { padding: 8px 12px; border-top: 1px solid #3F3028; display: flex; align-items: center; gap: 8px; }
        .tv-item.pending { border-left: 3px solid #C8A45C; }
        .tv-item.preparing { border-left: 3px solid #D06040; background: #33251E; }
        .tv-item.returned { border-left: 3px solid #E89440; background: #332A1E; }
        .tv-badge { font-size: 0.65rem; padding: 1px 6px; border-radius: 999px; font-weight: 600; }
        .tv-time { font-size: 0.7rem; color: #9E8268; font-weight: 500; }
        .tv-time.overdue { color: #D06040; font-weight: 700; }
        .tv-time.warning { color: #C8A45C; }
        .summary-bar { display: flex; gap: 16px; padding: 10px 20px; background: #2A1F1A; border-bottom: 1px solid #3F3028; font-size: 0.85rem; }
        .summary-item { display: flex; align-items: center; gap: 5px; }
        .summary-count { font-weight: 800; font-size: 1rem; }
        ::-webkit-scrollbar { width: 4px; }
        ::-webkit-scrollbar-track { background: #1A0F0A; }
        ::-webkit-scrollbar-thumb { background: #4F3E31; border-radius: 2px; }
        img.tv-thumb { width: 28px; height: 28px; object-fit: cover; border-radius: 4px; border: 1px solid #4F3E31; }
    </style>
</head>
<body>
    {{-- Summary bar with brand colors --}}
    <div class="summary-bar">
        <div class="summary-item">
            <span style="color: #C8A45C;">●</span>
            <span style="color: #B8A088;">Pendientes:</span>
            <span class="summary-count" style="color: #C8A45C;">{{ $summary['pending'] }}</span>
        </div>
        <div class="summary-item">
            <span style="color: #D06040;">●</span>
            <span style="color: #B8A088;">Preparando:</span>
            <span class="summary-count" style="color: #D06040;">{{ $summary['preparing'] }}</span>
        </div>
        <div class="summary-item">
            <span style="color: #5CB85C;">●</span>
            <span style="color: #B8A088;">Listos:</span>
            <span class="summary-count" style="color: #5CB85C;">{{ $summary['ready'] }}</span>
        </div>
        <div class="summary-item" style="margin-left: auto;">
            <span style="color: #6A5441; font-family: 'Playfair Display', serif; font-weight: 700;">{{ now()->format('H:i') }}</span>
        </div>
    </div>

    {{-- Orders grid --}}
    <div class="tv-grid">
        @forelse ($orders as $order)
            <div class="tv-card {{ $order['priority'] === 'vip' ? 'vip' : ($order['priority'] === 'urgent' ? 'urgent' : '') }}">
                <div class="tv-header" style="{{ $order['priority'] === 'vip' ? 'background: #3b1f5e;' : ($order['priority'] === 'urgent' ? 'background: #5e1f1f;' : 'background: #333;') }}">
                    <span>Mesa {{ $order['table_number'] }}</span>
                    <div class="flex items-center gap-3">
                        <span class="text-sm font-normal text-gray-400">{{ $order['zone'] }}</span>
                        <span class="tv-time {{ $order['minutes_ago'] > 15 ? 'overdue' : ($order['minutes_ago'] > 10 ? 'warning' : '') }}">
                            {{ $order['created_at'] }} · {{ $order['minutes_ago'] }} min
                        </span>
                    </div>
                </div>
                @foreach ($order['items'] as $item)
                    <div class="tv-item {{ $item['status'] }} {{ $item['return_reason'] ? 'returned' : '' }}">
                        @if ($item['photo'])
                            <img src="{{ $item['photo'] }}" alt="" class="tv-thumb" />
                        @endif
                        <div class="flex-1 min-w-0">
                            <div class="flex items-center gap-2">
                                <span style="font-weight: 600;">{{ $item['quantity'] }}x {{ $item['dish'] }}</span>
                                @if ($item['modifiers'])
                                    <span class="tv-badge" style="background: #444; color: #ccc;">{{ $item['modifiers'] }}</span>
                                @endif
                                @if ($item['return_reason'])
                                    <span class="tv-badge" style="background: #7c3a1e; color: #fdba74;">🔄 {{ $item['return_reason'] }}</span>
                                @endif
                            </div>
                            @if ($item['notes'])
                                <div class="tv-time" style="color: #f59e0b; margin-top: 2px;">"{{ $item['notes'] }}"</div>
                            @endif
                            @if ($item['kitchen_note'])
                                <div class="tv-time" style="color: #fbbf24; margin-top: 2px;">👨‍🍳 {{ $item['kitchen_note'] }}</div>
                            @endif
                        </div>
                        <span class="tv-time {{ $item['status'] === 'pending' && $item['minutes_since_created'] > 15 ? 'overdue' : ($item['status'] === 'pending' && $item['minutes_since_created'] > 10 ? 'warning' : '') }}">
                            {{ $item['minutes_since_created'] }} min
                            @if ($item['status'] === 'preparing')
                                <span class="tv-badge" style="background: #2563eb; color: #bfdbfe; margin-left: 4px;">prep</span>
                            @elseif ($item['status'] === 'pending')
                                <span class="tv-badge" style="background: #92400e; color: #fde68a; margin-left: 4px;">pend</span>
                            @endif
                        </span>
                    </div>
                @endforeach
            </div>
        @empty
            <div style="grid-column: 1 / -1; text-align: center; padding: 80px 20px; color: #666;">
                <div style="font-size: 4rem; margin-bottom: 16px;">🍽️</div>
                <h2 style="font-size: 1.5rem; font-weight: 600;">No hay pedidos activos</h2>
                <p style="margin-top: 8px;">Los pedidos aparecerán automáticamente.</p>
            </div>
        @endforelse
    </div>

    <script>
        setTimeout(() => { location.reload(); }, 30000);
    </script>
</body>
</html>
