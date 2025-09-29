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
        body{
            background-color: white;
        }
        /* Custom styles for landing page 2 */
        .yellow-bg {
            background-color: #23E288;
        }
        
        .white-bg {
            background-color: #FFFFFF;
        }
        
        .black-bg {
            background-color: #000000;
        }
        
        .text-black {
            color: #000000;
        }
        
        .text-white {
            color: #FFFFFF;
        }
        
        .dropdown {
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 8px 12px;
            min-width: 120px;
            position: relative;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .dropdown:hover {
            border-color: #9CA3AF;
        }
        
        .dropdown-arrow {
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #000000;
            margin-left: 8px;
            transition: transform 0.2s;
        }
        
        .dropdown.open .dropdown-arrow {
            transform: rotate(180deg);
        }
        
        .dropdown-menu {
            position: absolute;
            top: 100%;
            left: 0;
            right: 0;
            background-color: #FFFFFF;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            display: none;
            max-height: 200px;
            overflow-y: auto;
        }
        
        .dropdown.open .dropdown-menu {
            display: block;
        }
        
        .dropdown-item {
            padding: 8px 12px;
            cursor: pointer;
            color: #000000;
            border-bottom: 1px solid #F3F4F6;
        }
        
        .dropdown-item:last-child {
            border-bottom: none;
        }
        
        .dropdown-item:hover {
            background-color: #F9FAFB;
        }
        
        .cta-button {
            background-color: #000000;
            color: #FFFFFF;
            border: none;
            border-radius: 6px;
            padding: 12px 24px;
            font-weight: bold;
            text-transform: uppercase;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: background-color 0.2s;
        }
        
        .cta-button:hover {
            background-color: #333333;
        }
        
        .cta-arrow {
            width: 0;
            height: 0;
            border-left: 4px solid transparent;
            border-right: 4px solid transparent;
            border-top: 4px solid #FFFFFF;
        }
        
        .social-icon {
            width: 20px;
            height: 20px;
            fill: #FFFFFF;
        }
        
        .logo-circle img {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            font-size: 18px;
            color: #10B981;
        }
        
        .hamburger {
            width: 24px;
            height: 24px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            cursor: pointer;
        }
        
        .hamburger-line {
            width: 100%;
            height: 2px;
            background-color: #000000;
        }
        
        .interactive-text {
            font-size: 2.625rem;
            font-weight: bold;
            color: #000000;
            text-align: center;
            line-height: 1.4;
        }
        
        .dropdown-container {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            margin-left: 8px;
        }
        
        .interactive-text > div {
            display: flex;
            align-items: center;
            justify-content: center;
            flex-wrap: wrap;
        }
        
        @media (max-width: 768px) {
            .interactive-text {
                font-size: 2.125rem;
            }
            
            .dropdown {
                min-width: 100px;
                padding: 6px 10px;
            }
        }
    </style>
</head>

<body class="min-h-screen flex flex-col">
    <!-- Header -->
    <header class="white-bg">
        <div class="mx-auto max-w-7xl px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Logo -->
                <div class="logo-circle">
                   <img src="https://www.bonus.ca/cdn-cgi/image/format=webp,quality=85/https://media.bonus.ca/images/bf-favicon_wp-300x300.png" alt="Logo" class="w-full h-full">
                </div>
                
                <!-- Hamburger Menu -->
                <div class="hamburger">
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                    <div class="hamburger-line"></div>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex flex-1">
        <div class="flex-1 flex-col flex items-center justify-center yellow-bg mx-auto max-w-[1200px] mx-auto px-6 text-center">
            <!-- Interactive Text with Dropdowns -->
            <div class="interactive-text mb-8">
                <div class="mb-4">
                    <span>I'd like to play</span>
                    <div class="dropdown-container">
                        <div class="dropdown" onclick="toggleDropdown('game-type')">
                            <span id="game-type-text">casino games</span>
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-menu" id="game-type-menu">
                                <div class="dropdown-item" onclick="selectOption('game-type', 'casino games')">casino games</div>
                                <div class="dropdown-item" onclick="selectOption('game-type', 'slots')">slots</div>
                                <div class="dropdown-item" onclick="selectOption('game-type', 'table games')">table games</div>
                                <div class="dropdown-item" onclick="selectOption('game-type', 'live casino')">live casino</div>
                                <div class="dropdown-item" onclick="selectOption('game-type', 'sports betting')">sports betting</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <span>with</span>
                    <div class="dropdown-container">
                        <div class="dropdown" onclick="toggleDropdown('bonus-type')">
                            <span id="bonus-type-text">lots of free spins</span>
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-menu" id="bonus-type-menu">
                                <div class="dropdown-item" onclick="selectOption('bonus-type', 'lots of free spins')">lots of free spins</div>
                                <div class="dropdown-item" onclick="selectOption('bonus-type', 'welcome bonus')">welcome bonus</div>
                                <div class="dropdown-item" onclick="selectOption('bonus-type', 'no deposit bonus')">no deposit bonus</div>
                                <div class="dropdown-item" onclick="selectOption('bonus-type', 'cashback')">cashback</div>
                                <div class="dropdown-item" onclick="selectOption('bonus-type', 'reload bonus')">reload bonus</div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mb-4">
                    <span>for around</span>
                    <div class="dropdown-container">
                        <div class="dropdown" onclick="toggleDropdown('amount')">
                            <span id="amount-text">£0</span>
                            <div class="dropdown-arrow"></div>
                            <div class="dropdown-menu" id="amount-menu">
                                <div class="dropdown-item" onclick="selectOption('amount', '£0')">£0</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£10')">£10</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£25')">£25</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£50')">£50</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£100')">£100</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£250')">£250</div>
                                <div class="dropdown-item" onclick="selectOption('amount', '£500+')">£500+</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Call to Action Button -->
            <button class="cta-button" onclick="findOffers()">
                FIND MY OFFERS
                <div class="cta-arrow"></div>
            </button>
        </div>
    </main>

    <!-- Footer -->
    <footer class="black-bg">
        <div class="mx-auto max-w-7xl px-6 py-6">
            <div class="flex items-center justify-between">
                <!-- Left side - Terms & Disclaimer -->
                <div class="text-white">
                    <p class="text-sm font-medium mb-1">T&Co</p>
                    <p class="text-xs text-gray-300">
                        DISCLAIMER: Online wagering is illegal in some jurisdictions. It is your responsibility to check local laws before participating.
                    </p>
                </div>
                
                <!-- Right side - Social Media Icons -->
                <div class="flex items-center gap-4">
                    <!-- Instagram -->
                    <svg class="social-icon" viewBox="0 0 24 24">
                        <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.919-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.79-4-4 0-2.209 1.791-4 4-4s4 1.791 4 4c0 2.21-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                    </svg>
                    
                    <!-- Facebook -->
                    <svg class="social-icon" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    
                    <!-- TikTok -->
                    <svg class="social-icon" viewBox="0 0 24 24">
                        <path d="M12.525.02c1.31-.02 2.61-.01 3.91-.02.08 1.53.63 3.09 1.75 4.17 1.12 1.11 2.7 1.62 4.24 1.79v4.03c-1.44-.05-2.89-.35-4.2-.97-.57-.26-1.1-.59-1.62-.93-.01 2.92.01 5.84-.02 8.75-.08 1.4-.54 2.79-1.35 3.94-1.31 1.92-3.58 3.17-5.91 3.21-1.43.08-2.86-.31-4.08-1.03-2.02-1.19-3.44-3.37-3.65-5.71-.02-.5-.03-1-.01-1.49.18-1.9 1.12-3.72 2.58-4.96 1.66-1.44 3.98-2.13 6.15-1.72.02 1.48-.04 2.96-.04 4.44-.99-.32-2.15-.23-3.02.37-.63.41-1.11 1.04-1.36 1.75-.21.51-.15 1.07-.14 1.61.24 1.64 1.82 3.02 3.5 2.87 1.12-.01 2.19-.66 2.77-1.61.19-.33.4-.67.41-1.06.1-1.79.06-3.57.07-5.36.01-4.03-.01-8.05.02-12.07z"/>
                    </svg>
                    
                    <!-- X (Twitter) -->
                    <svg class="social-icon" viewBox="0 0 24 24">
                        <path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"/>
                    </svg>
                </div>
            </div>
        </div>
    </footer>

    <script>
        function toggleDropdown(type) {
            // Close all other dropdowns first
            const allDropdowns = document.querySelectorAll('.dropdown');
            allDropdowns.forEach(dropdown => {
                if (dropdown.id !== type + '-dropdown') {
                    dropdown.classList.remove('open');
                }
            });
            
            // Toggle the clicked dropdown
            const dropdown = document.querySelector(`[onclick="toggleDropdown('${type}')"]`);
            dropdown.classList.toggle('open');
        }

        function selectOption(type, value) {
            // Update the text
            document.getElementById(type + '-text').textContent = value;
            
            // Close the dropdown
            const dropdown = document.querySelector(`[onclick="toggleDropdown('${type}')"]`);
            dropdown.classList.remove('open');
        }

        function findOffers() {
            const gameType = document.getElementById('game-type-text').textContent;
            const bonusType = document.getElementById('bonus-type-text').textContent;
            const amount = document.getElementById('amount-text').textContent;
            
            // Create search query from selections
            const query = `${gameType} ${bonusType} ${amount}`;
            
            // Redirect to search results or show alert for now
            alert(`Searching for: ${query}`);
            
            // In a real implementation, you would redirect to search results:
            // window.location.href = `/search?q=${encodeURIComponent(query)}`;
        }

        // Close dropdowns when clicking outside
        document.addEventListener('click', function(event) {
            const isDropdownClick = event.target.closest('.dropdown');
            if (!isDropdownClick) {
                const allDropdowns = document.querySelectorAll('.dropdown');
                allDropdowns.forEach(dropdown => {
                    dropdown.classList.remove('open');
                });
            }
        });

        // Prevent dropdown menu clicks from closing the dropdown
        document.addEventListener('click', function(event) {
            if (event.target.closest('.dropdown-menu')) {
                event.stopPropagation();
            }
        });
    </script>

</body>

</html>
