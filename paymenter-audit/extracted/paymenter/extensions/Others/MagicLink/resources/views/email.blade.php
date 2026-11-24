<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Magic Link</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; margin: 0; padding: 0;">
    <div style="max-width: 600px; margin: 0 auto; padding: 40px 20px;">
        <div style="text-align: center; margin-bottom: 30px;">
            <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="#4f46e5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
            </svg>
        </div>
        
        <h1 style="color: #1f2937; text-align: center; margin-bottom: 20px;">Login to Your Account</h1>
        
        <p style="color: #4b5563; margin-bottom: 20px;">Hi {{ $user->name ?? 'there' }},</p>
        
        <p style="color: #4b5563; margin-bottom: 30px;">Click the button below to login to your account. This link will expire in {{ config('extensions.magic_link.token_expiry', 15) }} minutes.</p>
        
        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ $loginUrl }}" 
               style="background-color: #4f46e5; color: white; padding: 14px 28px; text-decoration: none; border-radius: 6px; font-weight: bold; display: inline-block;">
                Login with Magic Link
            </a>
        </div>
        
        <p style="color: #6b7280; font-size: 14px; text-align: center; margin-top: 30px;">
            If you didn't request this email, you can safely ignore it.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e5e7eb; margin: 30px 0;">
        
        <p style="color: #9ca3af; font-size: 12px; text-align: center;">
            This email was sent to {{ $user->email ?? $email }}.
        </p>
    </div>
</body>
</html>
