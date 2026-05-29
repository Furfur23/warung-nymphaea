<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Keranjang Belanja — Warung Nymphaea</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body:    ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cream:    '#FDF6EC',
                        bark:     '#5C3D2E',
                        ember:    '#D4622A',
                        saffron:  '#F2A035',
                        sage:     '#7A9E7E',
                        charcoal: '#2C2016',
                    },
                    keyframes: {
                        'slide-up': {
                            '0%':   { opacity: '0', transform: 'translateY(16px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        'slide-up': 'slide-up 0.4s ease-out both',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }
        .qty-btn { transition: background 0.15s, transform 0.1s; }
        .qty-btn:active { transform: scale(0.9); }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-thumb { background: #D4622A; border-radius: 3px; }
        @keyframes toast-in {
            from { opacity:0; transform: translateY(100%) scale(0.9); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }
        .toast-enter { animation: toast-in 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        .item-row { transition: opacity 0.3s, transform 0.3s; }
        .item-row.removing { opacity: 0; transform: translateX(20px); }
    </style>
</head>

<body class="relative text-charcoal min-h-screen">

{{-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bark shadow-lg">
    <div class="max-w-4xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-2 text-cream/70 hover:text-cream transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-semibold">Lanjut Belanja</span>
        </a>
        <a href="{{ route('catalog.index') }}" class="font-display text-cream text-xl tracking-wide">
            Warung<span class="text-saffron italic">Nymphaea</span>
        </a>
        <div class="w-28"></div> {{-- spacer --}}
    </div>
</header>


{{-- ═══════════════════════════════════════
     FLASH MESSAGE
═══════════════════════════════════════ --}}
@if(session('success') || session('error'))
<div id="flash-toast"
     class="toast-enter fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl
            {{ session('success') ? 'bg-sage text-white' : 'bg-red-500 text-white' }}
            text-sm font-semibold max-w-sm w-full">
    <span class="text-lg">{{ session('success') ? '✅' : '❌' }}</span>
    <span>{{ session('success') ?? session('error') }}</span>
    <button onclick="document.getElementById('flash-toast').remove()" class="ml-auto text-white/70 hover:text-white">✕</button>
</div>
<script>setTimeout(() => { const t = document.getElementById('flash-toast'); if (t) t.remove(); }, 4000);</script>
@endif


{{-- ═══════════════════════════════════════
     PAGE HEADER
═══════════════════════════════════════ --}}
<div class="bg-gradient-to-r from-bark to-charcoal text-cream py-8 px-4">
    <div class="max-w-4xl mx-auto">
        <h1 class="font-display text-3xl mb-0.5">Keranjang <span class="italic text-saffron">Belanja</span></h1>
        <p class="text-cream/50 text-sm">
            {{ $cartCount > 0 ? $cartCount . ' item dipilih' : 'Keranjang masih kosong' }}
        </p>
    </div>
</div>


{{-- ═══════════════════════════════════════
     MAIN CONTENT
═══════════════════════════════════════ --}}
<main class="max-w-4xl mx-auto px-4 py-8 relative z-10">

    @if(empty($cart))
        {{-- Empty State --}}
        <div class="text-center py-24 animate-slide-up">
            <div class="w-28 h-28 bg-bark/5 rounded-full mx-auto flex items-center justify-center mb-5 border-4 border-dashed border-bark/15">
                <svg class="w-14 h-14 text-bark/25" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
            </div>
            <h2 class="font-display text-2xl text-bark/50 mb-2">Masih Kosong</h2>
            <p class="text-bark/40 text-sm mb-8">Yuk, pilih menu favoritmu dulu!</p>
            <a href="{{ route('catalog.index') }}"
               class="inline-flex items-center gap-2 bg-ember hover:bg-orange-600 text-cream font-bold px-8 py-3 rounded-full shadow-lg transition-all active:scale-95">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
                </svg>
                Lihat Menu
            </a>
        </div>

    @else
        <div class="lg:grid lg:grid-cols-3 lg:gap-8 items-start">

            {{-- ─── ITEM LIST (col-span-2) ─── --}}
            <div class="lg:col-span-2 space-y-3 mb-6 lg:mb-0">

                {{-- Header --}}
                <div class="flex justify-between items-center mb-4">
                    <h2 class="font-bold text-bark text-base">Item Pesanan</h2>
                    <form action="{{ route('cart.clear') }}" method="POST"
                          onsubmit="return confirm('Hapus semua item dari keranjang?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-xs text-red-400 hover:text-red-600 font-semibold transition-colors flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                            </svg>
                            Kosongkan
                        </button>
                    </form>
                </div>

                {{-- Item Cards --}}
                @foreach($cart as $key => $item)
                <div class="item-row animate-slide-up bg-white rounded-2xl border border-bark/8 shadow-sm overflow-hidden"
                     style="animation-delay: {{ $loop->index * 0.06 }}s"
                     id="item-row-{{ $item['id'] }}">

                    <div class="flex items-start gap-3 p-4">

                        {{-- Product Thumbnail --}}
                        <div class="w-16 h-16 rounded-xl bg-gradient-to-br from-saffron/20 to-ember/20 flex-shrink-0 overflow-hidden">
                            @if($item['image'])
                                <img src="{{ asset('storage/' . $item['image']) }}"
                                     alt="{{ $item['name'] }}"
                                     class="w-full h-full object-cover">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <span class="font-display text-2xl text-ember/30">{{ mb_substr($item['name'], 0, 1) }}</span>
                                </div>
                            @endif
                        </div>

                        {{-- Name & Notes --}}
                        <div class="flex-1 min-w-0">
                            <h3 class="font-bold text-bark text-sm leading-snug truncate">{{ $item['name'] }}</h3>
                            <p class="text-ember font-semibold text-sm mt-0.5">
                                Rp {{ number_format($item['price'], 0, ',', '.') }}
                            </p>
                            @if(!empty($item['notes']))
                                <p class="text-bark/45 text-xs mt-1 italic bg-bark/5 rounded-lg px-2 py-1 line-clamp-1">
                                    "{{ $item['notes'] }}"
                                </p>
                            @endif
                        </div>

                        {{-- Delete Button --}}
                        <form action="{{ route('cart.remove', $item['id']) }}" method="POST" class="flex-shrink-0">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    title="Hapus item"
                                    class="w-8 h-8 rounded-full bg-red-50 hover:bg-red-100 text-red-400 hover:text-red-600 flex items-center justify-center transition-colors">
                                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                                </svg>
                            </button>
                        </form>
                    </div>

                    {{-- Quantity Controls & Subtotal --}}
                    <div class="border-t border-bark/6 px-4 py-3 flex items-center justify-between bg-cream/40">

                        {{-- Qty Stepper --}}
                        <form action="{{ route('cart.update', $item['id']) }}" method="POST"
                              id="qty-form-{{ $item['id'] }}"
                              class="flex items-center gap-2">
                            @csrf
                            @method('PATCH')
                            <input type="hidden" name="quantity" id="qty-input-{{ $item['id'] }}" value="{{ $item['quantity'] }}">

                            <button type="button"
                                    onclick="adjustQty({{ $item['id'] }}, -1)"
                                    class="qty-btn w-8 h-8 rounded-full bg-bark/10 hover:bg-bark/20 text-bark font-bold text-lg flex items-center justify-center leading-none">
                                −
                            </button>

                            <span id="qty-display-{{ $item['id'] }}"
                                  class="w-8 text-center font-bold text-bark text-sm select-none">
                                {{ $item['quantity'] }}
                            </span>

                            <button type="button"
                                    onclick="adjustQty({{ $item['id'] }}, 1)"
                                    class="qty-btn w-8 h-8 rounded-full bg-ember/15 hover:bg-ember/25 text-ember font-bold text-lg flex items-center justify-center leading-none">
                                +
                            </button>
                        </form>

                        {{-- Subtotal --}}
                        <div class="text-right">
                            <p class="text-[10px] text-bark/40 font-medium uppercase tracking-wider">Subtotal</p>
                            <p class="font-display font-bold text-bark text-base" id="subtotal-{{ $item['id'] }}">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>


            {{-- ─── ORDER SUMMARY (sidebar) ─── --}}
            <div class="lg:col-span-1">
                <div class="sticky top-24 bg-white rounded-2xl border border-bark/10 shadow-sm overflow-hidden animate-slide-up" style="animation-delay:0.2s">

                    {{-- Summary Header --}}
                    <div class="bg-gradient-to-r from-bark to-charcoal text-cream px-5 py-4">
                        <h2 class="font-display text-lg">Ringkasan <span class="italic text-saffron">Pesanan</span></h2>
                    </div>

                    <div class="p-5 space-y-3">
                        {{-- Item breakdown --}}
                        @foreach($cart as $item)
                        <div class="flex justify-between items-start text-sm">
                            <span class="text-bark/70 flex-1 pr-2 line-clamp-1">
                                {{ $item['name'] }}
                                <span class="text-bark/40"> ×{{ $item['quantity'] }}</span>
                            </span>
                            <span class="font-semibold text-bark flex-shrink-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </span>
                        </div>
                        @endforeach

                        {{-- Divider --}}
                        <div class="border-t-2 border-dashed border-bark/10 pt-3">
                            <div class="flex justify-between items-center">
                                <span class="font-bold text-bark">Total</span>
                                <span class="font-display font-bold text-ember text-xl">
                                    Rp {{ number_format($total, 0, ',', '.') }}
                                </span>
                            </div>
                            <p class="text-bark/35 text-[11px] mt-1">Belum termasuk ongkos kirim</p>
                        </div>

                        {{-- CTA Checkout --}}
                        <a href="{{ route('checkout.index') }}"
                           class="mt-2 w-full bg-ember hover:bg-orange-600 active:scale-95 text-cream font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all shadow-md hover:shadow-lg text-sm">
                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            Lanjut ke Checkout
                        </a>

                        <a href="{{ route('catalog.index') }}"
                           class="w-full border-2 border-bark/20 text-bark/70 hover:border-bark/40 hover:text-bark font-semibold py-2.5 rounded-xl flex items-center justify-center gap-1.5 transition-all text-sm">
                            + Tambah Menu Lagi
                        </a>
                    </div>
                </div>
            </div>

        </div>
    @endif
</main>


{{-- ═══════════════════════════════════════
     STICKY CHECKOUT BAR (mobile only)
═══════════════════════════════════════ --}}
@if(!empty($cart))
<div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white border-t border-bark/10 shadow-2xl px-4 py-3">
    <div class="flex items-center justify-between gap-3">
        <div>
            <p class="text-[10px] text-bark/40 font-medium uppercase tracking-wider">Total</p>
            <p class="font-display font-bold text-ember text-lg leading-tight">Rp {{ number_format($total, 0, ',', '.') }}</p>
        </div>
        <a href="{{ route('checkout.index') }}"
           class="flex-1 bg-ember hover:bg-orange-600 text-cream font-bold py-3 rounded-xl text-center text-sm transition-all active:scale-95 shadow-lg">
            Checkout Sekarang →
        </a>
    </div>
</div>
<div class="h-20 lg:hidden"></div> {{-- Spacer agar konten tidak tertutup sticky bar --}}
@endif


{{-- ═══════════════════════════════════════
     JAVASCRIPT — Quantity Stepper
═══════════════════════════════════════ --}}
<script>
/**
 * Mengubah quantity secara optimistic di UI,
 * lalu submit form ke server untuk sinkronisasi session.
 */
function adjustQty(productId, delta) {
    const input   = document.getElementById('qty-input-' + productId);
    const display = document.getElementById('qty-display-' + productId);
    const form    = document.getElementById('qty-form-' + productId);

    if (!input || !form) return;

    let newQty = parseInt(input.value) + delta;
    if (newQty < 1)  newQty = 1;
    if (newQty > 99) newQty = 99;

    // Update display & input value
    input.value    = newQty;
    display.textContent = newQty;

    // Update subtotal secara lokal (harga tersimpan di data-attribute)
    const row       = document.getElementById('item-row-' + productId);
    const priceCell = row?.querySelector('[data-price]');
    const subtotalEl = document.getElementById('subtotal-' + productId);
    if (priceCell && subtotalEl) {
        const price    = parseInt(priceCell.dataset.price);
        const subtotal = price * newQty;
        subtotalEl.textContent = 'Rp ' + subtotal.toLocaleString('id-ID');
    }

    // Debounce server submit (300ms setelah berhenti klik)
    clearTimeout(window['qtyTimer_' + productId]);
    window['qtyTimer_' + productId] = setTimeout(() => form.submit(), 300);
}
</script>

</body>
</html>
