<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class GuestList extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'student_id';
    protected $fillable = [
        'student_id', 'event_id', 'student_name', 'phone_number'
    ];
}
