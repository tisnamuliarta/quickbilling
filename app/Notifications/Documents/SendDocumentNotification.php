<?php

namespace App\Notifications\Documents;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendDocumentNotification extends Notification
{
    use Queueable;

    protected $documents;

    protected $messages;

    protected $attachments;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($documents, $messages, $attachments)
    {
        $this->documents = $documents;
        $this->messages = $messages;
        $this->attachments = $attachments;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $url = config('app.front_url').'/view?no='.$this->documents->document_number;

        return (new MailMessage)
            ->greeting('Dear '.$this->documents->contact_name)
            ->line($this->messages)
            ->action('View Invoice', $url)
            ->line('Thank you for using our application!')
            ->attach($this->attachments);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'document_number' => $this->documents->document_number,
            'type' => $this->documents->type,
        ];
    }
}
