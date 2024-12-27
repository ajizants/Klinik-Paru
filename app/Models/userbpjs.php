<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class userbpjs extends Model
{
    protected $table = 'user_bpjs';
    protected $filable = ['id', 'username', 'password'];
}
