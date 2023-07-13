<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = ['member_id', 'amount', 'type', 'transacted_at'];

    public function member()
    {
        return $this->belongsTo(Member::class);
    }
}

