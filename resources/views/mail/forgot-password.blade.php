<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password Anda</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f5f5f5; font-family: Arial, sans-serif;">
    <table width="100%" bgcolor="#f5f5f5" cellpadding="0" cellspacing="0" style="margin: 0; padding: 0;">
        <tr>
            <td align="center" style="padding: 20px 0;">
                <table width="100%" max-width="400px" bgcolor="#ffffff" cellpadding="0" cellspacing="0" style="border-radius: 15px; padding: 30px; box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.1);">
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <img src="https://raw.githubusercontent.com/superti4r/public_assets/refs/heads/main/esaturasi-mail-auth.png" alt="esaturasi-mail-auth" width="50" height="50" style="display: block;">
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <h3 style="margin: 0; font-size: 20px; color: #333;">Reset Password Anda</h3>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <p style="margin: 0; font-size: 16px; color: #555;">
                                Hai {{ $details['nama'] ?? 'Pengguna' }}, kami menerima permintaan untuk mereset password akun Anda.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <a href="{{ $details['url'] }}" style="background-color: #2446CE; color: #ffffff; text-decoration: none; padding: 10px 20px; font-size: 16px; border-radius: 5px; display: inline-block;">
                                Reset Password
                            </a>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-bottom: 20px;">
                            <p style="margin: 0; font-size: 14px; color: #777;">
                                Jika Anda tidak meminta reset password, abaikan email ini.
                            </p>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="padding-top: 20px; background-color: #f0f0f5; border-radius: 10px; padding: 15px;">
                            <p style="margin: 0; font-size: 14px; color: #6c757d;">
                                Email ini dikirim otomatis ke alamat email Anda. &mdash; E-Saturasi
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
