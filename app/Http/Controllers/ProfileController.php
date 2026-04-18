<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Education;
use App\Models\JobTitle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ProfileController extends Controller
{
    public function show()
    {
        return view('profile.show', [
            'user' => Auth::user(),
            'divisions' => Division::orderBy('name')->get(),
            'jobTitles' => JobTitle::orderBy('name')->get(),
            'educations' => Education::orderBy('name')->get(),
        ]);
    }

    public function deletePhoto(Request $request)
    {
        Auth::user()->deleteProfilePhoto();
        return back()->with('flash.banner', 'Foto profil dihapus.');
    }
}
