<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ForgotPassword extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */
  public $user_email;
  public $password;
  public function __construct($user_email, $password)
  {
    $this->user_email = $user_email;
    $this->password = $password;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject = "Your New Temporary Password";
    $name = "BPLS Currimao";
    $address = "arellanokaeresvil@gmail.com";
    return $this->view('emails.forgot-password')
      ->from($address, $name)
      ->subject($subject)
      ->with([
        'user_email'=> $this->user_email,
        'password'=> $this->password
      ]);
  }
}
