@echo off
echo 🌱 Starting PostgreSQL Database Seeding...
docker exec username-service php utils/dbSeederPostgresql.util.php
echo.
echo ✅ Database seeding completed!
pause
