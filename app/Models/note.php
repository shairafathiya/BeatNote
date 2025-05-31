<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Note extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'content',
        'audio',
        'tags',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'tags' => 'array',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the tags as a comma-separated string.
     *
     * @return string
     */
    public function getTagsStringAttribute(): string
    {
        return is_array($this->tags) ? implode(', ', $this->tags) : '';
    }

    /**
     * Check if note has a specific tag.
     *
     * @param string $tag
     * @return bool
     */
    public function hasTag(string $tag): bool
    {
        return is_array($this->tags) && in_array($tag, $this->tags);
    }

    /**
     * Scope to filter notes by tag.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $tag
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithTag($query, string $tag)
    {
        return $query->whereJsonContains('tags', $tag);
    }

    /**
     * Scope to search notes by title or content.
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string $search
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch($query, string $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('title', 'like', "%{$search}%")
              ->orWhere('content', 'like', "%{$search}%");
        });
    }
}