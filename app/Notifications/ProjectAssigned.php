<?php

namespace App\Notifications;

use App\Models\Construction;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ProjectAssigned implements ShouldQueue
{
    use Queueable;

    public function __construct(public Construction $construction, public string $type = 'assigned')
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toFilament(object $notifiable): FilamentNotification
    {
        $message = $this->getMessage();
        
        return FilamentNotification::make()
            ->title($message['title'])
            ->body($message['body'])
            ->sendToDatabase($notifiable);
    }

    private function getMessage(): array
    {
        return [
            'title' => match ($this->type) {
                'assigned' => 'Anda Ditugaskan ke Proyek Baru',
                'completed' => 'Proyek Telah Diselesaikan',
                'cancelled' => 'Proyek Telah Dibatalkan',
                'replaced' => 'Anda Tidak Lagi Ditugaskan ke Proyek',
                'replacement' => 'Penugasan Baru',
                default => 'Notifikasi Proyek',
            },
            'body' => match ($this->type) {
                'assigned' => "Proyek {$this->construction->construction_name} di {$this->construction->location} dimulai pada {$this->construction->start_date->format('d M Y')}.",
                'completed' => "Proyek {$this->construction->construction_name} telah selesai. Terima kasih atas kontribusi Anda.",
                'cancelled' => "Proyek {$this->construction->construction_name} telah dibatalkan. Tidak ada aktivitas lanjutan yang diperlukan.",
                'replaced' => "Anda telah digantikan dari proyek {$this->construction->construction_name}. Terima kasih atas tugas Anda sebelumnya.",
                'replacement' => "Anda ditugaskan menggantikan supervisor sebelumnya pada proyek {$this->construction->construction_name}. Harap lakukan koordinasi.",
                default => '',
            },
        ];
    }
}