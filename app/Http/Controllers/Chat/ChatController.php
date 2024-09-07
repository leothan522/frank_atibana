<?php

namespace App\Http\Controllers\Chat;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ChatController extends Controller
{
    public function index($rowquid = null)
    {
        $return = view('chat.index');
        if (!Auth::check()){
            if (!is_null($rowquid)){
                $user = User::where('rowquid', $rowquid)->first();
                if ($user){
                    Auth::login($user);
                }else{
                    $return = redirect()->route('web.index');
                }
            }else{
                $return = redirect()->route('web.index');
            }
        }
        return $return;
    }

}
