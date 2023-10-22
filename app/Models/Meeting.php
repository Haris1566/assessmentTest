<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Meeting extends Model
{
    use HasFactory;

    protected $fillable = ["subject", "time", "google_event_id", "date", "description", "created_by"];
    protected $casts = [
        'date' => 'datetime'];

    public function attendees(): HasMany
    {
        return $this->hasMany(Attendee::class, 'meeting_id');
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
