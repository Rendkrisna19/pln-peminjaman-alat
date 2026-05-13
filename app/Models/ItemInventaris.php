<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItemInventaris extends Model
{
    protected $table = 'tbl_item_inventaris';
    protected $guarded = [];

    public function peralatan(): BelongsTo
    {
        return $this->belongsTo(Peralatan::class, 'peralatan_id');
    }
}