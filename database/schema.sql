-- Master Database Schema for AD-Task-3
-- Execute this file to create all tables in the correct order

-- Users Table (Base table - no dependencies)
CREATE TABLE IF NOT EXISTS public."users" (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    first_name varchar(225) NOT NULL,
    middle_name varchar(225),
    last_name varchar(225) NOT NULL,
    password varchar(225) NOT NULL,
    username varchar(225) NOT NULL UNIQUE,
    role varchar(225) NOT NULL,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP
);

-- Projects Table (depends on users)
CREATE TABLE IF NOT EXISTS public."projects" (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    name varchar(225) NOT NULL,
    description text,
    status varchar(50) DEFAULT 'active',
    start_date date,
    end_date date,
    created_by uuid REFERENCES users(id),
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP
);

-- Project-User Assignments Table (many-to-many relationship)
CREATE TABLE IF NOT EXISTS public."project_users" (
    project_id uuid NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    user_id uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role varchar(100) DEFAULT 'member',
    assigned_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (project_id, user_id)
);

-- Tasks Table (depends on projects and users)
CREATE TABLE IF NOT EXISTS public."tasks" (
    id uuid NOT NULL PRIMARY KEY DEFAULT gen_random_uuid(),
    title varchar(225) NOT NULL,
    description text,
    status varchar(50) DEFAULT 'pending',
    priority varchar(20) DEFAULT 'medium',
    project_id uuid NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    assigned_to uuid REFERENCES users(id),
    created_by uuid REFERENCES users(id),
    due_date date,
    created_at timestamp DEFAULT CURRENT_TIMESTAMP,
    updated_at timestamp DEFAULT CURRENT_TIMESTAMP
);

-- Create indexes for better performance
CREATE INDEX IF NOT EXISTS idx_users_username ON public."users"(username);
CREATE INDEX IF NOT EXISTS idx_users_role ON public."users"(role);
CREATE INDEX IF NOT EXISTS idx_projects_status ON public."projects"(status);
CREATE INDEX IF NOT EXISTS idx_tasks_status ON public."tasks"(status);
CREATE INDEX IF NOT EXISTS idx_tasks_project_id ON public."tasks"(project_id);
CREATE INDEX IF NOT EXISTS idx_tasks_assigned_to ON public."tasks"(assigned_to);
