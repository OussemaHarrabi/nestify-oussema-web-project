@echo off
REM Nestify Backend Migration Script for Windows
REM This script will migrate from agency-based to promoter-based system

echo 🚀 Starting Nestify Backend Migration...
echo ========================================

REM Step 1: Backup database
echo 📦 Step 1: Creating database backup...
if exist "database\database.sqlite" (
    copy "database\database.sqlite" "database\database.backup.sqlite"
    echo ✅ Database backed up to database.backup.sqlite
) else (
    echo ⚠️  No SQLite database found, skipping backup
)

REM Step 2: Run migrations
echo.
echo 🏗️  Step 2: Running migrations...
php artisan migrate --force
echo ✅ Migrations completed

REM Step 3: Run seeders
echo.
echo 🌱 Step 3: Running seeders...
php artisan db:seed --force
echo ✅ Seeders completed

REM Step 4: Clear caches
echo.
echo 🧹 Step 4: Clearing caches...
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear
echo ✅ Caches cleared

echo.
echo 🎉 Migration completed successfully!
echo ==================================
echo.
echo Next steps:
echo 1. Update your frontend to use the new API endpoints
echo 2. Test the promoter login functionality
echo 3. Verify that projects and properties are properly linked
echo 4. Check that leads are being captured correctly
echo.
echo New API endpoints:
echo - /api/promoters (instead of /api/agencies)
echo - /api/projects (new)
echo - /api/leads (new)
echo.
echo Rollback command (if needed):
echo copy database\database.backup.sqlite database\database.sqlite
pause

