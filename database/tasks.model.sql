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
