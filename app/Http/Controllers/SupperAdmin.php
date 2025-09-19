<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Middleware\SupperAdmin as SupperAdminMiddleware;

class SupperAdmin extends Controller
{
    public function __construct()
    {
        $this->middleware([SupperAdminMiddleware::class]);
    }
    public function index()
    {
        $admins = User::where('utype', 'ADM')->get();
        return view('user.supper-admin', compact('admins'));
    }
    public function admin_users($keyword)
    {
        $users = User::where('name', 'like', "%{$keyword}%")->limit(10)->get();
        return response()->json($users);
    }
    public function create_admin(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $user->utype = 'ADM';
        $user->save();
        return response()->json(['message' => 'User promoted to admin successfully.','status'=>'success']);
    }
    public function remove_admin($id)
    {
        $user = User::findOrFail($id);
        $user->utype = 'USR';
        $user->save();
        return response()->json(['message' => 'Admin rights removed successfully.','status'=>'success']);
    }
}
