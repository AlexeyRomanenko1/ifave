<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function index(Request $request)
    {
        $user_id = Auth::id();
        $user_info = DB::table('users')->select('*')->where('id', $user_id)->get();
        $user_image = DB::table('users')->where('id', $user_id)->pluck('image');
        return view('user.profile', compact('user_info', 'user_image'));
    }

    public function user_profile_update(Request $request)
    {
        $user_id = Auth::id();
        if ($request->hasFile('profile_picture') && $request->file('profile_picture')->isValid()) {
            $file = $request->file('profile_picture');
            $allowedExtensions = ['jpg', 'jpeg', 'png', 'JPG', 'JPEG', 'PNG'];
            $extension = $file->getClientOriginalExtension();
            if (!in_array($extension, $allowedExtensions)) {
                return redirect()->back()->with('error', "Only JPG,JPEG,PNG entensions are allowed");
            }
            $uniqueName = date('m_d_Y_his') . $file->getClientOriginalName();
            // $file->storeAs(public_path('images/posts/'), $uniqueName);
            $path =  $file->move(public_path('images/user_images/'), $uniqueName);
            $update_votes = DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'image' => $uniqueName,
                    'bio' => $request->bio
                ]);
            return redirect()->back()->with('success', 'Profile Updated successfully');
        } else {
            $update_votes = DB::table('users')
                ->where('id', $user_id)
                ->update([
                    'bio' => $request->bio
                ]);
            return redirect()->back()->with('success', 'Profile Updated successfully');
        }
    }
}
