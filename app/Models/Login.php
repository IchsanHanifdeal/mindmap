<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Login extends Model
{
    protected $fillable = ['users', 'ip_address', 'user_agent', 'logged_in_at'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
