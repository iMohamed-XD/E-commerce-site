<?php

namespace Tests\Unit;

use ArrayObject;
use Illuminate\Mail\Message;
use Illuminate\Support\Facades\Log;
use Symfony\Component\Mailer\Event\FailedMessageEvent;
use Symfony\Component\Mailer\Exception\TransportException;
use Symfony\Component\Mailer\SentMessage;
use Symfony\Component\Mailer\Transport\AbstractTransport;
use Symfony\Component\Mime\RawMessage;
use Tests\TestCase;

class MailFailoverConfigTest extends TestCase
{
    public function test_failover_mailer_configuration_keeps_resend_primary_and_brevo_backup(): void
    {
        $this->assertSame('resend', config('mail.mailers.resend.transport'));
        $this->assertSame('smtp', config('mail.mailers.brevo.transport'));
        $this->assertSame(['resend', 'brevo'], config('mail.mailers.failover.mailers'));
    }

    public function test_mail_transport_failures_are_logged_with_sanitized_context(): void
    {
        config()->set('mail.default', 'failover');
        config()->set('mail.mailers.failover.mailers', ['resend', 'brevo']);

        Log::spy();

        event(new FailedMessageEvent(
            new RawMessage('mail body'),
            new \RuntimeException('smtp://user:secret@example.com')
        ));

        Log::shouldHaveReceived('warning')
            ->once()
            ->withArgs(function (string $message, array $context): bool {
                $this->assertStringContainsString('Mail transport attempt failed', $message);
                $this->assertSame('failover', $context['default_mailer']);
                $this->assertSame(['resend', 'brevo'], $context['failover_mailers']);
                $this->assertSame(RawMessage::class, $context['message_class']);
                $this->assertSame(\RuntimeException::class, $context['error_class']);
                $this->assertSame(0, $context['error_code']);
                $this->assertArrayNotHasKey('error_message', $context);

                return true;
            });
    }

    public function test_failover_mailer_falls_back_to_backup_transport_when_primary_transport_fails(): void
    {
        $mailManager = app('mail.manager');
        $events = app('events');
        $capturedMessages = new ArrayObject();

        $mailManager->extend('always_fail', function () use ($events) {
            return new class($events) extends AbstractTransport
            {
                public function __construct($events)
                {
                    parent::__construct($events);
                }

                protected function doSend(SentMessage $message): void
                {
                    throw new TransportException('Forced transport failure for testing.');
                }

                public function __toString(): string
                {
                    return 'always_fail';
                }
            };
        });

        $mailManager->extend('capture', function () use ($events, $capturedMessages) {
            return new class($events, $capturedMessages) extends AbstractTransport
            {
                public function __construct($events, private ArrayObject $capturedMessages)
                {
                    parent::__construct($events);
                }

                protected function doSend(SentMessage $message): void
                {
                    $this->capturedMessages->append($message);
                }

                public function __toString(): string
                {
                    return 'capture';
                }
            };
        });

        config()->set('mail.default', 'failover');
        config()->set('mail.mailers.resend.transport', 'always_fail');
        config()->set('mail.mailers.brevo.transport', 'capture');

        $mailManager->forgetMailers();

        $mailManager->mailer('failover')->raw('Failover body', function (Message $message): void {
            $message->to('buyer@example.com')->subject('Failover test');
        });

        $this->assertCount(1, $capturedMessages);
        $this->assertSame('Failover test', $capturedMessages[0]->getOriginalMessage()->getSubject());
    }
}
