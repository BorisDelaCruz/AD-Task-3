@echo off
echo 📋 Database Operations Menu
echo ========================
echo.
echo Choose an operation:
echo 1. Reset Database (Drop + Create + Sample Data)
echo 2. Migrate Database (Drop + Create Tables Only)
echo 3. Seed Database (Add Dummy Data to Existing Tables)
echo 4. Complete Setup (Reset + Seed)
echo 5. Exit
echo.
set /p choice="Enter your choice (1-5): "

if "%choice%"=="1" (
    echo.
    echo 🔄 Running Database Reset...
    docker exec username-service composer postgresql:reset
    echo.
    echo ✅ Database reset completed!
    pause
    goto :eof
)

if "%choice%"=="2" (
    echo.
    echo 🔄 Running Database Migration...
    docker exec username-service composer postgresql:migrate
    echo.
    echo ✅ Database migration completed!
    pause
    goto :eof
)

if "%choice%"=="3" (
    echo.
    echo 🌱 Running Database Seeding...
    docker exec username-service composer postgresql:seed
    echo.
    echo ✅ Database seeding completed!
    pause
    goto :eof
)

if "%choice%"=="4" (
    echo.
    echo 🔄 Running Complete Database Setup...
    docker exec username-service composer postgresql:reset
    echo.
    docker exec username-service composer postgresql:seed
    echo.
    echo ✅ Complete database setup finished!
    pause
    goto :eof
)

if "%choice%"=="5" (
    echo Goodbye!
    goto :eof
)

echo Invalid choice. Please try again.
pause
goto :eof
