<?php
declare(strict_types=1);

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Mail\Mailable;

class MailSent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * @var Mailable
     */
    public $mail;

    /**
     * @var string
     */
    public $recipient;

    /**
     * @var bool
     */
    public $success;

    /**
     * Create a new event instance.
     *
     * @param  Mailable  $mail
     * @param  string    $recipient
     * @param  bool      $success
     * @return void
     */
    public function __construct(
        Mailable $mail,
        string $recipient,
        bool $success
    ) {
        $this->mail      = $mail;
        $this->recipient = $recipient;
        $this->success   = $success;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
