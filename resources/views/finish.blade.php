<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Status Pembayaran — Warung Nymphaea</title>
 
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
                        wa:       '#25D366',
                        'wa-dark':'#128C7E',
                    },
                    keyframes: {
                        'slide-up':    { '0%': { opacity: '0', transform: 'translateY(24px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        'scale-in':    { '0%': { opacity: '0', transform: 'scale(0.5)' }, '60%': { transform: 'scale(1.12)' }, '100%': { opacity: '1', transform: 'scale(1)' } },
                        'ping-once':   { '0%': { transform: 'scale(1)', opacity: '1' }, '80%, 100%': { transform: 'scale(2.2)', opacity: '0' } },
                        'bounce-soft': { '0%, 100%': { transform: 'translateY(0)' }, '50%': { transform: 'translateY(-6px)' } },
                        'wa-pulse':    { '0%, 100%': { 'box-shadow': '0 0 0 0 rgba(37,211,102,0.5)' }, '50%': { 'box-shadow': '0 0 0 10px rgba(37,211,102,0)' } },
                    },
                    animation: {
                        'slide-up':    'slide-up 0.5s ease-out both',
                        'scale-in':    'scale-in 0.6s cubic-bezier(0.34,1.56,0.64,1) both',
                        'ping-once':   'ping-once 0.8s ease-out 0.3s both',
                        'bounce-soft': 'bounce-soft 2s ease-in-out infinite',
                        'wa-pulse':    'wa-pulse 2s ease-in-out 1s infinite',
                    }
                }
            }
        }
    </script>
 
    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }
        body::before {
            content: ''; position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }
        .wa-btn { animation: wa-pulse 2s ease-in-out 1.2s infinite; }
    </style>
</head>
 
<body class="relative text-charcoal min-h-screen">
 
{{-- NAVBAR --}}
<header class="sticky top-0 z-50 bg-bark shadow-md">
    <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-center">
        <a href="{{ route('catalog.index') }}" class="font-display text-cream text-xl">
            Warung<span class="text-saffron italic">Nymphaea</span>
        </a>
    </div>
</header>
 
<main class="max-w-3xl mx-auto px-4 py-10 relative z-10">
<div class="max-w-md w-full mx-auto text-center">
 
    {{-- ════════════════════════════════════════════════
         TENTUKAN STATE: success / pending / failed
         Status FINAL ditentukan webhook, bukan halaman ini.
    ═════════════════════════════════════════════════ --}}
    @php
        $status    = $transactionStatus ?? 'unknown';
        $isSuccess = in_array($status, ['settlement', 'capture']);
        $isPending = in_array($status, ['pending', 'authorize']);
        $isFailed  = in_array($status, ['cancel', 'expire', 'deny', 'failure']);
 
        // ── Bangun teks WhatsApp otomatis ─────────────────────────────────────
        // Nomor HP admin UMKM — ganti sesuai nomor WA admin
        $adminPhone = '6281234567890'; // ← GANTI NOMOR INI
 
        $waText = '';
        if ($order) {
            $items = $order->relationLoaded('items') ? $order->items : $order->items()->get();
 
            $itemLines = $items->map(fn($item) =>
                '- ' . $item->product?->name ?? '[Produk]' . ' x' . $item->quantity
            )->implode("\n");
 
            $orderType   = $order->order_type === 'delivery' ? '🛵 Delivery' : '🥡 Takeaway';
            $totalFormatted = 'Rp ' . number_format($order->total_price, 0, ',', '.');
 
            $rawText = "Halo Admin Warung Nymphaea, saya ingin mengonfirmasi pesanan saya:\n\n"
                . "*Nota:* {$order->order_code}\n"
                . "*Nama:* {$order->customer_name}\n"
                . "*No. WA:* {$order->customer_phone}\n"
                . "*Metode:* {$orderType}\n"
                . ($order->shipping_address ? "*Alamat:* {$order->shipping_address}\n" : '')
                . "*Total Bayar:* {$totalFormatted}\n\n"
                . "*Detail Menu:*\n{$itemLines}\n\n"
                . "Mohon segera diproses ya, terima kasih! 🙏";
 
            $waText = urlencode($rawText);
        }
        $waUrl = "https://wa.me/{$adminPhone}?text={$waText}";
    @endphp
 
 
    {{-- ════════════════════════════
         ICON & HEADING PER STATE
    ════════════════════════════ --}}
    @if($isSuccess)
        <div class="relative mb-6">
            <div class="absolute inset-0 flex items-center justify-center">
                <div class="w-24 h-24 rounded-full bg-sage/20 animate-ping-once"></div>
            </div>
            <div class="relative w-24 h-24 mx-auto rounded-full bg-sage flex items-center justify-center shadow-lg animate-scale-in">
                <svg class="w-12 h-12 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                </svg>
            </div>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl text-bark mb-1 animate-slide-up">
            Pembayaran <span class="italic text-sage">Berhasil!</span>
        </h1>
        <p class="text-bark/55 text-sm mb-6 animate-slide-up" style="animation-delay:0.1s">
            Terima kasih! Pesananmu sedang kami siapkan. 🎉
        </p>
 
    @elseif($isPending)
        <div class="relative mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-saffron/20 border-4 border-saffron flex items-center justify-center animate-bounce-soft">
                <svg class="w-12 h-12 text-saffron" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl text-bark mb-1 animate-slide-up">
            Menunggu <span class="italic text-saffron">Konfirmasi</span>
        </h1>
        <p class="text-bark/55 text-sm mb-6 animate-slide-up" style="animation-delay:0.1s">
            Pembayaranmu sedang diverifikasi. Pesanan akan otomatis diproses setelah dikonfirmasi.
        </p>
 
    @else
        <div class="relative mb-6">
            <div class="w-24 h-24 mx-auto rounded-full bg-red-100 border-4 border-red-400 flex items-center justify-center animate-scale-in">
                <svg class="w-12 h-12 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12"/>
                </svg>
            </div>
        </div>
        <h1 class="font-display text-3xl sm:text-4xl text-bark mb-1 animate-slide-up">
            Pembayaran <span class="italic text-red-500">Gagal</span>
        </h1>
        <p class="text-bark/55 text-sm mb-6 animate-slide-up" style="animation-delay:0.1s">
            Transaksi dibatalkan atau kadaluarsa. Silakan coba lagi.
        </p>
    @endif
 
 
    {{-- ════════════════════════════
         DETAIL CARD PESANAN
    ════════════════════════════ --}}
    @if($order)
    <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-5 mb-4 text-left animate-slide-up" style="animation-delay:0.15s">
        <h2 class="font-bold text-bark/40 text-xs uppercase tracking-widest mb-4">Detail Pesanan</h2>
 
        <div class="space-y-2.5 mb-4">
            <div class="flex justify-between text-sm">
                <span class="text-bark/50">Kode Pesanan</span>
                <span class="font-bold font-mono text-bark tracking-wide">{{ $order->order_code }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-bark/50">Nama</span>
                <span class="font-semibold text-bark">{{ $order->customer_name }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-bark/50">No. WhatsApp</span>
                <span class="font-semibold text-bark">{{ $order->customer_phone }}</span>
            </div>
            <div class="flex justify-between text-sm">
                <span class="text-bark/50">Jenis Pesanan</span>
                <span class="font-semibold text-bark">
                    {{ $order->order_type === 'delivery' ? '🛵 Delivery' : '🥡 Takeaway' }}
                </span>
            </div>
            @if($order->shipping_address)
            <div class="flex justify-between text-sm gap-4">
                <span class="text-bark/50 flex-shrink-0">Alamat</span>
                <span class="font-medium text-bark text-right">{{ $order->shipping_address }}</span>
            </div>
            @endif
            <div class="flex justify-between text-sm">
                <span class="text-bark/50">Status Bayar</span>
                @if($isSuccess)
                    <span class="font-semibold text-sage">✅ Lunas</span>
                @elseif($isPending)
                    <span class="font-semibold text-saffron">⏳ Menunggu</span>
                @else
                    <span class="font-semibold text-red-500">❌ Gagal</span>
                @endif
            </div>
        </div>
 
        {{-- Item list --}}
        @php $orderItems = $order->relationLoaded('items') ? $order->items : $order->items()->get(); @endphp
        @if($orderItems->isNotEmpty())
        <div class="border-t border-dashed border-bark/10 pt-4 mb-4">
            <p class="text-bark/40 text-xs font-bold uppercase tracking-widest mb-3">Menu Dipesan</p>
            <div class="space-y-2">
                @foreach($orderItems as $item)
                <div class="flex justify-between items-center text-sm">
                    <div class="flex items-center gap-2 flex-1 min-w-0">
                        {{-- Inisial thumbnail --}}
                        <div class="w-8 h-8 rounded-lg bg-ember/10 flex items-center justify-center flex-shrink-0">
                            <span class="text-ember font-bold text-xs">
                                {{ mb_substr($item->product?->name ?? 'P', 0, 1) }}
                            </span>
                        </div>
                        <span class="text-bark font-medium truncate">
                            {{ $item->product?->name ?? '[Produk Dihapus]' }}
                        </span>
                    </div>
                    <div class="text-right flex-shrink-0 ml-3">
                        <span class="text-bark/50 text-xs">×{{ $item->quantity }}</span>
                        <span class="text-bark font-semibold ml-2">
                            Rp {{ number_format($item->price * $item->quantity, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif
 
        {{-- Total --}}
        <div class="border-t-2 border-dashed border-bark/10 pt-3 flex justify-between items-center">
            <span class="font-bold text-bark">Total Bayar</span>
            <span class="font-display font-bold text-ember text-2xl">
                Rp {{ number_format($order->total_price, 0, ',', '.') }}
            </span>
        </div>
    </div>
    @endif
 
 
    {{-- ════════════════════════════════════════
         TOMBOL KONFIRMASI WHATSAPP
         Tampil hanya untuk success dan pending
    ════════════════════════════════════════ --}}
    @if(($isSuccess || $isPending) && $order)
    <div class="animate-slide-up mb-4" style="animation-delay:0.25s">
 
        {{-- Label pengantar --}}
        <div class="flex items-center gap-2 mb-3">
            <div class="flex-1 h-px bg-bark/10"></div>
            <span class="text-bark/40 text-xs font-semibold uppercase tracking-wider px-2">Langkah Selanjutnya</span>
            <div class="flex-1 h-px bg-bark/10"></div>
        </div>
 
        {{-- Kotak penjelasan --}}
        <div class="bg-emerald-50 border border-emerald-200 rounded-xl p-4 mb-3 text-left">
            <div class="flex gap-3">
                <span class="text-xl flex-shrink-0">💬</span>
                <div>
                    <p class="text-emerald-800 font-bold text-sm">Konfirmasi ke Admin via WhatsApp</p>
                    <p class="text-emerald-700/70 text-xs mt-0.5 leading-relaxed">
                        Kirim detail pesananmu ke Admin agar segera diproses. Pesan sudah otomatis terisi — kamu tinggal klik kirim.
                    </p>
                </div>
            </div>
        </div>
 
        {{-- TOMBOL UTAMA WHATSAPP --}}
        <a href="{{ $waUrl }}"
           target="_blank"
           rel="noopener noreferrer"
           class="wa-btn w-full flex items-center justify-center gap-3
                  bg-[#25D366] hover:bg-[#1ebe5d] active:bg-[#128C7E]
                  active:scale-[0.98] text-white font-bold
                  py-4 px-6 rounded-2xl shadow-lg hover:shadow-xl
                  transition-all duration-200 text-base">
 
            {{-- WhatsApp SVG Icon --}}
            <svg class="w-6 h-6 flex-shrink-0" viewBox="0 0 24 24" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
            </svg>
 
            <span>Konfirmasi Pesanan via WhatsApp</span>
 
            {{-- Arrow icon --}}
            <svg class="w-4 h-4 flex-shrink-0 ml-auto" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
            </svg>
        </a>
 
        <p class="text-bark/30 text-xs mt-2 text-center">
            Membuka WhatsApp dengan pesan yang sudah otomatis terisi
        </p>
    </div>
    @endif
 
 
    {{-- ════════════════════════════
         INFO NOTE (pending/success)
    ════════════════════════════ --}}
    @if($isSuccess || $isPending)
    <div class="bg-bark/5 border border-bark/10 rounded-xl p-4 mb-5 text-left animate-slide-up" style="animation-delay:0.3s">
        <div class="flex gap-3 text-sm text-bark/60">
            <span class="text-base flex-shrink-0">ℹ️</span>
            <p class="leading-relaxed text-xs">
                Status pesanan diperbarui otomatis oleh sistem. Halaman ini menampilkan status sementara — konfirmasi final dikirim via notifikasi.
            </p>
        </div>
    </div>
    @endif
 
 
    {{-- ════════════════════════════
         CTA BUTTONS BAWAH
    ════════════════════════════ --}}
    <div class="flex flex-col sm:flex-row gap-3 animate-slide-up" style="animation-delay:0.35s">
        @if($isFailed)
        <a href="{{ route('checkout.index') }}"
           class="flex-1 bg-ember hover:bg-orange-600 text-white font-bold py-3.5 rounded-xl
                  flex items-center justify-center gap-2 transition-all active:scale-95 shadow-md text-sm">
            🔄 Coba Bayar Lagi
        </a>
        @endif
 
        <a href="{{ route('catalog.index') }}"
           class="flex-1 font-bold py-3.5 rounded-xl flex items-center justify-center gap-2
                  transition-all active:scale-95 text-sm
                  {{ $isFailed
                      ? 'border-2 border-bark/20 text-bark/60 hover:border-bark/40 hover:text-bark'
                      : 'bg-bark hover:bg-charcoal text-white shadow-md' }}">
            {{ $isSuccess ? '🍽️ Pesan Lagi' : ($isPending ? '🏠 Kembali ke Menu' : 'Kembali ke Menu') }}
        </a>
    </div>
 
</div>
</main>
 
</body>
</html>