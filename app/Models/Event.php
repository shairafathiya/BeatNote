<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'date',
        'type',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'date' => 'date',
    ];

    /**
     * Get events ordered by date ascending
     */
    public static function getEventsByDate()
    {
        return self::orderBy('date', 'asc')->get();
    }

    /**
     * Get border color based on event type
     */
    public function getBorderColorAttribute()
    {
        return match($this->type) {
            'Manggung' => '#8b0000',
            'Latihan' => '#006400',
            'Recording' => '#1e90ff',
            default => '#8b0000'
        };
    }
}