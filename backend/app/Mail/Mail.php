<?php
declare(strict_types=1);

namespace App\Mail;

use Swift_Mime_ContentEncoder_PlainContentEncoder;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Mail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * メールの文字コード
     *
     * @var string
     */
    public $charset = 'utf-8';

    /**
     * メールヘッダー
     *
     * @var array
     */
    protected $headers = [];

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        if (isset($this->headers['from']['address']) && null != $this->headers['from']['address']) {
            $this->from($this->headers['from']['address'], ($this->headers['from']['name'] ?? null));
        }

        $this->withSwiftMessage(function ($message) {
            $message->setEncoder(new Swift_Mime_ContentEncoder_PlainContentEncoder('7bit'));
            $message->setCharset($this->charset);
            $message->setMaxLineLength(0);

            if (isset($this->headers['return-path']) && null != $this->headers['return-path']) {
                $message->setReturnPath($this->headers['return-path']);
            }
            if (isset($this->headers['envelope-from']) && null != $this->headers['envelope-from']) {
                $message->getHeaders()->addTextHeader('Envelope-From', $this->headers['envelope-from']);
            }
        });

        // NOTE: subject や text|view については継承先サブクラスで実装
        return $this;
    }
}
