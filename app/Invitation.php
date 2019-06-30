<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    public function worker()
    {
        return $this->belongsTo(User::class, 'worker_id');
    }
    
    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
