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

        $path = $request->file('photo')->store('public');
        $user->photo_path = $path;
        $user->save();

        return redirect()->route('users.profile', ['user' => $user->id])
        ->with('addPhotoUrl', $addPhotoUrl);
    }
}
