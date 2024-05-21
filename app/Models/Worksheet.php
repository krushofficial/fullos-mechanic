<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Worksheet extends Model
{
    use HasFactory;

    protected $guarded = ["id"];

    public function advisor(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function worksheet_items(): HasMany
    {
        return $this->hasMany(WorksheetItem::class);
    }
}
