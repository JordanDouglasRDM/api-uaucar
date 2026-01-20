<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PasswordResetMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;
    protected readonly string $url;
    protected readonly ?string $logoUrl;
    protected readonly ?string $firmName;

    /**
     * Create a new message instance.
     */
    public function __construct(string $url, ?string $logoUrl, ?string $firmName)
    {
        $this->url = $url;
        $this->logoUrl = $logoUrl ? url($logoUrl) : null;
        $this->firmName = $firmName;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build(): PasswordResetMail
    {
        $appName = config('app.name');
        $subject = "Recuperação de Senha - $appName";
        return $this->view('mail.password-reset')
            ->with([
                'url' => $this->url,
                'logoUrl' => $this->logoUrl,
                'firmName' => $this->firmName
            ])
            ->subject($subject)
            ->onQueue('emails');
    }
}
