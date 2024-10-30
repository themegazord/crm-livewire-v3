<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UsuarioResetadoNotification extends Notification
{
  use Queueable;

  /**
   * Create a new notification instance.
   */
  public function __construct(private readonly string $nome)
  {
    //
  }

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
      ->subject('Restauração de cadastro.')
      ->greeting("Olá, $this->nome, ótimas noticias para você.")
      ->line('Recebemos seu contato pelo suporte sobre sua vontade de voltar a usar o ' . env('APP_NAME') . ' novamente.')
      ->line('Nosso time responsável analisou sua situação e decidimos reativar seu cadastro novamente, basta apenas você entrar no sistema com seu email e senha antiga.')
      ->line('Caso você não se lembre da sua senha, basta clicar no botão abaixo para ir na rotina para resetar sua senha.')
      ->action('Redefine sua senha', url('/recuperar_senha'))
      ->salutation('Obrigado por voltar a usar nosso sistema. Te esperamos com muita saudade :)');
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
