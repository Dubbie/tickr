<?php

namespace App\Traits;

use App\Models\StatusChange;
use Illuminate\Support\Facades\Log;

/**
 * Trait TracksStatusChanges
 *
 * This trait tracks changes to the `status` attribute of a model.
 * When the `status` field is updated, a new `StatusChange` record
 * is created to log the old and new statuses, along with the timestamp
 * of the change. Additionally, it logs the status change to the application logs.
 *
 * To use this trait, the model must have a `statusChanges` relationship
 * defined, pointing to the `StatusChange` model.
 *
 * Example Usage:
 * ```php
 * use App\Traits\TracksStatusChanges;
 *
 * class Ticket extends Model {
 *     use TracksStatusChanges;
 * }
 * ```
 *
 * @package App\Traits
 */
trait TracksStatusChanges
{
    /**
     * Boot the TracksStatusChanges trait.
     *
     * Registers a model event handler for the `updating` event
     * to detect and handle changes to the `status` attribute.
     *
     * @return void
     */
    protected static function bootTracksStatusChanges()
    {
        static::updating(function ($model) {
            // Check if the 'status' attribute has changed
            if ($model->isDirty('status')) {
                $originalStatus = $model->getOriginal('status');
                $newStatus = $model->status;

                // Log the status change
                Log::info("Model {$model->id} status changed from {$originalStatus} to {$newStatus}");

                // Create a new StatusChange record
                $model->statusChanges()->create([
                    'old_status' => $originalStatus,
                    'new_status' => $newStatus,
                    'changed_at' => now(),
                ]);
            }
        });
    }

    /**
     * Define the relationship to the `StatusChange` model.
     *
     * This assumes that the parent model has a `statusChanges` relationship
     * defined as a one-to-many relationship with the `StatusChange` model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function statusChanges()
    {
        return $this->hasMany(StatusChange::class);
    }
}
