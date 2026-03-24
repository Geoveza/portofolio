<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * SECURITY FIX: Removed 'read' and 'responded' to prevent mass assignment attacks
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'subject',
        'message',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'read' => 'boolean',
        'responded' => 'boolean',
    ];

    /**
     * Mark message as read
     * SECURITY: This method ensures only the 'read' field is updated
     */
    public function markAsRead(): void
    {
        $this->update(['read' => true]);
    }

    /**
     * Mark message as responded
     * SECURITY: This method ensures only the 'responded' field is updated
     */
    public function markAsResponded(): void
    {
        $this->update(['responded' => true]);
    }

    /**
     * Scope for unread messages
     */
    public function scopeUnread($query)
    {
        return $query->where('read', false);
    }

    /**
     * Scope for responded messages
     */
    public function scopeResponded($query)
    {
        return $query->where('responded', true);
    }
}
