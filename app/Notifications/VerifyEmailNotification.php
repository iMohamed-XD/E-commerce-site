<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\VerifyEmail;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

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
            ->subject(Lang::get('تفعيل حسابك في محلي | Mahly'))
            ->greeting(Lang::get('مرحباً بك في محلي!'))
            ->line(Lang::get('يسعدنا انضمامك إلينا. يرجى الضغط على الزر أدناه لتفعيل بريدك الإلكتروني والبدء في استكشاف المنتجات المحلية الفاخرة.'))
            ->action(Lang::get('تفعيل البريد الإلكتروني'), $verificationUrl)
            ->line(Lang::get('إذا لم تقم بإنشاء حساب، فلا داعي لاتخاذ أي إجراء.'))
            ->salutation(Lang::get('مع تحيات فريق محلي'));
    }
}
