@echo off
cls
echo ========================================
echo   Starting Laravel Development Server
echo ========================================
echo.
cd /d "%~dp0"
php artisan serve
if errorlevel 1 (
    echo.
    echo [ERROR] Server failed to start!
    echo Exit code: %errorlevel%
    pause
) else (
    echo.
    echo Server stopped.
    pause
)
