@php
    $start = $submission->start_date ? \Carbon\Carbon::parse($submission->start_date)->locale('id')->translatedFormat('d F Y') : '-';
    
    // Generate letter number dynamically
    $nomorSurat = \App\Services\LetterService::generateLetterNumber($submission->registration_number, $submission->updated_at ?? now());
@endphp
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Penunjukan Hakim Pendamping Penelitian</title>
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
                            <h2 style="margin: 0 0 20px 0; font-family: 'Outfit', 'Segoe UI', sans-serif; font-size: 20px; font-weight: 700; color: #0A2240;">
                                Yth. Bapak/Ibu {{ $submission->hakim->nama_hakim ?? 'Hakim Pendamping' }},
                            </h2>

                            <!-- Intro Message -->
                            <p style="margin: 0 0 30px 0; font-size: 15px; line-height: 1.6; color: #4a5568;">
                                Diberitahukan dengan hormat bahwa Bapak/Ibu telah ditunjuk sebagai **Hakim Pendamping Penelitian** pada Pengadilan Tinggi Tanjungkarang untuk permohonan berikut:
                            </p>

                            <!-- Submission Details Table -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px; margin-bottom: 30px; font-size: 14px; color: #334155;">
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; width: 35%; color: #0A2240;">Nama Pemohon</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">{{ $submission->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0A2240;">Nomor Registrasi</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-family: monospace; font-weight: bold; color: #0f172a;">{{ $submission->registration_number }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0A2240;">Nomor Surat Riset</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-family: monospace; font-weight: bold;">{{ trim($nomorSurat) }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0A2240;">Judul Penelitian</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; line-height: 1.5; font-weight: 500;">{{ $submission->research_title }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0A2240;">Konsentrasi</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: bold; color: #1e3a8a;">{{ $submission->konsentrasi ?? '-' }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0; font-weight: 700; color: #0A2240;">Tanggal Penelitian</td>
                                    <td style="padding: 16px 20px; border-bottom: 1px solid #e2e8f0;">{{ $start }}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 16px 20px; font-weight: 700; color: #0A2240;">Jadwal Pendampingan</td>
                                    <td style="padding: 16px 20px;">{{ $start }}</td>
                                </tr>
                            </table>

                            <!-- Important Note -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #eff6ff; border-left: 4px solid #3b82f6; border-radius: 4px; margin-bottom: 30px;">
                                <tr>
                                    <td style="padding: 16px 20px;">
                                        <p style="margin: 0; font-size: 14px; line-height: 1.5; color: #1e3a8a; font-weight: 500;">
                                            ℹ️ **Informasi penting:** Bapak/Ibu Hakim dimohon untuk mengetahui atau mendampingi pelaksanaan penelitian pemohon sesuai dengan jadwal yang telah ditentukan di atas.
                                        </p>
                                    </td>
                                </tr>
                            </table>

                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 0 0 25px 0;">

                            <!-- Sign Off -->
                            <p style="margin: 0 0 4px 0; font-size: 14px; color: #4a5568;">
                                Hormat kami,
                            </p>
                            <p style="margin: 0; font-size: 15px; font-weight: 700; color: #0A2240;">
                                Bagian Hukum & Hubungan Masyarakat<br>
                                Pengadilan Tinggi Tanjungkarang
                            </p>
                        </td>
                    </tr>
                </table>

                <!-- Email Footer (outside card) -->
                <table width="600" cellpadding="0" cellspacing="0" border="0" style="margin-top: 30px; text-align: center;">
                    <tr>
                        <td style="padding: 0 10px;">
                            <p style="margin: 0 0 8px 0; font-family: 'Outfit', sans-serif; font-size: 14px; font-weight: 700; color: #475569;">
                                Pengadilan Tinggi Tanjungkarang
                            </p>
                            <p style="margin: 0 0 20px 0; font-size: 11px; line-height: 1.5; color: #64748b;">
                                Pesan ini dibuat secara otomatis oleh sistem informasi E-Riset. Harap tidak membalas email ini secara langsung.
                            </p>
                            <p style="margin: 0; font-size: 11px; color: #94a3b8;">
                                &copy; 2026 Pengadilan Tinggi Tanjungkarang. Seluruh Hak Cipta Dilindungi.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
