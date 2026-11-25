<!doctype html>
<html lang="id" data-theme="dark">
<head>
    <meta charset="utf-8" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Login — Tokabe Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Sistem Invoice dan Kasir Tokabe" name="description" />
    <meta content="Tokabe" name="author" />

    <!-- Synchronous theme init before first paint (zero flash) -->
    <script>
        (function() {
            var saved = null;
            try { saved = localStorage.getItem('tokabe_theme') || localStorage.getItem('tokabe-theme'); } catch (e) {}
            var theme = (saved === 'light' || saved === 'dark') ? saved : 'dark';
            document.documentElement.setAttribute('data-theme', theme);
        })();
    </script>

    <!-- App favicon -->
    <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('images/favicon-32x32.png') }}?v=3">
    <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('images/favicon-16x16.png') }}?v=3">
    <link rel="icon" type="image/png" href="{{ asset('images/LogoTKB.png') }}?v=3">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}?v=3">
    <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('images/apple-touch-icon.png') }}?v=3">

    <!-- Icons & Global Tokabe Theme -->
    <link href="{{ asset('css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <link href="{{ asset('css/tokabe-theme.css') }}?v={{ file_exists(public_path('css/tokabe-theme.css')) ? filemtime(public_path('css/tokabe-theme.css')) : time() }}" rel="stylesheet" type="text/css" />

    <style>
        /* ==========================================================================
           TOKABE LOGIN PAGE — SPLIT LAYOUT (DARK & LIGHT MODE)
           ========================================================================== */
        :root,
        [data-theme="dark"] {
            --login-bg: #070D0B;
            --login-panel-bg: linear-gradient(155deg, #0D261C 0%, #0B1F17 55%, #091913 100%);
            --login-panel-border: rgba(52, 211, 153, 0.10);
            --login-blob-1: rgba(20, 184, 122, 0.13);
            --login-blob-2: rgba(20, 184, 122, 0.08);
            --login-card-bg: #0F1A15;
            --login-card-border: rgba(255, 255, 255, 0.09);
            --login-card-divider: rgba(255, 255, 255, 0.08);
            --login-card-shadow: 0 14px 36px rgba(0, 0, 0, 0.35);
            --login-row-focus: rgba(20, 184, 122, 0.06);
            --login-btn-grad: linear-gradient(135deg, #0E7A4D 0%, #14B87A 100%);
            --login-btn-grad-hover: linear-gradient(135deg, #118F5A 0%, #1DD18E 100%);
            --login-btn-shadow: 0 10px 26px rgba(20, 184, 122, 0.34);
            --login-sec-bg: #111F19;
            --login-sec-border: rgba(255, 255, 255, 0.10);
            --login-sec-hover: #172B22;
            --login-sec-shadow: none;

            /* Vector Illustration Tokens (Dark) */
            --illus-stroke: #5EEAD4;
            --illus-stroke-soft: #38BDF8;
            --illus-desk: #5EEAD4;
            --illus-surface: #0C221A;
            --illus-laptop-bg: #0B1F17;
            --illus-shirt: #34D399;
            --illus-bar: #34D399;
            --illus-accent-amber: #F59E0B;
            --illus-leaf: #11362A;
            --illus-leaf-stem: #5EEAD4;
            --illus-hair: #3F594F;
            --illus-pants: #374F46;
            --illus-skin: #F1B392;
        }

        [data-theme="light"] {
            --login-bg: #F6FAF7;
            --login-panel-bg: linear-gradient(155deg, #DFF4E9 0%, #EAF7F0 52%, #F1FAF5 100%);
            --login-panel-border: rgba(15, 155, 96, 0.10);
            --login-blob-1: rgba(15, 155, 96, 0.14);
            --login-blob-2: rgba(15, 155, 96, 0.08);
            --login-card-bg: #FFFFFF;
            --login-card-border: #E2ECE7;
            --login-card-divider: #E9F1EC;
            --login-card-shadow: 0 14px 36px rgba(15, 45, 32, 0.065);
            --login-row-focus: rgba(15, 155, 96, 0.045);
            --login-btn-grad: linear-gradient(135deg, #0B804E 0%, #12B06E 100%);
            --login-btn-grad-hover: linear-gradient(135deg, #096C42 0%, #0F9B60 100%);
            --login-btn-shadow: 0 10px 24px rgba(15, 155, 96, 0.28);
            --login-sec-bg: #FFFFFF;
            --login-sec-border: #E2ECE7;
            --login-sec-hover: #F2F8F4;
            --login-sec-shadow: 0 6px 18px rgba(15, 45, 32, 0.05);

            /* Vector Illustration Tokens (Light) */
            --illus-stroke: #1B6B7F;
            --illus-stroke-soft: #2A7D91;
            --illus-desk: #16657A;
            --illus-surface: #FFFFFF;
            --illus-laptop-bg: #F5FBF8;
            --illus-shirt: #0F9B60;
            --illus-bar: #0F9B60;
            --illus-accent-amber: #E5A00D;
            --illus-leaf: #DCEFEA;
            --illus-leaf-stem: #1B6B7F;
            --illus-hair: #1E2E28;
            --illus-pants: #1B2A24;
            --illus-skin: #F2B594;
        }

        *, *::before, *::after {
            box-sizing: border-box;
        }

        html, body {
            margin: 0;
            padding: 0;
            min-height: 100vh;
            background: var(--login-bg) !important;
            color: var(--text-primary) !important;
            font-family: var(--tkb-font) !important;
            -webkit-font-smoothing: antialiased;
        }

        /* Top-right Theme Toggle */
        .tkb-login-topbar {
            position: fixed;
            top: 20px;
            right: 24px;
            z-index: 50;
        }

        /* Split Shell */
        .tkb-login-shell {
            display: grid;
            grid-template-columns: 1.04fr 1fr;
            min-height: 100vh;
            width: 100%;
            padding: 16px;
            gap: 16px;
        }

        /* Left Visual Panel */
        .tkb-login-visual {
            position: relative;
            overflow: hidden;
            border-radius: 22px;
            background: var(--login-panel-bg);
            border: 1px solid var(--login-panel-border);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 48px 36px;
            text-align: center;
        }

        .tkb-login-blob-1 {
            position: absolute;
            top: -90px;
            left: -70px;
            width: 310px;
            height: 310px;
            border-radius: 50%;
            background: var(--login-blob-1);
            pointer-events: none;
        }

        .tkb-login-blob-2 {
            position: absolute;
            width: 410px;
            height: 410px;
            border-radius: 50%;
            background: var(--login-blob-2);
            top: 46%;
            left: 52%;
            transform: translate(-50%, -50%);
            pointer-events: none;
        }

        .tkb-login-illus-wrap {
            position: relative;
            z-index: 2;
            width: 100%;
            max-width: 440px;
            margin-bottom: 28px;
        }

        .tkb-login-illus-wrap svg {
            width: 100%;
            height: auto;
            display: block;
        }

        .tkb-login-copy {
            position: relative;
            z-index: 2;
            max-width: 390px;
        }

        .tkb-login-copy h2 {
            font-size: 21px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            margin: 0 0 8px 0;
            line-height: 1.3;
        }

        .tkb-login-copy p {
            font-size: 13.5px;
            color: var(--text-secondary);
            margin: 0;
            line-height: 1.6;
        }

        /* Right Form Column */
        .tkb-login-form-pane {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 36px 28px;
        }

        .tkb-login-box {
            width: 100%;
            max-width: 386px;
        }

        /* Brand Header */
        .tkb-login-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 28px;
            text-decoration: none;
        }

        .tkb-login-brand__badge {
            width: 46px;
            height: 46px;
            min-width: 46px;
            border-radius: 50%;
            background: #FFFFFF;
            border: 1px solid var(--border-default);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 3px;
            box-shadow: none;
            overflow: hidden;
        }

        .tkb-login-brand__badge img {
            width: 100%;
            height: 100%;
            object-fit: contain;
            display: block;
        }

        .tkb-login-brand__name {
            font-size: 17px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .tkb-login-brand__sub {
            font-size: 12.5px;
            color: var(--text-tertiary);
            margin-top: 2px;
        }

        /* Greeting */
        .tkb-login-heading {
            font-size: 32px;
            font-weight: 800;
            color: var(--text-primary);
            letter-spacing: -0.03em;
            margin: 0 0 6px 0;
            line-height: 1.15;
        }

        .tkb-login-sub {
            font-size: 14px;
            color: var(--text-secondary);
            margin: 0 0 26px 0;
        }

        /* Alerts */
        .tkb-login-alert {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            padding: 12px 14px;
            border-radius: 12px;
            font-size: 13px;
            font-weight: 500;
            margin-bottom: 18px;
            line-height: 1.45;
        }

        .tkb-login-alert--danger {
            background: var(--danger-subtle);
            border: 1px solid var(--danger-border);
            color: var(--danger-text);
        }

        .tkb-login-alert--success {
            background: var(--success-subtle);
            border: 1px solid var(--success-border);
            color: var(--success-text);
        }

        .tkb-login-alert i {
            font-size: 17px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        /* Stacked Input Card */
        .tkb-input-stack {
            background: var(--login-card-bg);
            border: 1px solid var(--login-card-border);
            border-radius: 16px;
            box-shadow: var(--login-card-shadow);
            overflow: hidden;
        }

        .tkb-input-row {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 14px 18px;
            transition: background-color 140ms ease;
        }

        .tkb-input-row + .tkb-input-row {
            border-top: 1px solid var(--login-card-divider);
        }

        .tkb-input-row:focus-within {
            background: var(--login-row-focus);
        }

        .tkb-input-row__icon {
            font-size: 18px;
            color: var(--text-tertiary);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .tkb-input-row:focus-within .tkb-input-row__icon {
            color: var(--brand);
        }

        .tkb-input-row__field {
            flex: 1;
            min-width: 0;
            display: flex;
            flex-direction: column;
        }

        .tkb-input-row__label {
            font-size: 11.5px;
            font-weight: 600;
            color: var(--text-tertiary);
            margin-bottom: 3px;
            line-height: 1.2;
            cursor: text;
        }

        .tkb-input-row__input {
            width: 100%;
            border: none !important;
            outline: none !important;
            background: transparent !important;
            box-shadow: none !important;
            color: var(--text-primary) !important;
            font-family: var(--tkb-font) !important;
            font-size: 14.5px;
            font-weight: 500;
            padding: 0;
            margin: 0;
            line-height: 1.4;
        }

        .tkb-input-row__input::placeholder {
            color: var(--text-disabled);
            font-weight: 400;
        }

        /* Autofill clean override */
        .tkb-input-row__input:-webkit-autofill,
        .tkb-input-row__input:-webkit-autofill:hover,
        .tkb-input-row__input:-webkit-autofill:focus {
            -webkit-text-fill-color: var(--text-primary) !important;
            -webkit-box-shadow: 0 0 0 1000px var(--login-card-bg) inset !important;
            transition: background-color 5000s ease-in-out 0s;
        }

        .tkb-pass-toggle {
            border: none;
            background: transparent;
            color: var(--text-tertiary);
            width: 32px;
            height: 32px;
            border-radius: 8px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 18px;
            cursor: pointer;
            flex-shrink: 0;
            padding: 0;
        }

        .tkb-pass-toggle:hover {
            color: var(--text-primary);
            background: var(--bg-hover);
        }

        .tkb-field-error {
            font-size: 12px;
            font-weight: 500;
            color: var(--danger-text);
            margin-top: 6px;
            display: block;
        }

        /* Action Buttons */
        .tkb-login-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-top: 24px;
        }

        .tkb-btn-login {
            flex: 1;
            height: 46px;
            border-radius: 12px;
            border: none;
            background: var(--login-btn-grad);
            color: #FFFFFF;
            font-family: var(--tkb-font);
            font-size: 14px;
            font-weight: 700;
            letter-spacing: -0.01em;
            cursor: pointer;
            box-shadow: var(--login-btn-shadow);
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: transform 120ms ease, filter 150ms ease;
        }

        .tkb-btn-login:hover {
            background: var(--login-btn-grad-hover);
            transform: translateY(-1px);
        }

        .tkb-btn-login:active {
            transform: translateY(0);
        }

        .tkb-btn-forgot {
            height: 46px;
            padding: 0 20px;
            border-radius: 12px;
            border: 1px solid var(--login-sec-border);
            background: var(--login-sec-bg);
            color: var(--text-primary);
            font-family: var(--tkb-font);
            font-size: 13.5px;
            font-weight: 600;
            text-decoration: none;
            white-space: nowrap;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: var(--login-sec-shadow);
            transition: background-color 140ms ease, border-color 140ms ease;
        }

        .tkb-btn-forgot:hover {
            background: var(--login-sec-hover);
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        /* Copyright */
        .tkb-login-footer {
            text-align: center;
            margin-top: 36px;
            font-size: 12.5px;
            color: var(--text-tertiary);
        }

        /* ==========================================================================
           RESPONSIVE (TABLET & MOBILE)
           ========================================================================== */
        @media (max-width: 991.98px) {
            .tkb-login-shell {
                grid-template-columns: 1fr;
                padding: 0;
                gap: 0;
            }
            .tkb-login-visual {
                display: none;
            }
            .tkb-login-form-pane {
                min-height: 100vh;
                padding: 76px 20px 32px 20px;
            }
            .tkb-login-topbar {
                top: 16px;
                right: 16px;
            }
        }

        @media (max-width: 420px) {
            .tkb-login-heading {
                font-size: 27px;
            }
            .tkb-login-actions {
                flex-direction: column;
                align-items: stretch;
            }
            .tkb-btn-login,
            .tkb-btn-forgot {
                width: 100%;
            }
        }
    </style>
</head>
<body>

    <!-- Top-Right Theme Toggle (Synced with Global Tokabe Theme + Circular Reveal) -->
    <div class="tkb-login-topbar">
        <button type="button" class="tkb-theme-toggle" onclick="window.toggleTokabeTheme(event)" aria-label="Ubah Mode Terang / Gelap" title="Ubah Mode Terang / Gelap">
            <span class="tkb-theme-toggle__thumb" aria-hidden="true"></span>
            <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--sun"><i class="ri-sun-line"></i></span>
            <span class="tkb-theme-toggle__icon tkb-theme-toggle__icon--moon"><i class="ri-moon-clear-line"></i></span>
        </button>
    </div>

    <main class="tkb-login-shell">
        <!-- LEFT PANEL: Illustration & Tagline -->
        <section class="tkb-login-visual" aria-hidden="true">
            <div class="tkb-login-blob-1"></div>
            <div class="tkb-login-blob-2"></div>

            <div class="tkb-login-illus-wrap">
                <svg viewBox="0 0 460 350" fill="none" xmlns="http://www.w3.org/2000/svg" role="img" aria-label="Ilustrasi kerja manajemen invoice Tokabe">
                    <!-- Wall Clock -->
                    <circle cx="105" cy="64" r="27" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2.6"/>
                    <path d="M105 49V64L116 71" stroke="var(--illus-stroke)" stroke-width="2.6" stroke-linecap="round" stroke-linejoin="round"/>

                    <!-- Floating Invoice / Message Card -->
                    <rect x="164" y="34" width="60" height="44" rx="7" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2.4"/>
                    <line x1="176" y1="49" x2="201" y2="49" stroke="var(--illus-stroke)" stroke-width="2.4" stroke-linecap="round"/>
                    <line x1="176" y1="58" x2="211" y2="58" stroke="var(--illus-stroke)" stroke-width="2.4" stroke-linecap="round"/>
                    <line x1="176" y1="67" x2="196" y2="67" stroke="var(--illus-stroke)" stroke-width="2.4" stroke-linecap="round"/>
                    <circle cx="212" cy="48" r="4" fill="var(--illus-bar)"/>

                    <!-- Dotted Circle + Flight Path + Paper Plane -->
                    <circle cx="250" cy="48" r="10" stroke="var(--illus-stroke)" stroke-width="1.6" stroke-dasharray="3 3" opacity="0.65"/>
                    <path d="M242 82C264 88 286 70 318 68" stroke="var(--illus-stroke)" stroke-width="1.6" stroke-dasharray="4 5" stroke-linecap="round" opacity="0.7"/>
                    <path d="M326 64L374 44L352 84L342 71L326 64Z" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2.3" stroke-linejoin="round"/>
                    <path d="M374 44L342 71" stroke="var(--illus-stroke)" stroke-width="2.1" stroke-linecap="round"/>

                    <!-- Potted Plant (Left) -->
                    <path d="M40 245C16 218 14 152 40 118C66 152 64 218 40 245Z" fill="var(--illus-leaf)"/>
                    <path d="M40 248V154" stroke="var(--illus-leaf-stem)" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M40 198L24 182" stroke="var(--illus-leaf-stem)" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M40 182L54 168" stroke="var(--illus-leaf-stem)" stroke-width="2.2" stroke-linecap="round"/>
                    <path d="M22 248H58L53 288H27L22 248Z" fill="var(--illus-accent-amber)"/>

                    <!-- Desk Surface & Legs -->
                    <rect x="78" y="196" width="276" height="8" rx="4" fill="var(--illus-desk)"/>
                    <line x1="94" y1="204" x2="86" y2="290" stroke="var(--illus-desk)" stroke-width="3.6" stroke-linecap="round"/>
                    <line x1="338" y1="204" x2="346" y2="290" stroke="var(--illus-desk)" stroke-width="3.6" stroke-linecap="round"/>
                    <line x1="89" y1="252" x2="343" y2="252" stroke="var(--illus-desk)" stroke-width="2" opacity="0.22"/>

                    <!-- Books / Binders on Left of Desk -->
                    <rect x="92" y="164" width="14" height="32" rx="2" fill="var(--illus-bar)"/>
                    <rect x="109" y="168" width="12" height="28" rx="2" fill="var(--illus-accent-amber)"/>
                    <rect x="124" y="172" width="11" height="24" rx="2" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2"/>

                    <!-- Briefcase Under Desk -->
                    <rect x="106" y="250" width="56" height="40" rx="5" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2.4"/>
                    <path d="M124 250V243C124 240.8 125.8 239 128 239H140C142.2 239 144 240.8 144 243V250" stroke="var(--illus-stroke)" stroke-width="2.4"/>
                    <line x1="134" y1="250" x2="134" y2="262" stroke="var(--illus-stroke)" stroke-width="2.2" stroke-linecap="round"/>

                    <!-- Desk Lamp (Right) -->
                    <path d="M312 196H342" stroke="var(--illus-desk)" stroke-width="4" stroke-linecap="round"/>
                    <path d="M334 196L342 142L312 118" stroke="var(--illus-stroke)" stroke-width="2.8" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M300 107L327 116L314 134L300 107Z" fill="var(--illus-stroke)"/>

                    <!-- Wastebasket (Far Right) -->
                    <path d="M366 244H402L397 290H371L366 244Z" fill="var(--illus-surface)" stroke="var(--illus-stroke)" stroke-width="2.4" stroke-linejoin="round"/>
                    <line x1="378" y1="253" x2="380" y2="281" stroke="var(--illus-stroke)" stroke-width="2" stroke-linecap="round"/>
                    <line x1="390" y1="253" x2="388" y2="281" stroke="var(--illus-stroke)" stroke-width="2" stroke-linecap="round"/>

                    <!-- Chair Back Cushion & Base -->
                    <circle cx="182" cy="176" r="32" fill="var(--illus-accent-amber)"/>
                    <rect x="158" y="208" width="54" height="10" rx="5" fill="var(--illus-pants)"/>
                    <line x1="184" y1="218" x2="184" y2="284" stroke="var(--illus-stroke)" stroke-width="3"/>
                    <line x1="164" y1="286" x2="204" y2="286" stroke="var(--illus-stroke)" stroke-width="3" stroke-linecap="round"/>

                    <!-- Person Sitting & Working -->
                    <!-- Hair bun + head -->
                    <circle cx="168" cy="84" r="9" fill="var(--illus-hair)"/>
                    <circle cx="184" cy="98" r="19" fill="var(--illus-skin)"/>
                    <path d="M167 93C170 80 189 77 199 88C191 84 176 86 167 93Z" fill="var(--illus-hair)"/>
                    <!-- Neck -->
                    <rect x="180" y="114" width="9" height="10" rx="3" fill="var(--illus-skin)"/>
                    <!-- Torso (Emerald Shirt) -->
                    <path d="M158 148C158 131 170 122 184 122C198 122 208 132 208 150V198C208 205 202 210 194 210H170C163 210 158 204 158 197V148Z" fill="var(--illus-shirt)"/>
                    <!-- Legs & Shoes -->
                    <path d="M180 206H214C220 206 225 211 225 217V282H211V220H180V206Z" fill="var(--illus-pants)"/>
                    <rect x="206" y="282" width="28" height="9" rx="4.5" fill="var(--illus-accent-amber)"/>
                    <!-- Arm reaching to laptop -->
                    <path d="M196 146L238 168" stroke="var(--illus-skin)" stroke-width="11" stroke-linecap="round"/>

                    <!-- Laptop with Bar Chart -->
                    <rect x="234" y="142" width="88" height="54" rx="7" fill="var(--illus-laptop-bg)" stroke="var(--illus-stroke)" stroke-width="2.6"/>
                    <rect x="249" y="172" width="10" height="16" rx="2.5" fill="var(--illus-bar)"/>
                    <rect x="265" y="162" width="10" height="26" rx="2.5" fill="var(--illus-bar)"/>
                    <rect x="281" y="168" width="10" height="20" rx="2.5" fill="var(--illus-bar)"/>
                    <rect x="297" y="154" width="10" height="34" rx="2.5" fill="var(--illus-bar)"/>
                    <line x1="226" y1="196" x2="328" y2="196" stroke="var(--illus-stroke)" stroke-width="3" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="tkb-login-copy">
                <h2>Kelola invoice dan kasir lebih mudah</h2>
                <p>Pantau pembayaran, pajak, dan tagihan vendor dalam satu dashboard.</p>
            </div>
        </section>

        <!-- RIGHT PANEL: Login Form -->
        <section class="tkb-login-form-pane">
            <div class="tkb-login-box">
                <!-- Brand -->
                <div class="tkb-login-brand">
                    <div class="tkb-login-brand__badge">
                        <img src="{{ asset('images/LogoTKB.png') }}" alt="Tokabe">
                    </div>
                    <div>
                        <div class="tkb-login-brand__name">Tokabe</div>
                        <div class="tkb-login-brand__sub">Sistem invoice dan kasir</div>
                    </div>
                </div>

                <!-- Greeting -->
                <h1 class="tkb-login-heading">Selamat Datang!</h1>
                <p class="tkb-login-sub">Silakan log in untuk mengakses aplikasi.</p>

                <!-- Session / Validation Alerts -->
                @if(session()->has('error'))
                    <div class="tkb-login-alert tkb-login-alert--danger" role="alert">
                        <i class="ri-error-warning-line"></i>
                        <span>{{ session('error') }}</span>
                    </div>
                @endif

                @if(session('success'))
                    <div class="tkb-login-alert tkb-login-alert--success" role="alert">
                        <i class="ri-checkbox-circle-line"></i>
                        <span>{{ session('success') }}</span>
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login') }}" method="POST" id="login_form">
                    @csrf

                    <div class="tkb-input-stack">
                        <!-- Email Row -->
                        <div class="tkb-input-row">
                            <span class="tkb-input-row__icon" aria-hidden="true"><i class="ri-mail-line"></i></span>
                            <div class="tkb-input-row__field">
                                <label for="username" class="tkb-input-row__label">Email</label>
                                <input
                                    type="email"
                                    class="tkb-input-row__input @error('email') is-invalid @enderror"
                                    id="username"
                                    name="email"
                                    value="{{ old('email') }}"
                                    placeholder="nama@email.com"
                                    required
                                    autocomplete="email"
                                    autofocus
                                >
                            </div>
                        </div>

                        <!-- Password Row -->
                        <div class="tkb-input-row">
                            <span class="tkb-input-row__icon" aria-hidden="true"><i class="ri-lock-line"></i></span>
                            <div class="tkb-input-row__field">
                                <label for="password-input" class="tkb-input-row__label">Password</label>
                                <input
                                    type="password"
                                    class="tkb-input-row__input @error('password') is-invalid @enderror"
                                    id="password-input"
                                    name="password"
                                    placeholder="Masukkan password"
                                    required
                                    autocomplete="current-password"
                                >
                            </div>
                            <button type="button" class="tkb-pass-toggle" id="password-addon" aria-label="Tampilkan atau sembunyikan password" title="Lihat password">
                                <i class="ri-eye-line" id="password-addon-icon"></i>
                            </button>
                        </div>
                    </div>

                    @error('email')
                        <span class="tkb-field-error" role="alert">{{ $message }}</span>
                    @enderror
                    @error('password')
                        <span class="tkb-field-error" role="alert">{{ $message }}</span>
                    @enderror

                    <div class="tkb-login-actions">
                        <button type="submit" class="tkb-btn-login">Log In</button>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="tkb-btn-forgot">Lupa password?</a>
                        @endif
                    </div>
                </form>

                <div class="tkb-login-footer">
                    © {{ date('Y') }} Tokabe
                </div>
            </div>
        </section>
    </main>

    <script>
        // Password visibility toggle
        (function() {
            var btn = document.getElementById('password-addon');
            var input = document.getElementById('password-input');
            var icon = document.getElementById('password-addon-icon');
            if (btn && input && icon) {
                btn.addEventListener('click', function() {
                    var isPassword = input.type === 'password';
                    input.type = isPassword ? 'text' : 'password';
                    icon.className = isPassword ? 'ri-eye-off-line' : 'ri-eye-line';
                });
            }
        })();

        // Synchronous Circular Reveal Theme Toggle (identical to layout/template.blade.php)
        window.toggleTokabeTheme = function(e) {
            var root = document.documentElement;
            var current = root.getAttribute('data-theme') || 'dark';
            var next = current === 'dark' ? 'light' : 'dark';

            var applyTheme = function() {
                root.setAttribute('data-theme', next);
                try { localStorage.setItem('tokabe_theme', next); } catch (err) {}
            };

            if (!document.startViewTransition || window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
                applyTheme();
                return;
            }

            if (window.__tkbActiveTransition && typeof window.__tkbActiveTransition.skipTransition === 'function') {
                try { window.__tkbActiveTransition.skipTransition(); } catch (err) {}
            }

            var x = window.innerWidth - 56;
            var y = 36;
            if (e && e.currentTarget && typeof e.currentTarget.getBoundingClientRect === 'function') {
                var rect = e.currentTarget.getBoundingClientRect();
                x = rect.left + rect.width / 2;
                y = rect.top + rect.height / 2;
            } else if (e && typeof e.clientX === 'number' && typeof e.clientY === 'number') {
                x = e.clientX;
                y = e.clientY;
            }

            var maxRadius = Math.hypot(
                Math.max(x, window.innerWidth - x),
                Math.max(y, window.innerHeight - y)
            );

            root.classList.add('theme-switching');
            var transition = document.startViewTransition(applyTheme);
            window.__tkbActiveTransition = transition;

            transition.ready.then(function() {
                root.animate(
                    {
                        clipPath: [
                            'circle(0px at ' + x + 'px ' + y + 'px)',
                            'circle(' + maxRadius + 'px at ' + x + 'px ' + y + 'px)'
                        ]
                    },
                    {
                        duration: 620,
                        easing: 'cubic-bezier(0.65, 0, 0.35, 1)',
                        pseudoElement: '::view-transition-new(root)'
                    }
                );
            }).catch(function() {});

            transition.finished.finally(function() {
                if (window.__tkbActiveTransition === transition) {
                    window.__tkbActiveTransition = null;
                    root.classList.remove('theme-switching');
                }
            });
        };
    </script>
</body>
</html>
