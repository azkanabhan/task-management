<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Team Invitation</title>
</head>
<body style="margin: 0; padding: 0; font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif; background-color: #f3f4f6; color: #1f2937; -webkit-font-smoothing: antialiased; -moz-osx-font-smoothing: grayscale;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f3f4f6; padding: 40px 0;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" max-width="600" cellspacing="0" cellpadding="0" border="0" style="max-width: 600px; width: 100%; background-color: #ffffff; border-radius: 16px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06); overflow: hidden;">
                    <!-- Header -->
                    <tr>
                        <td style="background: linear-gradient(135deg, #4f46e5 0%, #3b82f6 100%); padding: 32px; text-align: center;">
                            <h1 style="color: #ffffff; font-size: 24px; font-weight: 700; margin: 0; letter-spacing: -0.5px;">Task Management</h1>
                        </td>
                    </tr>
                    <!-- Content Body -->
                    <tr>
                        <td style="padding: 40px 32px;">
                            <h2 style="font-size: 20px; font-weight: 600; color: #111827; margin-top: 0; margin-bottom: 16px; letter-spacing: -0.3px;">You've Been Invited!</h2>
                            <p style="font-size: 16px; line-height: 24px; color: #4b5563; margin-top: 0; margin-bottom: 24px;">
                                Hello,
                            </p>
                            <p style="font-size: 16px; line-height: 24px; color: #4b5563; margin-top: 0; margin-bottom: 24px;">
                                <strong style="color: #111827;">{{ $senderName }}</strong> has invited you to join their team, <strong style="color: #111827;">{{ $invitation->team->name }}</strong>, as a <strong style="color: #111827;">{{ ucfirst($invitation->role) }}</strong>.
                            </p>

                            <!-- Details Card -->
                            @if ($invitation->team->description)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" border="0" style="background-color: #f9fafb; border-radius: 12px; border: 1px solid #e5e7eb; margin-bottom: 28px; width: 100%;">
                                    <tr>
                                        <td style="padding: 20px 24px;">
                                            <h3 style="margin-top: 0; margin-bottom: 8px; font-size: 14px; color: #6b7280; text-transform: uppercase; font-weight: 600; letter-spacing: 0.5px;">About the Team</h3>
                                            <p style="margin: 0; font-size: 15px; line-height: 22px; color: #111827; font-weight: 500;">
                                                {{ $invitation->team->description }}
                                            </p>
                                        </td>
                                    </tr>
                                </table>
                            @endif

                            <p style="font-size: 15px; line-height: 22px; color: #4b5563; margin-top: 0; margin-bottom: 28px;">
                                If you accept, you will be able to collaborate on tasks, projects, and see notifications for this team.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 36px; margin-bottom: 12px;">
                                <a href="{{ $teamsUrl }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 28px; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 8px; display: inline-block; transition: background-color 0.2s ease;">
                                    View Invitation & Join Team
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #f3f4f6;">
                            <p style="margin: 0 0 8px 0; font-size: 12px; color: #9ca3af;">
                                This invitation was sent to {{ $invitation->email }}.
                            </p>
                            <p style="margin: 0; font-size: 12px; color: #9ca3af;">
                                &copy; 2026 Task Management. All rights reserved.
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
