@echo off
set PHP_PATH=C:\Users\User\AppData\Local\Microsoft\WinGet\Packages\PHP.PHP.8.3_Microsoft.Winget.Source_8wekyb3d8bbwe
set PATH=%PATH%;%PHP_PATH%

if "%1"=="serve" (
    echo Starting SIRS server on http://localhost:8080...
    %PHP_PATH%\php.exe spark serve --port 8080
) else if "%1"=="migrate" (
    echo Running migrations...
    %PHP_PATH%\php.exe spark migrate
) else if "%1"=="seed" (
    echo Running seeders...
    %PHP_PATH%\php.exe spark db:seed
) else if "%1"=="build" (
    echo Building frontend...
    call npm run build
) else if "%1"=="install" (
    echo Installing dependencies...
    %PHP_PATH%\php.exe %PHP_PATH%\composer install --optimize-autoloader
    call npm install
) else if "%1"=="fresh" (
    echo Fresh migration...
    %PHP_PATH%\php.exe spark migrate:refresh
    %PHP_PATH%\php.exe spark db:seed
) else (
    echo.
    echo SIRS Commands:
    echo   sirs serve     - Start development server
    echo   sirs migrate   - Run database migrations
    echo   sirs seed      - Run database seeders
    echo   sirs build     - Build frontend assets
    echo   sirs install   - Install all dependencies
    echo   sirs fresh     - Fresh migrate + seed
    echo.
)
