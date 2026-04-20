<!DOCTYPE html>
<html class="light" lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Mycology IoT') }}</title>
    
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600&display=swap" rel="stylesheet"/>
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet"/>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              colors: {
                  "primary": "#2e5227",
                  "on-secondary-fixed": "#001f2a", "surface-container": "#ecefea", "tertiary": "#185066",
                  "on-primary-fixed": "#002201", "outline-variant": "#c2c8c0", "tertiary-container": "#35687f",
                  "surface-dim": "#d5dcce", "primary-fixed-dim": "#a1d494", "secondary": "#506447",
                  "primary": "#2e5227", "surface-container-lowest": "#ffffff", "secondary-fixed-dim": "#b6cdaa",
                  "surface-container-low": "#f2f4ef", "background": "#f8faf5", "primary-container": "#456b3d",
                  "secondary-fixed": "#d2eac5", "error": "#ba1a1a", "surface-container-highest": "#dee5d6",
                  "on-secondary": "#ffffff", "on-tertiary-container": "#a9f1ff", "on-error-container": "#93000a",
                  "inverse-on-surface": "#eff1ec", "secondary-container": "#d2eac5", "on-secondary-fixed-variant": "#2e4b57",
                  "surface-bright": "#f5fced", "tertiary-fixed": "#9eefff", "inverse-primary": "#a1d494",
                  "on-primary": "#ffffff", "on-error": "#ffffff", "on-background": "#171d14",
                  "error-container": "#ffdad6", "on-secondary-container": "#4a6774", "surface-tint": "#3b6934",
                  "inverse-surface": "#2c3228", "primary-fixed": "#bcf0ae", "on-tertiary-fixed": "#001f24",
                  "surface-variant": "#dee5d6", "surface": "#f5fced", "on-surface": "#171d14",
                  "outline": "#737971", "on-tertiary": "#ffffff", "on-surface-variant": "#424842",
                  "on-primary-container": "#bef3b0", "on-primary-fixed-variant": "#23501e",
                  "tertiary-fixed-dim": "#55d7ed", "on-tertiary-fixed-variant": "#004e59", "surface-container-high": "#e3ebdc"
              },
              fontFamily: { headline: ["Plus Jakarta Sans"], body: ["Inter"], label: ["Inter"] }
            }
          }
        }
    </script>
    <style>
        .material-symbols-outlined { font-variation-settings: "FILL" 0, "wght" 400, "GRAD" 0, "opsz" 24 }
        body { font-family: 'Inter', sans-serif; }
        h1, h2, h3, h4, h5, h6, .font-headline { font-family: 'Plus Jakarta Sans', sans-serif; }

        .bg-login-hero {
            background-image: url('{{ asset('assets/images/hero-mushroom.png') }}');
            background-size: cover;
            background-position: center;
        }
    </style>
</head>
<body class="bg-background font-body text-on-surface selection:bg-primary-fixed selection:text-on-primary-fixed min-h-screen flex items-center justify-center p-4 md:p-0 overflow-hidden">
    {{ $slot }}
</body>
</html>
