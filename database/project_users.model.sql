CREATE TABLE IF NOT EXISTS public."project_users" (
    project_id uuid NOT NULL REFERENCES projects(id) ON DELETE CASCADE,
    user_id uuid NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    role varchar(100) DEFAULT 'member',
    assigned_at timestamp DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (project_id, user_id)
);
