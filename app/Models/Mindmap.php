<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mindmap extends Model
{
    protected $fillable = [
        'user',
        'title',
        'node',
        'parent_node',
        'type',
        'shareable',
        'gambar_mindmap',
    ];

    public function userRelation()
    {
        return $this->belongsTo(User::class, 'user');
    }
}
