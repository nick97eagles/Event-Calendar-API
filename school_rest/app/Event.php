<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Calendar extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $primaryKey = 'event_id';
    protected $fillable = [
        'event_id', 'event_name', 'event_desc', 'event_date', 'hosted_by', 'location'
    ];
}
