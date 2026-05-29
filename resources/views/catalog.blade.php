<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warung Nymphaea — Menu Hari Ini</title>

    {{-- Tailwind CSS CDN --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Google Fonts: Playfair Display (display) + Plus Jakarta Sans (body) --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Playfair Display"', 'serif'],
                        body: ['"Plus Jakarta Sans"', 'sans-serif'],
                    },
                    colors: {
                        cream:   '#FDF6EC',
                        bark:    '#5C3D2E',
                        ember:   '#D4622A',
                        saffron: '#F2A035',
                        sage:    '#7A9E7E',
                        charcoal:'#2C2016',
                    },
                    keyframes: {
                        'slide-up': {
                            '0%':   { opacity: '0', transform: 'translateY(20px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                        'fade-in': {
                            '0%':   { opacity: '0' },
                            '100%': { opacity: '1' },
                        },
                        'pop': {
                            '0%, 100%': { transform: 'scale(1)' },
                            '50%':      { transform: 'scale(1.08)' },
                        }
                    },
                    animation: {
                        'slide-up': 'slide-up 0.5s ease-out both',
                        'fade-in':  'fade-in 0.4s ease-out both',
                        'pop':      'pop 0.3s ease',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }

        /* Noise texture overlay untuk depth */
        body::before {
            content: '';
            position: fixed;
            inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none;
            z-index: 0;
        }

        /* Card hover lift */
        .product-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(92,61,46,0.15); }

        /* Category pill active */
        .cat-pill-active { background: #D4622A; color: #FDF6EC; box-shadow: 0 4px 12px rgba(212,98,42,0.35); }

        /* Stagger animation for cards */
        .product-grid > *:nth-child(1)  { animation-delay: 0.05s; }
        .product-grid > *:nth-child(2)  { animation-delay: 0.10s; }
        .product-grid > *:nth-child(3)  { animation-delay: 0.15s; }
        .product-grid > *:nth-child(4)  { animation-delay: 0.20s; }
        .product-grid > *:nth-child(5)  { animation-delay: 0.25s; }
        .product-grid > *:nth-child(6)  { animation-delay: 0.30s; }
        .product-grid > *:nth-child(7)  { animation-delay: 0.35s; }
        .product-grid > *:nth-child(8)  { animation-delay: 0.40s; }
        .product-grid > *:nth-child(n+9){ animation-delay: 0.45s; }

        /* Custom scrollbar */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FDF6EC; }
        ::-webkit-scrollbar-thumb { background: #D4622A; border-radius: 3px; }

        /* Flash toast animation */
        @keyframes toast-in {
            from { opacity:0; transform: translateY(100%) scale(0.9); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }
        .toast-enter { animation: toast-in 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards; }

        /* Notes textarea focus */
        textarea:focus { outline: none; border-color: #D4622A; box-shadow: 0 0 0 3px rgba(212,98,42,0.15); }
    </style>
</head>
<body class="relative text-charcoal min-h-screen">

{{-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bark shadow-lg">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between">

        {{-- Logo --}}
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 group">
            <div class="w-9 h-9 rounded-full bg-ember flex items-center justify-center text-cream font-display font-bold text-lg leading-none shadow-inner group-hover:scale-110 transition-transform">
                W
            </div>
            <span class="font-display text-cream text-xl tracking-wide">Warung<span class="text-saffron italic">Nymphaea</span></span>
        </a>

        {{-- Cart Icon --}}
        <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 bg-ember hover:bg-orange-600 text-cream px-4 py-2 rounded-full font-semibold text-sm transition-all hover:shadow-lg active:scale-95">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
            </svg>
            <span class="hidden sm:inline">Keranjang</span>
            @if($cartCount > 0)
                <span class="absolute -top-1.5 -right-1.5 bg-saffron text-charcoal text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow">
                    {{ $cartCount > 99 ? '99+' : $cartCount }}
                </span>
            @endif
        </a>
    </div>
</header>


{{-- ═══════════════════════════════════════
     HERO STRIP
═══════════════════════════════════════ --}}
<section class="bg-gradient-to-br from-bark via-[#3D2314] to-charcoal text-cream py-10 px-4 relative overflow-hidden">
    {{-- Decorative ring --}}
    <div class="absolute -right-16 -top-16 w-64 h-64 rounded-full border-[40px] border-saffron/10 pointer-events-none"></div>
    <div class="absolute -left-8 bottom-0 w-40 h-40 rounded-full border-[24px] border-ember/10 pointer-events-none"></div>

    <div class="max-w-6xl mx-auto relative z-10">
        <p class="text-saffron text-sm font-semibold tracking-widest uppercase mb-1 animate-fade-in">Selamat datang</p>
        <h1 class="font-display text-3xl sm:text-4xl lg:text-5xl leading-tight mb-2 animate-slide-up">
            Menu <span class="italic text-saffron">Hari Ini</span>
        </h1>
        <p class="text-cream/60 text-sm sm:text-base max-w-md animate-slide-up" style="animation-delay:0.1s">
            Masakan rumahan, bahan segar, diantarkan hangat ke pintumu.
        </p>
    </div>
</section>


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
     CATEGORY FILTER
═══════════════════════════════════════ --}}
<div class="bg-cream/80 backdrop-blur border-b border-bark/10 sticky top-16 z-40">
    <div class="max-w-6xl mx-auto px-4 py-3 overflow-x-auto">
        <div class="flex gap-2 min-w-max">
            {{-- Semua Menu --}}
            <a href="{{ route('catalog.index') }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-bark/20 transition-all
                      {{ is_null($activeCategory) ? 'cat-pill-active border-ember' : 'text-bark hover:border-ember hover:text-ember' }}">
                Semua Menu
            </a>

            {{-- Per Kategori --}}
            @foreach($categories as $cat)
                <a href="{{ route('catalog.index', ['category' => $cat->slug]) }}"
                   class="px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-bark/20 transition-all whitespace-nowrap
                          {{ ($activeCategory && $activeCategory->id === $cat->id) ? 'cat-pill-active border-ember' : 'text-bark hover:border-ember hover:text-ember' }}">
                    {{ $cat->name }}
                </a>
            @endforeach
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════
     PRODUCT GRID
═══════════════════════════════════════ --}}
<main class="max-w-6xl mx-auto px-4 py-8 relative z-10">

    @if($activeCategory)
        <div class="mb-6 flex items-center gap-2">
            <span class="text-bark/50 text-sm">Menampilkan:</span>
            <span class="bg-ember/10 text-ember text-sm font-semibold px-3 py-1 rounded-full border border-ember/30">
                {{ $activeCategory->name }}
            </span>
            <a href="{{ route('catalog.index') }}" class="text-bark/40 hover:text-bark text-xs ml-1">✕ hapus filter</a>
        </div>
    @endif

    @if($products->isEmpty())
        <div class="text-center py-20">
            <div class="text-6xl mb-4">🍽️</div>
            <p class="text-bark/60 font-semibold text-lg">Menu sedang tidak tersedia</p>
            <p class="text-bark/40 text-sm mt-1">Coba kategori lain atau kembali lagi nanti.</p>
        </div>
    @else
        <div class="product-grid grid grid-cols-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4 sm:gap-5">
            @foreach($products as $product)
            <article class="product-card animate-slide-up bg-white rounded-2xl overflow-hidden border border-bark/8 shadow-sm flex flex-col">

                {{-- Product Image --}}
                <div class="relative bg-gradient-to-br from-saffron/20 to-ember/20 aspect-[4/3] overflow-hidden flex-shrink-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}"
                             alt="{{ $product->name }}"
                             class="w-full h-full object-cover">
                    @else
                        {{-- Placeholder dengan inisial produk --}}
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="font-display text-5xl text-ember/30">
                                {{ mb_substr($product->name, 0, 1) }}
                            </span>
                        </div>
                    @endif

                    {{-- Category badge --}}
                    <span class="absolute top-2 left-2 bg-bark/70 backdrop-blur-sm text-cream/90 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                        {{ $product->category->name }}
                    </span>
                </div>

                {{-- Product Info --}}
                <div class="p-3 flex flex-col flex-1">
                    <h2 class="font-bold text-bark text-sm leading-snug mb-0.5 line-clamp-2">{{ $product->name }}</h2>

                    @if($product->description)
                        <p class="text-bark/50 text-xs leading-relaxed line-clamp-2 mb-2">{{ $product->description }}</p>
                    @endif

                    <div class="mt-auto">
                        <p class="font-display text-ember font-bold text-base mb-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>

                        {{-- Add to Cart Form --}}
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-1.5">
                            @csrf
                            <input type="hidden" name="quantity" value="1">

                            {{-- Notes Input --}}
                            <textarea name="notes"
                                      rows="1"
                                      placeholder="Catatan (opsional)…"
                                      class="w-full text-xs border border-bark/20 rounded-lg px-2.5 py-1.5 bg-cream/50 text-bark placeholder-bark/30 resize-none transition-all"
                                      oninput="this.rows = this.value ? 2 : 1"></textarea>

                            <button type="submit"
                                    class="w-full bg-ember hover:bg-orange-600 active:scale-95 text-cream text-xs font-bold py-2 rounded-xl transition-all flex items-center justify-center gap-1.5 shadow-sm hover:shadow-md">
                                <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4v16m8-8H4"/>
                                </svg>
                                Tambah
                            </button>
                        </form>
                    </div>
                </div>

            </article>
            @endforeach
        </div>

        <p class="text-center text-bark/30 text-xs mt-10">
            Menampilkan {{ $products->count() }} menu{{ $activeCategory ? ' dalam kategori ' . $activeCategory->name : '' }}
        </p>
    @endif
</main>


{{-- ═══════════════════════════════════════
     FLOATING CART BUTTON (mobile)
═══════════════════════════════════════ --}}
@if($cartCount > 0)
<div class="fixed bottom-6 right-4 z-50 sm:hidden animate-slide-up">
    <a href="{{ route('cart.index') }}"
       class="flex items-center gap-2 bg-bark text-cream pl-4 pr-5 py-3 rounded-full shadow-2xl font-bold text-sm border-2 border-saffron/50 active:scale-95 transition-transform">
        <span class="bg-ember text-white text-xs font-black w-6 h-6 rounded-full flex items-center justify-center">{{ $cartCount }}</span>
        Lihat Keranjang
    </a>
</div>
@endif


{{-- ═══════════════════════════════════════
     FOOTER
═══════════════════════════════════════ --}}
<footer class="bg-charcoal text-cream/40 text-center text-xs py-6 mt-12">
    <p>© {{ date('Y') }} Warung Nymphaea &mdash; Dibuat dengan ❤️ untuk UMKM Indonesia</p>
</footer>

</body>
</html>
