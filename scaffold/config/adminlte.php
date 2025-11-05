<?php

return [
    'title' => 'Minna-no-Bukatsu',
    'classes_body' => 'hold-transition sidebar-mini',
    'dashboard_url' => 'dashboard',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'plugins' => [
        'Chartjs' => [
            'active' => true,
            'files' => [
                ['type' => 'js', 'asset' => true, 'location' => 'vendor/chart.js/Chart.bundle.min.js'],
            ],
        ],
    ],
    'menu' => [
        ['text' => 'ダッシュボード', 'route' => 'dashboard', 'icon' => 'fas fa-tachometer-alt'],
        ['text' => '募集管理', 'route' => 'dashboard.projects.index', 'icon' => 'fas fa-clipboard-list', 'can' => 'club-only'],
        ['text' => '申込', 'route' => 'dashboard', 'icon' => 'fas fa-handshake', 'can' => 'company-only'],
        ['text' => 'メッセージ', 'route' => 'dashboard', 'icon' => 'fas fa-comments', 'can' => 'company-only'],
        ['header' => '管理者', 'can' => 'admin-access'],
        ['text' => '審査', 'route' => 'admin.reviews', 'icon' => 'fas fa-check', 'can' => 'admin-access'],
        ['text' => '通報', 'route' => 'admin.reports', 'icon' => 'fas fa-flag', 'can' => 'admin-access'],
        ['text' => 'マスター', 'route' => 'admin.master', 'icon' => 'fas fa-cog', 'can' => 'admin-access'],
        ['text' => 'KPI', 'route' => 'admin.kpi', 'icon' => 'fas fa-chart-bar', 'can' => 'admin-access'],
    ],
];
