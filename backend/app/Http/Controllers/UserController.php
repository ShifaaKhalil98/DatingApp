<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Hash;
use App\Models\User;

class UserController extends Controller
{
    public function add_photo(Request $request, $user)
    {
        $request->validate([
            'photo' => 'required|image|max:2048',
        ]);

        $auth_user = Auth::user();

        $add_photo_url = route('users.add_photo', ['user' => $user->id]);

        $path = $request->file('photo')->store('public');
        $auth_user->photo_path = $path;
        $auth_user->save();

        return redirect()->back()->with('add_photo_url', $add_photo_url);

    
    }
    // public function edit_info(Request $request, $user)
    // {
    //     $request->validate([
    //         'name' => 'required|string',
    //         'dob' => 'required',
    //     ])

    //     $auth_user = Auth::user();


    // }

}
