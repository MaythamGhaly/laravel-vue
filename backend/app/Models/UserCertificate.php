<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCertificate extends Model
{
    use HasFactory;

    protected $table = 'user_certificate';

    protected $fillable = [
        'user_id',
        'certificate_id',
        'level',
    ];
    function user()
    {
        return $this->belongsTo(User::class);
    }

    function certificate()
    {
        return $this->belongsTo(Certification::class);
    }

}
