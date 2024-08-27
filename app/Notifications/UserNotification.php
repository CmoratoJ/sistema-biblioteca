<?php

namespace App\Notifications;

use App\Models\Book;
use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class UserNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected Loan $loan;

    protected Book $book;

    /**
     * Create a new notification instance.
     */
    public function __construct(Loan $loan, Book $book)
    {
        $this->loan = $loan;
        $this->book = $book;
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
            ->subject('Bamaq | Empréstimo de livro realizado')
            ->greeting('Olá, ' . $notifiable->name)
            ->line('Você tem um novo empréstimo de livro cadastrado!')
            ->line('Livro : ' . $this->book->title)
            ->line('Emprestado por : ' . $notifiable->name)
            ->line('Emprestado em : ' . date('d/m/Y',strtotime($this->loan->loan_date)))
            ->line('Previsão de devolução : ' . date('d/m/Y',strtotime($this->loan->due_date)))
            ->salutation("Atenciosamente, Bamaq.");
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
