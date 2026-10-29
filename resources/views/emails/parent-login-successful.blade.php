<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi berhasil di Portal Admission</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f4f6f9; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; -webkit-font-smoothing: antialiased;">
    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color: #f4f6f9; padding: 40px 10px;">
        <tr>
            <td align="center">
                <!-- Main Container Card -->
                <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 540px; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);">
                    
                    <!-- Header Banner -->
                    <tr>
                        <td align="center" style="background: linear-gradient(135deg, #1e3a8a 0%, #2563eb 100%); padding: 36px 20px;">
                            <!-- Welcome Icon / Check -->
                             <div style="background-color: rgba(255, 255, 255, 0.2); width: 60px; height: 60px; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; margin-bottom: 12px; overflow: hidden;">
                                @if ($user->avatar)
                                    <img src="{{ $user->avatar }}" alt="Foto profil {{ $user->name }}" width="60" height="60" style="display: block; width: 60px; height: 60px; object-fit: cover;">
                                @else
                                    <svg width="30" height="30" viewBox="0 0 24 24" fill="none" stroke="#ffffff" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path d="M22 11.08V12a10 10 0 1 1-5.93-9.14"></path>
                                        <polyline points="22 4 12 14.01 9 11.01"></polyline>
                                    </svg>
                                @endif
                            </div>
                            <h1 style="color: #ffffff; margin: 0; font-size: 22px; font-weight: 700; letter-spacing: -0.3px;">Selamat Datang / Welcome</h1>
                            <p style="color: #dbeafe; font-size: 14px; margin: 6px 0 0 0;">Portal Admission {{ config('app.name') }}</p>
                        </td>
                    </tr>

                    <!-- Body Content -->
                    <tr>
                        <td style="padding: 32px 28px;">
                            <p style="color: #1f2937; font-size: 16px; margin: 0 0 16px 0; font-weight: 600;">
                                Halo, {{ $user->name }}! 👋
                            </p>

                            <!-- Indonesian Version -->
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 16px 0;">
                                Selamat, akun Anda berhasil dibuat di portal penerimaan siswa baru <strong>{{ config('app.name') }}</strong> menggunakan email <strong>{{ $user->email }}</strong>.
                            </p>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 24px 0;">
                                Sekarang Anda dapat melanjutkan proses pendaftaran serta melengkapi berkas dan data persyaratan langsung melalui dasbor akun Anda.
                            </p>

                            <hr style="border: 0; border-top: 1px solid #e2e8f0; margin: 24px 0;">

                            <!-- English Version -->
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 16px 0;">
                                <em>Welcome! Your account has been successfully created on the <strong>{{ config('app.name') }}</strong> admission portal using <strong>{{ $user->email }}</strong>.</em>
                            </p>
                            <p style="color: #4b5563; font-size: 14px; line-height: 1.6; margin: 0 0 28px 0;">
                                <em>You can now proceed with your registration and complete the required documents directly through your account dashboard.</em>
                            </p>

                            <!-- CTA Button -->
                            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin-bottom: 28px;">
                                <tr>
                                    <td align="center">
                                        <a href="{{ url('/parent/dashboard') }}" style="background-color: #2563eb; color: #ffffff; text-decoration: none; font-size: 14px; font-weight: 600; padding: 12px 28px; border-radius: 6px; display: inline-block; box-shadow: 0 2px 6px rgba(37, 99, 235, 0.3);">
                                            Masuk ke Dasbor / Go to Dashboard
                                        </a>
                                    </td>
                                </tr>
                            </table>

                            <!-- Security Note (Subtle) -->
                            <div style="background-color: #f8fafc; border: 1px dashed #cbd5e1; padding: 12px 16px; border-radius: 6px;">
                                <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin: 0 0 6px 0;">
                                    <strong>Catatan Keamanan:</strong> Jika Anda tidak merasa membuat akun ini, silakan segera hubungi Tim Admin Admission kami.
                                </p>
                                <p style="color: #64748b; font-size: 12px; line-height: 1.5; margin: 0;">
                                    <em><strong>Security Note:</strong> If you did not create this account, please contact our Admission Admin team immediately.</em>
                                </p>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f8fafc; padding: 20px 28px; border-top: 1px solid #f1f5f9; text-align: center;">
                            <p style="color: #64748b; font-size: 13px; margin: 0 0 4px 0;">
                                Salam hangat / <em>Warm regards</em>,
                            </p>
                            <p style="color: #1e293b; font-size: 14px; font-weight: 600; margin: 0 0 16px 0;">
                                Tim Admission {{ config('app.name') }}
                            </p>
                            <p style="color: #94a3b8; font-size: 11px; margin: 0;">
                                Email ini dikirim secara otomatis oleh sistem. / <em>This is an automated system email.</em>
                            </p>
                        </td>
                    </tr>

                </table>
            </td>
        </tr>
    </table>
</body>
</html>