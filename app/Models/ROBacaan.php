<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ROBacaan extends Model
{
    use HasFactory;

    protected $table = 't_rontgen_bacaan';

    protected $fillable = [
        'id',
        'norm',
        'notrans',
        'tanggal',
        'tanggal_ro',
        'bacaan_radiolog',
        'created_at',
        'updated_at',
    ];
}
