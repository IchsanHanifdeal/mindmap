<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ringkasan extends Model
{
    protected $table = 'ringkasans';
    protected $fillable = [
        'user',
        'mindmaps',
        'ringkasan',
    ];
    public function userRelation()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
