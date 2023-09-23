<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Certification extends Model
{
    use HasFactory;


    protected $table = 'certificates';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'certificate_name',
    ];

    function users()
    {
        return $this->belongsToMany(User::class, 'user_certificate', 'certificate_id', 'user_id');
    }

}
