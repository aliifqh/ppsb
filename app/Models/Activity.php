<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Activity extends Model
{
    protected $fillable = [
        'user_id',
        'type',
        'subject_type',
        'subject_id',
        'description',
        'properties'
    ];

    protected $casts = [
        'properties' => 'array'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function subject(): MorphTo
    {
        return $this->morphTo();
    }

    public static function log($userId, $type, $description, $subject = null, $properties = null)
    {
        return self::create([
            'user_id' => $userId,
            'type' => $type,
            'subject_type' => $subject ? get_class($subject) : null,
            'subject_id' => $subject ? $subject->id : null,
            'description' => $description,
            'properties' => $properties
        ]);
    }

    public function getIconClass()
    {
        return match($this->type) {
            'login' => 'fa-sign-in-alt text-blue-600 bg-blue-100',
            'create' => 'fa-plus text-green-600 bg-green-100',
            'update' => 'fa-edit text-yellow-600 bg-yellow-100',
            'delete' => 'fa-trash text-red-600 bg-red-100',
            default => 'fa-info-circle text-gray-600 bg-gray-100'
        };
    }
    
    /**
     * Scope a query to only include notifications from the last week.
     *
     * @param  \Illuminate\Database\Eloquent\Builder  $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRecent($query)
    {
        return $query->where('created_at', '>=', Carbon::now()->subWeek());
    }
}
