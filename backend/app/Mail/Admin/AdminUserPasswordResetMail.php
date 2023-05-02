<?php
declare(strict_types=1);

namespace App\Mail\Admin;

use App\Mail\Mail;
use App\Models\Entities\AdminUser;

class AdminUserPasswordResetMail extends Mail
{
    /**
     * @var AdminUser
     */
    public $user;

    /**
     * Create a new message instance.
     *
     */
    public function __construct(AdminUser $user)
    {
        $this->user = $user;

        $this->headers['from'] = [
            'address' => config('mail.admin.user-password-reset.from.address'),
            'name'    => config('mail.admin.user-password-reset.from.name'),
        ];
        $this->headers['return-path']   = config('mail.admin.user-password-reset.return-path', config('mail.from.return-path'));
        $this->headers['envelope-from'] = config('mail.admin.user-password-reset.envelope-from', config('mail.from.envelope-from'));
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        parent::build();

        $subject  = config('mail.admin.user-password-reset.subject');
        $textView = 'emails.admin.user-password-reset-text';

        return $this->subject($subject)
                    ->text($textView);
    }
}
