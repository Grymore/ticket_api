<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Client\Request;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;

class KirimEmail extends Mailable
{

    use Queueable, SerializesModels;
    public $data;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->data = $data;
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope;
    }


    public function build()
{
    //kalian bisa mengirim variabel yang berasal dari database ke view
    //bisa menggunakan eloquent atau query builder dan diparsing ke view


   

    // return $this->view('pesanemail',[
    //     "email" => $namacust
    // ]);

        return $this->markdown('pesanemail')
                    ->subject('E-Tiket Konser Denny Cak Nan 2023 - Madiun')
                    ->with('data', $this->data);
}

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function content()
    {   

     
        return new Content(
            view: '',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
