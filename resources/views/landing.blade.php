<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>BonusFinder+ai — Find the best casino bonuses</title>
    <meta name="description" content="BonusFinder+ai helps you discover the best casino bonuses instantly with an AI-powered search experience." />

    <!-- Font -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet" />

    <link rel="icon" href="/favicon.ico" sizes="any">
<link rel="icon" href="/favicon.svg" type="image/svg+xml">
<link rel="apple-touch-icon" href="/apple-touch-icon.png">

<link rel="preconnect" href="https://fonts.bunny.net">
<link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])
@fluxAppearance

  

    <style>
        /* Flat design - removed glass noise texture */
        /* Friendly waving hand animation */
        
        @keyframes wave-hand {
            0% {
                transform: rotate(0deg);
            }
            10% {
                transform: rotate(14deg);
            }
            20% {
                transform: rotate(-8deg);
            }
            30% {
                transform: rotate(14deg);
            }
            40% {
                transform: rotate(-4deg);
            }
            50% {
                transform: rotate(10deg);
            }
            60% {
                transform: rotate(0deg);
            }
            100% {
                transform: rotate(0deg);
            }
        }
        
        .wave-hand {
            display: inline-block;
            transform-origin: 70% 70%;
            animation: wave-hand 2s ease-in-out infinite;
        }
        
        @media (prefers-reduced-motion: reduce) {
            .wave-hand {
                animation: none;
            }
        }
    </style>
</head>

<body class="min-h-screen bg-slate-950 text-white antialiased font-sans selection:bg-brandBlue/30 selection:text-white">
    <!-- Background aesthetic: gradient + blurred blobs -->
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute -top-32 -left-40 h-[46rem] w-[46rem] rounded-full bg-brandBlue/40 blur-3xl"></div>
        <div class="absolute -bottom-40 -right-40 h-[46rem] w-[46rem] rounded-full bg-brandGreen/40 blur-3xl"></div>
        <div class="pointer-events-none absolute inset-0 bg-[radial-gradient(50%_50%_at_50%_0%,rgba(255,255,255,0.10)_0%,rgba(16,23,42,0.0)_60%)]"></div>
    </div>

    <!-- Header -->
    <header class="relative z-10">
        <div class="mx-auto max-w-7xl px-6 py-6">
            <div class="flex items-center justify-between">
                <a href="#" class="group inline-flex items-center gap-3">
                    <span class="grid h-9 w-9 place-items-center rounded-2xl bg-slate-800 border border-slate-700 shadow-flat">
                        <img src="https://www.bonus.ca/cdn-cgi/image/format=webp,quality=85/https://media.bonus.ca/images/bf-favicon_wp-300x300.png" alt="" class="border-2 border-none rounded-md">

                    </span>
                    <span class="text-lg font-semibold tracking-tight">
              <span class="bg-gradient-to-r from-brandBlue via-white to-brandGreen bg-clip-text text-transparent">BonusFinder+ai</span>
                    </span>
                </a>
                <div class="hidden sm:flex items-center gap-3">
                    <button class="p-2 rounded-lg border border-slate-700 bg-slate-800 hover:bg-slate-700 transition-colors" aria-label="Open main menu">
                        <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Hero -->
    <main class="relative z-10">
        <section class="mx-auto max-w-7xl px-6 pt-8 pb-24 sm:pt-16">
            <div class="mx-auto max-w-3xl text-center">
                <div class="mb-3 sm:mb-4 flex justify-center">
                    <span class="wave-hand text-4xl sm:text-5xl" aria-hidden="true">👋</span>
                    <span class="sr-only">Hello</span>
                </div>
                <h1 class="text-4xl sm:text-6xl font-extrabold tracking-tight leading-tight">
                    <span class="block bg-gradient-to-r from-brandBlue via-white to-brandGreen bg-clip-text text-transparent">I find you bonuses... <br>for real!</span>
                </h1>
                <p class="mt-6 text-base sm:text-lg text-white/70">
                    Chat your way to the best bonuses and offers. <br>Compare casinos, get information or just chat about life.
                </p>
            </div>

            <!-- Glass search card -->
            <div class="mx-auto mt-6 sm:mt-10 max-w-3xl px-4 sm:px-0">
                @if ($errors->any())
                    <div class="mb-4 rounded-2xl border border-red-500/50 bg-red-500/10 p-3 sm:p-4 text-center">
                        <p class="text-sm text-red-400">{{ $errors->first() }}</p>
                    </div>
                @endif
                
                <div class="relative rounded-2xl sm:rounded-3xl border border-slate-700 bg-slate-800/90 p-1.5 sm:p-2 shadow-flat">
                    <form id="bonus-form" class="relative" action="{{ route('landing.search') }}" method="POST">
                        @csrf
                        <label for="query" class="sr-only">Your bonus preferences</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-3 sm:left-4 top-1/2 -translate-y-1/2 text-white/60">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-5 w-5 sm:h-6 sm:w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.4 5.4a7.5 7.5 0 0011.25 11.25z" />
                  </svg>
                </span>
                            <input id="query" name="q" type="text" inputmode="text" autocomplete="off" placeholder="e.g. high RTP slots, no wagering, EU, fast payout" class="h-12 sm:h-16 w-full rounded-xl sm:rounded-2xl border border-slate-600 bg-slate-800/80 pl-11 sm:pl-14 pr-20 sm:pr-36 text-sm sm:text-base text-white placeholder-white/50 outline-none focus:border-brandBlue focus:ring-2 focus:ring-brandBlue/40 disabled:opacity-50 disabled:cursor-not-allowed"/>

                            <!-- Loading indicator overlay for input -->
                            <div id="input-loading" class="hidden absolute left-11 sm:left-14 top-1/2 -translate-y-1/2 flex items-center gap-2 text-white/70">
                                <div class="parsing-dot"></div>
                                <div class="parsing-dot"></div>
                                <div class="parsing-dot"></div>
                                <span class="text-sm">Finding bonuses...</span>
                            </div>

                            <button type="submit" id="submit-btn" class="absolute right-1 sm:right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-1 sm:gap-2 rounded-lg sm:rounded-xl bg-gradient-to-r from-brandBlue to-brandGreen px-3 sm:px-5 py-2 sm:py-3 font-semibold text-slate-900 shadow-lg shadow-brandBlue/20 transition active:scale-[0.99] disabled:opacity-50 disabled:cursor-not-allowed text-xs sm:text-sm">
                  <span id="btn-text">Find</span>
                  <span id="btn-shortcut" class="hidden sm:inline-flex rounded-md bg-black/10 px-2 py-0.5 text-xs text-slate-900">↵ Enter</span>
                </button>
                        </div>
                    </form>

                    <!-- Quick suggestions -->
                    <div class="mt-2 sm:mt-3 flex flex-wrap items-center gap-1.5 sm:gap-2 px-1 sm:px-2 pb-1">
                        <span class="text-xs text-white/60">Try:</span>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-2 sm:px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="No deposit, EU, new players">No deposit</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-2 sm:px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="High roller, VIP, 1000€+ bonus">High roller</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-2 sm:px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="No wagering, fast payout, trusted">No wagering</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-2 sm:px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="Crypto casinos, BTC deposit bonus">Crypto</button>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 ">
        <div class="mx-auto max-w-4xl px-6 py-12 border-t border-slate-700">
            <div class="flex flex-col items-center justify-between gap-4 sm:flex-row">
                <p class="text-xs text-white/50">© <span id="year"></span> BonusFinder+ai. All rights reserved.</p>
                <div class="flex items-center gap-3 text-xs text-white/60">
                    <a href="#" class="hover:text-white/80">Privacy</a>
                    <span aria-hidden>•</span>
                    <a href="#" class="hover:text-white/80">Terms</a>
                </div>
            </div>
        </div>
    </footer>

    <script>
        // Handle form submission with loading state
        document.getElementById('bonus-form').addEventListener('submit', function(e) {
            const query = document.getElementById('query').value.trim();
            const submitBtn = document.getElementById('submit-btn');
            const btnText = document.getElementById('btn-text');
            const btnShortcut = document.getElementById('btn-shortcut');
            const inputLoading = document.getElementById('input-loading');
            const queryInput = document.getElementById('query');
            
            if (query) {
                // Show loading state
                submitBtn.disabled = true;
                btnText.textContent = 'Find';
                btnShortcut.classList.add('hidden');
                
                // Hide input text and show loading indicator
                queryInput.style.color = 'transparent';
                queryInput.readOnly = true;
                inputLoading.classList.remove('hidden');
                
                // Allow form to submit normally - don't prevent default
            } else {
                e.preventDefault();
                alert('Please enter a search query');
            }
        });

        // Handle quick suggestion buttons
        document.querySelectorAll('[data-suggest]').forEach(button => {
            button.addEventListener('click', function() {
                const suggestion = this.getAttribute('data-suggest');
                document.getElementById('query').value = suggestion;
                document.getElementById('query').focus();
            });
        });

        // Set current year in footer
        document.getElementById('year').textContent = new Date().getFullYear();
    </script>

</body>

</html>