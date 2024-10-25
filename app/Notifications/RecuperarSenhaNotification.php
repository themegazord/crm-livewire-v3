<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Password;

class RecuperarSenhaNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(
    private readonly string $token,
    private readonly string $email
  ){}

  /**
   * Get the notification's delivery channels.
   *
   * @return array<int, string>
   */
  public function via(object $notifiable): array
  {
    return ['mail'];
  }

  /**
   * Get the mail representation of the notification.
   */
  public function toMail($notifiable): MailMessage
  {
    return (new MailMessage)
      ->greeting('Ola')
      ->subject('Redefinição de Senha')
      ->line('Você está recebendo este email porque recebemos um pedido de redefinição de senha para sua conta.')
      ->action('Redefinir Senha', url('password/reset', [$this->token, $this->email]))
      ->line('Se você não solicitou uma redefinição de senha, nenhuma ação adicional é necessária.')
      ->salutation('Ate mais');
  }

  /**
   * Get the array representation of the notification.
   *
   * @return array<string, mixed>
   */
  public function toArray(object $notifiable): array
  {
    return [
      //
    ];
  }
}
