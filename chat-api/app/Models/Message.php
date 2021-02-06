<?php

namespace App\Models;

use App\Events\NewMessage;
use broadcast;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'message',
        'room_id',
        'sender_id',
        'images',
    ];
    public static function boot()
    {
        parent::boot();
        static::created(function ($item) {
            $message = Message::with('sender')->find($item->id);
            broadcast(new NewMessage($message));

        });
    }
    protected $hidden = [
        'images',
    ];
    protected $appends = [
        'images_url',
    ];

    public function getImagesUrlAttribute()
    {
        if (!empty($this->images) && count(json_decode($this->images)) > 0) {
            $images = [];
            foreach (json_decode($this->images) as $key => $value) {
                $images[] = url('uploads/' . $value);
            }
            return $images;
        }
        return [];
    }

    public function sender()
    {
        return $this->belongsTo(User::class, 'sender_id');
    }
}
