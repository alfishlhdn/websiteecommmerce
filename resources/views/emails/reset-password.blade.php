@component('mail::message')
<!-- Header (text logo) -->
<div style="text-align: center; margin-bottom: 18px;">
    <div style="font-weight:700; font-size:20px; color:#0f172a;">
        AGA IT COMPUTER
    </div>
    <div style="font-size:12px; color:#6b7280; margin-top:4px;">
        Toko Komputer &amp; Service Laptop — Malang
    </div>
</div>

# Halo {{ $user->name ?? 'Pengguna' }},

Kami menerima permintaan untuk mengatur ulang kata sandi akun Anda. Untuk melanjutkan proses, silakan klik tombol di bawah ini:

@component('mail::button', ['url' => $url, 'color' => 'primary'])
Reset Password
@endcomponent

<div style="margin-top: 18px; color:#6b7280; font-size:13px;">
    Tautan ini hanya berlaku selama <strong>60 menit</strong>. Jika tautan kedaluwarsa, silakan lakukan permintaan reset kembali melalui halaman <em>Lupa Password</em>.
</div>

<div style="margin-top:18px; font-size:13px; color:#374151;">
    Jika Anda mengalami kendala saat mengklik tombol di atas, salin dan tempel URL berikut ke peramban:
    <div style="word-break:break-all; margin-top:8px; color:#4b5563;">{{ $url }}</div>
</div>

<hr style="border:none; border-top:1px solid #e6e9ee; margin:22px 0;">

<div style="font-size:13px; color:#374151;">
    <strong>Keamanan &amp; Informasi Penting</strong>
    <ul style="margin:8px 0 0 18px; color:#6b7280;">
        <li>Kami tidak pernah meminta kata sandi Google Anda. Jika Anda login dengan Google (SSO), pengelolaan sandi dilakukan melalui akun Google.</li>
        <li>Jika Anda tidak meminta pengaturan ulang ini, abaikan email ini atau hubungi tim kami segera.</li>
    </ul>
</div>

<div style="margin-top:18px; font-size:13px; color:#6b7280;">
    Salam,<br>
    <strong>Tim AGA IT COMPUTER</strong><br>
    Jl. Watu Gede, Ruko Chandra Kirana, Watugede — Singosari, Malang<br>
    WA: <a href="https://wa.me/6281333892111" style="color:#2563eb">+62 813-3389-2111</a> • Email: <a href="mailto:agakomputer25@gmail.com" style="color:#2563eb">agakomputer25@gmail.com</a>
</div>
@endcomponent
