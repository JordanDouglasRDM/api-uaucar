<?php

declare(strict_types = 1);

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable;
    use SerializesModels;

    protected readonly ?string $logoUrl;

    /**
     * Create a new message instance.
     */
    public function __construct(protected readonly string $url, ?string $logoUrl, protected readonly ?string $firmName)
    {
        $this->logoUrl = $logoUrl ? url($logoUrl) : null;
    }

    /**
     * Build the message.
     */
    public function build(): PasswordResetMail
    {
        $appName = config('app.name');
        $subject = 'Recuperação de Senha - ' . $appName;

        return $this->view('mail.password-reset')
            ->with([
                'url'      => $this->url,
                'logoUrl'  => $this->logoUrl,
                'firmName' => $this->firmName,
            ])
            ->subject($subject)
            ->onQueue('emails');
    }
}
