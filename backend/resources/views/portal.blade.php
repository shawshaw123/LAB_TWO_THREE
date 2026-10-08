<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SIMS Portal - Student Information Management System</title>
    <!-- Modern Typography: Plus Jakarta Sans & JetBrains Mono -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
:root {
    /* Primary: Dark Green */
    --primary: #064e3b;
    --primary-hover: #022c22;
    --primary-light: #ecfdf5;

    /* Secondary: Amber Yellow */
    --secondary: #f59e0b;
    --secondary-hover: #d97706;
    --secondary-light: #fffbeb;
    --amber: #f59e0b;
    --amber-dark: #b45309;
    --amber-light: #fef3c7;

    /* Base Surfaces: Pure White & Subtle Off-White Canvas */
    --bg: #f8fafc;
    --surface: #ffffff;
    --surface-hover: #f1f5f9;
    --text-main: #0f172a;
    --text-muted: #475569;
    --text-light: #94a3b8;

    /* Borders & Accents */
    --border: #e2e8f0;
    --border-focus: #059669;
    --success: #059669;
    --success-light: #ecfdf5;
    --danger: #ef4444;
    --danger-light: #fef2f2;
    --warning: #f59e0b;
    --warning-light: #fffbeb;

    /* Elevation & Curves */
    --radius-sm: 6px;
    --radius-md: 10px;
    --radius-lg: 16px;
    --shadow-sm: 0 1px 2px 0 rgb(0 0 0 / 0.05);
    --shadow: 0 4px 6px -1px rgb(0 0 0 / 0.07), 0 2px 4px -2px rgb(0 0 0 / 0.07);
    --shadow-md: 0 10px 15px -3px rgb(0 0 0 / 0.08), 0 4px 6px -4px rgb(0 0 0 / 0.05);
    --shadow-lg: 0 20px 25px -5px rgb(0 0 0 / 0.1), 0 8px 10px -6px rgb(0 0 0 / 0.1);
}

* {
    box-sizing: border-box;
    margin: 0;
    padding: 0;
}

body {
    font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    background-color: var(--bg);
    color: var(--text-main);
    min-height: 100vh;
    line-height: 1.5;
    -webkit-font-smoothing: antialiased;
}

/* Top Navbar */
.navbar {
    background: rgba(255, 255, 255, 0.95);
    backdrop-filter: blur(12px);
    border-bottom: 1px solid var(--border);
    position: sticky;
    top: 0;
    z-index: 40;
    padding: 0 24px;
    height: 64px;
    display: flex;
    align-items: center;
}

.navbar-content {
    max-width: 1240px;
    width: 100%;
    margin: 0 auto;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.brand-logo {
    display: flex;
    align-items: center;
    gap: 12px;
    text-decoration: none;
    color: var(--text-main);
}

.brand-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #f59e0b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 800;
    font-size: 0.85rem;
    letter-spacing: -0.02em;
    box-shadow: 0 2px 8px rgba(6, 78, 59, 0.3);
    flex-shrink: 0;
}

.brand-text h1 {
    font-size: 1.1rem;
    font-weight: 800;
    letter-spacing: -0.025em;
    background: linear-gradient(135deg, #022c22 0%, #064e3b 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    line-height: 1.2;
}

.brand-text p {
    font-size: 0.75rem;
    color: var(--text-muted);
    font-weight: 500;
    line-height: 1.2;
}

.nav-actions {
    display: flex;
    align-items: center;
    gap: 12px;
}

.api-pill {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 0 10px;
    height: 32px;
    border-radius: 9999px;
    background: #ecfdf5;
    color: #064e3b;
    font-size: 0.75rem;
    font-weight: 600;
    border: 1px solid #a7f3d0;
    line-height: 1;
    box-sizing: border-box;
    white-space: nowrap;
}

.api-dot {
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: #059669;
    box-shadow: 0 0 6px #059669;
    flex-shrink: 0;
}

.nav-divider {
    width: 1px;
    height: 20px;
    background: var(--border);
    margin: 0 2px;
    flex-shrink: 0;
}

.user-profile-widget {
    display: inline-flex;
    align-items: center;
    gap: 10px;
}

.user-avatar {
    width: 32px;
    height: 32px;
    border-radius: 50%;
    background: #ecfdf5;
    color: #064e3b;
    font-size: 0.75rem;
    font-weight: 800;
    display: flex;
    align-items: center;
    justify-content: center;
    border: 1px solid #a7f3d0;
    flex-shrink: 0;
}

.user-name {
    font-weight: 700;
    font-size: 0.875rem;
    color: var(--text-main);
    white-space: nowrap;
    line-height: 1;
}

/* Container */
.container {
    max-width: 1240px;
    margin: 0 auto;
    padding: 24px;
}

/* Buttons */
.btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
    padding: 8px 16px;
    font-family: inherit;
    font-size: 0.875rem;
    font-weight: 600;
    border-radius: var(--radius-sm);
    border: 1px solid transparent;
    cursor: pointer;
    transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    text-decoration: none;
}

.btn-primary {
    background: var(--primary);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(6, 78, 59, 0.25);
}

.btn-primary:hover {
    background: var(--primary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(6, 78, 59, 0.35);
}

.btn-amber,
.btn-warning {
    background: var(--secondary);
    color: #ffffff;
    box-shadow: 0 2px 4px rgba(245, 158, 11, 0.25);
}

.btn-amber:hover,
.btn-warning:hover {
    background: var(--secondary-hover);
    transform: translateY(-1px);
    box-shadow: 0 4px 10px rgba(217, 119, 6, 0.35);
}

.btn-secondary {
    background: var(--surface);
    color: var(--text-main);
    border-color: var(--border);
}

.btn-secondary:hover {
    background: var(--surface-hover);
    border-color: #cbd5e1;
}

.btn-danger {
    background: var(--danger-light);
    color: var(--danger);
    border-color: #fecaca;
}

.btn-danger:hover {
    background: var(--danger);
    color: white;
}

.btn-sm {
    padding: 0 12px;
    height: 32px;
    font-size: 0.8rem;
    border-radius: var(--radius-sm);
    line-height: 1;
    box-sizing: border-box;
    white-space: nowrap;
}

/* Cards */
.card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 24px;
    box-shadow: var(--shadow-sm);
    transition: box-shadow 0.2s ease;
    margin-bottom: 24px;
}

.card:hover {
    box-shadow: var(--shadow);
}

.card-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 18px;
}

.card-title {
    font-size: 1.125rem;
    font-weight: 700;
    color: var(--text-main);
}

/* Stats Grid */
.stats-grid {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
    margin-bottom: 24px;
}

.stat-card {
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 4px;
    box-shadow: var(--shadow-sm);
    border-top: 3px solid var(--primary);
    /* Dark Green */
}

.stat-card:nth-child(2) {
    border-top-color: #f59e0b;
}

/* Amber Yellow */
.stat-card:nth-child(3) {
    border-top-color: #047857;
}

/* Emerald Dark Green */
.stat-card:nth-child(4) {
    border-top-color: #d97706;
}

/* Deep Amber */
.stat-val {
    font-size: 1.8rem;
    font-weight: 800;
    color: var(--text-main);
    line-height: 1.2;
}

.stat-label {
    font-size: 0.75rem;
    font-weight: 600;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
}

/* Modern Tabs */
.nav-tabs {
    display: flex;
    gap: 6px;
    background: #f1f5f9;
    padding: 6px;
    border-radius: var(--radius-md);
    margin-bottom: 24px;
    overflow-x: auto;
}

.tab-button {
    padding: 10px 18px;
    border-radius: var(--radius-sm);
    font-family: inherit;
    font-weight: 600;
    font-size: 0.875rem;
    color: var(--text-muted);
    background: transparent;
    border: none;
    cursor: pointer;
    transition: all 0.15s ease;
    white-space: nowrap;
}

.tab-button:hover {
    color: var(--primary);
}

.tab-button.active {
    background: var(--surface);
    color: var(--primary);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
    border-bottom: 2px solid var(--secondary);
    /* Amber Yellow Indicator */
}

/* Data Tables */
.table-responsive {
    overflow-x: auto;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}

table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.875rem;
    text-align: left;
}

thead {
    background: #f8fafc;
    border-bottom: 1px solid var(--border);
}

th {
    padding: 12px 16px;
    font-size: 0.75rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.05em;
    color: var(--text-muted);
}

tbody tr {
    border-bottom: 1px solid var(--border);
    transition: background-color 0.15s ease;
}

tbody tr:last-child {
    border-bottom: none;
}

tbody tr:hover {
    background-color: #f8fafc;
}

td {
    padding: 14px 16px;
    color: var(--text-main);
    vertical-align: middle;
}

/* Badges */
.badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    gap: 4px;
    padding: 0 8px;
    height: 22px;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.025em;
    text-transform: uppercase;
    line-height: 1;
    box-sizing: border-box;
    white-space: nowrap;
}

.badge-indigo {
    background: #ecfdf5;
    color: #064e3b;
    border: 1px solid #a7f3d0;
}

/* Dark Green */
.badge-emerald {
    background: #d1fae5;
    color: #065f46;
    border: 1px solid #6ee7b7;
}

/* Green */
.badge-amber {
    background: #fef3c7;
    color: #92400e;
    border: 1px solid #fde68a;
}

/* Amber Yellow */
.badge-slate {
    background: #f1f5f9;
    color: #475569;
    border: 1px solid #e2e8f0;
}

/* Slate */
.badge-rose {
    background: #ffe4e6;
    color: #9f1239;
    border: 1px solid #fecdd3;
}

/* Rose */

/* Forms & Inputs */
.form-group {
    margin-bottom: 16px;
}

label {
    display: block;
    font-size: 0.8rem;
    font-weight: 600;
    margin-bottom: 6px;
    color: var(--text-muted);
}

input,
select,
textarea {
    width: 100%;
    padding: 9px 13px;
    font-family: inherit;
    font-size: 0.875rem;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    color: var(--text-main);
    transition: all 0.15s ease;
}

input:focus,
select:focus,
textarea:focus {
    outline: none;
    border-color: var(--border-focus);
    box-shadow: 0 0 0 3px rgba(5, 150, 105, 0.2);
}

.grid-2 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
    gap: 16px;
}

.grid-3 {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
    gap: 16px;
}

/* Filter Toolbar */
.filter-toolbar {
    display: flex;
    flex-wrap: wrap;
    gap: 12px;
    align-items: center;
    margin-bottom: 18px;
    padding: 14px;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}

.filter-toolbar input,
.filter-toolbar select {
    width: auto;
    min-width: 180px;
}

/* Login Screen Card */
.auth-container {
    max-width: 900px;
    margin: 40px auto;
    display: grid;
    grid-template-columns: 1fr 1fr;
    background: var(--surface);
    border: 1px solid var(--border);
    border-radius: var(--radius-lg);
    overflow: hidden;
    box-shadow: var(--shadow-lg);
}

@media (max-width: 768px) {
    .auth-container {
        grid-template-columns: 1fr;
    }
}

.auth-welcome {
    background: linear-gradient(135deg, #022c22 0%, #064e3b 50%, #047857 100%);
    color: white;
    padding: 40px;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
}

.auth-welcome h2 {
    font-size: 1.8rem;
    font-weight: 800;
    line-height: 1.2;
    margin-bottom: 12px;
}

.auth-welcome p {
    color: #e6fffa;
    font-size: 0.9rem;
    line-height: 1.6;
}

.auth-form-side {
    padding: 40px;
}

.auth-nav-tabs {
    display: flex;
    gap: 6px;
    background: #f1f5f9;
    padding: 4px;
    border-radius: var(--radius-sm);
    margin-bottom: 20px;
    border: 1px solid var(--border);
}

.auth-nav-tab {
    flex: 1;
    padding: 8px 12px;
    font-size: 0.8rem;
    font-weight: 700;
    text-align: center;
    border: none;
    background: transparent;
    color: var(--text-muted);
    border-radius: var(--radius-sm);
    cursor: pointer;
    transition: all 0.15s ease;
}

.auth-nav-tab:hover {
    color: var(--primary);
}

.auth-nav-tab.active {
    background: var(--surface);
    color: var(--primary);
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.08);
}

.quick-role-btn {
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 10px 14px;
    border: 1px solid var(--border);
    border-radius: var(--radius-sm);
    background: var(--surface);
    cursor: pointer;
    transition: all 0.15s ease;
    width: 100%;
    text-align: left;
    margin-bottom: 8px;
}

.quick-role-btn:hover {
    border-color: var(--primary);
    background: var(--primary-light);
    transform: translateX(3px);
}

/* Toast Notifications */
#toast-container {
    position: fixed;
    top: 24px;
    right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 10px;
    pointer-events: none;
}

.toast {
    pointer-events: auto;
    min-width: 300px;
    max-width: 420px;
    padding: 14px 18px;
    border-radius: var(--radius-md);
    background: var(--surface);
    box-shadow: var(--shadow-lg);
    border-left: 4px solid var(--primary);
    display: flex;
    align-items: center;
    gap: 12px;
    font-size: 0.875rem;
    animation: slideIn 0.3s cubic-bezier(0.16, 1, 0.3, 1);
    transition: all 0.3s ease;
}

.toast-indicator {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: var(--primary);
    flex-shrink: 0;
}

.toast-success {
    border-left-color: var(--success);
}

.toast-success .toast-indicator {
    background: var(--success);
}

.toast-danger {
    border-left-color: var(--danger);
}

.toast-danger .toast-indicator {
    background: var(--danger);
}

@keyframes slideIn {
    from {
        transform: translateX(100%);
        opacity: 0;
    }

    to {
        transform: translateX(0);
        opacity: 1;
    }
}

/* Modal Dialog */
.modal-overlay {
    position: fixed;
    inset: 0;
    background: rgba(15, 23, 42, 0.5);
    backdrop-filter: blur(4px);
    z-index: 100;
    display: flex;
    align-items: center;
    justify-content: center;
    padding: 20px;
    opacity: 0;
    pointer-events: none;
    transition: opacity 0.2s ease;
}

.modal-overlay.active {
    opacity: 1;
    pointer-events: auto;
}

.modal-box {
    background: var(--surface);
    border-radius: var(--radius-lg);
    width: 100%;
    max-width: 650px;
    max-height: 90vh;
    overflow-y: auto;
    padding: 28px;
    box-shadow: var(--shadow-lg);
    transform: translateY(20px) scale(0.97);
    transition: transform 0.2s ease;
}

.modal-overlay.active .modal-box {
    transform: translateY(0) scale(1);
}

/* Code & Monospace helpers */
.mono {
    font-family: 'JetBrains Mono', monospace;
    font-size: 0.85em;
}

.info-box {
    padding: 16px;
    background: #f8fafc;
    border: 1px solid var(--border);
    border-radius: var(--radius-md);
}

.info-box-label {
    font-size: 0.75rem;
    text-transform: uppercase;
    font-weight: 700;
    color: var(--text-muted);
    margin-bottom: 4px;
}

.modal-actions {
    display: flex;
    justify-content: flex-end;
    gap: 10px;
    margin-top: 24px;
}

/* Empty states */
.empty-state {
    text-align: center;
    padding: 48px 20px;
    color: var(--text-muted);
}

.empty-icon {
    font-size: 1.1rem;
    font-weight: 700;
    margin-bottom: 8px;
    color: var(--text-muted);
}

/* ========================================================
   HOVER EXPANDABLE SIDEBAR NAVIGATOR
   Theme: White, Dark Green & Amber Yellow
   ======================================================== */
.hover-sidebar {
    position: fixed;
    top: 0;
    left: 0;
    height: 100vh;
    width: 68px;
    background: #022c22;
    /* Deep Dark Green */
    color: #e2e8f0;
    z-index: 100;
    display: none;
    flex-direction: column;
    justify-content: space-between;
    transition: width 0.3s cubic-bezier(0.4, 0, 0.2, 1), box-shadow 0.3s ease;
    overflow: hidden;
    white-space: nowrap;
    border-right: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 4px 0 20px rgba(0, 0, 0, 0.35);
}

.hover-sidebar:hover {
    width: 256px;
    box-shadow: 14px 0 35px -5px rgba(0, 0, 0, 0.55);
}

.sidebar-brand {
    height: 64px;
    display: flex;
    align-items: center;
    padding: 0 16px;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    gap: 14px;
    flex-shrink: 0;
}

.sidebar-logo-icon {
    width: 36px;
    height: 36px;
    border-radius: var(--radius-md);
    background: linear-gradient(135deg, #064e3b 0%, #047857 50%, #f59e0b 100%);
    display: flex;
    align-items: center;
    justify-content: center;
    color: #ffffff;
    font-weight: 800;
    font-size: 0.85rem;
    flex-shrink: 0;
    box-shadow: 0 2px 8px rgba(245, 158, 11, 0.35);
}

.sidebar-brand-text {
    opacity: 0;
    transform: translateX(-10px);
    transition: opacity 0.25s ease, transform 0.25s ease;
    overflow: hidden;
}

.hover-sidebar:hover .sidebar-brand-text {
    opacity: 1;
    transform: translateX(0);
}

.sidebar-brand-title {
    font-weight: 800;
    font-size: 0.95rem;
    color: #ffffff;
    letter-spacing: -0.01em;
}

.sidebar-brand-subtitle {
    font-size: 0.68rem;
    color: #f59e0b;
    /* Amber Yellow */
    text-transform: uppercase;
    letter-spacing: 0.06em;
    font-weight: 700;
}

.sidebar-menu {
    flex: 1;
    padding: 16px 8px;
    display: flex;
    flex-direction: column;
    gap: 6px;
    overflow-y: auto;
    overflow-x: hidden;
}

.sidebar-section-title {
    font-size: 0.65rem;
    text-transform: uppercase;
    letter-spacing: 0.08em;
    color: #a7f3d0;
    font-weight: 700;
    padding: 10px 10px 4px 10px;
    opacity: 0;
    transition: opacity 0.2s ease;
}

.hover-sidebar:hover .sidebar-section-title {
    opacity: 0.85;
}

.sidebar-link {
    display: flex;
    align-items: center;
    gap: 14px;
    padding: 11px 14px;
    border-radius: var(--radius-md);
    color: #cbd5e1;
    text-decoration: none;
    font-weight: 600;
    font-size: 0.85rem;
    cursor: pointer;
    background: transparent;
    border: none;
    width: 100%;
    text-align: left;
    transition: all 0.2s ease;
    position: relative;
}

.sidebar-link:hover {
    color: #ffffff;
    background: rgba(255, 255, 255, 0.1);
}

.sidebar-link.active {
    color: #ffffff;
    background: linear-gradient(90deg, rgba(245, 158, 11, 0.22) 0%, rgba(6, 78, 59, 0.4) 100%);
    border-left: 3px solid #f59e0b;
    /* Amber Yellow Accent */
}

.sidebar-link.active .sidebar-icon {
    color: #f59e0b;
    /* Amber Yellow Active Icon */
}

.sidebar-icon {
    width: 24px;
    height: 24px;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    color: inherit;
}

.sidebar-link-label {
    opacity: 0;
    transform: translateX(-8px);
    transition: opacity 0.25s ease, transform 0.25s ease;
    white-space: nowrap;
}

.hover-sidebar:hover .sidebar-link-label {
    opacity: 1;
    transform: translateX(0);
}

.sidebar-footer {
    padding: 14px 10px;
    border-top: 1px solid rgba(255, 255, 255, 0.1);
    display: flex;
    flex-direction: column;
    gap: 8px;
    flex-shrink: 0;
    background: rgba(2, 44, 34, 0.85);
    /* Deep Dark Green */
}

.sidebar-user-card {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 6px;
    border-radius: var(--radius-md);
}

.sidebar-user-avatar {
    width: 34px;
    height: 34px;
    border-radius: 50%;
    background: #064e3b;
    /* Dark Green */
    color: #fbbf24;
    /* Amber Gold Initial */
    font-weight: 700;
    font-size: 0.85rem;
    display: flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
    border: 2px solid #f59e0b;
    /* Amber Yellow border ring */
}

.sidebar-user-meta {
    opacity: 0;
    transition: opacity 0.2s ease;
    overflow: hidden;
}

.hover-sidebar:hover .sidebar-user-meta {
    opacity: 1;
}

.sidebar-user-name {
    font-size: 0.82rem;
    font-weight: 700;
    color: #ffffff;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    max-width: 155px;
}

.sidebar-user-role {
    font-size: 0.68rem;
    color: #f59e0b;
    /* Amber Yellow */
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.04em;
}

body.has-sidebar {
    padding-left: 68px;
    transition: padding-left 0.2s ease;
}

body.has-sidebar .navbar {
    left: 68px;
    width: calc(100% - 68px);
    transition: left 0.2s ease, width 0.2s ease;
}

@media (max-width: 768px) {
    body.has-sidebar {
        padding-left: 0 !important;
    }

    body.has-sidebar .navbar {
        left: 0 !important;
        width: 100% !important;
    }

    .hover-sidebar {
        display: none !important;
    }
}
    </style>
</head>
<body>
<!-- Toast Notifications Area -->
    <div id="toast-container"></div>

    <!-- HOVER EXPANDABLE SIDEBAR NAVIGATOR (For Admin, Registrar, Instructor) -->
    <aside id="hover-sidebar" class="hover-sidebar" aria-label="Sidebar Navigation">
        <div>
            <div class="sidebar-brand">
                <div class="sidebar-logo-icon">SIMS</div>
                <div class="sidebar-brand-text">
                    <div class="sidebar-brand-title">SIMS Portal</div>
                    <div class="sidebar-brand-subtitle" id="sidebar-role-subtitle">Workspace</div>
                </div>
            </div>

            <nav class="sidebar-menu" id="sidebar-menu-links">
                <!-- Dynamically populated in App.buildSidebarNavigation(role) -->
            </nav>
        </div>

        <div class="sidebar-footer">
            <a href="http://127.0.0.1:8000/api/docs" target="_blank" class="sidebar-link" title="Swagger API Docs" style="color: #cbd5e1;">
                <span class="sidebar-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>
                </span>
                <span class="sidebar-link-label">Swagger API Docs</span>
            </a>

            <button type="button" class="sidebar-link" onclick="App.openConfigApiModal()" title="Configure API Base URL" style="color: #cbd5e1;">
                <span class="sidebar-icon">
                    <span class="api-dot" style="margin: 0 auto;"></span>
                </span>
                <span class="sidebar-link-label" style="display: flex; align-items: center; gap: 6px;">
                    <span style="font-size: 0.72rem; color: #a7f3d0;">API:</span>
                    <span id="sb-api-base-url" class="mono" style="font-size: 0.72rem; color: #fef3c7; overflow: hidden; text-overflow: ellipsis; max-width: 140px;">/api/v1</span>
                </span>
            </button>

            <div class="sidebar-user-card" style="margin-top: 4px; border-top: 1px solid rgba(255, 255, 255, 0.08); padding-top: 10px;">
                <div class="sidebar-user-avatar" id="sidebar-avatar">U</div>
                <div class="sidebar-user-meta">
                    <div class="sidebar-user-name" id="sidebar-username">User</div>
                    <div class="sidebar-user-role" id="sidebar-role">Role</div>
                </div>
            </div>
            <button type="button" class="sidebar-link" onclick="App.logout()" style="color: #f87171;" title="Logout">
                <span class="sidebar-icon">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"></path><polyline points="16 17 21 12 16 7"></polyline><line x1="21" y1="12" x2="9" y2="12"></line></svg>
                </span>
                <span class="sidebar-link-label">Sign Out</span>
            </button>
        </div>
    </aside>

    <!-- Top Navigation Bar -->
    <nav class="navbar">
        <div class="navbar-content">
            <div class="brand-logo">
                <div class="brand-icon">SIMS</div>
                <div class="brand-text">
                    <h1>SIMS Portal</h1>
                    <p>Student Information Management System</p>
                </div>
            </div>

            <div class="nav-actions">
                <div id="user-profile-widget" class="user-profile-widget" style="display: none;">
                    <div class="user-avatar" id="user-avatar">U</div>
                    <span id="user-display-name" class="user-name"></span>
                    <span id="user-display-role" class="badge badge-indigo"></span>
                    <button type="button" class="btn btn-secondary btn-sm" onclick="App.logout()">Logout</button>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">

        <!-- SCREEN 1: MODERN LOGIN & ROLE SWITCHER -->
        <div id="login-view">
            <div class="auth-container">
                <div class="auth-welcome">
                    <div>
                        <div class="badge badge-indigo" style="background: rgba(255,255,255,0.2); color: #fff; margin-bottom: 16px;">Laravel 12 REST API Client</div>
                        <h2>Academic Portal Access</h2>
                        <p style="margin-top: 14px;">Welcome to the Student Information Management System. This client communicates natively with authenticated <code class="mono" style="background: rgba(255,255,255,0.15); padding: 2px 6px; border-radius: 4px;">/api/v1</code> endpoints.</p>
                    </div>

                    <div style="display: none;">
                        <div id="backend-url-display"></div>
                    </div>
                </div>

                <div class="auth-form-side">
                    <!-- Auth Mode Toggle -->
                    <div class="auth-nav-tabs">
                        <button type="button" id="tab-auth-login" class="auth-nav-tab active" onclick="App.showAuthTab('login')">Sign In</button>
                        <button type="button" id="tab-auth-register" class="auth-nav-tab" onclick="App.showAuthTab('register')">Register as Student</button>
                    </div>

                    <!-- SIGN IN PANEL -->
                    <div id="auth-panel-login">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">Sign In</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 24px;">Enter your credentials or use a 1-click test role below.</p>

                        <form id="login-form" onsubmit="App.login(event)">
                            <div class="form-group">
                                <label>Email Address</label>
                                <input type="email" id="login-email" value="admin@example.com" required placeholder="user@example.com">
                            </div>
                            <div class="form-group">
                                <label>Password</label>
                                <input type="password" id="login-password" value="Password123!" required>
                            </div>
                            <button type="submit" id="login-submit-btn" class="btn btn-primary" style="width: 100%; margin-top: 8px;">Sign In to Portal</button>
                        </form>

                        <div style="margin-top: 24px; padding-top: 20px; border-top: 1px solid var(--border);">
                            <div style="font-size: 0.75rem; font-weight: 700; text-transform: uppercase; color: var(--text-light); margin-bottom: 12px; letter-spacing: 0.05em;">One-Click Test Accounts:</div>
                            <button type="button" class="quick-role-btn" onclick="App.quickLogin('admin@example.com')">
                                <div>
                                    <strong style="font-size: 0.85rem;">Juan Dela Cruz (Administrator)</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">admin@example.com</div>
                                </div>
                                <span class="badge badge-indigo">Admin</span>
                            </button>
                            <button type="button" class="quick-role-btn" onclick="App.quickLogin('registrar@example.com')">
                                <div>
                                    <strong style="font-size: 0.85rem;">Maria Clara Santos (Registrar)</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">registrar@example.com</div>
                                </div>
                                <span class="badge badge-emerald">Registrar</span>
                            </button>
                            <button type="button" class="quick-role-btn" onclick="App.quickLogin('instructor@example.com')">
                                <div>
                                    <strong style="font-size: 0.85rem;">Prof. Jose Rizal</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">instructor@example.com</div>
                                </div>
                                <span class="badge badge-amber">Instructor</span>
                            </button>
                            <button type="button" class="quick-role-btn" onclick="App.quickLogin('student@example.com')">
                                <div>
                                    <strong style="font-size: 0.85rem;">Crisostomo Ibarra (Student)</strong>
                                    <div style="font-size: 0.75rem; color: var(--text-muted);">student@example.com</div>
                                </div>
                                <span class="badge badge-slate">Student</span>
                            </button>
                        </div>
                    </div>

                    <!-- STUDENT SELF-REGISTRATION PANEL -->
                    <div id="auth-panel-register" style="display: none;">
                        <h3 style="font-size: 1.25rem; font-weight: 700; margin-bottom: 8px;">Create Student Account</h3>
                        <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 20px;">Register your official student profile to access academic transcripts and courses.</p>

                        <form id="student-register-form" onsubmit="App.registerStudent(event)">
                            <div class="form-group">
                                <label>Full Name *</label>
                                <input type="text" id="reg-name" required placeholder="e.g. Maria Clara Santos">
                            </div>
                            <div class="form-group">
                                <label>Email Address *</label>
                                <input type="email" id="reg-email" required placeholder="student@example.com">
                            </div>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Password *</label>
                                    <input type="password" id="reg-password" required minlength="8" placeholder="Min 8 chars">
                                </div>
                                <div class="form-group">
                                    <label>Confirm Password *</label>
                                    <input type="password" id="reg-password-confirmation" required minlength="8" placeholder="Repeat password">
                                </div>
                            </div>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Degree Program *</label>
                                    <select id="reg-program" required>
                                        <option value="">Loading programs...</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label>Year Level *</label>
                                    <select id="reg-year-level" required>
                                        <option value="1">1st Year</option>
                                        <option value="2">2nd Year</option>
                                        <option value="3">3rd Year</option>
                                        <option value="4">4th Year</option>
                                        <option value="5">5th Year</option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label>Student Number (Optional)</label>
                                <input type="text" id="reg-student-number" placeholder="Leave blank to auto-generate (e.g. 2026-XXXXX)">
                                <small style="font-size: 0.72rem; color: var(--text-muted);">Leave empty to auto-assign official sequential student number.</small>
                            </div>
                            <div class="grid-2">
                                <div class="form-group">
                                    <label>Birth Date</label>
                                    <input type="date" id="reg-birth-date" value="2005-06-15">
                                </div>
                                <div class="form-group">
                                    <label>Contact Phone</label>
                                    <input type="text" id="reg-phone" placeholder="+63 912 345 6789">
                                </div>
                            </div>
                            <button type="submit" id="register-submit-btn" class="btn btn-primary" style="width: 100%; margin-top: 8px;">
                                Register & Access Portal
                            </button>
                        </form>

                        <div style="margin-top: 18px; text-align: center;">
                            <button type="button" class="btn btn-secondary btn-sm" onclick="App.showAuthTab('login')" style="width: 100%;">
                                Already have an account? Sign In
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- SCREEN 2: MAIN AUTHENTICATED DASHBOARD -->
        <div id="main-view" style="display: none;">

            <!-- Role Context Banner -->
            <div id="role-context-banner" style="background: white; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 18px 24px; margin-bottom: 24px; display: flex; justify-content: space-between; align-items: center; box-shadow: var(--shadow-sm); flex-wrap: wrap; gap: 12px;">
                <div>
                    <div style="display: flex; align-items: center; gap: 10px;">
                        <h2 id="role-context-title" style="font-size: 1.2rem; font-weight: 800; color: var(--text-main);">Administrator Console</h2>
                        <span id="role-context-badge" class="badge badge-indigo">ADMIN</span>
                    </div>
                    <p id="role-context-desc" style="font-size: 0.85rem; color: var(--text-muted); margin-top: 4px;">
                        Full system administration and access to all managed resources.
                    </p>
                </div>
                <div id="role-context-tag" class="badge badge-slate" style="font-size: 0.75rem;">
                    REST API Session Active
                </div>
            </div>

            <!-- Overview Statistics Banner -->
            <div class="stats-grid">
                <div class="stat-card">
                    <div class="stat-val" id="stat-val-1">105</div>
                    <div class="stat-label" id="stat-lbl-1">Total Students</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" id="stat-val-2">4</div>
                    <div class="stat-label" id="stat-lbl-2">Degree Programs</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" id="stat-val-3">22</div>
                    <div class="stat-label" id="stat-lbl-3">Active Courses</div>
                </div>
                <div class="stat-card">
                    <div class="stat-val" id="stat-val-4">24</div>
                    <div class="stat-label" id="stat-lbl-4">Course Offerings</div>
                </div>
            </div>

            <!-- Tab Navigation Bar (Built dynamically per role) -->
            <div class="nav-tabs" id="nav-tabs-bar">
                <button type="button" class="tab-button active" onclick="App.switchTab('students')">
                    Students Directory
                </button>
                <button type="button" class="tab-button" onclick="App.switchTab('academic')">
                    Programs & Curriculum
                </button>
                <button type="button" class="tab-button" onclick="App.switchTab('offerings')">
                    Offerings & Enrollments
                </button>
                <button type="button" class="tab-button" onclick="App.switchTab('grades')">
                    Grades & Academic Record
                </button>
            </div>

            <!-- TAB 1: STUDENTS DIRECTORY -->
            <div id="tab-students" class="tab-content">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Students Registry</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Manage student profiles, program assignments, and academic status.</p>
                        </div>
                        <button type="button" id="btn-add-student" class="btn btn-primary" onclick="App.openCreateStudentModal()">
                            + Register Student
                        </button>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="filter-toolbar">
                        <div style="flex: 1; min-width: 240px; position: relative;">
                            <input type="text" id="st-search" placeholder="Search name or student number..." oninput="App.debounceSearch()">
                        </div>
                        <select id="st-prog-filter" onchange="App.loadStudents(1)">
                            <option value="">All Programs</option>
                        </select>
                        <select id="st-status-filter" onchange="App.loadStudents(1)">
                            <option value="">All Statuses</option>
                            <option value="ACTIVE">ACTIVE</option>
                            <option value="INACTIVE">INACTIVE</option>
                        </select>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="App.resetStudentFilters()">Reset</button>
                    </div>

                    <!-- Students Table -->
                    <div class="table-responsive">
                        <table id="students-table">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Full Name</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="empty-state">Loading students data...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination Controls -->
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-top: 18px; padding-top: 12px; border-top: 1px solid var(--border);">
                        <div id="st-page-info" style="font-size: 0.85rem; color: var(--text-muted); font-weight: 500;"></div>
                        <div style="display: flex; gap: 8px;">
                            <button type="button" id="st-prev-btn" class="btn btn-secondary btn-sm" onclick="App.loadStudents(App.state.stPage - 1)">Previous</button>
                            <span id="st-page-pill" class="badge badge-slate" style="align-self: center;">Page 1</span>
                            <button type="button" id="st-next-btn" class="btn btn-secondary btn-sm" onclick="App.loadStudents(App.state.stPage + 1)">Next</button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 2: ACADEMIC REFERENCE DATA -->
            <div id="tab-academic" class="tab-content" style="display: none;">
                <div class="grid-2">
                    <!-- Programs Card -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Academic Programs</div>
                                <span class="badge badge-indigo">Degree Levels</span>
                            </div>
                            <button type="button" id="btn-add-program" class="btn btn-secondary btn-sm" onclick="App.openCreateProgramModal()">
                                + Add Program
                            </button>
                        </div>
                        <div class="table-responsive">
                            <table id="programs-table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Program Name</th>
                                        <th>Students</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Courses Card -->
                    <div class="card">
                        <div class="card-header">
                            <div>
                                <div class="card-title">Course Curriculum</div>
                                <span class="badge badge-indigo">Units & Codes</span>
                            </div>
                            <button type="button" id="btn-add-course" class="btn btn-secondary btn-sm" onclick="App.openCreateCourseModal()">
                                + Add Course
                            </button>
                        </div>
                        <div class="table-responsive" style="max-height: 480px; overflow-y: auto;">
                            <table id="courses-table">
                                <thead>
                                    <tr>
                                        <th>Code</th>
                                        <th>Course Title</th>
                                        <th>Units</th>
                                    </tr>
                                </thead>
                                <tbody></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Academic Terms Card -->
                <div class="card" style="margin-top: 24px;">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Academic Terms & Semesters</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Manage academic school years, semester terms, and active academic periods.</p>
                        </div>
                        <button type="button" id="btn-add-term" class="btn btn-primary btn-sm" onclick="App.openCreateTermModal()">
                            + Add Academic Term
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="terms-table">
                            <thead>
                                <tr>
                                    <th>Academic Year</th>
                                    <th>Term / Semester</th>
                                    <th>Start Date</th>
                                    <th>End Date</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="empty-state">Loading academic terms...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 3: OFFERINGS & ENROLLMENTS -->
            <div id="tab-offerings" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Course Offerings & Section Schedules</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Current active sections, instructor assignments, capacities, and enrolled rosters.</p>
                        </div>
                        <button type="button" id="btn-add-offering" class="btn btn-primary btn-sm" onclick="App.openCreateOfferingModal()">
                            + Create Course Offering
                        </button>
                    </div>
                    <div class="table-responsive">
                        <table id="offerings-table">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Course</th>
                                    <th>Schedule & Room</th>
                                    <th>Instructor</th>
                                    <th>Enrollment</th>
                                    <th style="text-align: right;">Action</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <!-- Quick Enrollment Form -->
                <div class="card" id="enrollment-management-card">
                    <div class="card-header">
                        <div class="card-title">Enroll Student into Course Offering</div>
                    </div>
                    <form onsubmit="App.enrollStudent(event)">
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Select Student *</label>
                                <select id="enr-student-select" required></select>
                            </div>
                            <div class="form-group">
                                <label>Select Offering Section *</label>
                                <select id="enr-offering-select" required></select>
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Process Enrollment</button>
                    </form>
                </div>
            </div>

            <!-- TAB 4: GRADES & TRANSCRIPT -->
            <div id="tab-grades" class="tab-content" style="display: none;">
                <!-- Official Transcript Generator -->
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Official Student Academic Record (Transcript)</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Lookup aggregated academic record grouped by semester with GWA calculation.</p>
                        </div>
                    </div>

                    <div style="display: flex; gap: 12px; margin-bottom: 20px; align-items: flex-end;">
                        <div class="form-group" style="margin-bottom: 0; flex: 1; max-width: 320px;">
                            <label>Student Record ID</label>
                            <input type="number" id="transcript-st-id" placeholder="e.g. 1" value="1">
                        </div>
                        <button type="button" class="btn btn-primary" onclick="App.loadTranscript()">Retrieve Official Record</button>
                    </div>

                    <div id="transcript-result" style="display: none; background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 24px;">
                        <div id="transcript-header" style="border-bottom: 2px solid var(--border); padding-bottom: 16px; margin-bottom: 20px;"></div>
                        <div id="transcript-tables"></div>
                    </div>
                </div>

                <!-- Encode Grade Card -->
                <div class="card" id="grade-encoding-card">
                    <div class="card-header">
                        <div class="card-title">Encode or Update Grade</div>
                        <span class="badge badge-amber">Authorized Workflow</span>
                    </div>
                    <form onsubmit="App.submitGrade(event)">
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Select Enrolled Student *</label>
                                <select id="grade-enr-id" required onchange="App.onAdminEnrollmentSelect(this.value)">
                                    <option value="">Select Enrolled Student...</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Midterm Grade (1.00 - 5.00)</label>
                                <input type="number" step="0.25" min="1.00" max="5.00" id="grade-midterm" value="1.75">
                            </div>
                            <div class="form-group">
                                <label>Final Grade (1.00 - 5.00) *</label>
                                <input type="number" step="0.25" min="1.00" max="5.00" id="grade-final" value="1.50" required>
                            </div>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Remarks *</label>
                                <select id="grade-remarks">
                                    <option value="PASSED">PASSED</option>
                                    <option value="FAILED">FAILED</option>
                                    <option value="INCOMPLETE">INCOMPLETE</option>
                                </select>
                            </div>
                            <div style="display:flex; align-items:flex-end; margin-bottom:16px;">
                                <button type="submit" class="btn btn-primary" style="height:38px;">Save Grade Submission</button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Recent Grades Table -->
                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Recent Grade Submissions</div>
                    </div>
                    <div class="table-responsive">
                        <table id="grades-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Midterm</th>
                                    <th>Final</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5: INSTRUCTOR ASSIGNED OFFERINGS -->
            <div id="tab-instructor-offerings" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">My Assigned Course Offerings</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Current active sections assigned for your instruction, schedule, room, and enrolled class rosters.</p>
                        </div>
                        <span class="badge badge-amber">Faculty Roster</span>
                    </div>
                    <div class="table-responsive">
                        <table id="instructor-offerings-table">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Course Code & Title</th>
                                    <th>Units</th>
                                    <th>Schedule & Room</th>
                                    <th>Enrolled / Capacity</th>
                                    <th style="text-align: right;">Class Roster</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="6" class="empty-state">Loading assigned course offerings...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 5B: INSTRUCTOR STUDENTS (MY STUDENTS) -->
            <div id="tab-instructor-students" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">My Enrolled Students</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Directory of students currently enrolled in course offerings you teach. Scoped strictly to your faculty assignment.</p>
                        </div>
                        <span class="badge badge-amber">Assigned Students Only</span>
                    </div>

                    <!-- Search & Filter Controls -->
                    <div class="filter-toolbar">
                        <div style="flex: 1; min-width: 240px;">
                            <input type="text" id="inst-st-search" placeholder="Search my students by name or student number..." oninput="App.debounceInstructorStudentSearch()">
                        </div>
                        <select id="inst-st-prog-filter" onchange="App.loadInstructorStudents()">
                            <option value="">All Degree Programs</option>
                        </select>
                        <button type="button" class="btn btn-secondary btn-sm" onclick="App.resetInstructorStudentFilters()">Reset</button>
                    </div>

                    <!-- Instructor Students Table -->
                    <div class="table-responsive">
                        <table id="instructor-students-table">
                            <thead>
                                <tr>
                                    <th>Student Number</th>
                                    <th>Full Name</th>
                                    <th>Program</th>
                                    <th>Year Level</th>
                                    <th>Email</th>
                                    <th>Status</th>
                                    <th style="text-align: right;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="7" class="empty-state">Loading enrolled students...</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 6: INSTRUCTOR GRADE MANAGEMENT -->
            <div id="tab-instructor-grades" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Encode or Update Student Grade</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Submit midterm and final grades for authorized enrollments in your assigned sections.</p>
                        </div>
                        <span class="badge badge-amber">Authorized Grading</span>
                    </div>
                    <form onsubmit="App.submitInstructorGrade(event)">
                        <div class="grid-3">
                            <div class="form-group">
                                <label>Select Enrolled Student *</label>
                                <select id="inst-grade-enr-id" required onchange="App.onInstructorEnrollmentSelect(this.value)">
                                    <option value="">Select Enrolled Student...</option>
                                </select>
                                <small style="color: var(--text-muted); font-size: 0.75rem;">Only students in your assigned sections are listed</small>
                            </div>
                            <div class="form-group">
                                <label>Midterm Grade (1.00 - 5.00)</label>
                                <input type="number" step="0.25" min="1.00" max="5.00" id="inst-grade-midterm" value="1.75">
                            </div>
                            <div class="form-group">
                                <label>Final Grade (1.00 - 5.00) *</label>
                                <input type="number" step="0.25" min="1.00" max="5.00" id="inst-grade-final" value="1.50" required>
                            </div>
                        </div>
                        <div class="grid-2">
                            <div class="form-group">
                                <label>Remarks *</label>
                                <select id="inst-grade-remarks">
                                    <option value="PASSED">PASSED</option>
                                    <option value="FAILED">FAILED</option>
                                    <option value="INCOMPLETE">INCOMPLETE</option>
                                </select>
                            </div>
                            <div style="display:flex; align-items:flex-end; margin-bottom:16px;">
                                <button type="submit" class="btn btn-primary" style="height:38px;">Save Grade Submission</button>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card">
                    <div class="card-header">
                        <div class="card-title">Submitted Student Grades</div>
                        <span class="badge badge-slate">Faculty Records</span>
                    </div>
                    <div class="table-responsive">
                        <table id="instructor-grades-table">
                            <thead>
                                <tr>
                                    <th>Student</th>
                                    <th>Course</th>
                                    <th>Midterm</th>
                                    <th>Final</th>
                                    <th>Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="5" class="empty-state">Loading grades...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 7: STUDENT PROFILE -->
            <div id="tab-student-profile" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Student Profile & Academic Information</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Official student registration details from the University Registrar.</p>
                        </div>
                        <span id="st-self-status-badge" class="badge badge-emerald">ACTIVE</span>
                    </div>

                    <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 12px;">
                        <div class="info-box">
                            <div class="info-box-label">Student Number</div>
                            <div id="st-self-num" class="mono" style="font-size: 1.15rem; font-weight: 800; color: var(--primary);">--</div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-label">Full Legal Name</div>
                            <div id="st-self-name" style="font-size: 1.15rem; font-weight: 700;">--</div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-label">Degree Program</div>
                            <div id="st-self-prog" style="font-size: 1rem; font-weight: 700;">--</div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-label">Current Year Level</div>
                            <div id="st-self-year" style="font-size: 1rem; font-weight: 700;">--</div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-label">Primary Email</div>
                            <div id="st-self-email" style="font-size: 0.95rem; font-weight: 600;">--</div>
                        </div>
                        <div class="info-box">
                            <div class="info-box-label">Contact Number</div>
                            <div id="st-self-contact" style="font-size: 0.95rem; font-weight: 600;">--</div>
                        </div>
                        <div class="info-box" style="grid-column: 1 / -1;">
                            <div class="info-box-label">Registered Address</div>
                            <div id="st-self-address" style="font-size: 0.95rem;">--</div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- TAB 8: STUDENT ENROLLMENTS -->
            <div id="tab-student-enrollments" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">My Registered Course Offerings</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Current term class assignments, schedules, room assignments, and course units.</p>
                        </div>
                        <span class="badge badge-indigo">Enrolled Classes</span>
                    </div>
                    <div class="table-responsive">
                        <table id="student-enrollments-table">
                            <thead>
                                <tr>
                                    <th>Section</th>
                                    <th>Course Code</th>
                                    <th>Course Title</th>
                                    <th>Units</th>
                                    <th>Schedule & Room</th>
                                    <th>Term</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr><td colspan="7" class="empty-state">Loading registered courses...</td></tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            <!-- TAB 9: STUDENT TRANSCRIPT & GRADES -->
            <div id="tab-student-transcript" class="tab-content" style="display: none;">
                <div class="card">
                    <div class="card-header">
                        <div>
                            <div class="card-title">Official Student Academic Record & Grades</div>
                            <p style="font-size: 0.85rem; color: var(--text-muted);">Aggregated semester transcripts, unit completions, and cumulative grade point average.</p>
                        </div>
                        <span class="badge badge-emerald">Verified Transcript</span>
                    </div>

                    <div id="st-self-transcript-header" style="background: #f8fafc; border: 1px solid var(--border); border-radius: var(--radius-md); padding: 20px; margin-bottom: 20px;"></div>
                    <div id="st-self-transcript-tables"></div>
                </div>
            </div>

        </div>
    </div>

    <!-- MODAL 1: REGISTER STUDENT MODAL -->
    <div id="student-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title" id="student-modal-title">Register New Student</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeStudentModal()">✕</button>
            </div>
            <form onsubmit="App.saveStudent(event)">
                <input type="hidden" id="editing-st-id" value="">
                <div class="grid-2">
                    <div class="form-group">
                        <label>Student Number *</label>
                        <input type="text" id="new-st-num" placeholder="e.g. 2026-99001" required>
                    </div>
                    <div class="form-group">
                        <label>Email Address *</label>
                        <input type="email" id="new-st-email" placeholder="student@example.com" required>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>First Name *</label>
                        <input type="text" id="new-st-fname" required>
                    </div>
                    <div class="form-group">
                        <label>Last Name *</label>
                        <input type="text" id="new-st-lname" required>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Academic Program *</label>
                        <select id="new-st-prog" required></select>
                    </div>
                    <div class="form-group">
                        <label>Year Level *</label>
                        <select id="new-st-year" required>
                            <option value="1">1st Year</option>
                            <option value="2">2nd Year</option>
                            <option value="3">3rd Year</option>
                            <option value="4">4th Year</option>
                        </select>
                    </div>
                </div>
                <div class="form-group">
                    <label>Birth Date *</label>
                    <input type="date" id="new-st-bdate" value="2004-05-15" required>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="App.closeStudentModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Save Student Record</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 2: CLASS ROSTER MODAL -->
    <div id="roster-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="card-header" style="margin-bottom: 18px;">
                <div>
                    <h3 class="card-title" id="roster-modal-title">Class Roster</h3>
                    <p id="roster-modal-subtitle" style="font-size: 0.85rem; color: var(--text-muted);"></p>
                </div>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeRosterModal()">✕</button>
            </div>
            <div class="table-responsive">
                <table id="roster-table">
                    <thead>
                        <tr>
                            <th>Student #</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
            <div style="text-align: right; margin-top: 18px;">
                <button type="button" class="btn btn-secondary" onclick="App.closeRosterModal()">Close</button>
            </div>
        </div>
    </div>

    <!-- MODAL 3: CONFIGURE API BASE URL MODAL -->
    <div id="config-api-modal" class="modal-overlay">
        <div class="modal-box" style="max-width: 480px;">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title">Configure Backend API URL</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeConfigApiModal()">✕</button>
            </div>
            <p style="font-size: 0.85rem; color: var(--text-muted); margin-bottom: 16px;">
                Specify the backend REST API base URL. This URL is used by the frontend network client for all HTTP requests.
            </p>
            <div class="form-group">
                <label>API Base URL *</label>
                <input type="text" id="config-api-url-input" class="mono" placeholder="http://127.0.0.1:8000/api/v1">
                <small style="color: var(--text-muted); font-size: 0.75rem;">Default: http://127.0.0.1:8000/api/v1 (or /api/v1 when served by Laravel)</small>
            </div>
            <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                <button type="button" class="btn btn-secondary" onclick="App.resetApiUrl()">Reset Default</button>
                <button type="button" class="btn btn-primary" onclick="App.saveApiUrl()">Save Configuration</button>
            </div>
        </div>
    </div>

    <!-- MODAL 4: ADD ACADEMIC TERM MODAL -->
    <div id="term-modal" class="modal-overlay">
        <div class="modal-box" style="max-width: 480px;">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title">Add Academic Term</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeTermModal()">✕</button>
            </div>
            <form onsubmit="App.saveAcademicTerm(event)">
                <div class="form-group">
                    <label>Academic Year * (Format: YYYY-YYYY)</label>
                    <input type="text" id="new-term-ay" placeholder="e.g. 2026-2027" required pattern="\d{4}-\d{4}">
                </div>
                <div class="form-group">
                    <label>Term / Semester *</label>
                    <select id="new-term-sem" required>
                        <option value="1st Semester">1st Semester</option>
                        <option value="2nd Semester">2nd Semester</option>
                        <option value="Summer">Summer</option>
                        <option value="Midyear">Midyear</option>
                    </select>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Start Date *</label>
                        <input type="date" id="new-term-start" required>
                    </div>
                    <div class="form-group">
                        <label>End Date *</label>
                        <input type="date" id="new-term-end" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Status</label>
                    <select id="new-term-status">
                        <option value="ACTIVE">ACTIVE</option>
                        <option value="INACTIVE">INACTIVE</option>
                    </select>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="App.closeTermModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Academic Term</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 5: ADD PROGRAM MODAL -->
    <div id="program-modal" class="modal-overlay">
        <div class="modal-box" style="max-width: 480px;">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title">Add Degree Program</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeProgramModal()">✕</button>
            </div>
            <form onsubmit="App.saveProgram(event)">
                <div class="form-group">
                    <label>Program Code * (e.g. BSIT)</label>
                    <input type="text" id="new-prog-code" required placeholder="e.g. BSDS">
                </div>
                <div class="form-group">
                    <label>Program Name *</label>
                    <input type="text" id="new-prog-name" required placeholder="e.g. Bachelor of Science in Data Science">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="new-prog-desc" rows="3" placeholder="Brief program description..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="App.closeProgramModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Program</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 6: ADD COURSE MODAL -->
    <div id="course-modal" class="modal-overlay">
        <div class="modal-box" style="max-width: 480px;">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title">Add Curriculum Course</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeCourseModal()">✕</button>
            </div>
            <form onsubmit="App.saveCourse(event)">
                <div class="grid-2">
                    <div class="form-group">
                        <label>Course Code *</label>
                        <input type="text" id="new-course-code" required placeholder="e.g. CS105">
                    </div>
                    <div class="form-group">
                        <label>Units *</label>
                        <input type="number" step="0.5" min="1" max="6" id="new-course-units" value="3.0" required>
                    </div>
                </div>
                <div class="form-group">
                    <label>Course Title *</label>
                    <input type="text" id="new-course-title" required placeholder="e.g. Advanced Database Systems">
                </div>
                <div class="form-group">
                    <label>Description</label>
                    <textarea id="new-course-desc" rows="3" placeholder="Course syllabus summary..."></textarea>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 20px;">
                    <button type="button" class="btn btn-secondary" onclick="App.closeCourseModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Create Course</button>
                </div>
            </form>
        </div>
    </div>

    <!-- MODAL 7: CREATE COURSE OFFERING MODAL -->
    <div id="offering-modal" class="modal-overlay">
        <div class="modal-box">
            <div class="card-header" style="margin-bottom: 18px;">
                <h3 class="card-title">Create Course Offering Section</h3>
                <button type="button" class="btn btn-secondary btn-sm" onclick="App.closeOfferingModal()">✕</button>
            </div>
            <form onsubmit="App.saveCourseOffering(event)">
                <div class="grid-2">
                    <div class="form-group">
                        <label>Course *</label>
                        <select id="new-off-course" required></select>
                    </div>
                    <div class="form-group">
                        <label>Academic Term *</label>
                        <select id="new-off-term" required></select>
                    </div>
                </div>
                <div class="grid-2">
                    <div class="form-group">
                        <label>Section Name *</label>
                        <input type="text" id="new-off-section" required placeholder="e.g. BSIT-3C">
                    </div>
                    <div class="form-group">
                        <label>Assigned Instructor *</label>
                        <select id="new-off-instructor" required></select>
                    </div>
                </div>
                <div class="grid-3">
                    <div class="form-group">
                        <label>Schedule *</label>
                        <input type="text" id="new-off-schedule" required placeholder="e.g. TTH 10:30 AM - 12:00 PM">
                    </div>
                    <div class="form-group">
                        <label>Room Assignment</label>
                        <input type="text" id="new-off-room" placeholder="e.g. Lab 402">
                    </div>
                    <div class="form-group">
                        <label>Max Capacity *</label>
                        <input type="number" id="new-off-capacity" min="5" max="100" value="40" required>
                    </div>
                </div>
                <div style="display: flex; justify-content: flex-end; gap: 10px; margin-top: 24px;">
                    <button type="button" class="btn btn-secondary" onclick="App.closeOfferingModal()">Cancel</button>
                    <button type="submit" class="btn btn-primary">Publish Offering</button>
                </div>
            </form>
        </div>
    </div>

    <!-- JAVASCRIPT FRONTEND API CLIENT ENGINE -->

    <!-- JAVASCRIPT FRONTEND API CLIENT ENGINE -->
    <script>
const ICONS = {
    students: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 0 0-3-3.87"></path><path d="M16 3.13a4 4 0 0 1 0 7.75"></path></svg>',
    academic: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M4 19.5A2.5 2.5 0 0 1 6.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 0 1 4 19.5v-15A2.5 2.5 0 0 1 6.5 2z"></path></svg>',
    offerings: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>',
    grades: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg>',
    chart: '<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="22 12 18 12 15 21 9 3 6 12 2 12"></polyline></svg>'
};

const App = {
    state: {
        token: localStorage.getItem('auth_token') || '',
        user: null,
        stPage: 1,
        programs: [],
        courses: [],
        terms: [],
        offerings: [],
        instructors: [],
        searchDebounceTimer: null,
        instSearchTimer: null,
        instructorEnrollments: [],
        adminEnrollments: []
    },

    // API Configuration
    getBaseUrl() {
        const custom = localStorage.getItem('api_base_url');
        if (custom?.trim()) return custom.trim().replace(/\/+$/, '');
        if (window.API_BASE_URL) return window.API_BASE_URL.replace(/\/+$/, '');
        return window.location.port === '8000' ? '/api/v1' : 'http://127.0.0.1:8000/api/v1';
    },

    updateApiUrlDisplays() {
        const url = this.getBaseUrl();
        ['backend-url-display', 'nav-api-base-url', 'sb-api-base-url'].forEach(id => {
            const el = document.getElementById(id);
            if (el) el.textContent = url;
        });
    },

    openModal(id) { document.getElementById(id)?.classList.add('active'); },
    closeModal(id) { document.getElementById(id)?.classList.remove('active'); },

    openConfigApiModal() {
        const input = document.getElementById('config-api-url-input');
        if (input) input.value = this.getBaseUrl();
        this.openModal('config-api-modal');
    },
    closeConfigApiModal() { this.closeModal('config-api-modal'); },

    saveApiUrl() {
        const val = document.getElementById('config-api-url-input')?.value.trim();
        if (!val) return this.showToast('Please provide a valid API Base URL.', 'danger');
        localStorage.setItem('api_base_url', val);
        this.updateApiUrlDisplays();
        this.closeConfigApiModal();
        this.showToast(`API Base URL updated: ${val}`, 'success');
    },

    resetApiUrl() {
        localStorage.removeItem('api_base_url');
        this.updateApiUrlDisplays();
        this.closeConfigApiModal();
        this.showToast('API Base URL reset to default.', 'success');
    },

    init() {
        this.updateApiUrlDisplays();
        this.loadRegistrationPrograms();
        if (this.state.token) this.fetchMe();
    },

    // HTTP Client
    async api(endpoint, method = 'GET', body = null) {
        const headers = { 'Accept': 'application/json', 'Content-Type': 'application/json' };
        if (this.state.token) headers['Authorization'] = `Bearer ${this.state.token}`;

        const clean = endpoint.startsWith('/') ? endpoint : `/${endpoint}`;
        try {
            const res = await fetch(`${this.getBaseUrl()}${clean}`, {
                method,
                headers,
                body: body ? JSON.stringify(body) : null
            });
            const data = await res.json().catch(() => ({}));
            if (!res.ok) throw data;
            return data;
        } catch (err) {
            if (err.status === 401 || err.message === 'Unauthenticated.') {
                this.logout();
            } else if (err instanceof TypeError || err.message?.toLowerCase().includes('failed to fetch')) {
                this.showToast('Backend REST API server is unreachable.', 'danger');
            }
            throw err;
        }
    },

    showToast(msg, type = 'success') {
        const container = document.getElementById('toast-container');
        if (!container) return;
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        toast.innerHTML = `
            <div class="toast-indicator"></div>
            <div style="flex: 1;">
                <div style="font-weight: 700; font-size: 0.85rem; margin-bottom: 2px;">
                    ${type === 'success' ? 'Success' : 'Notification'}
                </div>
                <div style="color: var(--text-muted); font-size: 0.8rem;">${msg}</div>
            </div>
        `;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(-10px)';
            setTimeout(() => toast.remove(), 300);
        }, 4000);
    },

    // Authentication
    showAuthTab(mode) {
        const loginBtn = document.getElementById('tab-auth-login');
        const regBtn = document.getElementById('tab-auth-register');
        const loginPanel = document.getElementById('auth-panel-login');
        const regPanel = document.getElementById('auth-panel-register');

        if (mode === 'register') {
            loginBtn?.classList.remove('active');
            regBtn?.classList.add('active');
            if (loginPanel) loginPanel.style.display = 'none';
            if (regPanel) regPanel.style.display = 'block';
            this.loadRegistrationPrograms();
        } else {
            regBtn?.classList.remove('active');
            loginBtn?.classList.add('active');
            if (regPanel) regPanel.style.display = 'none';
            if (loginPanel) loginPanel.style.display = 'block';
        }
    },

    async loadRegistrationPrograms() {
        const sel = document.getElementById('reg-program');
        if (!sel || (sel.options.length > 1 && !sel.options[0].textContent.includes('Loading'))) return;
        try {
            const res = await this.api('/programs?per_page=100');
            const progs = res.data || [];
            sel.innerHTML = '<option value="">Select Degree Program *</option>' +
                progs.map(p => `<option value="${p.id}">${p.code} - ${p.name}</option>`).join('');
        } catch (err) {
            console.error('Error loading programs for registration:', err);
        }
    },

    async registerStudent(e) {
        e.preventDefault();
        const btn = document.getElementById('register-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Creating Account...';

        const name = document.getElementById('reg-name').value.trim();
        const email = document.getElementById('reg-email').value.trim();
        const password = document.getElementById('reg-password').value;
        const password_confirmation = document.getElementById('reg-password-confirmation').value;
        const program_id = document.getElementById('reg-program').value;
        const year_level = document.getElementById('reg-year-level').value;
        const student_number = document.getElementById('reg-student-number').value.trim();
        const birth_date = document.getElementById('reg-birth-date').value;
        const phone = document.getElementById('reg-phone').value.trim();

        if (password !== password_confirmation) {
            btn.disabled = false;
            btn.innerText = 'Register & Access Portal';
            return this.showToast('Password confirmation does not match.', 'danger');
        }

        const payload = {
            name,
            email,
            password,
            password_confirmation,
            program_id: Number(program_id),
            year_level: Number(year_level)
        };
        if (student_number) payload.student_number = student_number;
        if (birth_date) payload.birth_date = birth_date;
        if (phone) payload.phone = phone;

        try {
            const res = await this.api('/auth/register', 'POST', payload);
            this.state.token = res.data.token;
            localStorage.setItem('auth_token', res.data.token);
            await this.fetchMe();
            this.showToast(`Account created! Welcome, ${res.data?.user?.name || name}`, 'success');
            document.getElementById('student-register-form').reset();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Registration failed. Please check form entries.', 'danger');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Register & Access Portal';
        }
    },

    quickLogin(email) {
        document.getElementById('login-email').value = email;
        document.getElementById('login-password').value = 'Password123!';
        document.getElementById('login-form').dispatchEvent(new Event('submit'));
    },

    async login(e) {
        e.preventDefault();
        const btn = document.getElementById('login-submit-btn');
        btn.disabled = true;
        btn.innerText = 'Signing In...';

        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;

        try {
            const res = await this.api('/auth/login', 'POST', { email, password });
            this.state.token = res.data.token;
            localStorage.setItem('auth_token', res.data.token);
            await this.fetchMe();
            this.showToast(`Authenticated as ${this.state.user?.name || 'User'}`, 'success');
        } catch (err) {
            this.showToast(err.message || 'Authentication failed. Please verify credentials.', 'danger');
        } finally {
            btn.disabled = false;
            btn.innerText = 'Sign In to Portal';
        }
    },

    async fetchMe() {
        try {
            const res = await this.api('/auth/me');
            this.state.user = res.data;
            this.showLoggedIn();
        } catch (err) {
            this.logout();
        }
    },

    logout() {
        if (this.state.token) this.api('/auth/logout', 'POST').catch(() => {});
        this.state.token = '';
        this.state.user = null;
        localStorage.removeItem('auth_token');

        const sb = document.getElementById('hover-sidebar');
        if (sb) sb.style.display = 'none';
        document.body.classList.remove('has-sidebar');

        document.getElementById('login-view').style.display = 'block';
        document.getElementById('main-view').style.display = 'none';
        document.getElementById('user-profile-widget').style.display = 'none';
        this.showToast('You have been logged out.');
    },

    showLoggedIn() {
        document.getElementById('login-view').style.display = 'none';
        document.getElementById('main-view').style.display = 'block';

        const u = this.state.user;
        if (!u) return;

        document.getElementById('user-profile-widget').style.display = 'inline-flex';
        document.getElementById('user-display-name').textContent = u.name;
        const initials = (u.name || 'U').split(' ').map(n => n[0]).join('').substring(0, 2).toUpperCase();
        document.getElementById('user-avatar').textContent = initials;
        document.getElementById('user-display-role').textContent = u.role.toUpperCase();

        const sidebar = document.getElementById('hover-sidebar');
        const hasSidebar = ['admin', 'registrar', 'instructor'].includes(u.role);
        if (sidebar) sidebar.style.display = hasSidebar ? 'flex' : 'none';
        document.body.classList.toggle('has-sidebar', hasSidebar);

        if (hasSidebar) {
            this.buildSidebarNavigation(u.role);
            document.getElementById('sidebar-avatar').textContent = initials;
            document.getElementById('sidebar-username').textContent = u.name;
            document.getElementById('sidebar-role').textContent = u.role.toUpperCase();
            const suites = { admin: 'Admin Suite', registrar: 'Registrar Suite', instructor: 'Faculty Suite' };
            document.getElementById('sidebar-role-subtitle').textContent = suites[u.role] || 'Workspace';
        }

        const bannerTitle = document.getElementById('role-context-title');
        const bannerBadge = document.getElementById('role-context-badge');
        const bannerDesc = document.getElementById('role-context-desc');

        if (u.role === 'admin') {
            bannerTitle.textContent = 'Administrator Console';
            bannerBadge.className = 'badge badge-indigo';
            bannerBadge.textContent = 'ADMINISTRATOR';
            bannerDesc.textContent = 'Full system administration and access to all managed resources.';
            this.buildNavigationTabs('admin');
            this.configureStaffView(true);
        } else if (u.role === 'registrar') {
            bannerTitle.textContent = 'Registrar Records Portal';
            bannerBadge.className = 'badge badge-emerald';
            bannerBadge.textContent = 'REGISTRAR / STAFF';
            bannerDesc.textContent = 'Manage students, academic records, programs, courses, and enrollments.';
            this.buildNavigationTabs('registrar');
            this.configureStaffView(false);
        } else if (u.role === 'instructor') {
            bannerTitle.textContent = 'Instructor Faculty Workspace';
            bannerBadge.className = 'badge badge-amber';
            bannerBadge.textContent = 'INSTRUCTOR';
            bannerDesc.textContent = 'View assigned course offerings and manage grades for authorized enrollments.';
            this.buildNavigationTabs('instructor');
            this.configureInstructorView();
        } else {
            bannerTitle.textContent = 'Student Academic Portal';
            bannerBadge.className = 'badge badge-slate';
            bannerBadge.textContent = 'STUDENT';
            bannerDesc.textContent = "View student profile, enrollments, and academic transcript.";
            this.buildNavigationTabs('student');
            this.configureStudentView();
        }
    },

    buildSidebarNavigation(role) {
        const container = document.getElementById('sidebar-menu-links');
        if (!container) return;
        container.innerHTML = '';

        const navConfigs = {
            admin: {
                title: 'Administration',
                items: [
                    { id: 'students', label: 'Students Directory', icon: ICONS.students },
                    { id: 'academic', label: 'Programs & Curriculum', icon: ICONS.academic },
                    { id: 'offerings', label: 'Course Offerings', icon: ICONS.offerings },
                    { id: 'grades', label: 'Grades & Records', icon: ICONS.grades }
                ]
            },
            registrar: {
                title: 'Registrar Portal',
                items: [
                    { id: 'students', label: 'Students Registry', icon: ICONS.students },
                    { id: 'academic', label: 'Curriculum & Terms', icon: ICONS.academic },
                    { id: 'offerings', label: 'Offerings & Enrollments', icon: ICONS.offerings },
                    { id: 'grades', label: 'Official Transcripts', icon: ICONS.grades }
                ]
            },
            instructor: {
                title: 'Faculty Workspace',
                items: [
                    { id: 'instructor-offerings', label: 'Assigned Classes', icon: ICONS.offerings },
                    { id: 'instructor-students', label: 'My Students', icon: ICONS.students },
                    { id: 'instructor-grades', label: 'Grade Management', icon: ICONS.chart }
                ]
            }
        };

        const config = navConfigs[role];
        if (!config) return;

        const secTitle = document.createElement('div');
        secTitle.className = 'sidebar-section-title';
        secTitle.textContent = config.title;
        container.appendChild(secTitle);

        config.items.forEach((item, idx) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `sidebar-link ${idx === 0 ? 'active' : ''}`;
            btn.dataset.tab = item.id;
            btn.title = item.label;
            btn.innerHTML = `<span class="sidebar-icon">${item.icon}</span><span class="sidebar-link-label">${item.label}</span>`;
            btn.onclick = () => this.switchTab(item.id);
            container.appendChild(btn);
        });
    },

    buildNavigationTabs(role) {
        const container = document.getElementById('nav-tabs-bar');
        container.innerHTML = '';

        const tabDefs = {
            admin: [
                { id: 'students', label: 'Students Directory' },
                { id: 'academic', label: 'Programs & Curriculum' },
                { id: 'offerings', label: 'Offerings & Enrollments' },
                { id: 'grades', label: 'Grades & Academic Record' }
            ],
            registrar: [
                { id: 'students', label: 'Students Directory' },
                { id: 'academic', label: 'Programs & Curriculum' },
                { id: 'offerings', label: 'Offerings & Enrollments' },
                { id: 'grades', label: 'Academic Records' }
            ],
            instructor: [
                { id: 'instructor-offerings', label: 'Assigned Offerings' },
                { id: 'instructor-students', label: 'My Enrolled Students' },
                { id: 'instructor-grades', label: 'Grade Management' }
            ],
            student: [
                { id: 'student-profile', label: 'Academic Profile' },
                { id: 'student-enrollments', label: 'My Enrolled Courses' },
                { id: 'student-transcript', label: 'My Grades & Transcript' }
            ]
        };

        (tabDefs[role] || []).forEach((tab, index) => {
            const btn = document.createElement('button');
            btn.type = 'button';
            btn.className = `tab-button ${index === 0 ? 'active' : ''}`;
            btn.textContent = tab.label;
            btn.onclick = (e) => this.switchTab(tab.id, e.currentTarget);
            container.appendChild(btn);
        });
    },

    switchTab(tabId, btnEl = null) {
        document.querySelectorAll('.tab-content').forEach(el => el.style.display = 'none');
        document.querySelectorAll('#nav-tabs-bar .tab-button').forEach(el => el.classList.remove('active'));
        document.querySelectorAll('#sidebar-menu-links .sidebar-link').forEach(el => el.classList.remove('active'));

        const target = document.getElementById(`tab-${tabId}`);
        if (target) target.style.display = 'block';

        const topButtons = Array.from(document.querySelectorAll('#nav-tabs-bar .tab-button'));
        const matchingTop = topButtons.find(b => b.textContent.toLowerCase().includes(tabId.replace('-', ' ')));
        if (matchingTop) matchingTop.classList.add('active');
        else if (btnEl?.classList.contains('tab-button')) btnEl.classList.add('active');
        else if (topButtons[0] && !matchingTop) topButtons[0].classList.add('active');

        const matchingSb = document.querySelector(`#sidebar-menu-links .sidebar-link[data-tab="${tabId}"]`);
        if (matchingSb) matchingSb.classList.add('active');

        if (tabId === 'academic') this.loadAcademic();
        else if (tabId === 'offerings') this.loadOfferings();
        else if (tabId === 'grades') this.loadGrades();
        else if (tabId === 'instructor-offerings') this.loadInstructorOfferings();
        else if (tabId === 'instructor-students') this.loadInstructorStudents();
        else if (tabId === 'instructor-grades') this.loadInstructorGrades();
        else if (tabId.startsWith('student-')) this.loadStudentSelfData();
    },

    configureStaffView(isAdmin) {
        document.getElementById('stat-lbl-1').textContent = isAdmin ? 'Total Students' : 'Registered Students';
        document.getElementById('stat-lbl-2').textContent = isAdmin ? 'Degree Programs' : 'Active Programs';
        document.getElementById('stat-lbl-3').textContent = isAdmin ? 'Active Courses' : 'Catalog Courses';
        document.getElementById('stat-lbl-4').textContent = isAdmin ? 'Course Offerings' : 'Active Offerings';

        document.getElementById('btn-add-student').style.display = 'inline-flex';
        document.getElementById('enrollment-management-card').style.display = 'block';
        document.getElementById('grade-encoding-card').style.display = isAdmin ? 'block' : 'none';

        this.switchTab('students');
        this.loadPrograms();
        this.loadStudents(1);
        this.loadAcademic();
        this.loadOfferings();
    },
    configureAdminView() { this.configureStaffView(true); },
    configureRegistrarView() { this.configureStaffView(false); },

    configureInstructorView() {
        ['Assigned Offerings', 'Enrolled Students', 'Final Grades Submitted', 'Teaching Status'].forEach((lbl, i) => {
            document.getElementById(`stat-lbl-${i + 1}`).textContent = lbl;
        });
        ['--', '--', '--', 'ACTIVE FACULTY'].forEach((val, i) => {
            document.getElementById(`stat-val-${i + 1}`).textContent = val;
        });
        this.switchTab('instructor-offerings');
        this.loadInstructorOfferings();
        this.loadInstructorStudents();
        this.loadInstructorGrades();
    },

    configureStudentView() {
        ['Cumulative GWA', 'Units Completed', 'Current Enrolled', 'Academic Standing'].forEach((lbl, i) => {
            document.getElementById(`stat-lbl-${i + 1}`).textContent = lbl;
        });
        ['--', '--', '--', 'GOOD STANDING'].forEach((val, i) => {
            document.getElementById(`stat-val-${i + 1}`).textContent = val;
        });
        this.switchTab('student-profile');
        this.loadStudentSelfData();
    },

    debounceSearch() {
        clearTimeout(this.state.searchDebounceTimer);
        this.state.searchDebounceTimer = setTimeout(() => this.loadStudents(1), 300);
    },

    resetStudentFilters() {
        document.getElementById('st-search').value = '';
        document.getElementById('st-prog-filter').value = '';
        document.getElementById('st-status-filter').value = '';
        this.loadStudents(1);
    },

    // Students Module
    async loadStudents(page = 1) {
        this.state.stPage = page;
        const search = document.getElementById('st-search').value;
        const prog = document.getElementById('st-prog-filter').value;
        const status = document.getElementById('st-status-filter').value;

        let q = `?page=${page}&per_page=10`;
        if (search) q += `&search=${encodeURIComponent(search)}`;
        if (prog) q += `&program_id=${prog}`;
        if (status) q += `&status=${status}`;

        try {
            const res = await this.api(`/students${q}`);
            const tb = document.querySelector('#students-table tbody');
            tb.innerHTML = '';

            const students = res.data || [];
            if (students.length === 0) {
                tb.innerHTML = `<tr><td colspan="7" class="empty-state"><div class="empty-icon">No Records Found</div>No student records match the filter.</td></tr>`;
            } else {
                students.forEach(s => {
                    tb.innerHTML += `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${s.student_number}</strong></td>
                            <td><strong>${s.full_name}</strong></td>
                            <td><span class="badge badge-indigo">${s.program?.code || 'N/A'}</span></td>
                            <td>Year ${s.year_level}</td>
                            <td style="color: var(--text-muted);">${s.email}</td>
                            <td><span class="badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${s.status}</span></td>
                            <td style="text-align: right;">
                                <button class="btn btn-secondary btn-sm" onclick="App.viewStudentTranscript(${s.id})">Transcript</button>
                                ${this.state.user?.role !== 'student' ? `
                                    <button class="btn btn-secondary btn-sm" onclick="App.openEditStudentModal(${s.id})">Edit</button>
                                    <button class="btn btn-danger btn-sm" onclick="App.deleteStudent(${s.id})">Delete</button>
                                ` : ''}
                            </td>
                        </tr>
                    `;
                });
            }

            const m = res.meta;
            document.getElementById('st-page-info').textContent = `Showing ${m.from || 0} to ${m.to || 0} of ${m.total} students`;
            document.getElementById('st-page-pill').textContent = `Page ${m.current_page} / ${m.last_page}`;
            document.getElementById('st-prev-btn').disabled = m.current_page <= 1;
            document.getElementById('st-next-btn').disabled = m.current_page >= m.last_page;
            const stat1 = document.getElementById('stat-val-1');
            if (stat1 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat1.textContent = m.total;
            }
        } catch (err) {
            this.showToast(err.message || 'Error loading students list', 'danger');
        }
    },

    openCreateStudentModal() {
        document.getElementById('editing-st-id').value = '';
        document.getElementById('student-modal-title').textContent = 'Register New Student';
        document.getElementById('new-st-num').value = '';
        document.getElementById('new-st-num').disabled = false;
        document.getElementById('new-st-email').value = '';
        document.getElementById('new-st-fname').value = '';
        document.getElementById('new-st-lname').value = '';
        document.getElementById('new-st-bdate').value = '2004-05-15';
        this.openModal('student-modal');
    },

    async openEditStudentModal(id) {
        try {
            const res = await this.api(`/students/${id}`);
            const s = res.data;
            document.getElementById('editing-st-id').value = s.id;
            document.getElementById('student-modal-title').textContent = `Edit Student: ${s.student_number}`;
            document.getElementById('new-st-num').value = s.student_number;
            document.getElementById('new-st-num').disabled = true;
            document.getElementById('new-st-email').value = s.email;
            document.getElementById('new-st-fname').value = s.first_name;
            document.getElementById('new-st-lname').value = s.last_name;
            document.getElementById('new-st-bdate').value = s.birth_date || '2004-05-15';
            if (s.program_id) document.getElementById('new-st-prog').value = s.program_id;
            if (s.year_level) document.getElementById('new-st-year').value = s.year_level;
            this.openModal('student-modal');
        } catch (err) {
            this.showToast(err.message || 'Failed to retrieve student record.', 'danger');
        }
    },
    closeStudentModal() { this.closeModal('student-modal'); },

    async saveStudent(e) {
        e.preventDefault();
        const editingId = document.getElementById('editing-st-id').value;
        const payload = {
            student_number: document.getElementById('new-st-num').value,
            email: document.getElementById('new-st-email').value,
            first_name: document.getElementById('new-st-fname').value,
            last_name: document.getElementById('new-st-lname').value,
            birth_date: document.getElementById('new-st-bdate').value,
            program_id: Number(document.getElementById('new-st-prog').value),
            year_level: Number(document.getElementById('new-st-year').value),
            status: 'ACTIVE'
        };

        try {
            if (editingId) {
                await this.api(`/students/${editingId}`, 'PUT', payload);
                this.showToast(`Student ${payload.student_number} updated!`, 'success');
            } else {
                await this.api('/students', 'POST', payload);
                this.showToast(`Student ${payload.student_number} registered!`, 'success');
            }
            this.closeStudentModal();
            this.loadStudents(this.state.stPage);
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Validation failed', 'danger');
        }
    },

    async deleteStudent(id) {
        if (!confirm('Are you sure you want to delete this student record?')) return;
        try {
            await this.api(`/students/${id}`, 'DELETE');
            this.showToast('Student record removed successfully.', 'success');
            this.loadStudents(this.state.stPage);
        } catch (err) {
            this.showToast(err.message || 'Failed to delete student.', 'danger');
        }
    },

    // Programs & Curriculum
    async loadPrograms() {
        try {
            const res = await this.api('/programs?per_page=100');
            this.state.programs = res.data || [];
            const sel1 = document.getElementById('new-st-prog');
            const sel2 = document.getElementById('st-prog-filter');
            if (sel1) sel1.innerHTML = '';
            if (sel2) sel2.innerHTML = '<option value="">All Programs</option>';

            this.state.programs.forEach(p => {
                if (sel1) sel1.innerHTML += `<option value="${p.id}">${p.code} - ${p.name}</option>`;
                if (sel2) sel2.innerHTML += `<option value="${p.id}">${p.code}</option>`;
            });
            const stat2 = document.getElementById('stat-val-2');
            if (stat2 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat2.textContent = this.state.programs.length;
            }
        } catch (e) {}
    },

    async loadAcademic() {
        try {
            const [prRes, crRes, termRes] = await Promise.all([
                this.api('/programs?per_page=100'),
                this.api('/courses?per_page=100'),
                this.api('/academic-terms?per_page=100')
            ]);

            this.state.programs = prRes.data || [];
            this.state.courses = crRes.data || [];
            this.state.terms = termRes.data || [];

            const pt = document.querySelector('#programs-table tbody');
            if (pt) {
                pt.innerHTML = this.state.programs.map(p => `
                    <tr>
                        <td><strong class="mono" style="color: var(--primary);">${p.code}</strong></td>
                        <td><strong>${p.name}</strong><br><small style="color: var(--text-muted);">${p.description || ''}</small></td>
                        <td><span class="badge badge-slate">${p.students_count || 0} enrolled</span></td>
                    </tr>
                `).join('');
            }

            const ct = document.querySelector('#courses-table tbody');
            if (ct) {
                ct.innerHTML = this.state.courses.map(c => `
                    <tr>
                        <td><strong class="mono" style="color: var(--primary);">${c.course_code}</strong></td>
                        <td>${c.course_title}</td>
                        <td><span class="badge badge-indigo">${c.units} units</span></td>
                    </tr>
                `).join('');
            }

            const tt = document.querySelector('#terms-table tbody');
            if (tt) {
                tt.innerHTML = this.state.terms.length === 0
                    ? '<tr><td colspan="5" class="empty-state">No academic terms recorded yet.</td></tr>'
                    : this.state.terms.map(t => `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${t.academic_year}</strong></td>
                            <td><strong>${t.term}</strong></td>
                            <td>${t.start_date}</td>
                            <td>${t.end_date}</td>
                            <td><span class="badge ${t.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${t.status}</span></td>
                        </tr>
                    `).join('');
            }

            const isStaff = ['admin', 'registrar'].includes(this.state.user?.role);
            if (isStaff) {
                document.getElementById('stat-val-2').textContent = this.state.programs.length;
                document.getElementById('stat-val-3').textContent = this.state.courses.length;
            }
        } catch (e) {}
    },

    openCreateTermModal() {
        document.getElementById('new-term-ay').value = '2026-2027';
        document.getElementById('new-term-sem').value = '1st Semester';
        document.getElementById('new-term-start').value = '2026-08-15';
        document.getElementById('new-term-end').value = '2026-12-20';
        document.getElementById('new-term-status').value = 'ACTIVE';
        this.openModal('term-modal');
    },
    closeTermModal() { this.closeModal('term-modal'); },

    async saveAcademicTerm(e) {
        e.preventDefault();
        const payload = {
            academic_year: document.getElementById('new-term-ay').value.trim(),
            term: document.getElementById('new-term-sem').value,
            start_date: document.getElementById('new-term-start').value,
            end_date: document.getElementById('new-term-end').value,
            status: document.getElementById('new-term-status').value
        };

        try {
            await this.api('/academic-terms', 'POST', payload);
            this.showToast(`Academic Term ${payload.academic_year} (${payload.term}) registered!`);
            this.closeTermModal();
            this.loadAcademic();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create academic term.', 'danger');
        }
    },

    openCreateProgramModal() {
        document.getElementById('new-prog-code').value = '';
        document.getElementById('new-prog-name').value = '';
        document.getElementById('new-prog-desc').value = '';
        this.openModal('program-modal');
    },
    closeProgramModal() { this.closeModal('program-modal'); },

    async saveProgram(e) {
        e.preventDefault();
        const payload = {
            code: document.getElementById('new-prog-code').value.trim().toUpperCase(),
            name: document.getElementById('new-prog-name').value.trim(),
            description: document.getElementById('new-prog-desc').value.trim(),
            status: 'ACTIVE'
        };

        try {
            await this.api('/programs', 'POST', payload);
            this.showToast(`Degree Program ${payload.code} created successfully!`);
            this.closeProgramModal();
            this.loadAcademic();
            this.loadPrograms();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create program.', 'danger');
        }
    },

    openCreateCourseModal() {
        document.getElementById('new-course-code').value = '';
        document.getElementById('new-course-title').value = '';
        document.getElementById('new-course-units').value = '3.0';
        document.getElementById('new-course-desc').value = '';
        this.openModal('course-modal');
    },
    closeCourseModal() { this.closeModal('course-modal'); },

    async saveCourse(e) {
        e.preventDefault();
        const payload = {
            course_code: document.getElementById('new-course-code').value.trim().toUpperCase(),
            course_title: document.getElementById('new-course-title').value.trim(),
            units: parseFloat(document.getElementById('new-course-units').value),
            description: document.getElementById('new-course-desc').value.trim(),
            status: 'ACTIVE'
        };

        try {
            await this.api('/courses', 'POST', payload);
            this.showToast(`Course ${payload.course_code} created!`);
            this.closeCourseModal();
            this.loadAcademic();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create course.', 'danger');
        }
    },

    openCreateOfferingModal() {
        const cSel = document.getElementById('new-off-course');
        const tSel = document.getElementById('new-off-term');
        const iSel = document.getElementById('new-off-instructor');

        if (cSel) cSel.innerHTML = (this.state.courses || []).map(c => `<option value="${c.id}">${c.course_code} - ${c.course_title}</option>`).join('');
        if (tSel) tSel.innerHTML = (this.state.terms || []).map(t => `<option value="${t.id}">${t.academic_year} (${t.term})</option>`).join('');
        if (iSel) {
            iSel.innerHTML = `
                <option value="3">Prof. Jose Rizal (Faculty)</option>
                <option value="4">Prof. Andres Bonifacio (Faculty)</option>
                <option value="5">Prof. Gabriela Silang (Faculty)</option>
            `;
            (this.state.instructors || []).forEach(inst => {
                if (![3, 4, 5].includes(Number(inst.id))) {
                    iSel.innerHTML += `<option value="${inst.id}">${inst.name}</option>`;
                }
            });
        }

        document.getElementById('new-off-section').value = '';
        document.getElementById('new-off-schedule').value = 'MWF 9:00 AM - 10:30 AM';
        document.getElementById('new-off-room').value = 'Lab 301';
        document.getElementById('new-off-capacity').value = '40';
        this.openModal('offering-modal');
    },
    closeOfferingModal() { this.closeModal('offering-modal'); },

    async saveCourseOffering(e) {
        e.preventDefault();
        const payload = {
            course_id: parseInt(document.getElementById('new-off-course').value),
            academic_term_id: parseInt(document.getElementById('new-off-term').value),
            instructor_id: parseInt(document.getElementById('new-off-instructor').value),
            section: document.getElementById('new-off-section').value.trim(),
            schedule: document.getElementById('new-off-schedule').value.trim(),
            room: document.getElementById('new-off-room').value.trim(),
            capacity: parseInt(document.getElementById('new-off-capacity').value),
            status: 'OPEN'
        };

        try {
            await this.api('/course-offerings', 'POST', payload);
            this.showToast(`Course Offering ${payload.section} created!`);
            this.closeOfferingModal();
            this.loadOfferings();
        } catch (err) {
            const msgs = err.errors ? Object.values(err.errors).flat().join(' ') : err.message;
            this.showToast(msgs || 'Failed to create course offering.', 'danger');
        }
    },

    // Offerings & Rosters
    async loadOfferings() {
        try {
            const [offRes, stRes] = await Promise.all([
                this.api('/course-offerings?per_page=100'),
                this.api('/students?per_page=100')
            ]);

            const ot = document.querySelector('#offerings-table tbody');
            const offSel = document.getElementById('enr-offering-select');
            if (ot) ot.innerHTML = '';
            if (offSel) offSel.innerHTML = '';

            const offerings = offRes.data || [];
            this.state.offerings = offerings;

            const instMap = new Map([[3, 'Prof. Jose Rizal'], [4, 'Prof. Andres Bonifacio'], [5, 'Prof. Gabriela Silang']]);
            offerings.forEach(o => {
                if (o.instructor?.id) instMap.set(o.instructor.id, o.instructor.name);
            });
            this.state.instructors = Array.from(instMap.entries()).map(([id, name]) => ({ id, name }));

            const stat4 = document.getElementById('stat-val-4');
            if (stat4 && ['admin', 'registrar'].includes(this.state.user?.role)) {
                stat4.textContent = offerings.length;
            }

            offerings.forEach(o => {
                const count = o.enrolled_count || 0;
                const pct = Math.round((count / o.capacity) * 100);
                if (ot) {
                    ot.innerHTML += `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${o.section}</strong></td>
                            <td><strong>${o.course?.course_code}</strong> - ${o.course?.course_title}</td>
                            <td>${o.schedule} <br><small style="color: var(--text-muted);">Room: ${o.room || 'TBA'}</small></td>
                            <td>${o.instructor?.name || 'Assigned Instructor'}</td>
                            <td>
                                <div><strong>${count}</strong> / ${o.capacity} (${pct}%)</div>
                                <div style="background:#e2e8f0; height:4px; border-radius:2px; width:100%; margin-top:4px;">
                                    <div style="background:var(--primary); height:4px; border-radius:2px; width:${Math.min(pct, 100)}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button class="btn btn-secondary btn-sm" onclick="App.viewRoster(${o.id}, '${o.section}')">View Roster</button>
                            </td>
                        </tr>
                    `;
                }
                if (offSel) {
                    offSel.innerHTML += `<option value="${o.id}">${o.section} - ${o.course?.course_code} (${count}/${o.capacity})</option>`;
                }
            });

            const stSel = document.getElementById('enr-student-select');
            if (stSel) {
                stSel.innerHTML = (stRes.data || []).map(s => `<option value="${s.id}">${s.student_number} - ${s.full_name}</option>`).join('');
            }
        } catch (e) {}
    },

    async viewRoster(offeringId, section) {
        try {
            const res = await this.api(`/course-offerings/${offeringId}/students`);
            const students = res.data || [];
            document.getElementById('roster-modal-title').textContent = `Class Roster: Section ${section}`;
            document.getElementById('roster-modal-subtitle').textContent = `Total Enrolled Students: ${students.length}`;

            const tb = document.querySelector('#roster-table tbody');
            tb.innerHTML = students.length === 0
                ? '<tr><td colspan="4" class="empty-state">No students are currently enrolled in this section.</td></tr>'
                : students.map(s => `
                    <tr>
                        <td><strong class="mono">${s.student_number}</strong></td>
                        <td><strong>${s.full_name}</strong></td>
                        <td>${s.email}</td>
                        <td><span class="badge badge-emerald">Enrolled</span></td>
                    </tr>
                `).join('');
            this.openModal('roster-modal');
        } catch (err) {
            this.showToast(err.message || 'Could not load section roster', 'danger');
        }
    },
    closeRosterModal() { this.closeModal('roster-modal'); },

    async enrollStudent(e) {
        e.preventDefault();
        const studentId = Number(document.getElementById('enr-student-select').value);
        const offeringId = Number(document.getElementById('enr-offering-select').value);

        try {
            await this.api('/enrollments', 'POST', {
                student_id: studentId,
                course_offering_id: offeringId,
                enrollment_date: new Date().toISOString().split('T')[0]
            });
            this.showToast('Student successfully enrolled into section!', 'success');
            this.loadOfferings();
        } catch (err) {
            this.showToast(err.message || 'Duplicate enrollment or capacity restriction.', 'danger');
        }
    },

    // Instructor Workspace
    async loadInstructorOfferings() {
        try {
            const res = await this.api('/course-offerings?per_page=100');
            const allOfferings = res.data || [];
            const u = this.state.user;

            const myOfferings = allOfferings.filter(o => 
                o.instructor_id === u.id || 
                o.instructor?.id === u.id ||
                o.instructor?.email?.toLowerCase() === u.email?.toLowerCase() ||
                o.instructor?.name?.toLowerCase() === u.name?.toLowerCase()
            );

            const displayList = myOfferings.length > 0 ? myOfferings : allOfferings;
            const tb = document.querySelector('#instructor-offerings-table tbody');
            let totalEnrolled = 0;

            if (displayList.length === 0) {
                tb.innerHTML = `<tr><td colspan="6" class="empty-state">No course offerings assigned to your account.</td></tr>`;
            } else {
                tb.innerHTML = displayList.map(o => {
                    const count = o.enrolled_count || 0;
                    totalEnrolled += count;
                    const pct = Math.round((count / o.capacity) * 100);
                    return `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${o.section}</strong></td>
                            <td><strong>${o.course?.course_code}</strong> - ${o.course?.course_title}</td>
                            <td><span class="badge badge-indigo">${o.course?.units || 3} units</span></td>
                            <td>${o.schedule} <br><small style="color: var(--text-muted);">Room: ${o.room || 'TBA'}</small></td>
                            <td>
                                <div><strong>${count}</strong> / ${o.capacity} (${pct}%)</div>
                                <div style="background:#e2e8f0; height:4px; border-radius:2px; width:100%; margin-top:4px;">
                                    <div style="background:var(--primary); height:4px; border-radius:2px; width:${Math.min(pct, 100)}%;"></div>
                                </div>
                            </td>
                            <td style="text-align: right;">
                                <button type="button" class="btn btn-secondary btn-sm" onclick="App.viewRoster(${o.id}, '${o.section}')">View Roster</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            document.getElementById('stat-val-1').textContent = displayList.length;
            document.getElementById('stat-val-2').textContent = totalEnrolled;
        } catch (err) {
            this.showToast(err.message || 'Failed to load assigned offerings.', 'danger');
        }
    },

    async loadInstructorGrades() {
        try {
            const [gradesRes, enrRes] = await Promise.all([
                this.api('/grades?per_page=50'),
                this.api('/enrollments?per_page=100')
            ]);
            const grades = gradesRes.data || [];
            this.state.instructorEnrollments = enrRes.data || [];

            // Populate the Enrollment dropdown with ONLY the instructor's enrolled students
            const enrSel = document.getElementById('inst-grade-enr-id');
            if (enrSel) {
                const curVal = enrSel.value;
                enrSel.innerHTML = '<option value="">-- Select Enrolled Student to Grade --</option>' +
                    this.state.instructorEnrollments.map(e => `
                        <option value="${e.id}">
                            ${e.student?.full_name} (${e.student?.student_number}) — ${e.course_offering?.course?.course_code} Sec ${e.course_offering?.section}
                        </option>
                    `).join('');
                if (curVal) enrSel.value = curVal;
            }

            const tb = document.querySelector('#instructor-grades-table tbody');
            if (tb) {
                tb.innerHTML = grades.length === 0
                    ? '<tr><td colspan="5" class="empty-state">No student grades recorded yet.</td></tr>'
                    : grades.map(g => `
                        <tr>
                            <td>
                                <strong>${g.enrollment?.student?.full_name || 'Enrolled Student'}</strong>
                                <br><small class="mono" style="color: var(--text-muted);">${g.enrollment?.student?.student_number || 'N/A'}</small>
                            </td>
                            <td>
                                <strong class="mono">${g.enrollment?.course_offering?.course?.course_code || 'Course'}</strong>
                                <br><small style="color: var(--text-muted);">Sec: ${g.enrollment?.course_offering?.section || 'N/A'}</small>
                            </td>
                            <td>${g.midterm_grade !== null ? g.midterm_grade : '-'}</td>
                            <td><strong style="color: var(--primary); font-size: 0.95rem;">${g.final_grade !== null ? g.final_grade : '-'}</strong></td>
                            <td><span class="badge ${g.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${g.remarks}</span></td>
                        </tr>
                    `).join('');
            }

            document.getElementById('stat-val-3').textContent = grades.length;
        } catch (err) {}
    },

    onInstructorEnrollmentSelect(enrId) {
        if (!enrId) return;
        const enr = (this.state.instructorEnrollments || []).find(e => String(e.id) === String(enrId));
        if (enr && enr.grade) {
            document.getElementById('inst-grade-midterm').value = enr.grade.midterm_grade !== null ? enr.grade.midterm_grade : '1.75';
            document.getElementById('inst-grade-final').value = enr.grade.final_grade !== null ? enr.grade.final_grade : '1.50';
            document.getElementById('inst-grade-remarks').value = enr.grade.remarks || 'PASSED';
        }
    },

    async submitInstructorGrade(e) {
        e.preventDefault();
        const enrId = document.getElementById('inst-grade-enr-id').value;
        if (!enrId) {
            return this.showToast('Please select a student from the dropdown list.', 'danger');
        }

        const payload = {
            enrollment_id: Number(enrId),
            midterm_grade: parseFloat(document.getElementById('inst-grade-midterm').value) || null,
            final_grade: parseFloat(document.getElementById('inst-grade-final').value),
            remarks: document.getElementById('inst-grade-remarks').value
        };

        try {
            await this.api('/grades', 'POST', payload);
            this.showToast('Grade saved successfully!', 'success');
            this.loadInstructorGrades();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Failed to submit grade. Verify enrollment ID.', 'danger');
        }
    },

    debounceInstructorStudentSearch() {
        clearTimeout(this.state.instSearchTimer);
        this.state.instSearchTimer = setTimeout(() => this.loadInstructorStudents(), 300);
    },

    resetInstructorStudentFilters() {
        const s = document.getElementById('inst-st-search');
        const p = document.getElementById('inst-st-prog-filter');
        if (s) s.value = '';
        if (p) p.value = '';
        this.loadInstructorStudents();
    },

    async loadInstructorStudents() {
        const search = document.getElementById('inst-st-search')?.value || '';
        const prog = document.getElementById('inst-st-prog-filter')?.value || '';

        let q = '?per_page=100';
        if (search) q += `&search=${encodeURIComponent(search)}`;
        if (prog) q += `&program_id=${prog}`;

        try {
            const res = await this.api(`/students${q}`);
            const students = res.data || [];
            const tb = document.querySelector('#instructor-students-table tbody');
            if (tb) {
                if (students.length === 0) {
                    tb.innerHTML = `<tr><td colspan="7" class="empty-state">No students found enrolled in your courses.</td></tr>`;
                } else {
                    tb.innerHTML = students.map(s => `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${s.student_number}</strong></td>
                            <td><strong>${s.full_name}</strong></td>
                            <td><span class="badge badge-indigo">${s.program?.code || 'N/A'}</span></td>
                            <td>Year ${s.year_level}</td>
                            <td style="color: var(--text-muted);">${s.email}</td>
                            <td><span class="badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}">${s.status}</span></td>
                            <td style="text-align: right; white-space: nowrap;">
                                <button type="button" class="btn btn-primary btn-sm" onclick="App.startGradingStudent(${s.id})">Grade</button>
                                <button type="button" class="btn btn-secondary btn-sm" onclick="App.viewStudentTranscript(${s.id})">Transcript</button>
                            </td>
                        </tr>
                    `).join('');
                }
            }

            // Populate program filter dropdown if empty
            const progSel = document.getElementById('inst-st-prog-filter');
            if (progSel && progSel.options.length <= 1) {
                const seenPrograms = new Map();
                students.forEach(s => {
                    if (s.program?.id && !seenPrograms.has(s.program.id)) {
                        seenPrograms.set(s.program.id, s.program.code);
                    }
                });
                seenPrograms.forEach((code, id) => {
                    progSel.innerHTML += `<option value="${id}">${code}</option>`;
                });
            }

            // Update enrolled students counter in stat card 2
            const stat2 = document.getElementById('stat-val-2');
            if (stat2 && this.state.user?.role === 'instructor') {
                stat2.textContent = res.meta?.total !== undefined ? res.meta.total : students.length;
            }
        } catch (err) {
            this.showToast(err.message || 'Failed to load assigned students.', 'danger');
        }
    },

    startGradingStudent(studentId) {
        this.switchTab('instructor-grades');
        // Pre-select enrollment if list is already populated
        const sel = document.getElementById('inst-grade-enr-id');
        const enr = (this.state.instructorEnrollments || []).find(e => e.student?.id === studentId || e.student_id === studentId);
        if (sel && enr) {
            sel.value = enr.id;
            this.onInstructorEnrollmentSelect(enr.id);
        }
    },

    // Transcript Shared Component
    buildTranscriptMarkup(rec) {
        const header = `
            <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:16px;">
                <div>
                    <h3 style="font-size:1.25rem; font-weight:800; color:var(--text-main); margin-bottom: 4px;">${rec.student.name}</h3>
                    <div style="color:var(--text-muted); font-size:0.85rem;">
                        Student Number: <strong class="mono">${rec.student.student_number}</strong> | Major: <strong>${rec.student.program}</strong>
                    </div>
                </div>
                <div style="display:flex; gap:14px;">
                    <div style="background:white; border:1px solid var(--border); padding:8px 18px; border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Cumulative GWA</div>
                        <div style="font-size:1.35rem; font-weight:800; color:var(--primary);">${rec.summary?.cumulative_gwa || 'N/A'}</div>
                    </div>
                    <div style="background:white; border:1px solid var(--border); padding:8px 18px; border-radius:var(--radius-sm); text-align:center;">
                        <div style="font-size:0.7rem; font-weight:700; color:var(--text-muted); text-transform:uppercase;">Units Completed</div>
                        <div style="font-size:1.35rem; font-weight:800; color:var(--success);">${rec.summary?.total_units_earned || 0}</div>
                    </div>
                </div>
            </div>
        `;

        const tables = (rec.academic_terms || []).map(t => `
            <div style="margin-top: 18px; background: white; padding: 18px; border: 1px solid var(--border); border-radius: var(--radius-md); box-shadow: var(--shadow-sm);">
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:12px;">
                    <div style="font-weight:800; font-size:1rem; color:var(--text-main);">${t.term}</div>
                    <div>
                        <span class="badge badge-slate">Term Units: ${t.total_units}</span>
                        <span class="badge badge-indigo">Term GWA: ${t.term_gwa || 'N/A'}</span>
                    </div>
                </div>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>Course Code</th>
                                <th>Course Title</th>
                                <th>Units</th>
                                <th>Final Grade</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            ${t.courses.map(c => `
                                <tr>
                                    <td><strong class="mono" style="color:var(--primary);">${c.course_code}</strong></td>
                                    <td>${c.course_title}</td>
                                    <td>${c.units}</td>
                                    <td><strong style="color:var(--primary); font-size:1rem;">${c.final_grade || '-'}</strong></td>
                                    <td><span class="badge ${c.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${c.remarks}</span></td>
                                </tr>
                            `).join('')}
                        </tbody>
                    </table>
                </div>
            </div>
        `).join('');

        return { header, tables: tables || '<div class="empty-state">No academic terms recorded.</div>' };
    },

    // Student Self Portal
    async loadStudentSelfData() {
        const u = this.state.user;
        const studentId = u?.student_profile?.id || u?.student?.id;
        if (!studentId) return;

        try {
            const [stRes, enrRes, recRes] = await Promise.all([
                this.api(`/students/${studentId}`),
                this.api(`/students/${studentId}/enrollments`),
                this.api(`/students/${studentId}/academic-record`)
            ]);

            const s = stRes.data;
            document.getElementById('st-self-num').textContent = s.student_number;
            document.getElementById('st-self-name').textContent = s.full_name;
            document.getElementById('st-self-prog').textContent = s.program ? `${s.program.code} - ${s.program.name}` : 'General Degree';
            document.getElementById('st-self-year').textContent = `Year ${s.year_level}`;
            document.getElementById('st-self-email').textContent = s.email;
            document.getElementById('st-self-contact').textContent = s.contact_number || '+63 912 345 6789';
            document.getElementById('st-self-address').textContent = s.address || 'Metro Manila, Philippines';
            const statusBadge = document.getElementById('st-self-status-badge');
            statusBadge.textContent = s.status;
            statusBadge.className = `badge ${s.status === 'ACTIVE' ? 'badge-emerald' : 'badge-slate'}`;

            const enrollments = enrRes.data || [];
            const enrTb = document.querySelector('#student-enrollments-table tbody');
            enrTb.innerHTML = enrollments.length === 0
                ? '<tr><td colspan="7" class="empty-state">No enrolled courses found.</td></tr>'
                : enrollments.map(enr => {
                    const off = enr.course_offering || {};
                    const cr = off.course || {};
                    const term = off.academic_term || {};
                    return `
                        <tr>
                            <td><strong class="mono" style="color: var(--primary);">${off.section || 'N/A'}</strong></td>
                            <td><strong class="mono">${cr.course_code || 'N/A'}</strong></td>
                            <td>${cr.course_title || 'N/A'}</td>
                            <td><span class="badge badge-indigo">${cr.units || 3} units</span></td>
                            <td>${off.schedule || 'TBA'} <br><small style="color: var(--text-muted);">Room: ${off.room || 'TBA'}</small></td>
                            <td>${term.term || 'Semester'} ${term.academic_year || ''}</td>
                            <td><span class="badge ${enr.status === 'COMPLETED' ? 'badge-emerald' : 'badge-amber'}">${enr.status}</span></td>
                        </tr>
                    `;
                }).join('');
            document.getElementById('stat-val-3').textContent = enrollments.length;

            const rec = recRes.data;
            document.getElementById('stat-val-1').textContent = rec.summary?.cumulative_gwa || 'N/A';
            document.getElementById('stat-val-2').textContent = `${rec.summary?.total_units_earned || 0} Units`;
            document.getElementById('stat-val-4').textContent = 'GOOD STANDING';

            const { header, tables } = this.buildTranscriptMarkup(rec);
            document.getElementById('st-self-transcript-header').innerHTML = header;
            document.getElementById('st-self-transcript-tables').innerHTML = tables;
        } catch (err) {
            this.showToast(err.message || 'Could not load student information.', 'danger');
        }
    },

    // Grades & Transcript (Admin / Registrar)
    async submitGrade(e) {
        e.preventDefault();
        const payload = {
            enrollment_id: Number(document.getElementById('grade-enr-id').value),
            midterm_grade: parseFloat(document.getElementById('grade-midterm').value) || null,
            final_grade: parseFloat(document.getElementById('grade-final').value),
            remarks: document.getElementById('grade-remarks').value
        };

        try {
            await this.api('/grades', 'POST', payload);
            this.showToast('Grade recorded successfully!', 'success');
            this.loadGrades();
        } catch (err) {
            const msg = err.errors ? Object.values(err.errors).flat().join('<br>') : err.message;
            this.showToast(msg || 'Failed to encode grade.', 'danger');
        }
    },

    async loadGrades() {
        try {
            const [res, enrRes] = await Promise.all([
                this.api('/grades?per_page=20'),
                this.api('/enrollments?per_page=100')
            ]);
            const gt = document.querySelector('#grades-table tbody');
            if (gt) {
                gt.innerHTML = (res.data || []).map(g => `
                    <tr>
                        <td><strong>${g.enrollment?.student?.full_name || 'Student'}</strong><br><small class="mono" style="color:var(--text-muted);">${g.enrollment?.student?.student_number || 'N/A'}</small></td>
                        <td><strong class="mono">${g.enrollment?.course_offering?.course?.course_code || 'Course'}</strong></td>
                        <td>${g.midterm_grade !== null ? g.midterm_grade : '-'}</td>
                        <td><strong style="color: var(--primary);">${g.final_grade !== null ? g.final_grade : '-'}</strong></td>
                        <td><span class="badge ${g.remarks === 'PASSED' ? 'badge-emerald' : 'badge-amber'}">${g.remarks}</span></td>
                    </tr>
                `).join('');
            }

            const sel = document.getElementById('grade-enr-id');
            if (sel) {
                this.state.adminEnrollments = enrRes.data || [];
                const cur = sel.value;
                sel.innerHTML = '<option value="">-- Select Enrolled Student --</option>' +
                    this.state.adminEnrollments.map(e => `
                        <option value="${e.id}">
                            ${e.student?.full_name} (${e.student?.student_number}) — ${e.course_offering?.course?.course_code} Sec ${e.course_offering?.section}
                        </option>
                    `).join('');
                if (cur) sel.value = cur;
            }
        } catch (e) {}
    },

    onAdminEnrollmentSelect(enrId) {
        if (!enrId) return;
        const enr = (this.state.adminEnrollments || []).find(e => String(e.id) === String(enrId));
        if (enr && enr.grade) {
            document.getElementById('grade-midterm').value = enr.grade.midterm_grade !== null ? enr.grade.midterm_grade : '1.75';
            document.getElementById('grade-final').value = enr.grade.final_grade !== null ? enr.grade.final_grade : '1.50';
            document.getElementById('grade-remarks').value = enr.grade.remarks || 'PASSED';
        }
    },

    async loadTranscript() {
        const id = document.getElementById('transcript-st-id').value;
        try {
            const res = await this.api(`/students/${id}/academic-record`);
            const resBox = document.getElementById('transcript-result');
            resBox.style.display = 'block';

            const { header, tables } = this.buildTranscriptMarkup(res.data);
            document.getElementById('transcript-header').innerHTML = header;
            document.getElementById('transcript-tables').innerHTML = tables;
            this.showToast('Academic record retrieved.', 'success');
        } catch (err) {
            this.showToast(err.message || 'Unable to load transcript for this student ID.', 'danger');
        }
    },

    viewStudentTranscript(id) {
        this.switchTab('grades');
        document.getElementById('transcript-st-id').value = id;
        this.loadTranscript();
    }
};

document.addEventListener('DOMContentLoaded', () => App.init());

    </script>
</body>
</html>
