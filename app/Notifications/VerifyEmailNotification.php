<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyEmailNotification extends VerifyEmail
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $verificationUrl = $this->verificationUrl($notifiable);

        return (new MailMessage)
            ->subject('تفعيل حسابك في Mahly')
            ->greeting('مرحباً بك في Mahly!')
            ->line('يسعدنا انضمامك إلينا. يرجى الضغط على الزر أدناه لتفعيل بريدك الإلكتروني والبدء في استكشاف المنتجات المحلية الفاخرة.')
            ->action('تفعيل البريد الإلكتروني', $verificationUrl)
            ->line('إذا لم تقم بإنشاء حساب، فلا داعي لاتخاذ أي إجراء.')
            ->salutation('مع تحيات فريق Mahly');
    }
}
