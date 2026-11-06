<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <link rel="icon" href="/assets/images/logos/paayu_fav.png" type="image/png">
    @stack('before-styles')
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700;800;900&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="/main.css" rel="stylesheet" />
    @stack('after-styles')
</head>

<body>
    @yield('content')

    @stack('before-scripts')
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js" integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO" crossorigin="anonymous"></script>

    <script>
        // Night-mode toggle.
        //
        // Runs immediately instead of waiting for DOMContentLoaded: this
        // script sits after @yield('content'), so #theme-toggle already
        // exists, and waiting for DOMContentLoaded proved fragile — on
        // the forwarded site the toggle stopped working entirely (the
        // click listener was never attached). Storage access is guarded:
        // if localStorage is unavailable/blocked, a throw used to kill
        // this whole script before the listener was attached; now the
        // toggle keeps working in-memory and just doesn't remember the
        // choice.
        (function () {
            var themeToggle = document.getElementById('theme-toggle');
            var htmlElement = document.documentElement; // Target the html element
            var paayuLogo = document.getElementById('paayu-logo'); // Get the logo element

            // Define logo paths
            var lightModeLogoPath = "/assets/images/logos/paayu_light_mode.png";
            var darkModeLogoPath = "/assets/images/logos/paayu_dark_mode.png";

            function readStoredTheme() {
                try { return localStorage.getItem('theme'); } catch (e) { return null; }
            }

            function writeStoredTheme(theme) {
                try { localStorage.setItem('theme', theme); } catch (e) { /* storage unavailable: keep in-memory only */ }
            }

            // Function to update the logo based on theme
            function updateLogo(theme) {
                if (paayuLogo) { // Check if the logo element exists
                    if (theme === 'dark') {
                        paayuLogo.src = darkModeLogoPath;
                    } else {
                        paayuLogo.src = lightModeLogoPath;
                    }
                }
            }

            // Function to set the theme and update logo
            function setTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                writeStoredTheme(theme);
                updateThemeIcon(theme);
                updateLogo(theme); // Call updateLogo when theme changes
            }

            // Function to update the icon
            function updateThemeIcon(theme) {
                if (themeToggle) { // Check if the button exists
                    var icon = themeToggle.querySelector('i');
                    if (icon) { // Check if icon element exists
                        if (theme === 'dark') {
                            icon.classList.remove('bi-moon');
                            icon.classList.add('bi-sun');
                        } else {
                            icon.classList.remove('bi-sun');
                            icon.classList.add('bi-moon');
                        }
                    }
                }
            }

            // Get the preferred theme from local storage or default to light
            var storedTheme = readStoredTheme();
            var initialTheme = storedTheme || 'light'; // Default to 'light' if no stored theme

            // Set the initial theme and update logo
            setTheme(initialTheme); // This will now also call updateLogo

            // Add event listener to the button
            if (themeToggle) { // Check if the button exists
                themeToggle.addEventListener('click', function () {
                    var currentTheme = htmlElement.getAttribute('data-bs-theme');
                    var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                    setTheme(newTheme);
                });
            }
        })();
    </script>

    @stack('after-scripts')

</body>

</html>
