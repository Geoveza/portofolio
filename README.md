# Web3 Portfolio - GitHub Pages Version

Ini adalah versi static HTML dari portfolio yang dikompatiblekan untuk GitHub Pages.

## Setup GitHub Pages

1. **Push folder `docs` ke repository GitHub**
   ```bash
   git add docs/
   git commit -m "Add GitHub Pages compatible static site"
   git push origin main
   ```

2. **Konfigurasi GitHub Pages**
   - Buka repository di GitHub
   - Go to **Settings** → **Pages**
   - Pada "Source", pilih **Deploy from a branch**
   - Pilih branch `main` dan folder `/docs`
   - Klik **Save**

3. **Akses website**
   - Website akan tersedia di: `https://[username].github.io/web3-portfolio/`
   - (Ganti [username] dengan username GitHub Anda)

## Setup Contact Form (Formspree)

Form kontak menggunakan Formspree (gratis untuk 50 submissions/bulan):

1. **Daftar di Formspree**: https://formspree.io/
2. **Buat form baru** dan dapatkan form ID
3. **Edit `contact.html`** dan ganti `YOUR_FORM_ID` dengan ID Anda:
   ```html
   <form action="https://formspree.io/f/YOUR_FORM_ID" method="POST">
   ```
4. **(Opsional)** Update redirect URL di hidden field:
   ```html
   <input type="hidden" name="_next" value="https://[username].github.io/web3-portfolio/contact.html?success=true">
   ```

## Struktur File

```
docs/
├── index.html          # Halaman utama
├── contact.html        # Halaman kontak dengan form
├── favicon.svg         # Favicon
├── images/
│   └── profile.png     # Foto profil
└── README.md           # File ini
```

## Fitur yang Didukung

- ✅ Responsive design
- ✅ Dark/Light mode toggle dengan localStorage
- ✅ Static content (Profile, Experience, Projects, Education)
- ✅ Contact form via Formspree
- ✅ External links (GitHub, LinkedIn, Twitter)
- ✅ Google Analytics

## Fitur yang Tidak Didukung (dari versi Laravel)

- ❌ Admin dashboard
- ❌ Database-driven content (educations, messages)
- ❌ Authentication
- ❌ Dynamic sitemap
- ❌ Server-side form handling

## Customization

Untuk mengubah konten, edit langsung file HTML:
- **index.html**: Edit bagian Experience, Projects, dan Education
- **contact.html**: Update social links dan form settings

## Troubleshooting

**Gambar tidak muncul?**
- Pastikan path gambar benar: `images/profile.png`
- Periksa case sensitivity (GitHub Pages case-sensitive)

**CSS/JS tidak berfungsi?**
- Pastikan tidak ada error di browser console
- Clear browser cache

**Form tidak mengirim?**
- Pastikan Formspree ID sudah benar
- Periksa apakah form sudah di-activate di dashboard Formspree
