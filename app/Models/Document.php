<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    public function company()
    {
        return $this->belongsTo('App\Models\Company', 'co_id');
    }
}
