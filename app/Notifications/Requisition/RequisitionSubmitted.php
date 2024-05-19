<?php

namespace App\Notifications\Requisition;

use App\Models\Requisition;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RequisitionSubmitted extends Notification
{
    use Queueable;

    private $requisition;
    /**
     * Create a new notification instance.
     */
    public function __construct(Requisition $r)
    {
        $this->requisition = $r;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(
                sprintf(
                    "%s submitted a requisition",
                    $this->requisition->user->name
                )
            )->markdown('mail.requisition.requisition-submitted',[
                'requisition' => $this->requisition
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'requisition_id' => $this->requisition->id
        ];
    }
}
