<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Skill extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'category',
        'level',
        'description',
        'icon',
        'color',
        'order',
        'featured',
    ];

    protected $casts = [
        'level' => 'integer',
        'order' => 'integer',
        'featured' => 'boolean',
    ];

    public const CATEGORIES = [
        'web3' => 'Web3 & Blockchain',
        'frontend' => 'Frontend',
        'backend' => 'Backend',
        'devops' => 'DevOps',
        'tools' => 'Tools',
        'soft' => 'Soft Skills',
    ];

    public const LEVELS = [
        1 => 'Beginner',
        2 => 'Intermediate',
        3 => 'Advanced',
        4 => 'Expert',
        5 => 'Master',
    ];

    public function getCategoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getLevelLabel(): string
    {
        return self::LEVELS[$this->level] ?? "Level {$this->level}";
    }

    public function getLevelPercentage(): int
    {
        return $this->level * 20;
    }
}