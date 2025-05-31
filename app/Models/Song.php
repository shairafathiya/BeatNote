<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Song extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'file_path',
        'genre',
        'status',
        'note_id',
        'user_id'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Relationship with Note
    public function note()
    {
        return $this->belongsTo(Note::class);
    }

    // Relationship with User (if you have user authentication)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Accessor for file URL
    public function getFileUrlAttribute()
    {
        return $this->file_path ? Storage::url($this->file_path) : null;
    }

   

}