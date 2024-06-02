<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Check extends Model
{
    use HasFactory;

    protected $fillable = ['client_id', 'completed'];

    public function client()
    {
        return $this->belongsTo(Client::class);
    }
}
