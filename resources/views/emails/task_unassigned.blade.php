<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Unassigned from Task</title>
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
                            <h2 style="font-size: 20px; font-weight: 600; color: #111827; margin-top: 0; margin-bottom: 16px; letter-spacing: -0.3px;">Unassigned from Task</h2>
                            <p style="font-size: 16px; line-height: 24px; color: #4b5563; margin-top: 0; margin-bottom: 24px;">
                                Hello <strong style="color: #111827;">{{ $notifiable->name }}</strong>,
                            </p>
                            <p style="font-size: 16px; line-height: 24px; color: #4b5563; margin-top: 0; margin-bottom: 28px;">
                                You have been unassigned from the task <strong style="color: #111827;">"{{ $taskTitle }}"</strong> by <strong style="color: #111827;">{{ $updaterName }}</strong>.
                            </p>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-top: 36px; margin-bottom: 12px;">
                                <a href="{{ route('tasks.index') }}" style="background-color: #4f46e5; color: #ffffff; padding: 12px 28px; font-size: 15px; font-weight: 600; text-decoration: none; border-radius: 8px; display: inline-block; transition: background-color 0.2s ease;">
                                    Go to Tasks List
                                </a>
                            </div>
                        </td>
                    </tr>
                    <!-- Footer -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 24px; text-align: center; border-top: 1px solid #f3f4f6;">
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
