<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class AppointmentEmail extends Mailable
{
  use Queueable, SerializesModels;

  /**
   * Create a new message instance.
   *
   * @return void
   */

  public $ref_no;
  public $date;
  public $resched;
  public function __construct($ref_no, $date, $resched )
  {
    $this->ref_no = $ref_no;
    $this->date = $date;
    $this->resched = $resched;
  }

  /**
   * Build the message.
   *
   * @return $this
   */
  public function build()
  {
    $subject =  $this->resched ? "Citizencare: RE-SCHEDULED APPOINTMENT  | ". $this->ref_no:"Citizencare: APPOINTMENT | ". $this->ref_no;
    $name = "BPLS Currimao";
    $address = "arellanokaeresvil@gmail.com";
    return $this->view('emails.appointment')
      ->from($address, $name)
      ->subject($subject)
      ->with([
        'ref_no'=> $this->ref_no,
        'date'=> $this->date
      ]);
  }
}
