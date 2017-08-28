<?php

namespace SmoDav\Auth\Models;

use Illuminate\Database\Eloquent\Model;

class RecoveryKey extends Model
{
    protected $fillable = ['user_id', 'key'];
}
