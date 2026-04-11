<?php

namespace App\Providers;

use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Event\FailedMessageEvent;
use Symfony\Component\Mime\Email;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Event::listen(FailedMessageEvent::class, function (FailedMessageEvent $event): void {
            $message = $event->getMessage();
            $error = $event->getError();

            $context = [
                'default_mailer' => config('mail.default'),
                'failover_mailers' => config('mail.mailers.failover.mailers', []),
                'message_class' => $message::class,
                'error_class' => $error::class,
                'error_code' => is_scalar($error->getCode()) ? $error->getCode() : null,
            ];

            if ($message instanceof Email) {
                $context['to_count'] = count($message->getTo());
                $context['cc_count'] = count($message->getCc());
                $context['bcc_count'] = count($message->getBcc());
                $context['has_attachments'] = count($message->getAttachments()) > 0;
            }

            Log::warning(
                'Mail transport attempt failed; Laravel will continue through the configured failover mailers if another transport is available.',
                $context
            );
        });
    }
}
