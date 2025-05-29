<?php

namespace App\Observers;

use App\Models\{Construction, User};
use App\Notifications\ProjectAssigned;

class ProjectAssignObserver
{
    public function created(Construction $construction): void
    {
        $supervisor = User::find($construction->supervisor_id);

        if ($supervisor) {
            $supervisor->notify(new ProjectAssigned($construction, 'assigned'));
        }
    }

    public function updated(Construction $construction): void
    {
        $currentSupervisor = User::find($construction->supervisor_id);

        if ($construction->wasChanged('status_construction')) {
            $status = $construction->status_construction;

            if ($status === 'selesai' && $currentSupervisor) {
                \Filament\Notifications\Notification::make()
                    ->title('Proyek Telah Diselesaikan')
                    ->body("Proyek {$construction->construction_name} telah selesai. Terima kasih atas kontribusi Anda.")
                    ->sendToDatabase($currentSupervisor);
            }

            // Similar for other status changes
        }

        if ($construction->wasChanged('supervisor_id')) {
            $oldSupervisorId = $construction->getOriginal('supervisor_id');
            $oldSupervisor = User::find($oldSupervisorId);

            if ($oldSupervisor) {
                \Filament\Notifications\Notification::make()
                    ->title('Anda Tidak Lagi Ditugaskan ke Proyek')
                    ->body("Anda telah digantikan dari proyek {$construction->construction_name}. Terima kasih atas tugas Anda sebelumnya.")
                    ->sendToDatabase($oldSupervisor);
            }

            if ($currentSupervisor) {
                \Filament\Notifications\Notification::make()
                    ->title('Penugasan Baru')
                    ->body("Anda ditugaskan menggantikan supervisor sebelumnya pada proyek {$construction->construction_name}. Harap lakukan koordinasi.")
                    ->sendToDatabase($currentSupervisor);
            }
        }
    }
}