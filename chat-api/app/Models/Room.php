<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{

    protected $fillable = [
        'name',
        'image',
    ];

    protected $hidden = [
        'name',
        'image',
    ];

    protected $appends = [
        'room_name',
        'room_image',
        'last_message',
        'message_send_at',
    ];

    public function getRoomNameAttribute()
    {
        if ($this->type == 'one') {
            $mmeber = RoomMember::with('user')->where(['room_id' => $this->id])
                ->where('user_id', '!=', auth()->id())->first();
            try {
                return $mmeber->user->name;
            } catch (\Throwable $th) {
                return $this->name;
            }
        } else {
            return $this->name ? $this->name : 'Group chat';
        }
    }

    public function getLastMessageAttribute()
    {
        try {
            return $message = Message::where('room_id', $this->id)->get()->last()->message;
        } catch (\Throwable $th) {
            return 'Say hi';
        }

    }

    public function getMessageSendAtAttribute()
    {
        try {
            return $message = Message::where('room_id', $this->id)->get()->last()->created_at;
        } catch (\Throwable $th) {
            return $this->created_at;
        }
    }

    public function getRoomImageAttribute()
    {
        if ($this->type == 'one') {
            $mmeber = RoomMember::with('user')->where(['room_id' => $this->id])
                ->where('user_id', '!=', auth()->id())->first();
            try {
                return $mmeber->user->profile_picture_url;
            } catch (\Throwable $th) {
                if ($this->image && file_exists('uploads/' . public_path($this->image))) {
                    return url('uploads/' . $this->image);
                }
                return 'https://www.eclass4learning.com/wp-content/uploads/2015/01/groups-300x199.jpg';

            }
        } else {
            if ($this->image && file_exists('uploads/' . public_path($this->image))) {
                return url('uploads/' . $this->image);
            }
            return 'https://www.eclass4learning.com/wp-content/uploads/2015/01/groups-300x199.jpg';

        }
    }

    public function members()
    {
        return $this->hasMany(RoomMember::class, 'room_id')->with('user');
    }
}
