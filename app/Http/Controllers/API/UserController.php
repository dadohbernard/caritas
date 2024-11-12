<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Role;
use App\Models\User;
use App\Models\Center;
use App\Models\Community;
use App\Events\NewUserCreatedEvent;
use App\Events\UserRegisteredEvent;
use App\Http\Requests\UserCreateRequest;
use App\Http\Requests\UserUpdateRequest;
use Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index(Request $request){
          $user = User::leftjoin('centers','centers.id','users.centrale_id')
          ->leftjoin('communities','communities.id','users.community_id')
          ->join('roles','roles.id','users.role')
->select('users.*','centers.center_name','communities.community_name','roles.name as role_name')
         ->where(function ($user)  {
                return (\Auth::user()->role!=1 && \Auth::user()->role !=5  ?
            $user->where('users.community_id', \Auth::user()->community_id) : \Auth::user()->role==4)?$user->where('users.centrale_id', auth()->user()->centrale_id):"";
            })
            ->where('users.is_delete',0)->get();
            return response()->json(['msg'=>"success", "data" => $user,"status"=>200]);
    }
}
