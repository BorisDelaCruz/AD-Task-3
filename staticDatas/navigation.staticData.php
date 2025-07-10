<?php
// Navigation configuration for the application

return [
    'public' => [
        'home' => [
            'title' => 'Home',
            'url' => '/',
            'icon' => 'fas fa-home'
        ],
        'login' => [
            'title' => 'Login',
            'url' => '/pages/login/',
            'icon' => 'fas fa-sign-in-alt'
        ]
    ],
    
    'authenticated' => [
        'dashboard' => [
            'title' => 'Dashboard',
            'url' => '/pages/dashboard/',
            'icon' => 'fas fa-tachometer-alt',
            'roles' => ['admin', 'manager', 'developer', 'designer', 'user']
        ],
        'projects' => [
            'title' => 'Projects',
            'url' => '/pages/projects/',
            'icon' => 'fas fa-project-diagram',
            'roles' => ['admin', 'manager', 'developer', 'designer']
        ],
        'tasks' => [
            'title' => 'Tasks',
            'url' => '/pages/tasks/',
            'icon' => 'fas fa-tasks',
            'roles' => ['admin', 'manager', 'developer', 'designer', 'user']
        ],
        'users' => [
            'title' => 'User Management',
            'url' => '/pages/users/',
            'icon' => 'fas fa-users',
            'roles' => ['admin', 'manager']
        ]
    ],
    
    'admin_only' => [
        'settings' => [
            'title' => 'Settings',
            'url' => '/pages/settings/',
            'icon' => 'fas fa-cog',
            'roles' => ['admin']
        ]
    ]
];
