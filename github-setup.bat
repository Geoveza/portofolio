@echo off
chcp 65001 >nul
echo ================================================
echo    🚀 GITHUB PRIVATE REPO SETUP
echo ================================================
echo.

REM Check if git is installed
where git >nul 2>&1
if %errorlevel% neq 0 (
    echo ❌ Git tidak ditemukan!
    echo Silakan install Git dari: https://git-scm.com/download/win
    pause
    exit /b 1
)

echo ✅ Git ditemukan
echo.

REM Check Git config
echo 🔍 Cek konfigurasi Git...
git config --global user.name >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  Git user.name belum di-set
    set /p GIT_NAME="Masukkan nama Anda: "
    git config --global user.name "%GIT_NAME%"
)

git config --global user.email >nul 2>&1
if %errorlevel% neq 0 (
    echo ⚠️  Git user.email belum di-set
    set /p GIT_EMAIL="Masukkan email GitHub Anda: "
    git config --global user.email "%GIT_EMAIL%"
)

echo ✅ Git config OK
echo.

REM Initialize git if not exists
if not exist .git (
    echo 🔧 Inisialisasi repository Git...
    git init
    echo ✅ Repository Git dibuat
) else (
    echo ✅ Repository Git sudah ada
)
echo.

REM Add all files
echo 📁 Menambahkan file ke staging...
git add .
echo ✅ File ditambahkan
echo.

REM Commit
echo 💾 Membuat commit...
git commit -m "Initial commit: Web3 Portfolio project"
echo ✅ Commit berhasil
echo.

REM Check if remote exists
git remote get-url origin >nul 2>&1
if %errorlevel% equ 0 (
    echo ⚠️  Remote origin sudah ada
    git remote -v
) else (
    echo ================================================
    echo    🔗 HUBUNGKAN DENGAN GITHUB
    echo ================================================
    echo.
    echo Langkah selanjutnya:
    echo.
    echo 1. Buka https://github.com/new di browser
    echo.
    echo 2. Isi detail repository:
    echo    - Repository name: web3-portfolio ^(atau nama lain^)
    echo    - Description: Portfolio Website with Laravel
    echo    - ☑️ Private ^(centang 'Private'^)
    echo    - ☐ Initialize with README ^(jangan dicentang^)
    echo    - ☐ Add .gitignore ^(jangan dicentang^)
    echo    - ☐ Choose a license ^(jangan dicentang^)
    echo.
    echo 3. Klik "Create repository"
    echo.
    echo 4. Copy URL repository ^(contoh: https://github.com/username/web3-portfolio.git^)
    echo.
    set /p REPO_URL="Paste URL repository di sini: "
    echo.
    
    if "!REPO_URL!"=="" (
        echo ❌ URL tidak boleh kosong
        pause
        exit /b 1
    )
    
    git remote add origin %REPO_URL%
    echo ✅ Remote ditambahkan
    echo.
    
    echo 🚀 Push ke GitHub...
    git branch -M main
    git push -u origin main
    
    if %errorlevel% equ 0 (
        echo.
        echo ================================================
        echo    ✅ BERHASIL!
        echo ================================================
        echo.
        echo Repository Anda sekarang private di GitHub!
        echo URL: %REPO_URL%
        echo.
        echo Hanya Anda yang bisa melihat/mengakses repository ini.
    ) else (
        echo.
        echo ❌ Push gagal. Coba manual dengan command:
        echo    git push -u origin main
    )
)

echo.
pause
