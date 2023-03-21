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
   
    public function update(Request $request)
    {
        $user = auth()->user();
    
        $request->validate([
            'name' => 'required|string|max:255',
            'gender' => 'required|in:female,male,Female,Male',
            'dob' => 'required|string',
            'bio' => 'string',
            'profile_image' => 'string',
        ]);
   
        $user->update([
            'name' => $request->name,
            'gender' => $request->gender,
            'dob' => $request->dob,
            'bio' => $request->bio,
            'profile_image' => $request->profile_image,
        ]);
    
        return redirect()->back()->with('success', 'User information updated successfully.');
    }
        
    public function users() {
        $users = App\Models\User::all();
        return $users;
    }

    public function filter_by_age(Request $request)
    {
    $minAge = $request->input('minAge', 0);
    $maxAge = $request->input('maxAge', 100);

    $users = User::whereBetween('age', [$minAge, $maxAge])->get();

    return $users;
    }

    public function search(Request $request)
    {
    $searchQuery = $request->input('q');

    $users = User::when($searchQuery, function ($query, $searchQuery) {
        return $query->where('name', 'like', '%'.$searchQuery.'%');
    })->get();

    return $users;
    }

}
