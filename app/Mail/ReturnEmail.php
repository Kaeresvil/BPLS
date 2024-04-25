<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ReturnEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */

  public $ref_no;
  public function __construct($ref_no)
  {
    $this->ref_no = $ref_no;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject = "Citizencare: RETURNED | ". $this->ref_no;
    $name = "BPLS Currimao";
    $address = "arellanokaeresvil@gmail.com";
    return $this->view('emails.returned')
      ->from($address, $name)
      ->subject($subject)
      ->with([
        'ref_no'=> $this->ref_no
      ]);
  }
}
