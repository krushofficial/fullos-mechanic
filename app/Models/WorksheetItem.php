<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorksheetItem extends Model
{
    use HasFactory;
    public $timestamps = false;

    protected $guarded = ["id"];

    public function item_template(): BelongsTo
    {
        return $this->belongsTo(AvailableItem::class, "item_id");
    }
}
