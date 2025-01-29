<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AddAsLead extends Notification
{
    use Queueable;

    protected $email;

    protected $action;

    /**
     * Create a new notification tenant.
     *
     * @return void
     */
    public function __construct(string $email, string $action)
    {
        $this->email = $email;
        $this->action = $action;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Lead | '.config('app.name'))
            ->greeting('Hello,')
            ->line('Add email as a new lead: '.$this->email)
            ->line('Action Performed: '.$this->action)
            ->line('Have a great day ahead!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}
