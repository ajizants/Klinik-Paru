<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginLogModel extends Model
{
    protected $table    = 'login_logs';
    protected $fillable = [
        'user_id', 'ip_address', 'user_agent', 'logged_in_at',
    ];

    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
