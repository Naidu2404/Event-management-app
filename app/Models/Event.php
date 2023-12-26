<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Event extends Model
{
    use HasFactory;

    protected $fillable = ['name','description','start_date','end_date','user_id'];

    //adding the relation with only one user
    public function user() : BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    //adding multiple attendees for the event
    public function attendees() : HasMany {
        return $this->hasMany(Attendee::class);
    }
}
