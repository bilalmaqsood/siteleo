<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\UsersChat;
use Auth;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($id, Request $request)
    {

        $chat = UsersChat::where(function($query) {
            $query->where('user_id', '=', Auth::user()->id)->orWhere('partner_id', '=', Auth::user()->id);
        })->where(function($query) use ($id) {
            $query->where('user_id', '=', $id)->orWhere('partner_id', '=', $id);
        })->orderBy('created_at', 'asc')->get();

        UsersChat::where('new', 1)
            ->where('partner_id', Auth::user()->id)
            ->update(['new' => 0]);
        \App\Models\CoutEvents::setUserId(Auth::user()->id)->add('chat_num', -9999);

        return view('user.chat', ['mess' => $chat, 'partner' => \App\User::find($id)]);
    }

    public function say($id, Request $request){
        $ad = \App\User::where('id', $id)->first();
        if(is_null($ad)) return redirect()->route('home');
        $this->validate($request, [
            'message' => 'required|string|min:2'
        ]);

        $chat = new UsersChat();
        $chat->user_id = Auth::user()->id;
        $chat->partner_id = $id;
        $chat->message = $request->message;
        $chat->save();

        \App\Models\CoutEvents::setUserId($id)->add('chat_num', 1);

        return back();
    }

    public function sayAjax($id, Request $request){
        $ad = \App\User::where('id', $id)->first();
        if(is_null($ad)) return redirect()->route('home');
        $this->validate($request, [
            'message' => 'required|string|min:2'
        ]);


        $request->message = preg_replace('/[a-zA-Z0-9_\-\.]+[a-zA-Z0-9]+@[a-zA-Z0-9\-]+\.[a-zA-Z0-9\-\.]+$/',
            '(Hidden Email <i class="fa fa-info" title="In the correspondence it is forbidden to send each other to Email"></i> )', $request->message);

        $chat = new UsersChat();
        $chat->user_id = Auth::user()->id;
        $chat->partner_id = $id;
        $chat->message = $request->message;
        $chat->save();

        \App\Models\CoutEvents::setUserId($id)->add('chat_num', 1);

        $message = UsersChat::find($chat->id);

        $html = "<div class=\"user-message-item me-message\">
            <div class=\"message-time\">{$message->created_at}</div>
            <div class=\"message-description\">
                <div class=\"message-text\">{$message->message}</div>
            </div>
        </div>";

        return $html;
    }

    public function liveChat(Request $request){
        $newMessages = UsersChat::where('new', 1)
            ->where('user_id', $request->width)
            ->where('partner_id', Auth::user()->id)
            ->where('new', 1)->get();
        $html = "";
        foreach ($newMessages as $message){
            $html .= "<div class=\"user-message-item\">
                <div class=\"message-description\">
                    <div class=\"message-text\">{$message->message}</div>
                </div>
                <div class=\"message-time\">{$message->created_at}</div>
            </div>";
            $message->new = 0;
            $message->save();
        }
        if(empty($html)) die('none');
        return $html;
        //die($request->width);
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
}
