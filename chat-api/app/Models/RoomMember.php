<?php

namespace App\Models;

use App\Events\NewRoom;
use broadcast;
use Illuminate\Database\Eloquent\Model;

class RoomMember extends Model
{

    protected $guarded = [];
    public static function boot()
    {
        parent::boot();
        static::created(function ($item) {
            try {
                broadcast(new NewRoom($item));
            } catch (\Throwable $th) {
                //throw $th;
            }

        });
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
