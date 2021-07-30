<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    public function users()
    {
        return $this->hasMany('App\Models\User', 'co_id', 'id');
    }

    public function documents()
    {
        return $this->hasMany('App\Models\Document', 'co_id', 'id');
    }
}
