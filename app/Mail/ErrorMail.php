<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Sentinel;
class ErrorMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $url)
    {
        $this->request = $request;
        $this->request_uri = $url;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('email.error_mail')
                    ->subject( "Prijava " . " greške " . " - ".  config('app.name'))
                    ->with([
                        'request' =>  $this->request,
                        'user' => Sentinel::getUser()->first_name .' '. Sentinel::getUser()->last_name,
                        'user_mail' => Sentinel::getUser()->email,
                        'request_uri' => $this->request_uri,
                        'url' => $_SERVER['HTTP_HOST']
                    ]);
    }
}
