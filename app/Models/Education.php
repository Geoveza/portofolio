<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    use HasFactory;

    protected $table = 'education';

    protected $fillable = [
        'institution',
        'degree',
        'field_of_study',
        'logo_url',
        'description',
        'start_date',
        'end_date',
        'is_current',
        'type',
        'location',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get formatted date range
     */
    public function getDateRangeAttribute(): string
    {
        $start = $this->start_date->format('Y');
        $end = $this->is_current ? 'Present' : ($this->end_date ? $this->end_date->format('Y') : 'Present');
        return "{$start} - {$end}";
    }

    /**
     * Scope for ordering
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order')->orderByDesc('start_date');
    }

    /**
     * Get type label
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'formal' => 'Formal Education',
            'bootcamp' => 'Bootcamp',
            'certification' => 'Certification',
            'course' => 'Online Course',
            default => 'Education',
        };
    }
}
