// Force dark mode always
document.addEventListener('DOMContentLoaded', function() {
    // Set Flux appearance to dark in localStorage
    localStorage.setItem('flux.appearance', 'dark');

    // Also ensure the HTML element has dark class
    document.documentElement.classList.add('dark');
    document.documentElement.classList.remove('light');
});

// Override any appearance changes on Alpine init
document.addEventListener('alpine:init', () => {
    // Force dark mode in Alpine store
    Alpine.store('flux', {
        appearance: 'dark'
    });

    // Also set in localStorage
    localStorage.setItem('flux.appearance', 'dark');
});