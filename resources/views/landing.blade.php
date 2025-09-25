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

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brandBlue: '#244EC3',
                        brandGreen: '#28E287',
                        ctaGreen: '#27E286',
                        ctaDark: '#1B1D28',
                    },
                    fontFamily: {
                        sans: ['Inter', 'ui-sans-serif', 'system-ui', 'sans-serif'],
                    },
                    boxShadow: {
                        flat: '0 4px 12px -2px rgba(0,0,0,0.3)'
                    }
                }
            }
        }
    </script>

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
              <svg width="18" height="18" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg" class="text-white/90">
                <path d="M4 12.5C4 8.91 6.91 6 10.5 6H13.5C17.09 6 20 8.91 20 12.5C20 16.09 17.09 19 13.5 19H10.5C6.91 19 4 16.09 4 12.5Z" fill="currentColor"/>
                <path d="M8 12.5C8 10.57 9.57 9 11.5 9C13.43 9 15 10.57 15 12.5C15 14.43 13.43 16 11.5 16C9.57 16 8 14.43 8 12.5Z" fill="#28E287"/>
              </svg>
            </span>
                    <span class="text-lg font-semibold tracking-tight">
              <span class="bg-gradient-to-r from-brandBlue via-white to-brandGreen bg-clip-text text-transparent">BonusFinder+ai</span>
                    </span>
                </a>
                <div class="hidden sm:flex items-center gap-3">
                    <span class="rounded-full border border-slate-700 bg-slate-800 px-3 py-1 text-xs text-white/70">Early Access</span>
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
            <div class="mx-auto mt-10 max-w-3xl">
                <div class="relative rounded-3xl border border-slate-700 bg-slate-800/90 p-2 shadow-flat">
                    <form id="bonus-form" class="relative" action="/dashboard" method="GET">
                        <label for="query" class="sr-only">Your bonus preferences</label>
                        <div class="relative">
                            <span class="pointer-events-none absolute left-4 top-1/2 -translate-y-1/2 text-white/60">
                  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" class="h-6 w-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-4.35-4.35m0 0A7.5 7.5 0 105.4 5.4a7.5 7.5 0 0011.25 11.25z" />
                  </svg>
                </span>
                            <input id="query" name="q" type="text" inputmode="text" autocomplete="off" placeholder="e.g. high RTP slots, no wagering, EU, fast payout" class="h-16 w-full rounded-2xl border border-slate-600 bg-slate-800/80 pl-14 pr-36 text-base text-white placeholder-white/50 outline-none focus:border-brandBlue focus:ring-2 focus:ring-brandBlue/40"/>

                            <button type="submit" class="absolute right-2 top-1/2 -translate-y-1/2 inline-flex items-center gap-2 rounded-xl bg-gradient-to-r from-brandBlue to-brandGreen px-5 py-3 font-semibold text-slate-900 shadow-lg shadow-brandBlue/20 transition active:scale-[0.99]">
                  <span>Find bonuses</span>
                  <span class="hidden sm:inline-flex rounded-md bg-black/10 px-2 py-0.5 text-xs text-slate-900">↵ Enter</span>
                </button>
                        </div>
                    </form>

                    <!-- Quick suggestions -->
                    <div class="mt-3 flex flex-wrap items-center gap-2 px-2 pb-1">
                        <span class="text-xs text-white/60">Try:</span>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="No deposit, EU, new players">No deposit</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="High roller, VIP, 1000€+ bonus">High roller</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="No wagering, fast payout, trusted">No wagering</button>
                        <button class="rounded-full border border-slate-600 bg-slate-800 px-3 py-1 text-xs text-white/80 hover:bg-slate-700" data-suggest="Crypto casinos, BTC deposit bonus">Crypto</button>
                    </div>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer -->
    <footer class="relative z-10 border-t border-slate-700">
        <div class="mx-auto max-w-7xl px-6 py-12">
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
        // Handle form submission
        document.getElementById('bonus-form').addEventListener('submit', function(e) {
            const query = document.getElementById('query').value.trim();
            if (query) {
                // The form will naturally redirect to /dashboard with the query parameter
                // No need to prevent default behavior
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