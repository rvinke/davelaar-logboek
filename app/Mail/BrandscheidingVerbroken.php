<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class BrandscheidingVerbroken extends Mailable
{
    use Queueable, SerializesModels;

    public $project;
    public $log;
    public $report;

    /**
     * Create a new message instance.
     *
     * @var $project
     * @var $log
     * @var $report
     * @return void
     */
    public function __construct($project, $log, $report)
    {
        $this->project = $project;
        $this->log = $log;
        $this->report = $report;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->from('info@davelaar.nl', 'Davelaarbouw B.V.')
            ->subject('Er is een brandscheiding verbroken')
            ->view('emails.report');
    }
}
