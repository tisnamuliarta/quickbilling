<?php

namespace App\Mail\Documents;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Str;

class DocumentSend extends Mailable
{
    use Queueable, SerializesModels;

    public $form;

    public $documents;

    public $files;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($form, $documents, $files)
    {
        $this->form = $form;
        $this->documents = $documents;
        $this->files = $files;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $url = config('app.front_url').'/view?no='.$this->documents->document_number;
        $file_name = Str::upper($this->documents->document_number).'.pdf';

        return $this->from($this->form['from_email'])
            ->markdown('emails.documents.send', [
                'url' => $url,
                'messages' => $this->form['messages'],
                'documents' => $this->documents,
            ])
            ->attachData($this->files, $file_name, [
                'mime' => 'application/pdf',
            ]);
    }
}
