<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerificationEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public $user_email;
  public function __construct($user_email)
  {
    $this->user_email = $user_email;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject = "Account Verification";
    $name = "BPLS Currimao";
    $address = "arellanokaeresvil@gmail.com";
    return $this->view('emails.verification')
      ->from($address, $name)
      ->subject($subject)
      ->with([
        'user_email'=> $this->user_email
      ]);
  }
}
