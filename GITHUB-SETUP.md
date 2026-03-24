# 📦 Setup GitHub Private Repository

Panduan untuk push project ini ke GitHub repository private.

---

## ⚡ Cara Cepat (Windows)

### Step 1: Jalankan Setup Script

Double-click file ini:
```
github-setup.bat
```

Atau buka Command Prompt:
```batch
cd C:\Users\Geoveza\Documents\web3-portfolio
github-setup.bat
```

### Step 2: Buat Repository di GitHub

1. Buka browser: https://github.com/new
2. Isi form:
   - **Repository name**: `web3-portfolio` (atau nama lain)
   - **Description**: `Portfolio Website with Laravel`
   - **☑️ Private** ← Centang ini!
   - **☐ Initialize with README** ← Jangan dicentang
   - **☐ Add .gitignore** ← Jangan dicentang
   - **☐ Choose a license** ← Jangan dicentang

3. Klik **"Create repository"**

4. Copy URL repository (contoh: `https://github.com/username/web3-portfolio.git`)

5. Paste URL ke script yang sedang berjalan

6. Done! ✅

---

## 🔧 Cara Manual (Jika Script Gagal)

### 1. Setup Git (Sekali saja)

```bash
# Config nama dan email
git config --global user.name "Nama Anda"
git config --global user.email "email@github.com"

# Cek config
git config --list
```

### 2. Inisialisasi Repository

```bash
# Masuk ke folder project
cd C:\Users\Geoveza\Documents\web3-portfolio

# Inisialisasi Git
git init

# Add semua file
git add .

# Commit
git commit -m "Initial commit: Web3 Portfolio project"
```

### 3. Push ke GitHub

```bash
# Tambahkan remote (ganti dengan URL Anda)
git remote add origin https://github.com/USERNAME/web3-portfolio.git

# Rename branch ke main
git branch -M main

# Push
git push -u origin main
```

---

## 🔐 Verifikasi Repository Private

Cek di browser:
1. Buka: `https://github.com/USERNAME/web3-portfolio`
2. Lihat badge: 🔒 **Private**
3. Hanya Anda yang bisa mengakses

---

## 🔑 Setup SSH Key (Opsional tapi Direkomendasikan)

Untuk push tanpa password:

```bash
# Generate SSH key
ssh-keygen -t ed25519 -C "email@github.com"

# Copy public key
cat ~/.ssh/id_ed25519.pub
# Windows: type %USERPROFILE%\.ssh\id_ed25519.pub
```

1. Buka GitHub → Settings → SSH and GPG keys
2. Klik "New SSH key"
3. Paste public key
4. Save

---

## 📝 Command Git Sehari-hari

```bash
# Setelah ada perubahan
git add .
git commit -m "Deskripsi perubahan"
git push

# Pull update terbaru
git pull

# Cek status
git status

# Cek log
git log --oneline
```

---

## ⚠️ File yang Tidak Di-push (Security)

File berikut otomatis di-exclude (via .gitignore):

- `.env` - File konfigurasi berisi secrets
- `vendor/` - Dependencies (install via composer)
- `node_modules/` - Node dependencies
- `database/database.sqlite` - Database lokal
- `storage/logs/` - Log files
- `*.log` - Semua log files

**Catatan**: Copy `.env.example` ke `.env` di server/VPS.

---

## 🆘 Troubleshooting

### "Git tidak dikenali"
- Install Git: https://git-scm.com/download/win
- Restart Command Prompt

### "Failed to push"
- Cek username/password GitHub
- Coba login via browser dulu
- Jika pakai 2FA, gunakan Personal Access Token

### "Repository not found"
- Pastikan URL remote benar: `git remote -v`
- Update: `git remote set-url origin URL_BARU`

### "Permission denied"
- Pastikan repository sudah di-set sebagai **Private** di GitHub
- Cek Anda adalah owner/collaborator

---

## 🌟 Tips Keamanan

1. **Jangan pernah push file `.env`** - Sudah di-exclude via .gitignore
2. **Gunakan SSH** - Lebih aman dari HTTPS + password
3. **Enable 2FA** - Di GitHub Settings → Security
4. **Personal Access Token** - Jika pakai HTTPS, gunakan PAT
5. **Private Repository** - Selalu cek badge 🔒 Private

---

## 📱 Akses Repository Private

Hanya Anda yang bisa:
- Melihat repository
- Clone/download
- Fork (tapi tetap private)

Orang lain akan lihat:
```
404 - This is not the web page you are looking for
```

---

Selamat! Project Anda sekarang aman di GitHub Private Repository! 🎉
