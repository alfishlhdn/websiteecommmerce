@php
    $menus = [
        [
            'label' => 'Tentang Kami',
            'route' => 'tentang',
            'icon' => '📖',
        ],
        [
            'label' => 'Cara Belanja',
            'route' => 'belanja',
            'icon' => '🛒',
        ],
        [
            'label' => 'Kebijakan Privasi',
            'route' => 'privacy',
            'icon' => '🔒',
        ],
        [
            'label' => 'Syarat & Ketentuan',
            'route' => 'syarat',
            'icon' => '📜',
        ],
        [
            'label' => 'Cara Pembayaran',
            'route' => 'cara-pembayaran',
            'icon' => '💳',
        ],
        [
            'label' => 'Metode Pengiriman',
            'route' => 'metode-pengiriman',
            'icon' => '🚚',
        ],
        // [
        //     'label' => 'Cara Pengembalian',
        //     'route' => 'pengembalian',
        //     'icon' => '↩️',
        // ],
        [
            'label' => 'Pusat Bantuan',
            'route' => 'bantuan',
            'icon' => '❓',
        ],
    ];
@endphp

<aside class="bg-white p-4 shadow rounded-md border" aria-label="Menu sisi">
    <ul class="space-y-2 text-sm font-medium" role="list">
        @foreach ($menus as $m)
            @php $active = request()->routeIs($m['route']); @endphp
            <li>
                <a href="{{ route($m['route']) }}"
                    class="flex items-center gap-2 block p-2 rounded transition
                  hover:bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-300
                  {{ $active ? 'bg-blue-50 text-blue-700 font-semibold' : 'text-gray-700' }}">
                    <span>{{ $m['icon'] ?? '' }}</span>
                    <span>{{ $m['label'] }}</span>
                </a>
            </li>
        @endforeach
    </ul>
</aside>
