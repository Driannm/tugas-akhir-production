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
                $currentSupervisor->notify(new ProjectAssigned($construction, 'completed'));
            }

            if ($status === 'dibatalkan' && $currentSupervisor) {
                $currentSupervisor->notify(new ProjectAssigned($construction, 'cancelled'));
            }
        }

        if ($construction->wasChanged('supervisor_id')) {
            $oldSupervisorId = $construction->getOriginal('supervisor_id');
            $oldSupervisor = User::find($oldSupervisorId);

            if ($oldSupervisor) {
                $oldSupervisor->notify(new ProjectAssigned($construction, 'replaced'));
            }

            if ($currentSupervisor) {
                $currentSupervisor->notify(new ProjectAssigned($construction, 'replacement'));
            }
        }
    }

    /**
     * Handle the Construction "deleted" event.
     */
    public function deleted(Construction $construction): void
    {
        //
    }

    /**
     * Handle the Construction "restored" event.
     */
    public function restored(Construction $construction): void
    {
        //
    }

    /**
     * Handle the Construction "force deleted" event.
     */
    public function forceDeleted(Construction $construction): void
    {
        //
    }
}
