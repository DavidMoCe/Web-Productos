<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PasswordResetToken extends Model
{
    use HasFactory;

    protected $fillable = [
        'email', 'token', 'created_at',
    ];

    /**
     * Get the user that owns the password reset token.
     */
    public function user(): BelongsTo{
        return $this->belongsTo(User::class, 'email', 'email');
    }
}
