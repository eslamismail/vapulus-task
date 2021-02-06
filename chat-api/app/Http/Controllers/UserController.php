<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomMember;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
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
        })->get()->pluck('id');
        $members = RoomMember::whereIn('room_id', $rooms)->get()->pluck('user_id');
        $users = User::where('id', '!=', auth()->id())->whereNotIn('id', $members)->get();
        return response()->json([
            'users' => $users,
        ]);
    }

    public function all()
    {
        $users = User::where('id', '!=', auth()->id())->get();
        return response()->json([
            'users' => $users,
        ]);
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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
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
    public function getUser()
    {
        $user = auth()->user();
        return response()->json([
            'user' => $user,
        ]);
    }
}
