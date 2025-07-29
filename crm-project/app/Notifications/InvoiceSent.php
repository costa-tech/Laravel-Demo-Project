<?php

namespace App\Notifications;

use App\Models\Invoice;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class InvoiceSent extends Notification
{
    use Queueable;

    private $invoice;

    public function __construct(Invoice $invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
                    ->subject('Invoice #' . $this->invoice->invoice_number)
                    ->greeting('Hello ' . $this->invoice->customer->name . '!')
                    ->line('Please find your invoice details below:')
                    ->line('Invoice Number: ' . $this->invoice->invoice_number)
                    ->line('Amount: $' . number_format($this->invoice->total_amount, 2))
                    ->line('Due Date: ' . $this->invoice->due_date->format('M d, Y'))
                    ->line('Description: ' . $this->invoice->description)
                    ->action('Pay Invoice', route('payment.show', $this->invoice->id))
                    ->line('Thank you for your business!');
    }

    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
