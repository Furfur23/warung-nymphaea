<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran — {{ $order->order_code }}</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;1,400&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{--
        Midtrans Snap.js — muat dari CDN Midtrans sesuai environment.
        data-client-key wajib ada untuk inisialisasi Snap.
    --}}
    <script src="{{ $snapJsUrl }}"
            data-client-key="{{ $clientKey }}"></script>

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
                        'slide-up':  { '0%': { opacity: '0', transform: 'translateY(20px)' }, '100%': { opacity: '1', transform: 'translateY(0)' } },
                        'pulse-dot': { '0%, 100%': { opacity: '1' }, '50%': { opacity: '0.3' } },
                        'spin-slow': { '0%': { transform: 'rotate(0deg)' }, '100%': { transform: 'rotate(360deg)' } },
                    },
                    animation: {
                        'slide-up':  'slide-up 0.5s ease-out both',
                        'pulse-dot': 'pulse-dot 1.4s ease-in-out infinite',
                        'spin-slow': 'spin-slow 2s linear infinite',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }

        /* Noise texture */
        body::before {
            content: ''; position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        /* Animated waiting dots */
        .dot-1 { animation: pulse-dot 1.4s ease-in-out 0s infinite; }
        .dot-2 { animation: pulse-dot 1.4s ease-in-out 0.2s infinite; }
        .dot-3 { animation: pulse-dot 1.4s ease-in-out 0.4s infinite; }

        /* Progress step */
        .step-done   { background: #7A9E7E; color: white; }
        .step-active { background: #D4622A; color: white; box-shadow: 0 0 0 4px rgba(212,98,42,0.2); }
        .step-todo   { background: #e8ddd5; color: #b8a89a; }
    </style>
</head>

<body class="relative text-charcoal min-h-screen">

{{-- NAVBAR --}}
<header class="sticky top-0 z-50 bg-bark shadow-md">
    <div class="max-w-3xl mx-auto px-4 h-16 flex items-center justify-center">
        <span class="font-display text-cream text-xl">Warung<span class="text-saffron italic">Nymphaea</span></span>
    </div>
</header>

{{-- PROGRESS STEPPER --}}
<div class="bg-gradient-to-r from-bark to-charcoal py-5 px-4">
    <div class="max-w-3xl mx-auto">
        <div class="flex items-center justify-center gap-0">
            <div class="flex items-center gap-2">
                <div class="step-done w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-cream/60 text-xs hidden sm:inline font-medium">Keranjang</span>
            </div>
            <div class="w-10 sm:w-16 h-0.5 bg-sage/40 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="step-done w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3"><path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/></svg>
                </div>
                <span class="text-cream/60 text-xs hidden sm:inline font-medium">Data Pengiriman</span>
            </div>
            <div class="w-10 sm:w-16 h-0.5 bg-sage/40 mx-2"></div>
            <div class="flex items-center gap-2">
                <div class="step-active w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                <span class="text-cream text-xs hidden sm:inline font-semibold">Pembayaran</span>
            </div>
        </div>
    </div>
</div>


{{-- MAIN CONTENT --}}
<main class="max-w-3xl mx-auto px-4 py-10 relative z-10">
    <div class="max-w-md mx-auto">

        {{-- Order info card --}}
        <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-6 mb-5 animate-slide-up">

            {{-- Waiting indicator --}}
            <div id="waiting-state" class="text-center mb-6">
                <div class="relative w-16 h-16 mx-auto mb-4">
                    <div class="absolute inset-0 rounded-full border-4 border-ember/20 animate-spin-slow"></div>
                    <div class="absolute inset-2 rounded-full bg-ember/10 flex items-center justify-center">
                        <svg class="w-7 h-7 text-ember" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 002.25-2.25V6.75A2.25 2.25 0 0019.5 4.5h-15a2.25 2.25 0 00-2.25 2.25v10.5A2.25 2.25 0 004.5 19.5z"/>
                        </svg>
                    </div>
                </div>
                <h1 class="font-display text-2xl text-bark mb-1">
                    Siap <span class="italic text-ember">Membayar?</span>
                </h1>
                <p class="text-bark/50 text-sm">
                    Halaman pembayaran Midtrans akan muncul sebentar lagi
                    <span class="inline-flex gap-0.5 ml-1">
                        <span class="dot-1 text-ember font-bold">.</span>
                        <span class="dot-2 text-ember font-bold">.</span>
                        <span class="dot-3 text-ember font-bold">.</span>
                    </span>
                </p>
            </div>

            {{-- Divider --}}
            <div class="border-t border-dashed border-bark/10 my-5"></div>

            {{-- Order details --}}
            <div class="space-y-2.5">
                <div class="flex justify-between text-sm">
                    <span class="text-bark/50 font-medium">Kode Pesanan</span>
                    <span class="font-bold text-bark font-mono tracking-wide">{{ $order->order_code }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-bark/50 font-medium">Nama</span>
                    <span class="font-semibold text-bark">{{ $order->customer_name }}</span>
                </div>
                <div class="flex justify-between text-sm">
                    <span class="text-bark/50 font-medium">Jenis</span>
                    <span class="font-semibold text-bark capitalize">
                        {{ $order->order_type === 'delivery' ? '🛵 Delivery' : '🥡 Takeaway' }}
                    </span>
                </div>
                <div class="border-t border-bark/8 pt-2.5 flex justify-between items-center">
                    <span class="font-bold text-bark">Total Bayar</span>
                    <span class="font-display font-bold text-ember text-2xl">
                        Rp {{ number_format($order->total_price, 0, ',', '.') }}
                    </span>
                </div>
            </div>

            {{-- Divider --}}
            <div class="border-t border-dashed border-bark/10 my-5"></div>

            {{-- Pay button (opens Snap popup) --}}
            <button id="pay-button"
                    onclick="openSnapPayment()"
                    class="w-full bg-ember hover:bg-orange-600 active:scale-[0.98] text-cream font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all text-base flex items-center justify-center gap-2 mb-3">
                <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                </svg>
                Bayar Sekarang
            </button>

            {{-- Fallback: redirect URL if popup blocked --}}
            <a id="redirect-fallback"
               href="{{ $redirectUrl }}"
               class="hidden w-full border-2 border-bark/20 text-bark/60 hover:border-bark/40 hover:text-bark font-semibold py-3 rounded-xl text-center text-sm transition-all block">
                Jika popup tidak muncul, klik di sini →
            </a>

            <p class="text-center text-bark/35 text-xs mt-3">
                🔒 Pembayaran aman melalui Midtrans. Data kamu terenkripsi SSL.
            </p>
        </div>

        {{-- Payment methods supported --}}
        <div class="bg-white/60 backdrop-blur rounded-2xl border border-bark/8 p-4 animate-slide-up" style="animation-delay:0.1s">
            <p class="text-bark/40 text-xs text-center font-semibold uppercase tracking-wider mb-3">Metode Pembayaran Tersedia</p>
            <div class="flex justify-center flex-wrap gap-2 text-sm">
                @foreach(['QRIS', 'GoPay', 'ShopeePay', 'Transfer Bank', 'OVO'] as $method)
                <span class="bg-cream border border-bark/10 text-bark/60 font-semibold px-3 py-1.5 rounded-full text-xs">
                    {{ $method }}
                </span>
                @endforeach
            </div>
        </div>

        {{-- Important note --}}
        <div class="mt-4 bg-saffron/10 border border-saffron/30 rounded-xl p-4 animate-slide-up" style="animation-delay:0.2s">
            <div class="flex gap-3">
                <span class="text-xl flex-shrink-0">⚠️</span>
                <div>
                    <p class="text-bark font-semibold text-sm">Jangan tutup halaman ini</p>
                    <p class="text-bark/60 text-xs mt-0.5 leading-relaxed">
                        Tetap di halaman ini sampai pembayaran selesai. Notifikasi WhatsApp akan dikirim otomatis setelah pembayaran berhasil dikonfirmasi.
                    </p>
                </div>
            </div>
        </div>

    </div>
</main>


{{-- ═══════════════════════════════════════
     JAVASCRIPT — Midtrans Snap Integration
═══════════════════════════════════════ --}}
<script>
/**
 * Membuka Midtrans Snap Pop-up.
 *
 * Callback:
 *  onSuccess  → redirect ke /checkout/finish?transaction_status=settlement&...
 *  onPending  → redirect ke /checkout/finish?transaction_status=pending&...
 *  onError    → tampilkan alert dan aktifkan link fallback
 *  onClose    → tampilkan pesan jika user menutup popup sebelum selesai
 */
function openSnapPayment() {
    const snapToken = @json($snapToken);
    const finishUrl = @json(route('checkout.finish'));

    // Tampilkan fallback link setelah 8 detik jika popup tidak muncul
    setTimeout(() => {
        document.getElementById('redirect-fallback')?.classList.remove('hidden');
    }, 8000);

    window.snap.pay(snapToken, {

        onSuccess: function(result) {
            // Pembayaran berhasil — arahkan ke halaman finish
            const params = new URLSearchParams({
                order_id:           result.order_id,
                status_code:        result.status_code,
                transaction_status: result.transaction_status,
            });
            window.location.href = finishUrl + '?' + params.toString();
        },

        onPending: function(result) {
            // Pembayaran pending (misal: transfer bank belum dikonfirmasi)
            const params = new URLSearchParams({
                order_id:           result.order_id,
                status_code:        result.status_code,
                transaction_status: result.transaction_status,
            });
            window.location.href = finishUrl + '?' + params.toString();
        },

        onError: function(result) {
            // Error dari Midtrans (kartu ditolak, dll.)
            console.error('[Midtrans] Payment error', result);

            const btn = document.getElementById('pay-button');
            if (btn) {
                btn.textContent = '⚠️ Gagal — Coba Lagi';
                btn.classList.remove('bg-ember', 'hover:bg-orange-600');
                btn.classList.add('bg-red-500', 'hover:bg-red-600');
            }

            // Aktifkan fallback link
            document.getElementById('redirect-fallback')?.classList.remove('hidden');

            alert('Pembayaran gagal: ' + (result.status_message || 'Silakan coba lagi.'));
        },

        onClose: function() {
            // User menutup popup tanpa selesai bayar
            const btn = document.getElementById('pay-button');
            if (btn) {
                btn.innerHTML = `
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                    Bayar Lagi
                `;
                btn.disabled = false;
            }
        }
    });
}

// Auto-open Snap popup setelah 800ms (beri waktu halaman render penuh)
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(openSnapPayment, 800);
});
</script>

</body>
</html>
