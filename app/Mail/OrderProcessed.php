<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;


class OrderProcessed extends Mailable{
    use Queueable, SerializesModels;

    public $orderDetails;
    /**
     * Create a new message instance.
     */
    public function __construct($orderDetails){
        $this->orderDetails = $orderDetails;
    }
    /**
     * Build the message.
     */
    public function build(){
        return $this->markdown('emails.orders.processed')->subject('Order Processed');
    }
    // public function build(){
    //     return $this->view('emails.orders.processed')->subject('Order Processed')->with('orderDetails', $this->orderDetails);
    // }

    /**
     * Get the message envelope.
     */
    // public function envelope(): Envelope{
    //     return new Envelope(
    //         subject: 'Order Processed',
    //     );
    // }

    // /**
    //  * Get the message content definition.
    //  */
    // public function content(): Content{
    //     return new Content(
    //         markdown: 'emails.orders.processed',
    //     );
    // }

    // /**
    //  * Get the attachments for the message.
    //  *
    //  * @return array<int, \Illuminate\Mail\Mailables\Attachment>
    //  */
    // public function attachments(): array{
    //     return [];
    // }
}
