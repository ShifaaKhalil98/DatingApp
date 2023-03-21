<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
// use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Likes;
use Carbon\Carbon;

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
   
        if($user){
            $user->name = $request->name;
            $user->gender = $request->gender;
            $user->dob = $request->dob;
            $user->bio = $request->bio;
            $user->profile_image = $request->profile_image;
    
            $user->save();
    
            return $user;
        }
        else{
            return "failure";
        }
    }

    public function users() {
        $user = auth()->user();

        if($user->gender=='female') $users = User::where('gender', 'male')->get();
        else $users = User::where('gender', 'female')->get();

        return $users;
    }

    public function age($dob)
    {
        $dob = Carbon::parse($dob);
        $now = Carbon::now();
        $age = $dob->diffInYears($now);

        return $age;
    }

    public function filter_by_age(Request $request)
    {
        $users=$this->users();
        $minAge = $request->input('min_age', 0);
        $maxAge = $request->input('max_age', 100);

    $users = $users->map(function ($user) {
        $user->age = $this->age($user->dob);
        return $user;
    });

    $users = $users->filter(function ($user) use ($minAge, $maxAge) {
        return $user->age >= $minAge && $user->age <= $maxAge;
    });

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

    public function add_to_favorites(Request $request){

        $user = auth()->user();
        $liked_user=User::find($request->liked_user_id);

        if ($user && $liked_user) {
    
            $likes = new Likes;
            $likes->user_id = $user->id;
            $likes->liked_user_id = $liked_user->id;

            $likes->save();
  
            return "success";
        } else {
            return "failure";
        }
    }

        
    public function add_to_blocks($user_id, $blocked_user_id){
        $user = User::find($user_id);
        $blocked_user=User::find($blocked_user_id);
        
        if ($user && $blocked_user) {
    
            $blocks = new blocks;
            $blocks->user_id = $user->id;
            $blocks->blocked_user_id = $blocked_user->id;

            $blocks->save();
  
            return redirect('');
        } else {

            return redirect('');
        }
    }
    
}
