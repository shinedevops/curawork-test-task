<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    use HasFactory;

    protected $fillable = [
        'sent_by',
        'sent_to',
        'status',
        'status'
    ];
    public function sentBy() {
        return $this->belongsTo(User::class, 'sent_by', 'id');
    }
    public function sentTo() {
        return $this->belongsTo(User::class, 'sent_to', 'id');
    }
}
