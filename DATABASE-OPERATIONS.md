# Database Operations Documentation

## Overview
This project includes automated database management utilities for PostgreSQL that handle schema creation, data seeding, and migration operations.

## Available Operations

### 1. Database Reset (`postgresql:reset`)
- **Purpose**: Complete database setup with sample data
- **What it does**:
  - Drops all existing tables
  - Recreates tables from schema files
  - Inserts basic sample data for testing
- **Usage**:
  ```bash
  docker exec username-service composer postgresql:reset
  # OR
  reset-database.bat
  ```

### 2. Database Migration (`postgresql:migrate`)
- **Purpose**: Update database schema without data
- **What it does**:
  - Drops all existing tables
  - Recreates tables from schema files
  - NO data insertion (tables will be empty)
- **Usage**: 
  ```bash
  docker exec username-service composer postgresql:migrate
  # OR
  migrate-database.bat
  ```

### 3. Database Seeding (`postgresql:seed`)
- **Purpose**: Populate existing tables with dummy data
- **What it does**:
  - Checks if tables exist
  - Clears existing data
  - Inserts comprehensive dummy data with relationships
- **Usage**: 
  ```bash
  docker exec username-service composer postgresql:seed
  # OR
  seed-database.bat
  ```

### 4. Complete Setup (`setup-database.bat`)
- **Purpose**: Full reset + seeding in one operation
- **What it does**:
  - Runs postgresql:reset
  - Runs postgresql:seed
- **Usage**: 
  ```cmd
  setup-database.bat
  ```

## When to Use Each Operation

### Use **Reset** when:
- Setting up the project for the first time
- You want a clean database with basic sample data
- Testing the application with minimal data

### Use **Migration** when:
- You've updated table schemas
- You want to preserve existing application data flow
- You plan to import/restore data from another source
- You need clean tables without any test data

### Use **Seeding** when:
- Tables already exist but you need test data
- You want to populate the database with realistic dummy data
- Testing application functionality with comprehensive data

## Database Schema

The system manages these tables:
- **users**: User accounts with roles and authentication
- **projects**: Project management with status and dates  
- **project_users**: Many-to-many assignments between projects and users
- **tasks**: Task management with assignments and priorities

## Data Relationships

The seeded data includes:
- 10 users with different roles (admin, manager, developer, designer, user)
- 5 projects in various states (active, planning, completed)
- 14 project-user assignments with specific roles
- 8 tasks with proper assignments and due dates

## Files Structure

```
utils/
├── dbResetPostgresql.util.php      # Reset utility
├── dbMigratePostgresql.util.php    # Migration utility
├── dbSeederPostgresql.util.php     # Seeding utility
└── envSetter.util.php              # Environment configuration

staticDatas/dummies/
├── users.staticData.php            # User dummy data
├── projects.staticData.php         # Project dummy data
├── tasks.staticData.php            # Task dummy data
└── project_users.staticData.php    # Assignment dummy data

database/
├── users.model.sql                 # Users table schema
├── projects.model.sql              # Projects table schema
├── tasks.model.sql                 # Tasks table schema
├── project_users.model.sql         # Assignments table schema
└── schema.sql                      # Master schema file

# Batch files for Windows
├── reset-database.bat              # Reset operation
├── migrate-database.bat            # Migration operation
├── seed-database.bat               # Seeding operation
├── setup-database.bat              # Complete setup
└── database-menu.bat               # Interactive menu
```

## Interactive Menu

Use `database-menu.bat` for a user-friendly interface to choose operations:
1. Reset Database (Drop + Create + Sample Data)
2. Migrate Database (Drop + Create Tables Only)
3. Seed Database (Add Dummy Data to Existing Tables)
4. Complete Setup (Reset + Seed)
5. Exit
