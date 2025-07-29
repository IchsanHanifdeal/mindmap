<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KataKunci extends Model
{
    protected $table = 'kata_kuncis';

    protected $fillable = [
        'materi',
        'user',
        'kata_kunci',
    ];

    public function materi()
    {
        return $this->belongsTo(Materi::class, 'materi');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
