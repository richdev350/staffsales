<?php
declare(strict_types=1);

namespace App\Services\Traits;

use Illuminate\Support\Facades\Mail;
use Illuminate\Mail\Mailable as MailableMail;
use App\Events\MailSent;

/**
 * メール送信用トレイト
 */
trait Mailable
{
    /**
     * @var array
     */
    protected $reportedFailureRecipients = [];

    /**
     * メールを送信する
     *
     * @param  MailableMail  $mail
     * @param  array     $recipients
     * @return bool  全件送信失敗の場合のみfalseを返す
     */
    public function send(MailableMail $mail, array $recipients): bool
    {
        $result = false;

        foreach ($recipients as $recipient) {
            Mail::to($recipient)->send($mail);

            if (0 < count(Mail::failures())) {
                foreach (Mail::failures() as $failureRecipient) {
                    if (false === array_search($failureRecipient, $this->reportedFailureRecipients)) {
                        event(new MailSent($mail, $failureRecipient, false));
                        array_push($this->reportedFailureRecipients, $failureRecipient);
                    }
                }
            } else {
                event(new MailSent($mail, $recipient, true));
                $result = true;
            }
        }

        return $result;
    }
}
