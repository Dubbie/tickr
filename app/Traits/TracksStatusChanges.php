<?php

namespace App\Traits;

use App\Models\StatusChange;
use Illuminate\Support\Facades\Log;

trait TracksStatusChanges
{
    protected static function bootTracksStatusChanges()
    {
        static::updating(function ($model) {
            if ($model->isDirty('status')) {
                $originalStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                Log::info("Model {$model->id} status changed from {$originalStatus} to {$newStatus}");

                $model->statusChanges()->create([
                    'old_status' => $originalStatus,
                    'new_status' => $newStatus,
                    'changed_at' => now(),
                ]);
            }
        });
    }

    /**
     * Define the relationship to the status changes.
     *
     * This assumes the model using this trait has a `statusChanges` relationship.
     */
    public function statusChanges()
    {
        return $this->hasMany(StatusChange::class);
    }
}
