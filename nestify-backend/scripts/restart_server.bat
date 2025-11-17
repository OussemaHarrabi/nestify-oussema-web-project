@echo off
echo.
echo ====================================================================
echo   RESTART LARAVEL SERVER
echo ====================================================================
echo.

echo [1/3] Stopping existing PHP processes...
taskkill /F /IM php.exe /FI "WINDOWTITLE eq *artisan serve*" 2>nul
if errorlevel 1 (
    echo No Laravel server found running.
) else (
    echo Laravel server stopped.
)

echo.
echo [2/3] Clearing all caches...
php artisan route:clear
php artisan config:clear
php artisan cache:clear

echo.
echo [3/3] Starting Laravel server...
echo.
echo ====================================================================
echo   SERVER STARTING ON http://127.0.0.1:8000
echo ====================================================================
echo   Press Ctrl+C to stop the server
echo ====================================================================
echo.

php artisan serve
