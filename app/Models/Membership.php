<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Membership extends Model
{
    protected $fillable = [
        'user_id',
        'member_number',
        'phone_number',
        'address',
        'class_room',
        'start_register',
        'validate_until',
    ];
}
