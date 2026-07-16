@php
    $status = strtolower($submission->current_status);
    $badgeBg = '#e2e8f0';
    $badgeColor = '#475569';
    $borderColor = '#94a3b8';
    
    if (str_contains($status, 'setuju') || str_contains($status, 'acc')) {
        $badgeBg = '#059669';
        $badgeColor = '#ffffff';
        $borderColor = '#059669';
    } elseif (str_contains($status, 'tolak')) {
        $badgeBg = '#dc2626';
        $badgeColor = '#ffffff';
        $borderColor = '#dc2626';
    } elseif (str_contains($status, 'proses') || str_contains($status, 'verifikasi')) {
        $badgeBg = '#d97706';
        $badgeColor = '#ffffff';
        $borderColor = '#d97706';
    }
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Pengajuan Penelitian Berhasil</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f1f5f9; font-family: 'Quicksand', 'Outfit', 'Segoe UI', Arial, sans-serif; -webkit-font-smoothing: antialiased;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f1f5f9; padding: 30px 0;">
        <tr>
            <td align="center">
                <!-- Header Logo -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 20px;">
                    <tr>
                        <td style="padding: 0 10px; font-family: 'Outfit', 'Segoe UI', Arial, sans-serif; font-size: 20px; font-weight: bold; color: #0A2240; text-transform: uppercase; letter-spacing: 0.5px;">
                            <span style="color: #F4A261; margin-right: 8px;">🏛</span> Pengadilan Tinggi Tanjungkarang
                        </td>
                    </tr>
                </table>

                <!-- Main Container Card -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; border-top: 5px solid #0A2240; border-radius: 8px; box-shadow: 0 4px 12px rgba(10, 34, 64, 0.05); overflow: hidden; text-align: left;">
                    <tr>
                        <td style="padding: 40px;">
                            <!-- Salutation -->
                            <h2 style="margin: 0 0 20px 0; font-family: 'Outfit', 'Segoe UI', sans-serif; font-size: 22px; font-weight: 700; color: #0A2240;">
                                Halo {{ $submission->name }},
                            </h2>

                            <!-- Intro Message -->
                            <p style="margin: 0 0 30px 0; font-size: 16px; line-height: 1.6; color: #4a5568;">
                                Terima kasih telah mengajukan permohonan penelitian.<br><br>
                                Nomor Register Anda:<br>
                                <strong style="font-size: 18px; color: #0A2240; font-family: monospace;">{{ $submission->registration_number }}</strong><br><br>
                                Silakan simpan nomor register tersebut untuk melakukan pelacakan status permohonan.
                            </p>

                            <!-- Status Box (Highlight Card) -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border-left: 4px solid {{ $borderColor }}; border-radius: 4px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 24px;">
                                        <!-- Status Saat Ini -->
                                        <p style="margin: 0 0 8px 0; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Status Saat Ini
                                        </p>
                                        <div style="margin-bottom: 20px;">
                                            <span style="display: inline-block; background-color: {{ $badgeBg }}; color: {{ $badgeColor }}; font-size: 13px; font-weight: 700; padding: 6px 16px; border-radius: 9999px; text-transform: uppercase; letter-spacing: 0.5px;">
                                                {{ $submission->current_status }}
                                            </span>
                                        </div>

                                        <!-- Judul Penelitian -->
                                        <p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Judul Penelitian
                                        </p>
                                        <p style="margin: 0 0 20px 0; font-size: 15px; font-weight: 500; color: #0d1b2a; line-height: 1.5;">
                                            {{ $submission->research_title ?? $submission->title }}
                                        </p>

                                        <!-- Tanggal Pengajuan -->
                                        <p style="margin: 0 0 6px 0; font-size: 14px; font-weight: 600; color: #64748b; text-transform: uppercase; letter-spacing: 0.5px;">
                                            Tanggal Pengajuan
                                        </p>
                                        <p style="margin: 0; font-size: 15px; font-weight: 500; color: #0d1b2a;">
                                            {{ $submission->created_at->translatedFormat('d F Y, H:i') }} WIB
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Instructions -->
                            <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.6; color: #4a5568; font-style: italic;">
                                Silakan cek status pengajuan secara berkala melalui tautan resmi di bawah ini untuk melihat perkembangan verifikasi berkas permohonan Anda.
                            </p>

                            <!-- Button -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 35px;">
                                <tr>
                                    <td align="left">
                                        <a href="{{ url('/track?registration_number=' . $submission->registration_number) }}" style="display: inline-block; background-color: #0A2240; color: #ffffff; font-family: 'Outfit', 'Segoe UI', sans-serif; font-size: 16px; font-weight: 600; text-decoration: none; padding: 14px 28px; border-radius: 6px; box-shadow: 0 4px 6px rgba(10, 34, 64, 0.15);">
                                            Cek Status Pengajuan &nbsp; →
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 0 0 25px 0;">

                            <!-- Sign Off -->
                            <p style="margin: 0 0 4px 0; font-size: 15px; color: #4a5568;">
                                Hormat kami,
                            </p>
                            <p style="margin: 0; font-size: 16px; font-weight: 700; color: #0A2240;">
                                Pengadilan Tinggi Tanjungkarang
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Verified footer inside white card -->
                    <tr>
                        <td style="background-color: #eff6ff; padding: 20px 40px; text-align: center; border-bottom-left-radius: 8px; border-bottom-right-radius: 8px; border-top: 1px solid #dbeafe;">
                            <p style="margin: 0 0 4px 0; font-size: 13px; font-weight: 600; color: #1e40af;">
                                🛡️ Email Terverifikasi Resmi
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #3b82f6; letter-spacing: 0.5px; font-family: monospace;">
                                ID TRANSAKSI: {{ $submission->registration_number }}
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Email Footer (outside card) -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; text-align: center;">
                    <tr>
                        <td style="padding: 0 10px;">
                            <p style="margin: 0 0 8px 0; font-family: 'Outfit', sans-serif; font-size: 15px; font-weight: 700; color: #475569;">
                                Pengadilan Tinggi Tanjungkarang
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 12px; line-height: 1.5; color: #64748b;">
                                Pesan ini dibuat secara otomatis oleh sistem informasi. Harap tidak membalas email ini secara langsung.
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 13px; color: #94a3b8;">
                                <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; margin: 0 8px;">Kebijakan Privasi</a> | 
                                <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; margin: 0 8px;">Syarat dan Ketentuan</a> | 
                                <a href="{{ url('/') }}" style="color: #64748b; text-decoration: none; margin: 0 8px;">Bantuan</a>
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #94a3b8;">
                                &copy; 2024 Pengadilan Tinggi Tanjungkarang. Seluruh Hak Cipta Dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
