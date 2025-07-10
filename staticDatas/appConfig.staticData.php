<?php
// Application configuration

return [
    'app' => [
        'name' => 'AD-Task-3',
        'description' => 'Project Management System',
        'version' => '1.0.0',
        'author' => 'Boris Dela Cruz'
    ],
    
    'security' => [
        'session_timeout' => 3600, // 1 hour
        'max_login_attempts' => 5,
        'lockout_duration' => 900 // 15 minutes
    ],
    
    'ui' => [
        'theme' => 'light',
        'items_per_page' => 10,
        'date_format' => 'Y-m-d',
        'datetime_format' => 'Y-m-d H:i:s'
    ],
    
    'roles' => [
        'admin' => [
            'title' => 'Administrator',
            'description' => 'Full system access',
            'color' => 'danger'
        ],
        'manager' => [
            'title' => 'Manager',
            'description' => 'Project and team management',
            'color' => 'warning'
        ],
        'developer' => [
            'title' => 'Developer',
            'description' => 'Development tasks',
            'color' => 'success'
        ],
        'designer' => [
            'title' => 'Designer',
            'description' => 'Design and UI tasks',
            'color' => 'info'
        ],
        'user' => [
            'title' => 'User',
            'description' => 'Basic access',
            'color' => 'secondary'
        ]
    ],
    
    'task_statuses' => [
        'pending' => [
            'title' => 'Pending',
            'color' => 'secondary'
        ],
        'in_progress' => [
            'title' => 'In Progress',
            'color' => 'primary'
        ],
        'completed' => [
            'title' => 'Completed',
            'color' => 'success'
        ],
        'cancelled' => [
            'title' => 'Cancelled',
            'color' => 'danger'
        ]
    ],
    
    'project_statuses' => [
        'planning' => [
            'title' => 'Planning',
            'color' => 'info'
        ],
        'active' => [
            'title' => 'Active',
            'color' => 'success'
        ],
        'on_hold' => [
            'title' => 'On Hold',
            'color' => 'warning'
        ],
        'completed' => [
            'title' => 'Completed',
            'color' => 'primary'
        ],
        'cancelled' => [
            'title' => 'Cancelled',
            'color' => 'danger'
        ]
    ],
    
    'priorities' => [
        'low' => [
            'title' => 'Low',
            'color' => 'success'
        ],
        'medium' => [
            'title' => 'Medium',
            'color' => 'warning'
        ],
        'high' => [
            'title' => 'High',
            'color' => 'danger'
        ]
    ]
];
