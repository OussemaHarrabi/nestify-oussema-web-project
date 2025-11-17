@echo off
echo.
echo ====================================================================
echo   NESTIFY ADMIN AUTHENTICATION - QUICK FIX
echo ====================================================================
echo.
echo This script will:
echo   1. Verify admin user exists
echo   2. Generate a fresh authentication token
echo   3. Test the token against the API
echo   4. Show you what to paste into Postman
echo.
echo Press any key to continue...
pause >nul
echo.

echo [1/3] Verifying admin user...
echo ----------------------------------------------------------------
php verify_admin.php
echo.

echo.
echo [2/3] Testing API authentication...
echo ----------------------------------------------------------------
php test_admin_auth.php
echo.

echo.
echo ====================================================================
echo   WHAT TO DO NOW IN POSTMAN
echo ====================================================================
echo.
echo 1. Import this collection:
echo    Nestify_Admin_Workflow_FIXED.postman_collection.json
echo.
echo 2. Open the collection and go to Variables tab
echo.
echo 3. Find 'admin_token' variable
echo.
echo 4. Paste the token from above output
echo.
echo 5. Click Save
echo.
echo 6. Test "Get Admin Dashboard" request
echo.
echo ====================================================================
echo.
echo Press any key to exit...
pause >nul
