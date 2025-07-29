<?php

namespace App\Traits;

use App\Models\Activity;
use Illuminate\Support\Facades\Auth;

trait LogsActivity
{
    protected static function bootLogsActivity()
    {
        static::created(function ($model) {
            self::logActivity('create', 'membuat ' . strtolower(class_basename($model)) . ' baru', $model);
        });

        static::updated(function ($model) {
            self::logActivity('update', 'mengubah ' . strtolower(class_basename($model)), $model, [
                'old' => array_intersect_key($model->getOriginal(), $model->getDirty()),
                'new' => $model->getDirty()
            ]);
        });

        static::deleted(function ($model) {
            self::logActivity('delete', 'menghapus ' . strtolower(class_basename($model)), $model);
        });
    }

    protected static function logActivity($type, $description, $model, $properties = null)
    {
        if (Auth::check()) {
            Activity::log(
                Auth::id(),
                $type,
                $description,
                $model,
                $properties
            );
        }
    }
} 