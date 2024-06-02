<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'email', 'identity_verified', 'status_708'];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function checks()
    {
        return $this->hasMany(Check::class);
    }
}
