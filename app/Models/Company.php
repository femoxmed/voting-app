<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
    ];

    public function agms()
    {
        return $this->hasMany(Agm::class);
    }

    public function shareholders()
    {
        return $this->hasMany(Shareholder::class);
    }
}