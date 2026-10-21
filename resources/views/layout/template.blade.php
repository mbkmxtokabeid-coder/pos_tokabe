<!doctype html>
<html lang="id" data-layout-mode="dark" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="sm-hover" data-sidebar-image="none" data-preloader="disable" style="background-color: #0C0F14; color-scheme: dark;">

<head>

<meta charset="utf-8" />
<meta name="color-scheme" content="dark">
<meta name="Kasir Tokabe" content="Tokabe">
<title>Tokabe Kasir</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta content="Kasir Tokabe" name="description" />

<!-- Critical Anti-Flicker / Dark Canvas Base Style -->
<style>
    :root {
        --bg-app: #0C0F14;
        --bg-surface: #12161D;
        --border-subtle: #232A35;
        --text-tertiary: #94A3B8;
    }
    :root, html, body, #layout-wrapper, .main-content, .page-content {
        background-color: var(--bg-app, #0C0F14) !important;
        color-scheme: dark !important;
    }
    .footer {
        background-color: var(--bg-app, #0C0F14) !important;
        color: var(--text-tertiary, #94A3B8) !important;
        border-top: 1px solid var(--border-subtle, #232A35) !important;
        box-shadow: none !important;
    }
</style>

<!-- App favicon -->
<link rel="icon" type="image/png" sizes="32x32" href="{{asset('images/favicon-32x32.png')}}?v=3">
<link rel="icon" type="image/png" sizes="16x16" href="{{asset('images/favicon-16x16.png')}}?v=3">
<link rel="icon" type="image/png" href="{{asset('images/LogoTKB.png')}}?v=3">
<link rel="shortcut icon" href="{{asset('favicon.ico')}}?v=3">
<link rel="apple-touch-icon" sizes="180x180" href="{{asset('images/apple-touch-icon.png')}}?v=3">
<script>
    (function() {
        var link = document.createElement('link');
        link.type = 'image/png';
        link.rel = 'icon';
        link.href = "{{asset('images/LogoTKB.png')}}?v=" + new Date().getTime();
        document.getElementsByTagName('head')[0].appendChild(link);
    })();
</script>

<script>
    // Ensure default sidebar is sm-hover for modern shadcn-style micro-expand rail
    if (!sessionStorage.getItem("data-sidebar-size") || sessionStorage.getItem("data-sidebar-size") === "lg") {
        sessionStorage.setItem("data-sidebar-size", "sm-hover");
    }
</script>

<!-- Layout config Js -->
<script src="{{asset('js/layout.js')}}"></script>
<!-- Bootstrap Css -->
<link href="{{asset('css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" />
<!-- Icons Css -->
<link href="{{asset('css/icons.min.css')}}" rel="stylesheet" type="text/css" />
<!-- App Css-->
<link href="{{asset('css/app.min.css')}}" rel="stylesheet" type="text/css" />
<link href="{{asset('libs/flatpickr/flatpickr.min.css')}}" rel="stylesheet" type="text/css" />

{{-- HEAD CSS  --}}
@yield('head')

<link rel="preconnect" href="https://code.jquery.com" crossorigin>
<link rel="preconnect" href="https://cdn.datatables.net" crossorigin>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{asset('libs/sweetalert2/sweetalert2.min.js')}}"></script>

<!-- Speculation Rules: Instant page transitions on link hover -->
<script type="speculationrules">
{
  "prefetch": [
    {
      "source": "list",
      "urls": [
        "/welcomeTKB",
        "/list-invoice/tokabe",
        "/list-invoice/custom",
        "/addInvoiceTokabe",
        "/addInvoiceTokabeCustom",
        "/daftar-spb",
        "/daftar-spj",
        "/pembelian",
        "/items",
        "/daftar-pelanggan",
        "/daftar-laporan",
        "/daftar-laporan/custom"
      ],
      "eagerness": "moderate"
    }
  ]
}
</script>
<script>
    // Universal hover-prefetch fallback for instant navigation
    document.addEventListener('DOMContentLoaded', function () {
        var prefetched = new Set();
        document.addEventListener('mouseover', function (e) {
            var a = e.target.closest('a');
            if (!a || !a.href || a.target === '_blank' || a.hasAttribute('download')) return;
            if (a.origin === window.location.origin && a.pathname !== window.location.pathname && !prefetched.has(a.href)) {
                prefetched.add(a.href);
                var link = document.createElement('link');
                link.rel = 'prefetch';
                link.href = a.href;
                document.head.appendChild(link);
            }
        }, { passive: true });
    });
</script>
  
  <style>
      /* Dark Mode Select, Input, and Option High-Contrast Fix */
      .form-select,
      select.form-select,
      .form-control,
      input.form-control {
          color: #f8f9fa !important;
          background-color: #212529 !important;
          border-color: #383e45 !important;
      }

      .form-select option,
      select option {
          background-color: #212529 !important;
          color: #f8f9fa !important;
          padding: 8px 12px;
      }

      .form-select:focus,
      .form-control:focus {
          color: #ffffff !important;
          background-color: #272c32 !important;
          border-color: #405189 !important;
      }

      /* ========================================================================= */
      /* SWEETALERT2 DARK THEME — DESIGN.md §15.3 & §4.1                          */
      /* ========================================================================= */
      .swal2-container {
          z-index: 100000 !important;
          background-color: rgba(0, 0, 0, 0.7) !important;
          backdrop-filter: blur(2px) !important;
      }
      .swal2-popup {
          background-color: #161B22 !important;
          background: #161B22 !important;
          color: #F8F9FA !important;
          border: 1px solid #2D333B !important;
          border-radius: 16px !important;
          box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.6), 0 8px 10px -6px rgba(0, 0, 0, 0.5) !important;
          padding: 24px !important;
      }
      .swal2-title {
          color: #F8F9FA !important;
          font-size: 18px !important;
          font-weight: 600 !important;
          margin-top: 12px !important;
          margin-bottom: 8px !important;
      }
      .swal2-html-container {
          color: #CBD5E1 !important;
          font-size: 14px !important;
          line-height: 1.5 !important;
          margin: 8px 0 16px 0 !important;
      }
      .swal2-actions {
          margin-top: 20px !important;
          gap: 12px !important;
      }
      .swal2-styled.swal2-confirm {
          border-radius: 8px !important;
          font-size: 14px !important;
          font-weight: 600 !important;
          padding: 9px 20px !important;
          box-shadow: 0 1px 2px rgba(0, 0, 0, 0.3) !important;
          transition: all 0.15s ease !important;
      }
      .swal2-styled.swal2-confirm:hover {
          transform: translateY(-1px) !important;
          box-shadow: 0 4px 12px rgba(0, 0, 0, 0.4) !important;
      }
      .swal2-styled.swal2-confirm:focus {
          box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.45) !important;
      }
      .swal2-styled.swal2-cancel {
          background-color: #1E293B !important;
          color: #CBD5E1 !important;
          border: 1px solid #334155 !important;
          border-radius: 8px !important;
          font-size: 14px !important;
          font-weight: 500 !important;
          padding: 9px 20px !important;
          transition: all 0.15s ease !important;
      }
      .swal2-styled.swal2-cancel:hover {
          background-color: #334155 !important;
          color: #FFFFFF !important;
          border-color: #475569 !important;
          transform: translateY(-1px) !important;
      }
      .swal2-styled.swal2-cancel:focus {
          box-shadow: 0 0 0 3px rgba(148, 163, 184, 0.35) !important;
      }
      .swal2-icon.swal2-warning {
          border-color: #F59E0B !important;
          color: #F59E0B !important;
      }
      .swal2-icon.swal2-error {
          border-color: #EF4444 !important;
          color: #EF4444 !important;
      }
      .swal2-icon.swal2-error [class^='swal2-x-mark-line'] {
          background-color: #EF4444 !important;
      }
      .swal2-icon.swal2-question {
          border-color: #3B82F6 !important;
          color: #3B82F6 !important;
      }
      .swal2-icon.swal2-success {
          border-color: #10B981 !important;
          color: #10B981 !important;
      }
      .swal2-icon.swal2-success [class^='swal2-success-line'] {
          background-color: #10B981 !important;
      }
      .swal2-icon.swal2-success .swal2-success-ring {
          border-color: rgba(16, 185, 129, 0.3) !important;
      }
      .swal2-icon.swal2-info {
          border-color: #6366F1 !important;
          color: #6366F1 !important;
      }
      .swal2-toast {
          background-color: #1E293B !important;
          color: #F1F5F9 !important;
          border: 1px solid #334155 !important;
          box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.5) !important;
          border-radius: 10px !important;
          padding: 12px 16px !important;
      }
      .swal2-toast .swal2-title {
          color: #F1F5F9 !important;
          font-size: 14px !important;
          font-weight: 600 !important;
          margin: 0 !important;
      }
      .swal2-toast .swal2-html-container {
          color: #CBD5E1 !important;
          font-size: 13px !important;
          margin: 4px 0 0 0 !important;
      }


      /* ========================================================================= */
      /* FLATPICKR SOLID DARK THEME (HIGH-CONTRAST OPAQUE CALENDAR FIX) */
      /* ========================================================================= */
      .flatpickr-calendar {
          background-color: #1e2227 !important;
          background: #1e2227 !important;
          border: 1px solid #383e45 !important;
          box-shadow: 0 10px 30px rgba(0, 0, 0, 0.8), 0 0 1px rgba(255, 255, 255, 0.15) !important;
          border-radius: 8px !important;
          z-index: 99999 !important;
          width: 310px !important;
          overflow: hidden !important;
      }

      .flatpickr-calendar .flatpickr-innerContainer,
      .flatpickr-calendar .flatpickr-rContainer,
      .flatpickr-calendar .flatpickr-days,
      .flatpickr-calendar .dayContainer {
          background-color: #1e2227 !important;
          background: #1e2227 !important;
          width: 308px !important;
          min-width: 308px !important;
          max-width: 308px !important;
      }

      /* Header Bulan & Tahun */
      .flatpickr-calendar .flatpickr-months {
          background: #2b7a67 !important;
          border-top-left-radius: 7px !important;
          border-top-right-radius: 7px !important;
          padding: 6px 0 !important;
          align-items: center !important;
      }

      .flatpickr-calendar .flatpickr-months .flatpickr-month {
          background: transparent !important;
          color: #ffffff !important;
          fill: #ffffff !important;
          height: 38px !important;
      }

      .flatpickr-calendar .flatpickr-current-month {
          color: #ffffff !important;
          font-size: 15px !important;
          font-weight: 600 !important;
          padding: 6px 0 0 0 !important;
      }

      .flatpickr-calendar .flatpickr-current-month .cur-month {
          color: #ffffff !important;
          font-weight: 700 !important;
      }

      .flatpickr-calendar .flatpickr-current-month .cur-year {
          color: #ffffff !important;
          font-weight: 700 !important;
      }

      .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months {
          color: #ffffff !important;
          font-weight: 700 !important;
          background-color: transparent !important;
      }

      .flatpickr-calendar .flatpickr-current-month .flatpickr-monthDropdown-months option {
          background-color: #212529 !important;
          color: #ffffff !important;
      }

      .flatpickr-calendar .flatpickr-current-month input.cur-year {
          color: #ffffff !important;
      }

      .flatpickr-calendar .flatpickr-current-month .numInputWrapper span.arrowUp:after {
          border-bottom-color: #ffffff !important;
      }

      .flatpickr-calendar .flatpickr-current-month .numInputWrapper span.arrowDown:after {
          border-top-color: #ffffff !important;
      }

      /* Tombol Navigasi Bulan Prev & Next */
      .flatpickr-calendar .flatpickr-prev-month,
      .flatpickr-calendar .flatpickr-next-month {
          color: #ffffff !important;
          fill: #ffffff !important;
          padding: 8px 12px !important;
          height: 38px !important;
      }

      .flatpickr-calendar .flatpickr-prev-month:hover,
      .flatpickr-calendar .flatpickr-next-month:hover {
          color: #ffffff !important;
          fill: #ffffff !important;
          background-color: rgba(255, 255, 255, 0.15) !important;
          border-radius: 4px !important;
      }

      .flatpickr-calendar .flatpickr-prev-month svg,
      .flatpickr-calendar .flatpickr-next-month svg {
          fill: #ffffff !important;
          stroke: #ffffff !important;
      }

      /* Baris Nama Hari (Sun, Mon, Tue...) */
      .flatpickr-calendar .flatpickr-weekdays {
          background-color: #236555 !important;
          height: 32px !important;
          border-bottom: 1px solid rgba(0, 0, 0, 0.2) !important;
      }

      .flatpickr-calendar span.flatpickr-weekday {
          color: #e8f5e9 !important;
          font-size: 12px !important;
          font-weight: 600 !important;
          letter-spacing: 0.5px !important;
      }

      /* Area Tanggal */
      .flatpickr-calendar .flatpickr-days {
          padding: 6px 0 !important;
      }

      .flatpickr-calendar .flatpickr-day {
          color: #ffffff !important;
          background: transparent !important;
          border: 1px solid transparent !important;
          border-radius: 50% !important;
          font-weight: 500 !important;
          height: 36px !important;
          line-height: 36px !important;
          max-width: 36px !important;
          margin: 2px auto !important;
          transition: all 0.15s ease !important;
      }

      /* Tanggal Bulan Sebelumnya/Berikutnya */
      .flatpickr-calendar .flatpickr-day.prevMonthDay,
      .flatpickr-calendar .flatpickr-day.nextMonthDay {
          color: #555b62 !important;
      }

      /* Hover Tanggal */
      .flatpickr-calendar .flatpickr-day:hover,
      .flatpickr-calendar .flatpickr-day:focus {
          background-color: #2c3238 !important;
          border-color: #2b7a67 !important;
          color: #ffffff !important;
      }

      /* Hari Ini (Today) */
      .flatpickr-calendar .flatpickr-day.today {
          border-color: #2b7a67 !important;
          color: #20c997 !important;
          font-weight: 700 !important;
          background-color: transparent !important;
      }

      .flatpickr-calendar .flatpickr-day.today:hover {
          background-color: #2b7a67 !important;
          color: #ffffff !important;
      }

      /* Tanggal Terpilih (Selected) */
      .flatpickr-calendar .flatpickr-day.selected,
      .flatpickr-calendar .flatpickr-day.selected:hover,
      .flatpickr-calendar .flatpickr-day.selected:focus {
          background-color: #2b7a67 !important;
          border-color: #2b7a67 !important;
          color: #ffffff !important;
          font-weight: 700 !important;
          box-shadow: 0 2px 8px rgba(43, 122, 103, 0.5) !important;
      }

      /* Panah Tooltip Kalender */
      .flatpickr-calendar.arrowTop:before {
          border-bottom-color: #383e45 !important;
      }
      .flatpickr-calendar.arrowTop:after {
          border-bottom-color: #2b7a67 !important;
      }
      .flatpickr-calendar.arrowBottom:before {
          border-top-color: #383e45 !important;
      }
      .flatpickr-calendar.arrowBottom:after {
          border-top-color: #1e2227 !important;
      }

      /* =========================================================
         ULTRA-SMOOTH SHADCN THEMED SIDEBAR STYLING
         ========================================================= */
      :root {
          --shadcn-sidebar-bg: #0d1117;
          --shadcn-sidebar-surface: #161b22;
          --shadcn-sidebar-border: rgba(255, 255, 255, 0.08);
          --shadcn-sidebar-muted: #8b949e;
          --shadcn-sidebar-text: #e6edf3;
          --shadcn-sidebar-active-bg: rgba(56, 189, 248, 0.12);
          --shadcn-sidebar-active-text: #38bdf8;
          --shadcn-sidebar-hover-bg: rgba(255, 255, 255, 0.06);
      }

      /* Sidebar Base Container with GPU acceleration */
      .app-menu.navbar-menu {
          background-color: var(--shadcn-sidebar-bg) !important;
          border-right: 1px solid var(--shadcn-sidebar-border) !important;
          box-shadow: 4px 0 24px rgba(0, 0, 0, 0.35) !important;
          transition: width 0.28s cubic-bezier(0.25, 1, 0.5, 1), box-shadow 0.28s cubic-bezier(0.25, 1, 0.5, 1) !important;
          will-change: width;
          display: flex !important;
          flex-direction: column !important;
          padding: 0 !important;
          overflow: hidden !important;
          z-index: 1002 !important;
          contain: paint;
      }

      /* Top: Org Header */
      .sidebar-org-box {
          height: 60px;
          min-height: 60px;
          display: flex;
          align-items: center;
          padding: 8px 0;
          border-bottom: 1px solid var(--shadcn-sidebar-border);
          box-sizing: border-box;
          overflow: hidden;
      }
      .org-trigger-btn {
          background: transparent !important;
          border: none !important;
          padding: 0 !important;
          margin: 0 8px !important;
          height: 44px !important;
          border-radius: 8px !important;
          color: var(--shadcn-sidebar-text) !important;
          display: flex !important;
          align-items: center !important;
          transition: background 0.18s ease !important;
          width: calc(100% - 16px) !important;
          overflow: hidden !important;
      }
      .org-trigger-btn:hover {
          background: var(--shadcn-sidebar-hover-bg) !important;
      }
      .org-avatar-badge {
          width: 36px;
          height: 36px;
          min-width: 36px;
          border-radius: 8px;
          background: #1f2937;
          display: flex;
          align-items: center;
          justify-content: center;
          overflow: hidden;
          border: 1px solid rgba(255, 255, 255, 0.12);
          margin-left: 8px;
          flex-shrink: 0;
      }
      .org-avatar-img {
          width: 26px;
          height: 26px;
          object-fit: contain;
      }
      .org-info {
          display: flex;
          align-items: center;
          justify-content: space-between;
          flex-grow: 1;
          margin-left: 10px;
          margin-right: 8px;
          white-space: nowrap;
          overflow: hidden;
          opacity: 1;
          transform: translateX(0);
          max-width: 170px;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      max-width 0.26s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .org-name {
          font-size: 13.5px;
          font-weight: 600;
          color: #f0f6fc;
          line-height: 1.2;
      }
      .org-badge-pro {
          font-size: 9px;
          font-weight: 700;
          letter-spacing: 0.05em;
          color: #38bdf8;
          background: rgba(56, 189, 248, 0.12);
          padding: 1px 5px;
          border-radius: 4px;
          display: inline-block;
          margin-top: 2px;
      }
      .org-chevron, .user-chevron {
          font-size: 15px;
          opacity: 0.6;
      }

      /* Middle Scroll Area */
      .sidebar-scroll-area {
          flex: 1 1 auto;
          overflow-y: auto !important;
          overflow-x: hidden !important;
          padding: 8px 0;
      }
      .sidebar-scroll-area::-webkit-scrollbar {
          width: 4px;
      }
      .sidebar-scroll-area::-webkit-scrollbar-thumb {
          background: rgba(255, 255, 255, 0.12);
          border-radius: 4px;
      }

      /* Navigation Links */
      .navbar-menu .navbar-nav {
          padding: 0 !important;
          margin: 0 !important;
          width: 100% !important;
      }
      .navbar-menu .navbar-nav .nav-item {
          width: 100% !important;
          margin-bottom: 2px !important;
      }
      .navbar-menu .navbar-nav .nav-link {
          display: flex !important;
          align-items: center !important;
          padding: 0 !important;
          margin: 2px 8px !important;
          height: 40px !important;
          border-radius: 8px !important;
          color: var(--shadcn-sidebar-muted) !important;
          font-size: 13px !important;
          font-weight: 500 !important;
          line-height: 1.4 !important;
          transition: background-color 0.18s ease, color 0.18s ease !important;
          text-decoration: none !important;
          overflow: hidden !important;
          position: relative !important;
      }
      .navbar-menu .navbar-nav .nav-link:hover {
          background-color: var(--shadcn-sidebar-hover-bg) !important;
          color: #ffffff !important;
      }
      .navbar-menu .navbar-nav .nav-link.active {
          background-color: var(--shadcn-sidebar-active-bg) !important;
          color: var(--shadcn-sidebar-active-text) !important;
          font-weight: 600 !important;
      }
      .navbar-menu .navbar-nav .nav-link.active:before {
          display: none !important;
      }
      .navbar-menu .navbar-nav .nav-link .icon-box {
          width: 52px !important;
          min-width: 52px !important;
          height: 40px !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          flex-shrink: 0 !important;
      }
      .navbar-menu .navbar-nav .nav-link i.menu-icon {
          font-size: 20px !important;
          color: inherit !important;
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          transition: transform 0.18s ease;
      }
      .navbar-menu .navbar-nav .nav-link:hover i.menu-icon {
          transform: scale(1.08);
      }
      .navbar-menu .navbar-nav .nav-link .menu-text {
          font-size: 13px !important;
          white-space: nowrap !important;
          overflow: hidden !important;
          opacity: 1;
          transform: translateX(0);
          max-width: 170px;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      max-width 0.26s cubic-bezier(0.16, 1, 0.3, 1);
      }

      /* Divider */
      .sidebar-divider {
          height: 1px;
          background-color: var(--shadcn-sidebar-border);
          margin: 8px 12px;
          transition: opacity 0.2s ease;
      }

      /* Badges */
      .sidebar-badge-beta {
          font-size: 9px !important;
          font-weight: 700 !important;
          padding: 2px 6px !important;
          border-radius: 4px !important;
          background: rgba(56, 189, 248, 0.15) !important;
          color: #38bdf8 !important;
          letter-spacing: 0.04em !important;
          margin-left: auto !important;
          margin-right: 12px !important;
          white-space: nowrap !important;
          opacity: 1;
          transform: scale(1);
          max-width: 45px;
          overflow: hidden;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s;
      }

      /* =========================================================
         COLLAPSIBLE MENU DROPDOWN (Surat & Laporan Smooth Slide)
         ========================================================= */
      /* Eliminate legacy bullet dot completely */
      .navbar-menu .navbar-nav .nav-sm .nav-link:before,
      .navbar-menu .navbar-nav .sub-menu-list .nav-link:before {
          display: none !important;
          content: none !important;
      }

      /* Dropdown chevron arrow on parent nav-link */
      .navbar-menu .navbar-nav .nav-link[data-bs-toggle="collapse"]:after {
          display: inline-flex !important;
          align-items: center !important;
          justify-content: center !important;
          content: "\f107" !important; /* Line Awesome angle-down */
          font-family: "Line Awesome Free" !important;
          font-weight: 900 !important;
          font-size: 13px !important;
          position: absolute !important;
          right: 14px !important;
          left: auto !important;
          color: var(--shadcn-sidebar-muted) !important;
          transform: rotate(-90deg) !important; /* points right when closed */
          transition: transform 0.28s cubic-bezier(0.25, 1, 0.5, 1), color 0.18s ease !important;
      }
      .navbar-menu .navbar-nav .nav-link[data-bs-toggle="collapse"][aria-expanded="true"]:after {
          transform: rotate(0deg) !important; /* points down when open */
          color: #ffffff !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu:not(:hover) .nav-link[data-bs-toggle="collapse"]:after {
          opacity: 0 !important;
          pointer-events: none !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu:hover .nav-link[data-bs-toggle="collapse"]:after {
          opacity: 1 !important;
          pointer-events: auto !important;
          transition: opacity 0.22s ease 0.08s, transform 0.28s cubic-bezier(0.25, 1, 0.5, 1) !important;
      }

      /* Bootstrap Collapsing & Show Smooth Transition */
      .navbar-menu .menu-dropdown.collapsing {
          display: block !important;
          height: 0;
          overflow: hidden !important;
          transition: height 0.28s cubic-bezier(0.25, 1, 0.5, 1) !important;
      }
      .navbar-menu .menu-dropdown.collapse.show {
          display: block !important;
          height: auto !important;
          overflow: visible !important;
      }
      /* Only suppress dropdown when sidebar is in collapsed rail mode (not hovered) */
      [data-sidebar-size="sm-hover"] .navbar-menu:not(:hover) .menu-dropdown {
          display: none !important;
      }

      /* Submenu List Container & Items */
      .navbar-menu .navbar-nav .sub-menu-list {
          padding: 2px 0 4px 0 !important;
          margin: 0 !important;
          list-style: none !important;
          position: relative !important;
      }
      /* Subtle vertical branch guide line (tree view) */
      .navbar-menu .navbar-nav .sub-menu-list:before {
          content: "";
          position: absolute;
          left: 33px;
          top: 4px;
          bottom: 6px;
          width: 1px;
          background: rgba(255, 255, 255, 0.08);
      }
      .navbar-menu .navbar-nav .sub-menu-list .nav-item {
          margin-bottom: 2px !important;
      }
      .navbar-menu .navbar-nav .sub-menu-list .nav-link {
          padding: 0 12px 0 46px !important;
          height: 36px !important;
          margin: 1px 8px !important;
          border-radius: 6px !important;
          font-size: 13px !important;
          color: #8b949e !important;
          display: flex !important;
          align-items: center !important;
          transition: background-color 0.16s ease, color 0.16s ease !important;
          background: transparent !important;
          position: relative !important;
      }
      .navbar-menu .navbar-nav .sub-menu-list .nav-link:hover {
          color: #ffffff !important;
          background: rgba(255, 255, 255, 0.05) !important;
      }
      .navbar-menu .navbar-nav .sub-menu-list .nav-link.active {
          color: #38bdf8 !important;
          background: rgba(56, 189, 248, 0.12) !important;
          font-weight: 600 !important;
      }
      .navbar-menu .navbar-nav .sub-menu-list .sub-menu-icon {
          font-size: 16px !important;
          min-width: 20px !important;
          margin-right: 8px !important;
          color: inherit !important;
          display: inline-flex !important;
          align-items: center !important;
      }
      /* Staggered slide in for sub-items */
      .navbar-menu .menu-dropdown.collapsing .sub-menu-list .nav-item,
      .navbar-menu .menu-dropdown.show .sub-menu-list .nav-item {
          animation: shadcnSubItemSlideIn 0.24s cubic-bezier(0.16, 1, 0.3, 1) both;
      }
      @keyframes shadcnSubItemSlideIn {
          from {
              opacity: 0;
              transform: translateY(-4px);
          }
          to {
              opacity: 1;
              transform: translateY(0);
          }
      }

      /* Bottom: User Footer */
      .sidebar-user-footer {
          margin-top: auto;
          border-top: 1px solid var(--shadcn-sidebar-border);
          padding: 8px 0;
          background: var(--shadcn-sidebar-bg);
          box-sizing: border-box;
          overflow: visible !important;
          position: relative;
          z-index: 1005;
      }
      .user-trigger-btn {
          background: transparent !important;
          border: none !important;
          padding: 0 !important;
          margin: 0 8px !important;
          height: 44px !important;
          border-radius: 8px !important;
          color: var(--shadcn-sidebar-text) !important;
          display: flex !important;
          align-items: center !important;
          transition: background 0.18s ease !important;
          width: calc(100% - 16px) !important;
          overflow: hidden !important;
          cursor: pointer;
      }
      .user-trigger-btn:hover,
      .user-trigger-btn[aria-expanded="true"] {
          background: var(--shadcn-sidebar-hover-bg) !important;
      }
      .user-trigger-btn[aria-expanded="true"] .user-chevron {
          color: #ffffff !important;
          transform: rotate(180deg);
      }
      .user-chevron {
          transition: transform 0.22s cubic-bezier(0.16, 1, 0.3, 1), color 0.18s ease;
      }
      .user-avatar-badge {
          width: 34px;
          height: 34px;
          min-width: 34px;
          border-radius: 50%;
          background: linear-gradient(135deg, #2563eb, #38bdf8);
          color: #ffffff;
          display: flex;
          align-items: center;
          justify-content: center;
          font-weight: 700;
          font-size: 12px;
          box-shadow: 0 2px 6px rgba(0, 0, 0, 0.3);
          margin-left: 9px;
          flex-shrink: 0;
      }
      .user-avatar-badge.size-sm {
          width: 28px;
          height: 28px;
          min-width: 28px;
          font-size: 11px;
          margin-left: 0;
      }
      .user-meta {
          display: flex;
          align-items: center;
          justify-content: space-between;
          flex-grow: 1;
          margin-left: 10px;
          margin-right: 8px;
          white-space: nowrap;
          overflow: hidden;
          opacity: 1;
          transform: translateX(0);
          max-width: 170px;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      max-width 0.26s cubic-bezier(0.16, 1, 0.3, 1);
      }
      .user-name {
          font-size: 13px;
          font-weight: 600;
          color: #f0f6fc;
          line-height: 1.2;
      }
      .user-role-label {
          font-size: 11px;
          color: var(--shadcn-sidebar-muted);
          line-height: 1.2;
      }

      /* Dropdown Menu popover container */
      .shadcn-dropdown-menu {
          background: transparent !important;
          border: none !important;
          box-shadow: none !important;
          padding: 0 !important;
          margin-bottom: 8px !important;
          min-width: 230px !important;
          z-index: 1050 !important;
      }

      /* Inner card with shadow & backdrop blur */
      .shadcn-menu-inner {
          background: #161b22 !important;
          border: 1px solid var(--shadcn-sidebar-border) !important;
          border-radius: 12px !important;
          box-shadow: 0 14px 36px rgba(0, 0, 0, 0.65), 0 0 0 1px rgba(255, 255, 255, 0.06) !important;
          backdrop-filter: blur(12px) !important;
          -webkit-backdrop-filter: blur(12px) !important;
          overflow: hidden;
          transform-origin: bottom left;
          will-change: transform, opacity;
      }

      /* Entrance animation when opening (Masuk) */
      .shadcn-dropdown-menu.show:not(.is-closing) .shadcn-menu-inner {
          animation: shadcnMenuEnter 0.22s cubic-bezier(0.16, 1, 0.3, 1) forwards !important;
      }

      /* Exit animation when closing (Keluar) */
      .shadcn-dropdown-menu.is-closing {
          display: block !important;
          pointer-events: none !important;
      }
      .shadcn-dropdown-menu.is-closing .shadcn-menu-inner {
          animation: shadcnMenuExit 0.16s cubic-bezier(0.4, 0, 1, 1) forwards !important;
      }

      @keyframes shadcnMenuEnter {
          0% {
              opacity: 0;
              transform: translateY(10px) scale(0.94);
          }
          100% {
              opacity: 1;
              transform: translateY(0) scale(1);
          }
      }

      @keyframes shadcnMenuExit {
          0% {
              opacity: 1;
              transform: translateY(0) scale(1);
          }
          100% {
              opacity: 0;
              transform: translateY(8px) scale(0.95);
          }
      }

      /* Item styling & micro-interactions */
      .shadcn-dropdown-menu .shadcn-item {
          padding: 8px 12px !important;
          border-radius: 7px !important;
          font-size: 13px !important;
          font-weight: 500 !important;
          color: #c9d1d9 !important;
          transition: background 0.14s ease, color 0.14s ease, transform 0.14s ease !important;
      }
      .shadcn-dropdown-menu .shadcn-item:hover {
          background: var(--shadcn-sidebar-hover-bg) !important;
          color: #ffffff !important;
          transform: translateX(3px) !important;
      }
      .shadcn-dropdown-menu .shadcn-item.text-danger:hover {
          background: rgba(239, 68, 68, 0.14) !important;
          color: #f87171 !important;
          transform: translateX(3px) !important;
      }

      /* Keep sidebar expanded when user dropdown is active */
      [data-sidebar-size="sm-hover"] .navbar-menu.user-dropdown-active {
          width: 250px !important;
          box-shadow: 14px 0 38px rgba(0, 0, 0, 0.6) !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu.user-dropdown-active .org-info,
      [data-sidebar-size="sm-hover"] .navbar-menu.user-dropdown-active .user-meta,
      [data-sidebar-size="sm-hover"] .navbar-menu.user-dropdown-active .menu-text {
          opacity: 1 !important;
          transform: translateX(0) !important;
          max-width: 170px !important;
          pointer-events: auto !important;
      }

      /* =========================================================
         SM-HOVER STATE MANAGEMENT (Zero Jumping, Butter-Smooth Glide)
         ========================================================= */
      [data-sidebar-size="sm-hover"] .navbar-menu {
          width: 68px !important;
      }
      /* Collapsed: Hide label text smoothly without display:none */
      [data-sidebar-size="sm-hover"] .navbar-menu .org-info,
      [data-sidebar-size="sm-hover"] .navbar-menu .user-meta,
      [data-sidebar-size="sm-hover"] .navbar-menu .menu-text {
          opacity: 0 !important;
          transform: translateX(-12px) !important;
          max-width: 0 !important;
          pointer-events: none !important;
          transition: opacity 0.15s ease, transform 0.15s ease, max-width 0.22s ease !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu .sidebar-badge-beta {
          opacity: 0 !important;
          transform: scale(0.7) !important;
          max-width: 0 !important;
          pointer-events: none !important;
          transition: opacity 0.15s ease, transform 0.15s ease !important;
      }

      /* Expanded: Glide in smoothly with 60ms delay */
      [data-sidebar-size="sm-hover"] .navbar-menu:hover {
          width: 250px !important;
          box-shadow: 14px 0 38px rgba(0, 0, 0, 0.6) !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu:hover .org-info,
      [data-sidebar-size="sm-hover"] .navbar-menu:hover .user-meta,
      [data-sidebar-size="sm-hover"] .navbar-menu:hover .menu-text {
          opacity: 1 !important;
          transform: translateX(0) !important;
          max-width: 170px !important;
          pointer-events: auto !important;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      max-width 0.26s cubic-bezier(0.16, 1, 0.3, 1) !important;
      }
      [data-sidebar-size="sm-hover"] .navbar-menu:hover .sidebar-badge-beta {
          opacity: 1 !important;
          transform: scale(1) !important;
          max-width: 45px !important;
          pointer-events: auto !important;
          transition: opacity 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s,
                      transform 0.22s cubic-bezier(0.16, 1, 0.3, 1) 0.06s !important;
      }

      /* Clean No-Header Layout (Modern Shadcn Dashboard) */
      #page-topbar {
          display: none !important;
          height: 0 !important;
          min-height: 0 !important;
      }
      .main-content {
          margin-top: 0 !important;
      }
      .page-content {
          padding-top: 24px !important;
      }

      .error-help-block{
        color: #ca131e;
    }
    #loading-overlay {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(26, 29, 33, 0.7);
        display: flex;
        justify-content: center;
        align-items: center;
        z-index: 9999;
    }
    .spinner-border{
        width: 7rem;
        height: 7rem;
    }
  </style>
  </head>

  {{-- batas --}}
  <body style="background-color: #1a1d21; color-scheme: dark;">
    @include('sweetalert::alert')
    <!-- Begin page -->
    <div id="layout-wrapper">
        {{-- Hidden Logout Form for Bottom Sidebar Profile --}}
        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
            @csrf
        </form>

      <div id="removeNotificationModal" class="modal fade zoomIn" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" id="NotificationModalbtn-close"></button>
                </div>

            </div><!-- /.modal-content -->
        </div><!-- /.modal-dialog -->
      </div><!-- /.modal -->
      <!-- ========== App Menu (SHADCN STYLE SIDEBAR) ========== -->
      <div class="app-menu navbar-menu shadcn-sidebar">
        <!-- TOP: Organization Header (like shadcn SessionNavBar) -->
        <div class="sidebar-org-box">
            <div class="dropdown w-100">
                <button type="button" class="btn org-trigger-btn d-flex align-items-center w-100" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="org-avatar-badge">
                        <img src="{{asset('images/LogoTKB.png')}}" alt="Tokabe Logo" class="org-avatar-img">
                    </div>
                    <div class="org-info">
                        <div class="text-start overflow-hidden">
                            <span class="org-name text-truncate d-block">Tokabe</span>
                            <span class="org-badge-pro">STORE</span>
                        </div>
                        <i class="ri-expand-up-down-line org-chevron ms-auto text-muted"></i>
                    </div>
                </button>
                <ul class="dropdown-menu dropdown-menu-dark shadcn-dropdown-menu shadow-lg">
                    <li><h6 class="dropdown-header text-muted text-uppercase fs-11">Organisasi</h6></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ url('/welcomeTKB') }}"><i class="las la-house-damage fs-16"></i> Dashboard Tokabe</a></li>
                    @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('user.index') }}"><i class="las la-users-cog fs-16"></i> Kelola Karyawan</a></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2" href="{{ route('rekening.index') }}"><i class="las la-wallet fs-16"></i> Rekening Toko</a></li>
                    @endif
                    <li><hr class="dropdown-divider border-secondary opacity-25"></li>
                    <li><a class="dropdown-item d-flex align-items-center gap-2 text-info" href="{{ route('list_invoice_tokabe') }}"><i class="ri-file-list-3-line fs-16"></i> Semua Transaksi</a></li>
                </ul>
            </div>
        </div>

        <!-- MIDDLE: Navigation Items (ScrollArea) -->
        <div id="scrollbar" class="sidebar-scroll-area">
            <div class="container-fluid p-0">
                <div id="two-column-menu"></div>
                <ul class="navbar-nav" id="navbar-nav">
                    @if (Auth::check() && (Auth::user()->role == 'Admin' || Auth::user()->role == 'Pemilik'))
                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('welcomeTKB*') ? 'active' : '' }}" href="{{ url('/welcomeTKB') }}">
                            <span class="icon-box"><i class="las la-tachometer-alt menu-icon"></i></span>
                            <span class="menu-text" data-key="t-invoices">Dashboard</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ (Request::is('list-invoice/tokabe*') || Request::is('list_invoice*') || Request::is('addInvoiceTokabe')) && !Request::is('*custom*') ? 'active' : '' }}" href="{{ route('list_invoice_tokabe') }}">
                            <span class="icon-box"><i class="las la-file-invoice-dollar menu-icon"></i></span>
                            <span class="menu-text" data-key="t-invoices">Invoice Keluar</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('list-invoice/custom*') || Request::is('addInvoiceTokabeCustom*') ? 'active' : '' }}" href="{{ route('list_invoice_tokabe_custom') }}">
                            <span class="icon-box"><i class="las la-file-signature menu-icon"></i></span>
                            <span class="menu-text" data-key="t-invoices-custom">Invoice Keluar Custom</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('items*') ? 'active' : '' }}" href="{{ route('item.index') }}">
                            <span class="icon-box"><i class="las la-tags menu-icon"></i></span>
                            <span class="menu-text">Item</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('pembelian*') ? 'active' : '' }}" href="{{ route('pembelian.index') }}">
                            <span class="icon-box"><i class="las la-shopping-cart menu-icon"></i></span>
                            <span class="menu-text">Invoice Masuk</span>
                        </a>
                    </li>

                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ (Request::is('daftar-spb*') || Request::is('spj*')) ? 'active' : '' }}" href="#sidebarSurat" data-bs-toggle="collapse" role="button" aria-expanded="{{ (Request::is('daftar-spb*') || Request::is('spj*')) ? 'true' : 'false' }}" aria-controls="sidebarSurat">
                            <span class="icon-box"><i class="las la-envelope-open-text menu-icon"></i></span>
                            <span class="menu-text" data-key="t-invoices">Surat</span>
                        </a>
                        <div class="collapse menu-dropdown {{ (Request::is('daftar-spb*') || Request::is('spj*')) ? 'show' : '' }}" id="sidebarSurat">
                            <ul class="nav nav-sm flex-column sub-menu-list">
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('daftar-spb*') ? 'active' : '' }}" href="{{ route('daftar-spb') }}">
                                        <i class="ri-truck-line sub-menu-icon"></i> <span class="menu-text">SPB</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('spj*') ? 'active' : '' }}" href="{{ route('spj.index') }}">
                                        <i class="ri-mail-send-line sub-menu-icon"></i> <span class="menu-text">SPJ</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('*laporan*') || Request::is('daftar.pelanggan*') ? 'active' : '' }}" href="#sidebarLaporan" data-bs-toggle="collapse" role="button" aria-expanded="{{ Request::is('*laporan*') || Request::is('daftar.pelanggan*') ? 'true' : 'false' }}" aria-controls="sidebarLaporan">
                            <span class="icon-box"><i class="ri-bar-chart-box-line menu-icon"></i></span>
                            <span class="menu-text" data-key="t-invoices">Laporan</span>
                        </a>
                        <div class="collapse menu-dropdown {{ Request::is('*laporan*') || Request::is('daftar.pelanggan*') ? 'show' : '' }}" id="sidebarLaporan">
                            <ul class="nav nav-sm flex-column sub-menu-list">
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('daftar-laporan') ? 'active' : '' }}" href="{{ url('/daftar-laporan') }}">
                                        <i class="ri-file-chart-line sub-menu-icon"></i> <span class="menu-text">Laporan Penjualan</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('daftar-laporan/custom*') ? 'active' : '' }}" href="{{ route('laporan.custom') }}">
                                        <i class="ri-file-list-3-line sub-menu-icon"></i> <span class="menu-text">Laporan Invoice Custom</span>
                                        <span class="badge" style="background: rgba(124, 58, 237, 0.2); color: #C084FC; font-size: 10px; font-weight: 700; padding: 2px 6px; border-radius: 4px; margin-left: auto;">CUSTOM</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('*laporan.pembelian*') ? 'active' : '' }}" href="{{ route('laporan.pembelian') }}">
                                        <i class="ri-shopping-bag-line sub-menu-icon"></i> <span class="menu-text">Laporan Pembelian</span>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link menu-link {{ Request::is('daftar.pelanggan*') ? 'active' : '' }}" href="{{ route('daftar.pelanggan') }}">
                                        <i class="ri-file-user-line sub-menu-icon"></i> <span class="menu-text">Daftar Pelanggan</span>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </li>

                    @if (Auth::user()->role == 'Pemilik')
                    <div class="sidebar-divider"></div>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ (Request::is('rekening*') || Request::is('daftar-rekening*') || Request::is('tambah-rekening*')) ? 'active' : '' }}" href="{{ route('rekening.index') }}">
                            <span class="icon-box"><i class="las la-wallet menu-icon"></i></span>
                            <span class="menu-text">Rekening</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('user*') ? 'active' : '' }}" href="{{ route('user.index') }}">
                            <span class="icon-box"><i class="las la-users-cog menu-icon"></i></span>
                            <span class="menu-text">Karyawan</span>
                            <span class="badge sidebar-badge-beta">TIM</span>
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link menu-link {{ Request::is('budget*') ? 'active' : '' }}" href="{{ route('budget.list') }}">
                            <span class="icon-box"><i class="las la-coins menu-icon"></i></span>
                            <span class="menu-text">Anggaran</span>
                        </a>
                    </li>
                    @endif
                    @endif
                </ul>
            </div>
        </div>

        <!-- BOTTOM: User Account Footer (like shadcn SessionNavBar footer) -->
        <div class="sidebar-user-footer">
            <div class="dropup w-100">
                <button type="button" class="btn user-trigger-btn d-flex align-items-center w-100" data-bs-toggle="dropdown" data-bs-offset="[0, 10]" aria-expanded="false">
                    <div class="user-avatar-badge">
                        <span class="avatar-fallback">{{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}</span>
                    </div>
                    <div class="user-meta">
                        <div class="text-truncate overflow-hidden text-start">
                            <span class="user-name text-truncate d-block">{{ Auth::user()->nama ?? 'User' }}</span>
                            <span class="user-role-label text-truncate d-block">{{ Auth::user()->role ?? 'Admin' }}</span>
                        </div>
                        <i class="ri-expand-up-down-line user-chevron ms-auto text-muted"></i>
                    </div>
                </button>
                <div class="dropdown-menu dropdown-menu-dark shadcn-dropdown-menu">
                    <div class="shadcn-menu-inner">
                        <div class="px-3 py-2 border-bottom border-secondary border-opacity-25">
                            <div class="d-flex align-items-center gap-2">
                                <div class="user-avatar-badge size-sm">
                                    <span class="avatar-fallback">{{ strtoupper(substr(Auth::user()->nama ?? 'U', 0, 1)) }}</span>
                                </div>
                                <div class="text-start overflow-hidden">
                                    <span class="fw-semibold text-light fs-13 d-block text-truncate">{{ Auth::user()->nama ?? 'User' }}</span>
                                    <span class="text-muted fs-11 d-block text-truncate">{{ Auth::user()->role ?? 'Admin' }}</span>
                                </div>
                            </div>
                        </div>
                        <div class="p-1">
                            <a class="dropdown-item shadcn-item d-flex align-items-center gap-2 mt-1" href="{{ route('auth.profile') }}">
                                <i class="las la-user-circle fs-16 text-primary"></i>
                                <span>Profil</span>
                            </a>
                            <div class="dropdown-divider my-1 border-secondary opacity-25"></div>
                            <a class="dropdown-item shadcn-item d-flex align-items-center gap-2 text-danger" href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                <i class="las la-sign-out-alt fs-16"></i>
                                <span>Keluar</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="sidebar-background"></div>
      </div>
      <!-- Left Sidebar End -->
      <!-- Vertical Overlay-->
      <div class="vertical-overlay"></div>
      <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
        @yield('content')

        @include('layout.footer')
        </div>

    </div>
    {{-- @extends('layout.customizer') --}}

    @extends('layout.vendor-script')

    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var hamburger = document.getElementById("topnav-hamburger-icon");
            if (hamburger) {
                hamburger.addEventListener("click", function (e) {
                    var html = document.documentElement;
                    var currentSize = html.getAttribute("data-sidebar-size");
                    var newSize = (currentSize === "sm-hover" || currentSize === "sm") ? "lg" : "sm-hover";
                    html.setAttribute("data-sidebar-size", newSize);
                    sessionStorage.setItem("data-sidebar-size", newSize);
                });
            }

            // Smooth entrance and exit animations for profile popup menu
            var userFooter = document.querySelector(".sidebar-user-footer");
            if (userFooter) {
                var toggleBtn = userFooter.querySelector('[data-bs-toggle="dropdown"]');
                var menuEl = userFooter.querySelector(".shadcn-dropdown-menu");
                var sidebar = document.querySelector(".navbar-menu");
                var dropContainer = userFooter.querySelector(".dropup, .dropdown");
                var isClosing = false;

                if (dropContainer && toggleBtn && menuEl) {
                    dropContainer.addEventListener("show.bs.dropdown", function () {
                        if (sidebar) sidebar.classList.add("user-dropdown-active");
                    });

                    dropContainer.addEventListener("hide.bs.dropdown", function (e) {
                        if (isClosing) {
                            return; // Allow the final hide to proceed smoothly
                        }

                        // Intercept dismissal to run smooth exit animation
                        e.preventDefault();
                        isClosing = true;
                        menuEl.classList.add("is-closing");

                        setTimeout(function () {
                            menuEl.classList.remove("is-closing");
                            if (sidebar) sidebar.classList.remove("user-dropdown-active");

                            var bsDropdown = bootstrap.Dropdown.getInstance(toggleBtn);
                            if (bsDropdown) {
                                bsDropdown.hide();
                            } else {
                                menuEl.classList.remove("show");
                            }
                            isClosing = false;
                        }, 160);
                    });

                    dropContainer.addEventListener("hidden.bs.dropdown", function () {
                        if (sidebar) sidebar.classList.remove("user-dropdown-active");
                    });
                }
            }
        });
    </script>

  </body>
</html>