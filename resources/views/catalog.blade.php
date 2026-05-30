<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Warung Nymphaea — Menu Hari Ini</title>
 
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
                        'slide-up': { '0%': { opacity:'0', transform:'translateY(20px)' }, '100%': { opacity:'1', transform:'translateY(0)' } },
                        'fade-in':  { '0%': { opacity:'0' }, '100%': { opacity:'1' } },
                        'float':    { '0%,100%': { transform:'translateY(0)' }, '50%': { transform:'translateY(-10px)' } },
                    },
                    animation: {
                        'slide-up': 'slide-up 0.5s ease-out both',
                        'fade-in':  'fade-in 0.4s ease-out both',
                        'float':    'float 5s ease-in-out infinite',
                    }
                }
            }
        }
    </script>
 
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='noise'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23noise)' opacity='0.03'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }
        .product-card { transition: transform 0.25s ease, box-shadow 0.25s ease; }
        .product-card:hover { transform: translateY(-4px); box-shadow: 0 16px 40px rgba(92,61,46,0.15); }
        .cat-pill-active { background: #D4622A; color: #FDF6EC; box-shadow: 0 4px 12px rgba(212,98,42,0.35); }
        .product-grid > *:nth-child(1)  { animation-delay: 0.05s; }
        .product-grid > *:nth-child(2)  { animation-delay: 0.10s; }
        .product-grid > *:nth-child(3)  { animation-delay: 0.15s; }
        .product-grid > *:nth-child(4)  { animation-delay: 0.20s; }
        .product-grid > *:nth-child(5)  { animation-delay: 0.25s; }
        .product-grid > *:nth-child(6)  { animation-delay: 0.30s; }
        .product-grid > *:nth-child(7)  { animation-delay: 0.35s; }
        .product-grid > *:nth-child(8)  { animation-delay: 0.40s; }
        .product-grid > *:nth-child(n+9){ animation-delay: 0.45s; }
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: #FDF6EC; }
        ::-webkit-scrollbar-thumb { background: #D4622A; border-radius: 3px; }
        @keyframes toast-in {
            from { opacity:0; transform: translateY(100%) scale(0.9); }
            to   { opacity:1; transform: translateY(0) scale(1); }
        }
        .toast-enter { animation: toast-in 0.35s cubic-bezier(0.34,1.56,0.64,1) forwards; }
        textarea:focus { outline: none; border-color: #D4622A; box-shadow: 0 0 0 3px rgba(212,98,42,0.15); }
 
        /* Scroll-reveal: elemen dengan kelas ini muncul saat masuk viewport */
        .reveal { opacity: 0; transform: translateY(28px); transition: opacity 0.6s ease, transform 0.6s ease; }
        .reveal.visible { opacity: 1; transform: translateY(0); }
        .reveal-delay-1 { transition-delay: 0.1s; }
        .reveal-delay-2 { transition-delay: 0.2s; }
        .reveal-delay-3 { transition-delay: 0.3s; }
 
        /* Nav link underline hover */
        .nav-link { position: relative; }
        .nav-link::after { content:''; position:absolute; bottom:-2px; left:0; width:0; height:2px; background:#F2A035; border-radius:2px; transition:width 0.3s ease; }
        .nav-link:hover::after, .nav-link.active::after { width:100%; }
    </style>
</head>
 
<body class="relative text-charcoal min-h-screen">
 
{{-- ═══════════════════════════════════════
     NAVBAR — Updated dengan nav links
═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bark shadow-lg">
    <div class="max-w-6xl mx-auto px-4 h-16 flex items-center justify-between gap-4">
 
        {{-- Logo --}}
        <a href="{{ route('catalog.index') }}" class="flex items-center gap-3 flex-shrink-0 group">
            <div class="w-9 h-9 rounded-full bg-ember flex items-center justify-center text-cream font-display font-bold text-lg leading-none shadow-inner group-hover:scale-110 transition-transform">
                W
            </div>
            <span class="font-display text-cream text-xl tracking-wide">Warung<span class="text-saffron italic">Nymphaea</span></span>
        </a>
 
        {{-- Desktop Nav Links --}}
        <nav class="hidden md:flex items-center gap-6 text-sm font-semibold text-cream/70">
            <a href="{{ route('catalog.index') }}"
               class="nav-link active hover:text-cream transition-colors">Menu</a>
            <a href="#about"
               class="nav-link hover:text-cream transition-colors">Tentang Kami</a>
            <a href="{{ route('contact') }}"
               class="nav-link hover:text-cream transition-colors">Kontak</a>
        </nav>
 
        {{-- Right: Cart + Mobile Hamburger --}}
        <div class="flex items-center gap-3">
            {{-- Cart Icon --}}
            <a href="{{ route('cart.index') }}" class="relative flex items-center gap-2 bg-ember hover:bg-orange-600 text-cream px-4 py-2 rounded-full font-semibold text-sm transition-all hover:shadow-lg active:scale-95">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
                <span class="hidden sm:inline">Keranjang</span>
                @if($cartCount > 0)
                    <span class="absolute -top-1.5 -right-1.5 bg-saffron text-charcoal text-xs font-bold w-5 h-5 rounded-full flex items-center justify-center shadow">
                        {{ $cartCount > 99 ? '99+' : $cartCount }}
                    </span>
                @endif
            </a>
 
            {{-- Mobile menu toggle --}}
            <button id="mobile-menu-btn"
                    class="md:hidden w-9 h-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition-colors"
                    aria-label="Toggle menu">
                <svg id="icon-open" class="w-5 h-5 text-cream" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
                <svg id="icon-close" class="w-5 h-5 text-cream hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </button>
        </div>
    </div>
 
    {{-- Mobile Dropdown Menu --}}
    <div id="mobile-menu" class="hidden md:hidden bg-charcoal border-t border-white/10">
        <nav class="max-w-6xl mx-auto px-4 py-3 flex flex-col gap-1">
            <a href="{{ route('catalog.index') }}" class="text-cream/80 hover:text-cream hover:bg-white/10 font-semibold text-sm px-3 py-2.5 rounded-lg transition-colors">🍽️ Menu</a>
            <a href="#about" id="mobile-about-link" class="text-cream/80 hover:text-cream hover:bg-white/10 font-semibold text-sm px-3 py-2.5 rounded-lg transition-colors">✨ Tentang Kami</a>
            <a href="{{ route('contact') }}" class="text-cream/80 hover:text-cream hover:bg-white/10 font-semibold text-sm px-3 py-2.5 rounded-lg transition-colors">📍 Kontak</a>
        </nav>
    </div>
</header>
 
 
{{-- ═══════════════════════════════════════
     HERO STRIP
═══════════════════════════════════════ --}}
<section class="bg-gradient-to-br from-bark via-[#3D2314] to-charcoal text-cream py-10 px-4 relative overflow-hidden">
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
 
 
{{-- FLASH MESSAGE --}}
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
            <a href="{{ route('catalog.index') }}"
               class="px-4 py-1.5 rounded-full text-sm font-semibold border-2 border-bark/20 transition-all
                      {{ is_null($activeCategory) ? 'cat-pill-active border-ember' : 'text-bark hover:border-ember hover:text-ember' }}">
                Semua Menu
            </a>
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
                <div class="relative bg-gradient-to-br from-saffron/20 to-ember/20 aspect-[4/3] overflow-hidden flex-shrink-0">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <span class="font-display text-5xl text-ember/30">{{ mb_substr($product->name, 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="absolute top-2 left-2 bg-bark/70 backdrop-blur-sm text-cream/90 text-[10px] font-semibold px-2 py-0.5 rounded-full">
                        {{ $product->category->name }}
                    </span>
                </div>
                <div class="p-3 flex flex-col flex-1">
                    <h2 class="font-bold text-bark text-sm leading-snug mb-0.5 line-clamp-2">{{ $product->name }}</h2>
                    @if($product->description)
                        <p class="text-bark/50 text-xs leading-relaxed line-clamp-2 mb-2">{{ $product->description }}</p>
                    @endif
                    <div class="mt-auto">
                        <p class="font-display text-ember font-bold text-base mb-2">
                            Rp {{ number_format($product->price, 0, ',', '.') }}
                        </p>
                        <form action="{{ route('cart.add', $product->id) }}" method="POST" class="space-y-1.5">
                            @csrf
                            <input type="hidden" name="quantity" value="1">
                            <textarea name="notes" rows="1" placeholder="Catatan (opsional)…"
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
     SECTION: TENTANG KAMI
═══════════════════════════════════════ --}}
<section id="about" class="bg-gradient-to-b from-cream to-[#F5EBDA] py-20 px-4 relative overflow-hidden">
 
    {{-- Decorative background blobs --}}
    <div class="absolute top-0 right-0 w-72 h-72 rounded-full bg-saffron/8 -translate-y-1/2 translate-x-1/3 pointer-events-none"></div>
    <div class="absolute bottom-0 left-0 w-56 h-56 rounded-full bg-ember/6 translate-y-1/2 -translate-x-1/3 pointer-events-none"></div>
 
    <div class="max-w-6xl mx-auto relative z-10">
 
        {{-- Section label --}}
        <div class="flex items-center gap-3 mb-12 justify-center">
            <div class="h-px flex-1 max-w-16 bg-bark/20"></div>
            <span class="text-ember text-xs font-bold uppercase tracking-[0.2em]">Kenalan Dulu Yuk</span>
            <div class="h-px flex-1 max-w-16 bg-bark/20"></div>
        </div>
 
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
 
            {{-- ── KOLOM KIRI: Ilustrasi Toko ───────────────────────────────── --}}
            <div class="reveal order-2 lg:order-1">
                <div class="relative">
 
                    {{-- Frame utama --}}
                    <div class="relative bg-gradient-to-br from-bark to-charcoal rounded-3xl overflow-hidden aspect-[4/3] shadow-2xl">
 
                        {{-- Background pattern --}}
                        <div class="absolute inset-0 opacity-10"
                             style="background-image: radial-gradient(circle, #F2A035 1px, transparent 1px); background-size: 20px 20px;"></div>
 
                        {{-- Content inside frame --}}
                        <div class="absolute inset-0 flex flex-col items-center justify-center p-8 text-center">
                            {{-- Ilustrasi SVG warung --}}
                            <svg class="w-24 h-24 text-saffron/80 mb-4 animate-float" viewBox="0 0 64 64" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <rect x="8" y="28" width="48" height="28" rx="2" fill="currentColor" fill-opacity="0.2" stroke="currentColor" stroke-width="1.5"/>
                                <path d="M4 28 L12 12 H52 L60 28" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" fill="currentColor" fill-opacity="0.15"/>
                                <rect x="24" y="38" width="16" height="18" rx="2" fill="currentColor" fill-opacity="0.4"/>
                                <rect x="12" y="34" width="10" height="10" rx="1.5" fill="currentColor" fill-opacity="0.5"/>
                                <rect x="42" y="34" width="10" height="10" rx="1.5" fill="currentColor" fill-opacity="0.5"/>
                                <path d="M4 28 H60" stroke="currentColor" stroke-width="1.5"/>
                                <circle cx="32" cy="8" r="4" fill="currentColor" fill-opacity="0.6"/>
                                <path d="M32 12 V18" stroke="currentColor" stroke-width="1.5" stroke-linecap="round"/>
                            </svg>
                            <p class="font-display text-cream text-2xl mb-1">Warung Nymphaea</p>
                            <p class="text-cream/50 text-xs font-medium tracking-widest uppercase">Berdiri sejak 2019</p>
                        </div>
 
                        {{-- Overlay gradient bottom --}}
                        <div class="absolute bottom-0 inset-x-0 h-1/3 bg-gradient-to-t from-charcoal/60 to-transparent"></div>
                    </div>
 
                    {{-- Floating stat cards --}}
                    <div class="absolute -bottom-4 -left-4 bg-white rounded-2xl shadow-xl border border-bark/8 px-4 py-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-ember/10 flex items-center justify-center">
                            <span class="text-xl">⭐</span>
                        </div>
                        <div>
                            <p class="font-display font-bold text-bark text-lg leading-none">4.9</p>
                            <p class="text-bark/50 text-xs">Rating Pelanggan</p>
                        </div>
                    </div>
 
                    <div class="absolute -top-4 -right-4 bg-white rounded-2xl shadow-xl border border-bark/8 px-4 py-3 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-xl bg-sage/15 flex items-center justify-center">
                            <span class="text-xl">🍽️</span>
                        </div>
                        <div>
                            <p class="font-display font-bold text-bark text-lg leading-none">500+</p>
                            <p class="text-bark/50 text-xs">Pesanan Selesai</p>
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- ── KOLOM KANAN: Narasi Toko ─────────────────────────────────── --}}
            <div class="order-1 lg:order-2 space-y-6">
                <div class="reveal">
                    <h2 class="font-display text-4xl sm:text-5xl text-bark leading-tight mb-4">
                        Cerita di Balik<br>
                        Setiap <span class="italic text-ember">Sajian</span>
                    </h2>
                    <p class="text-bark/65 leading-relaxed text-base">
                        Warung Nymphaea lahir dari dapur kecil di Lumajang pada 2019 — berawal dari keinginan sederhana: menyajikan masakan rumahan yang lezat, jujur bahan-bahannya, dan terjangkau untuk semua kalangan.
                    </p>
                </div>
 
                {{-- Feature points --}}
                <div class="reveal reveal-delay-1 space-y-4">
                    @php
                    $pillars = [
                        ['icon'=>'🌿', 'title'=>'Bahan Segar Setiap Hari',   'desc'=>'Kami memilih bahan dari pasar lokal setiap pagi. Tidak ada yang dimasak kemarin untuk dijual hari ini.'],
                        ['icon'=>'🧑‍🍳', 'title'=>'Dimasak dengan Cinta',     'desc'=>'Resep turun-temurun yang terus kami jaga, dipadukan dengan teknik memasak modern agar selalu konsisten.'],
                        ['icon'=>'🛵', 'title'=>'Delivery Cepat & Hangat',   'desc'=>'Pesananmu dikemas rapat agar tetap hangat saat tiba. Area Lumajang kota kami layani dalam 30–45 menit.'],
                        ['icon'=>'🤝', 'title'=>'Mendukung Produk Lokal',    'desc'=>'Seluruh bahan kami sumber dari petani dan pedagang lokal Lumajang. Belanja di sini berarti ikut menggerakkan ekonomi daerah.'],
                    ];
                    @endphp
                    @foreach($pillars as $p)
                    <div class="flex gap-4 items-start">
                        <div class="w-11 h-11 rounded-xl bg-white border border-bark/10 shadow-sm flex items-center justify-center flex-shrink-0 text-xl">
                            {{ $p['icon'] }}
                        </div>
                        <div>
                            <h3 class="font-bold text-bark text-sm mb-0.5">{{ $p['title'] }}</h3>
                            <p class="text-bark/55 text-xs leading-relaxed">{{ $p['desc'] }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
 
                {{-- CTA --}}
                <div class="reveal reveal-delay-2 flex flex-wrap gap-3 pt-2">
                    <a href="{{ route('catalog.index') }}"
                       class="bg-ember hover:bg-orange-600 text-cream font-bold px-6 py-3 rounded-full text-sm transition-all active:scale-95 shadow-md hover:shadow-lg">
                        Lihat Menu Kami
                    </a>
                    <a href="{{ route('contact') }}"
                       class="border-2 border-bark/25 text-bark hover:border-bark/50 font-bold px-6 py-3 rounded-full text-sm transition-all active:scale-95">
                        Hubungi Kami
                    </a>
                </div>
            </div>
 
        </div>
    </div>
</section>
 
 
{{-- ═══════════════════════════════════════
     SECTION: KEUNGGULAN / WHY US (strip kecil)
═══════════════════════════════════════ --}}
<section class="bg-bark py-10 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6 text-center">
            @php
            $stats = [
                ['val'=>'2019',  'label'=>'Tahun Berdiri',      'icon'=>'🏠'],
                ['val'=>'500+',  'label'=>'Pesanan Terkirim',   'icon'=>'🛵'],
                ['val'=>'25+',   'label'=>'Varian Menu',        'icon'=>'🍽️'],
                ['val'=>'4.9★',  'label'=>'Rata-rata Rating',   'icon'=>'⭐'],
            ];
            @endphp
            @foreach($stats as $s)
            <div class="reveal">
                <div class="text-2xl mb-1">{{ $s['icon'] }}</div>
                <p class="font-display text-saffron text-3xl font-bold">{{ $s['val'] }}</p>
                <p class="text-cream/50 text-xs mt-1 font-medium">{{ $s['label'] }}</p>
            </div>
            @endforeach
        </div>
    </div>
</section>
 
 
{{-- FLOATING CART BUTTON (mobile) --}}
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
     FOOTER — Updated dengan links lengkap
═══════════════════════════════════════ --}}
<footer class="bg-charcoal text-cream pt-12 pb-6 px-4">
    <div class="max-w-6xl mx-auto">
        <div class="grid grid-cols-1 sm:grid-cols-3 gap-8 mb-10">
 
            {{-- Brand --}}
            <div class="sm:col-span-1">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-9 h-9 rounded-full bg-ember flex items-center justify-center text-cream font-display font-bold text-lg">W</div>
                    <span class="font-display text-cream text-xl">Warung<span class="text-saffron italic">Nymphaea</span></span>
                </div>
                <p class="text-cream/40 text-xs leading-relaxed max-w-xs">
                    Masakan rumahan yang lezat, jujur bahan-bahannya, dan terjangkau untuk semua. Melayani area Lumajang sejak 2019.
                </p>
            </div>
 
            {{-- Navigasi --}}
            <div>
                <p class="text-cream/30 text-xs font-bold uppercase tracking-widest mb-4">Navigasi</p>
                <ul class="space-y-2.5 text-sm">
                    <li><a href="{{ route('catalog.index') }}" class="text-cream/60 hover:text-saffron transition-colors">🍽️ Menu Hari Ini</a></li>
                    <li><a href="#about" class="text-cream/60 hover:text-saffron transition-colors">✨ Tentang Kami</a></li>
                    <li><a href="{{ route('contact') }}" class="text-cream/60 hover:text-saffron transition-colors">📍 Kontak & Lokasi</a></li>
                    <li><a href="{{ route('cart.index') }}" class="text-cream/60 hover:text-saffron transition-colors">🛒 Keranjang</a></li>
                </ul>
            </div>
 
            {{-- Kontak singkat --}}
            <div>
                <p class="text-cream/30 text-xs font-bold uppercase tracking-widest mb-4">Hubungi Kami</p>
                <ul class="space-y-2.5 text-sm text-cream/60">
                    <li class="flex items-start gap-2">
                        <span class="flex-shrink-0">📍</span>
                        <span>Jl. Melati No. 12, Lumajang, Jawa Timur</span>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>📱</span>
                        <a href="https://wa.me/6281234567890" target="_blank" class="hover:text-saffron transition-colors">0812-3456-7890</a>
                    </li>
                    <li class="flex items-center gap-2">
                        <span>🕐</span>
                        <span>Buka: 07.00 – 21.00 WIB</span>
                    </li>
                </ul>
            </div>
        </div>
 
        {{-- Bottom bar --}}
        <div class="border-t border-white/8 pt-6 flex flex-col sm:flex-row justify-between items-center gap-2 text-xs text-cream/25">
            <p>© {{ date('Y') }} Warung Nymphaea. Semua hak cipta dilindungi.</p>
            <p>Dibuat dengan ❤️ untuk UMKM Indonesia</p>
        </div>
    </div>
</footer>
 
 
{{-- ═══════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════ --}}
<script>
// Mobile menu toggle
const btn   = document.getElementById('mobile-menu-btn');
const menu  = document.getElementById('mobile-menu');
const open  = document.getElementById('icon-open');
const close = document.getElementById('icon-close');
 
btn?.addEventListener('click', () => {
    menu.classList.toggle('hidden');
    open.classList.toggle('hidden');
    close.classList.toggle('hidden');
});
 
// Close mobile menu when clicking a nav link
document.querySelectorAll('#mobile-menu a').forEach(link => {
    link.addEventListener('click', () => {
        menu.classList.add('hidden');
        open.classList.remove('hidden');
        close.classList.add('hidden');
    });
});
 
// Scroll-reveal using IntersectionObserver
const observer = new IntersectionObserver((entries) => {
    entries.forEach(e => {
        if (e.isIntersecting) {
            e.target.classList.add('visible');
            observer.unobserve(e.target); // fire once
        }
    });
}, { threshold: 0.15 });
 
document.querySelectorAll('.reveal').forEach(el => observer.observe(el));
</script>
 
</body>
</html>