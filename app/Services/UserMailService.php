<?php

namespace App\Services;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Crypt;

class UserMailService
{
    /**
     * Configure mail settings based on user credentials
     */
    public function configureUserMail($user)
    {
        Config::set('mail.mailers.user_specific', [
            'transport' => 'smtp',
            'host' => $user->smtp_host ?? env('MAIL_HOST'),
            'port' => $user->smtp_port ?? env('MAIL_PORT'),
            'encryption' => $user->smtp_encryption ?? env('MAIL_ENCRYPTION'),
            'username' => $user->email_username,
            'password' => Crypt::decryptString($user->email_password),
            'timeout' => null,
        ]);

        Config::set('mail.from', [
            'address' => $user->email,
            'name' => $user->name,
        ]);
    }

    /**
     * Send email using user's SMTP settings
     */
    public function sendWithUserConfig($user, $mailable)
{
    // Validate user object
    if (!$user || !is_object($user)) {
        \Log::error('Invalid user object provided to sendWithUserConfig');
        return $this->sendWithDefaultMailer($mailable);
    }

    // Verify required user properties
    if (empty($user->email) || empty($user->email_username) || empty($user->email_password)) {
        \Log::warning('User missing required email credentials', [
            'user_id' => $user->id ?? null,
            'has_email' => !empty($user->email),
            'has_username' => !empty($user->email_username),
            'has_password' => !empty($user->email_password),
        ]);
        return $this->sendWithDefaultMailer($mailable);
    }

    try {
        $this->configureUserMail($user);
        
        // Verify mailable has recipients
        if (empty($mailable->to) && empty($mailable->cc) && empty($mailable->bcc)) {
            throw new \RuntimeException('Mailable missing recipient headers');
        }

        Mail::mailer('user_specific')->send($mailable);
        return true;
        
    } catch (\Exception $e) {
        \Log::error('User-specific email failed', [
            'error' => $e->getMessage(),
            'user_id' => $user->id,
            'trace' => $e->getTraceAsString()
        ]);
        return $this->sendWithDefaultMailer($mailable);
    }
}

protected function sendWithDefaultMailer($mailable)
{
    try {
        if (empty($mailable->to) && empty($mailable->cc) && empty($mailable->bcc)) {
            throw new \RuntimeException('Cannot fallback - no recipients specified');
        }
        
        Mail::send($mailable);
        return true;
    } catch (\Exception $e) {
        \Log::critical('Fallback email failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return false;
    }
}
}
