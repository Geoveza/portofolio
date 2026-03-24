<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'content',
        'category',
        'technologies',
        'image_url',
        'live_url',
        'repo_url',
        'contract_address',
        'blockchain',
        'featured',
        'order',
        'status',
    ];

    protected $casts = [
        'technologies' => 'array',
        'featured' => 'boolean',
        'order' => 'integer',
    ];

    public const CATEGORIES = [
        'web3' => 'Web3 & Blockchain',
        'defi' => 'DeFi',
        'nft' => 'NFT',
        'dapp' => 'dApp',
        'traditional' => 'Traditional Web',
        'other' => 'Other',
    ];

    public const STATUSES = [
        'published' => 'Published',
        'draft' => 'Draft',
        'archived' => 'Archived',
    ];

    public function getCategoryLabel(): string
    {
        return self::CATEGORIES[$this->category] ?? $this->category;
    }

    public function getStatusLabel(): string
    {
        return self::STATUSES[$this->status] ?? $this->status;
    }

    public function isWeb3(): bool
    {
        return in_array($this->category, ['web3', 'defi', 'nft', 'dapp']);
    }
}