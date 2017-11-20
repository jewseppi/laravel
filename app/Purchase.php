<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Purchase extends Model
{
    use SoftDeletes;

    public $incrementing = false;
    protected $keyType = 'char(36)';
    protected $dates = ['deleted_at'];

    public function user() {
      return $this->belongsTo('App\User');
    }
}
