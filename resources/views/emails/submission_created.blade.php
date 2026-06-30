<!DOCTYPE html>
<html>
<head>
    <title>Pengajuan Penelitian Berhasil</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo {{ $submission->name }},</h2>
    
    <p>Terima kasih telah mengajukan permohonan izin penelitian di Pengadilan Tinggi Tanjungkarang.</p>
    
    <p>Berikut adalah detail pengajuan Anda:</p>
    <ul>
        <li><strong>Nomor Registrasi:</strong> {{ $submission->registration_number }}</li>
        <li><strong>Judul Penelitian:</strong> {{ $submission->title }}</li>
        <li><strong>Tanggal Pengajuan:</strong> {{ $submission->created_at->translatedFormat('d F Y, H:i') }} WIB</li>
        <li><strong>Status Saat Ini:</strong> {{ $submission->current_status }}</li>
    </ul>

    <p>Anda dapat mengecek status pengajuan secara berkala melalui link berikut:</p>
    <p><a href="{{ url('/track-status?reg=' . $submission->registration_number) }}" style="background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Cek Status Pengajuan</a></p>

    <br>
    <p>Hormat kami,</p>
    <p><strong>Pengadilan Tinggi Tanjungkarang</strong></p>
</body>
</html>
