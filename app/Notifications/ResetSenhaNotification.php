<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ResetSenhaNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(
    private readonly string $name
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
  public function toMail(object $notifiable): MailMessage
  {
    return (new MailMessage)
      ->greeting("{$this->name}, deu tudo certo no final :)")
      ->line('Sua senha foi alterada com sucesso e a essa altura do campeonato, você já está logado novamente no nosso sistema, ebaa!!!!')
      ->line('Caso precise novamente resetar sua senha em algum momento, é só contar conosco. Divirta-se no nosso app.')
      ->salutation('Até mais <3');
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
