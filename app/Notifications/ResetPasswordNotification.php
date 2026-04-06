<?php

namespace App\Notifications;

use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Support\Facades\Lang;

class ResetPasswordNotification extends ResetPassword
{
    /**
     * Build the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject(Lang::get('إعادة تعيين كلمة المرور | Mahly'))
            ->greeting(Lang::get('طلب إعادة تعيين كلمة المرور'))
            ->line(Lang::get('تلقينا طلباً لإعادة تعيين كلمة المرور الخاصة بحسابك في محلي.'))
            ->action(Lang::get('إعادة تعيين كلمة المرور'), route('password.reset', ['token' => $this->token, 'email' => $notifiable->getEmailForPasswordReset()]))
            ->line(Lang::get('تنتهي صلاحية هذا الرابط خلال :count دقيقة.', ['count' => config('auth.passwords.'.config('auth.defaults.passwords').'.expire')]))
            ->line(Lang::get('إذا لم تطلب إعادة تعيين كلمة المرور، فلا داعي لاتخاذ أي إجراء.'))
            ->salutation(Lang::get('مع تحيات فريق محلي'));
    }
}
