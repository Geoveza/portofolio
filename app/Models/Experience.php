<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Experience extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'company',
        'location',
        'description',
        'start_date',
        'end_date',
        'current',
        'type',
        'skills',
        'logo_url',
        'order',
    ];

    protected $casts = [
        'current' => 'boolean',
        'skills' => 'array',
        'start_date' => 'date',
        'end_date' => 'date',
        'order' => 'integer',
    ];

    public const TYPES = [
        'full-time' => 'Full Time',
        'part-time' => 'Part Time',
        'contract' => 'Contract',
        'freelance' => 'Freelance',
        'internship' => 'Internship',
    ];

    public function getTypeLabel(): string
    {
        return self::TYPES[$this->type] ?? $this->type;
    }

    public function getDuration(): string
    {
        $start = $this->start_date->format('M Y');
        $end = $this->current ? 'Present' : $this->end_date->format('M Y');
        
        return $start . ' - ' . $end;
    }
}