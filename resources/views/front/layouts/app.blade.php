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
        // Hardened twice over, because the toggle kept dying on the
        // forwarded site: first it waited for DOMContentLoaded and the
        // listener was never attached; then it ran immediately with a
        // direct listener and STILL did not work. So now the click is
        // handled by delegation on `document` — it works no matter when
        // the button enters the DOM, and there is exactly one listener
        // (no direct one to double-toggle with). Storage access is
        // guarded: if localStorage is unavailable/blocked, a throw used
        // to kill this whole script before anything was attached; now
        // the toggle keeps working in-memory and just doesn't remember
        // the choice.
        (function () {
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

            // Function to update the icon (looks the button up fresh, so
            // it works even if the button was rendered after this script)
            function updateThemeIcon(theme) {
                var themeToggle = document.getElementById('theme-toggle');
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

            // Function to set the theme and update logo
            function setTheme(theme) {
                htmlElement.setAttribute('data-bs-theme', theme);
                writeStoredTheme(theme);
                updateThemeIcon(theme);
                updateLogo(theme); // Call updateLogo when theme changes
            }

            function toggleTheme() {
                var currentTheme = htmlElement.getAttribute('data-bs-theme');
                var newTheme = currentTheme === 'dark' ? 'light' : 'dark';
                setTheme(newTheme);
            }

            // Delegated click: a single listener on document, so the
            // toggle works regardless of when #theme-toggle appears.
            document.addEventListener('click', function (e) {
                var target = e.target;
                var btn = target && target.closest ? target.closest('#theme-toggle') : null;
                if (btn) {
                    toggleTheme();
                }
            });

            // Apply the stored (or default light) theme right away, and
            // again once the document is ready, in case this script ran
            // before the button was parsed.
            setTheme(readStoredTheme() || 'light');
            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function () {
                    setTheme(readStoredTheme() || 'light');
                });
            }
        })();
    </script>

    @stack('after-scripts')

</body>

</html>
