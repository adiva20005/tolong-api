<?php

namespace App\Http\Controllers;

use App\Models\Portfolio;
use App\Models\Skill;
use App\Models\User;
use App\Models\Warning;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class ProfileController extends Controller
{
    public function show(?User $user = null)
    {
        $targetUser = $user ?? Auth::user(); 

        if (!$targetUser) {
            return redirect()->route('login')->with('error', 'Anda harus login untuk melihat profil.');
        }

        $warnings = Warning::where('user_id', $targetUser->id)
                            ->with('admin')
                            ->latest()
                            ->get();

        $portfolios = Portfolio::where('user_id', $targetUser->id)
                                ->with('skills') 
                                ->latest()
                                ->get();

        $skills = Skill::orderBy('name')->get(); 

        return view('profile', compact('targetUser', 'warnings', 'portfolios', 'skills')); 
    }

    public function update(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
            'department' => 'required|string',
            'batch' => 'required|string',
            'description' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'department' => $request->department,
            'batch' => $request->batch,
            'description' => $request->description,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('profile')->with('success', 'Profile updated successfully!');
    }
}