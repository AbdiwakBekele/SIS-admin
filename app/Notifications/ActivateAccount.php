<?php

namespace App\Notifications;

use App\Models\Tenant;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Arr;

class ActivateAccount extends Notification
{
    use Queueable;

    protected $tenant;

    /**
     * Create a new notification tenant.
     *
     * @return void
     */
    public function __construct(Tenant $tenant)
    {
        $this->tenant = $tenant;
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
        $domainName = Arr::get(parse_url(config('app.url')), 'host');
        $url = 'http://'.$this->tenant->domain.'.'.$domainName;

        return (new MailMessage)
            ->subject('Account Activation | '.config('app.name'))
            ->greeting('Hello '.$this->tenant->name)
            ->line('Thank you for registering an account with us.')
            ->line('Your activation token is : '.$this->tenant->getMeta('activation_token'))
            ->line('Click on the below link to start setting up your application!')
            ->action('Click here!', $url)
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
