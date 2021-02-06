<?php

namespace App\Http\Controllers;

use App\Events\RoomUpdated;
use App\Models\Message;
use App\Models\Room;
use App\Models\RoomMember;
use Illuminate\Http\Request;
use Storage;

class RoomController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $rooms = Room::whereHas('members', function ($q) {
            $q->where('user_id', auth()->id());
        })->get();
        return response()->json(['rooms' => $rooms]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $checkMember = Room::whereHas('members', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('members', function ($q) use ($request) {
            $q->where('user_id', $request->user_id);
        })->where('type', 'one')->get();
        if (count($checkMember) > 0) {
            return response()->json([
                'message' => 'You contact this preson before',
            ], 400);
        }
        $room = new Room();
        $room->save();
        // $members = new RoomMember();
        // $members->user_id = $request->user_id;
        // $members->room_id = $room->id;
        // $members->save();
        // $members = new RoomMember();
        // $members->user_id = auth()->id();
        // $members->room_id = $room->id;
        // $members->save();

        $data = [
            'user_id' => $request->user_id,
            'room_id' => $room->id,
        ];

        RoomMember::create($data);

        $data = [
            'user_id' => auth()->id(),
            'room_id' => $room->id,
        ];

        RoomMember::create($data);

        return response()->json(['message' => 'room created successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try {
            $room = Room::findOrFail($id);

        } catch (\Throwable $th) {
            $messages = Message::with('sender')->where('room_id', $id)->get();
            foreach ($messages as $message) {
                $message->delete();
            }

            return response()->json([
                'message' => 'room deleted by the admin',
            ], 404);
        }
        $messages = Message::with('sender')->where('room_id', $id)->get();
        return response()->json(['messages' => $messages]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function sendMessage(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|min:1',
        ]);

        $data = $request->only(['message']);
        if ($request->has('image')) {
            $image = [Storage::disk('uploads')->put('messages', $request->image)];
            $data['images'] = json_encode($image);
        }
        $data['sender_id'] = auth()->id();
        $data['room_id'] = $id;
        $message = Message::create($data);
        $message = Message::with('sender')->find($message->id);
        $members = RoomMember::where('room_id', $id)->get();
        foreach ($members as $member) {
            try {
                broadcast(new RoomUpdated($member));
            } catch (\Throwable $th) {
                //throw $th;
            }
        }
        return response()->json([
            'message' => 'message sent successfully',
        ]);
    }
    public function storeGroup(Request $request)
    {
        $request->validate([
            'users' => 'required|exists:users,id',
        ]);

        $users = $request->users;

        $checkMember = Room::whereHas('members', function ($q) {
            $q->where('user_id', auth()->id());
        })->whereHas('members', function ($q) use ($users) {
            $q->whereIn('user_id', $users);
        })->where('type', 'group')
            ->with('members')
            ->first();

        $room = new Room();
        $room->type = 'group';
        $room->save();

        $members = new RoomMember();
        $members->user_id = auth()->id();
        $members->room_id = $room->id;
        $members->save();
        foreach ($users as $user) {
            $members = new RoomMember();
            $members->user_id = $user;
            $members->room_id = $room->id;
            $members->save();
        }

        return response()->json(['message' => 'room created successfully']);

    }
}
