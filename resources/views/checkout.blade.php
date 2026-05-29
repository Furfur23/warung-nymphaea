<!DOCTYPE html>
<html lang="id" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Checkout — Warung Nymphaea</title>

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
                            '0%':   { opacity: '0', transform: 'translateY(18px)' },
                            '100%': { opacity: '1', transform: 'translateY(0)' },
                        },
                    },
                    animation: {
                        'slide-up': 'slide-up 0.45s ease-out both',
                    }
                }
            }
        }
    </script>

    <style>
        body { font-family: 'Plus Jakarta Sans', sans-serif; background-color: #FDF6EC; }

        /* Subtle noise texture */
        body::before {
            content: '';
            position: fixed; inset: 0;
            background-image: url("data:image/svg+xml,%3Csvg viewBox='0 0 256 256' xmlns='http://www.w3.org/2000/svg'%3E%3Cfilter id='n'%3E%3CfeTurbulence type='fractalNoise' baseFrequency='0.9' numOctaves='4' stitchTiles='stitch'/%3E%3C/filter%3E%3Crect width='100%25' height='100%25' filter='url(%23n)' opacity='0.025'/%3E%3C/svg%3E");
            pointer-events: none; z-index: 0;
        }

        /* Form input base style */
        .form-input {
            width: 100%;
            padding: 0.75rem 1rem;
            border: 2px solid #e8ddd5;
            border-radius: 0.75rem;
            background-color: #fff;
            color: #2C2016;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 0.9rem;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }
        .form-input:focus {
            border-color: #D4622A;
            box-shadow: 0 0 0 4px rgba(212, 98, 42, 0.12);
        }
        .form-input.error-field {
            border-color: #ef4444;
            box-shadow: 0 0 0 4px rgba(239, 68, 68, 0.1);
        }
        .form-input::placeholder { color: #b8a89a; }

        /* Radio card style */
        .radio-card input[type="radio"] { display: none; }
        .radio-card label {
            display: flex; align-items: center; gap: 0.75rem;
            padding: 0.9rem 1rem;
            border: 2px solid #e8ddd5;
            border-radius: 0.875rem;
            cursor: pointer;
            transition: border-color 0.2s, background-color 0.2s, box-shadow 0.2s;
            user-select: none;
        }
        .radio-card input[type="radio"]:checked + label {
            border-color: #D4622A;
            background-color: rgba(212, 98, 42, 0.05);
            box-shadow: 0 0 0 4px rgba(212, 98, 42, 0.12);
        }
        .radio-card .radio-dot {
            width: 1.1rem; height: 1.1rem;
            border: 2px solid #c0a898;
            border-radius: 50%;
            flex-shrink: 0;
            transition: border-color 0.2s, background-color 0.2s;
            position: relative;
        }
        .radio-card input[type="radio"]:checked + label .radio-dot {
            border-color: #D4622A;
            background-color: #D4622A;
        }
        .radio-card input[type="radio"]:checked + label .radio-dot::after {
            content: '';
            position: absolute; inset: 3px;
            background: white; border-radius: 50%;
        }

        /* Progress stepper */
        .step-done  { background: #7A9E7E; color: white; }
        .step-active { background: #D4622A; color: white; box-shadow: 0 0 0 4px rgba(212,98,42,0.2); }
        .step-todo  { background: #e8ddd5; color: #b8a89a; }

        /* Scrollbar */
        ::-webkit-scrollbar { width: 5px; }
        ::-webkit-scrollbar-thumb { background: #D4622A; border-radius: 3px; }

        @keyframes toast-in {
            from { opacity:0; transform:translateY(80%) scale(0.92); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }
        .toast-enter { animation: toast-in 0.4s cubic-bezier(0.34,1.56,0.64,1) both; }
    </style>
</head>

<body class="relative text-charcoal min-h-screen pb-28 lg:pb-0">

{{-- ═══════════════════════════════════════
     NAVBAR
═══════════════════════════════════════ --}}
<header class="sticky top-0 z-50 bg-bark shadow-md">
    <div class="max-w-5xl mx-auto px-4 h-16 flex items-center justify-between">
        <a href="{{ route('cart.index') }}" class="flex items-center gap-2 text-cream/70 hover:text-cream transition-colors">
            <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M15 19l-7-7 7-7"/>
            </svg>
            <span class="text-sm font-semibold">Kembali</span>
        </a>
        <span class="font-display text-cream text-xl">Warung<span class="text-saffron italic">Nymphaea</span></span>
        <div class="w-20"></div>
    </div>
</header>


{{-- ═══════════════════════════════════════
     PROGRESS STEPPER
═══════════════════════════════════════ --}}
<div class="bg-gradient-to-r from-bark to-charcoal py-5 px-4">
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-center gap-0">

            {{-- Step 1: Keranjang (done) --}}
            <div class="flex items-center gap-2">
                <div class="step-done w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">
                    <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="3">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"/>
                    </svg>
                </div>
                <span class="text-cream/60 text-xs hidden sm:inline font-medium">Keranjang</span>
            </div>

            <div class="w-10 sm:w-16 h-0.5 bg-sage/40 mx-2"></div>

            {{-- Step 2: Data Pengiriman (active) --}}
            <div class="flex items-center gap-2">
                <div class="step-active w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">2</div>
                <span class="text-cream text-xs hidden sm:inline font-semibold">Data Pengiriman</span>
            </div>

            <div class="w-10 sm:w-16 h-0.5 bg-cream/15 mx-2"></div>

            {{-- Step 3: Pembayaran (todo) --}}
            <div class="flex items-center gap-2">
                <div class="step-todo w-8 h-8 rounded-full flex items-center justify-center text-sm font-bold flex-shrink-0">3</div>
                <span class="text-cream/40 text-xs hidden sm:inline font-medium">Pembayaran</span>
            </div>
        </div>
    </div>
</div>


{{-- ═══════════════════════════════════════
     FLASH ERROR
═══════════════════════════════════════ --}}
@if(session('error'))
<div id="flash-toast"
     class="toast-enter fixed bottom-6 left-1/2 -translate-x-1/2 z-50 flex items-center gap-3 px-5 py-3.5 rounded-2xl shadow-2xl bg-red-500 text-white text-sm font-semibold max-w-sm w-[calc(100%-2rem)]">
    <span class="text-lg flex-shrink-0">❌</span>
    <span class="flex-1">{{ session('error') }}</span>
    <button onclick="this.closest('#flash-toast').remove()" class="text-white/70 hover:text-white ml-1 flex-shrink-0">✕</button>
</div>
<script>setTimeout(() => document.getElementById('flash-toast')?.remove(), 6000);</script>
@endif


{{-- ═══════════════════════════════════════
     MAIN LAYOUT
═══════════════════════════════════════ --}}
<main class="max-w-5xl mx-auto px-4 py-8 relative z-10">
    <div class="lg:grid lg:grid-cols-5 lg:gap-10 items-start">


        {{-- ═══════════════════════════════
             FORM CHECKOUT (col-span-3)
        ═══════════════════════════════ --}}
        <div class="lg:col-span-3 animate-slide-up">

            <h1 class="font-display text-3xl text-bark mb-1">Data <span class="italic text-ember">Pengiriman</span></h1>
            <p class="text-bark/50 text-sm mb-7">Isi data dengan benar agar pesanan sampai tepat sasaran.</p>

            <form action="{{ route('checkout.process') }}" method="POST" id="checkout-form" novalidate>
                @csrf

                {{-- ─── Section 1: Identitas ─── --}}
                <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-5 mb-4 space-y-4">
                    <h2 class="font-bold text-bark text-sm uppercase tracking-wider opacity-50">Identitas Pemesan</h2>

                    {{-- Nama --}}
                    <div>
                        <label for="customer_name" class="block text-bark font-semibold text-sm mb-1.5">
                            Nama Lengkap <span class="text-ember">*</span>
                        </label>
                        <input type="text"
                               id="customer_name"
                               name="customer_name"
                               value="{{ old('customer_name') }}"
                               placeholder="Contoh: Budi Santoso"
                               autocomplete="name"
                               class="form-input {{ $errors->has('customer_name') ? 'error-field' : '' }}">
                        @error('customer_name')
                            <p class="text-red-500 text-xs mt-1.5 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>

                    {{-- Nomor WhatsApp --}}
                    <div>
                        <label for="customer_phone" class="block text-bark font-semibold text-sm mb-1.5">
                            Nomor WhatsApp <span class="text-ember">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-3.5 pointer-events-none">
                                <span class="text-bark/40 text-sm font-semibold">🇮🇩</span>
                            </div>
                            <input type="tel"
                                   id="customer_phone"
                                   name="customer_phone"
                                   value="{{ old('customer_phone') }}"
                                   placeholder="08123456789"
                                   autocomplete="tel"
                                   class="form-input pl-10 {{ $errors->has('customer_phone') ? 'error-field' : '' }}">
                        </div>
                        <p class="text-bark/40 text-xs mt-1">Notifikasi WA akan dikirim ke nomor ini.</p>
                        @error('customer_phone')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ─── Section 2: Jenis Pesanan ─── --}}
                <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-5 mb-4 space-y-3">
                    <h2 class="font-bold text-bark text-sm uppercase tracking-wider opacity-50">Jenis Pesanan</h2>

                    <div class="grid grid-cols-2 gap-3">
                        {{-- Delivery --}}
                        <div class="radio-card">
                            <input type="radio"
                                   id="type_delivery"
                                   name="order_type"
                                   value="delivery"
                                   {{ old('order_type', 'delivery') === 'delivery' ? 'checked' : '' }}
                                   onchange="toggleAddressField(this.value)">
                            <label for="type_delivery">
                                <span class="radio-dot"></span>
                                <div>
                                    <p class="font-semibold text-bark text-sm">🛵 Delivery</p>
                                    <p class="text-bark/45 text-xs">Diantar ke alamat</p>
                                </div>
                            </label>
                        </div>

                        {{-- Takeaway --}}
                        <div class="radio-card">
                            <input type="radio"
                                   id="type_takeaway"
                                   name="order_type"
                                   value="takeaway"
                                   {{ old('order_type') === 'takeaway' ? 'checked' : '' }}
                                   onchange="toggleAddressField(this.value)">
                            <label for="type_takeaway">
                                <span class="radio-dot"></span>
                                <div>
                                    <p class="font-semibold text-bark text-sm">🥡 Takeaway</p>
                                    <p class="text-bark/45 text-xs">Ambil sendiri</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    @error('order_type')
                        <p class="text-red-500 text-xs flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                            </svg>
                            {{ $message }}
                        </p>
                    @enderror

                    {{-- Alamat (conditional) --}}
                    <div id="address-field"
                         class="{{ old('order_type', 'delivery') === 'takeaway' ? 'hidden' : '' }} transition-all">
                        <label for="shipping_address" class="block text-bark font-semibold text-sm mb-1.5">
                            Alamat Lengkap <span class="text-ember">*</span>
                        </label>
                        <textarea id="shipping_address"
                                  name="shipping_address"
                                  rows="3"
                                  placeholder="Nama jalan, nomor rumah, RT/RW, kelurahan, kecamatan, kota..."
                                  class="form-input resize-none {{ $errors->has('shipping_address') ? 'error-field' : '' }}">{{ old('shipping_address') }}</textarea>
                        @error('shipping_address')
                            <p class="text-red-500 text-xs mt-1 flex items-center gap-1">
                                <svg class="w-3.5 h-3.5 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                                </svg>
                                {{ $message }}
                            </p>
                        @enderror
                    </div>
                </div>

                {{-- ─── Section 3: Catatan Pesanan ─── --}}
                <div class="bg-white rounded-2xl border border-bark/8 shadow-sm p-5 mb-6">
                    <label for="order_notes" class="block text-bark font-semibold text-sm mb-1.5">
                        Catatan Pesanan <span class="text-bark/35 font-normal">(opsional)</span>
                    </label>
                    <textarea id="order_notes"
                              name="order_notes"
                              rows="2"
                              placeholder="Contoh: tidak pedas, tanpa bawang, dll."
                              class="form-input resize-none">{{ old('order_notes') }}</textarea>
                </div>

                {{-- Submit Button (desktop only; mobile has sticky bar) --}}
                <button type="submit"
                        id="submit-btn"
                        class="hidden lg:flex w-full items-center justify-center gap-2 bg-ember hover:bg-orange-600 active:scale-[0.98] text-cream font-bold py-4 rounded-2xl shadow-lg hover:shadow-xl transition-all text-base">
                    <svg class="w-5 h-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                    </svg>
                    <span id="submit-text">Lanjut ke Pembayaran</span>
                </button>

            </form>
        </div>


        {{-- ═══════════════════════════════
             ORDER SUMMARY (col-span-2)
        ═══════════════════════════════ --}}
        <div class="hidden lg:block lg:col-span-2 animate-slide-up" style="animation-delay:0.15s">
            <div class="sticky top-24">

                <div class="bg-white rounded-2xl border border-bark/10 shadow-sm overflow-hidden">
                    <div class="bg-gradient-to-r from-bark to-charcoal text-cream px-5 py-4">
                        <h2 class="font-display text-lg">Ringkasan <span class="italic text-saffron">Pesanan</span></h2>
                        <p class="text-cream/50 text-xs mt-0.5">{{ collect($cart)->sum('quantity') }} item</p>
                    </div>

                    <div class="p-5 divide-y divide-bark/6 max-h-[340px] overflow-y-auto">
                        @foreach($cart as $item)
                        <div class="flex items-start gap-3 py-3 first:pt-0 last:pb-0">
                            <div class="w-10 h-10 rounded-lg bg-gradient-to-br from-saffron/20 to-ember/20 flex-shrink-0 overflow-hidden">
                                @if($item['image'])
                                    <img src="{{ asset('storage/' . $item['image']) }}" alt="{{ $item['name'] }}" class="w-full h-full object-cover">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <span class="font-display text-ember/40 text-lg">{{ mb_substr($item['name'], 0, 1) }}</span>
                                    </div>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-bark text-sm leading-snug line-clamp-1">{{ $item['name'] }}</p>
                                @if(!empty($item['notes']))
                                    <p class="text-bark/40 text-xs italic line-clamp-1">"{{ $item['notes'] }}"</p>
                                @endif
                                <p class="text-bark/50 text-xs mt-0.5">
                                    Rp {{ number_format($item['price'], 0, ',', '.') }} × {{ $item['quantity'] }}
                                </p>
                            </div>
                            <p class="font-bold text-bark text-sm flex-shrink-0">
                                Rp {{ number_format($item['price'] * $item['quantity'], 0, ',', '.') }}
                            </p>
                        </div>
                        @endforeach
                    </div>

                    <div class="border-t-2 border-dashed border-bark/10 mx-5"></div>
                    <div class="p-5 pt-4">
                        <div class="flex justify-between items-center mb-1">
                            <span class="text-bark/60 text-sm">Subtotal</span>
                            <span class="font-semibold text-bark text-sm">Rp {{ number_format($total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center mb-4">
                            <span class="text-bark/60 text-sm">Ongkos Kirim</span>
                            <span class="text-sage font-semibold text-sm">Gratis 🎉</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-bark text-base">Total Bayar</span>
                            <span class="font-display font-bold text-ember text-2xl">
                                Rp {{ number_format($total, 0, ',', '.') }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Security badges --}}
                <div class="mt-4 flex items-center justify-center gap-4 text-bark/35 text-xs">
                    <span class="flex items-center gap-1">🔒 SSL Terenkripsi</span>
                    <span>·</span>
                    <span class="flex items-center gap-1">✅ Powered by Midtrans</span>
                </div>
            </div>
        </div>

    </div>
</main>


{{-- ═══════════════════════════════════════
     STICKY BOTTOM BAR (mobile)
═══════════════════════════════════════ --}}
<div class="fixed bottom-0 left-0 right-0 z-50 lg:hidden bg-white border-t border-bark/10 shadow-2xl px-4 py-3">
    <div class="flex items-center justify-between gap-3">
        <div class="flex-shrink-0">
            <p class="text-[10px] text-bark/40 uppercase tracking-wider font-medium">Total</p>
            <p class="font-display font-bold text-ember text-lg leading-tight">
                Rp {{ number_format($total, 0, ',', '.') }}
            </p>
        </div>
        <button type="submit"
                form="checkout-form"
                class="flex-1 bg-ember hover:bg-orange-600 text-cream font-bold py-3.5 rounded-xl flex items-center justify-center gap-2 transition-all active:scale-95 shadow-lg text-sm">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M17 8l4 4m0 0l-4 4m4-4H3"/>
            </svg>
            Lanjut ke Pembayaran
        </button>
    </div>
</div>


{{-- ═══════════════════════════════════════
     JAVASCRIPT
═══════════════════════════════════════ --}}
<script>
/**
 * Tampilkan/sembunyikan field alamat berdasarkan pilihan order_type.
 */
function toggleAddressField(value) {
    const field = document.getElementById('address-field');
    const input = document.getElementById('shipping_address');
    if (value === 'delivery') {
        field.classList.remove('hidden');
        field.classList.add('block');
        input.required = true;
    } else {
        field.classList.add('hidden');
        field.classList.remove('block');
        input.required = false;
        input.value = '';
    }
}

// Inisialisasi state berdasarkan nilai yang sudah dipilih (old input)
document.addEventListener('DOMContentLoaded', () => {
    const checkedType = document.querySelector('input[name="order_type"]:checked');
    if (checkedType) toggleAddressField(checkedType.value);
});

/**
 * Loading state saat form disubmit agar tidak double-submit.
 */
document.getElementById('checkout-form').addEventListener('submit', function(e) {
    const btn  = document.getElementById('submit-btn');
    const text = document.getElementById('submit-text');
    if (btn) {
        btn.disabled = true;
        btn.classList.add('opacity-70', 'cursor-not-allowed');
        text.textContent = 'Memproses...';
    }
    // Ubah juga tombol mobile
    const mobileBtn = document.querySelector('button[form="checkout-form"]');
    if (mobileBtn) {
        mobileBtn.disabled = true;
        mobileBtn.innerHTML = '<svg class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"></path></svg> Memproses...';
    }
});
</script>

</body>
</html>
