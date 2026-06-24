<!DOCTYPE html>
<html>
<head>
    <title>Perubahan Status Pengajuan</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333;">
    <h2>Halo {{ $submission->name }},</h2>
    
    <p>Status permohonan izin penelitian Anda (<strong>{{ $submission->registration_number }}</strong>) telah diperbarui.</p>
    
    <ul>
        <li><strong>Status Terbaru:</strong> <span style="font-size: 16px; font-weight: bold; color: #d9534f;">{{ $submission->current_status }}</span></li>
        <li><strong>Catatan Admin:</strong> {{ $submission->admin_notes ?: '-' }}</li>
        <li><strong>Tanggal Update:</strong> {{ now()->format('d/m/Y H:i') }}</li>
    </ul>

    <p>Silakan login atau cek status pengajuan secara berkala melalui link berikut:</p>
    <p><a href="{{ url('/track-status?reg=' . $submission->registration_number) }}" style="background-color: #007bff; color: white; padding: 10px 15px; text-decoration: none; border-radius: 5px;">Cek Status Pengajuan</a></p>

    <br>
    <p>Hormat kami,</p>
    <p><strong>Pengadilan Tinggi Tanjungkarang</strong></p>
</body>
</html>
