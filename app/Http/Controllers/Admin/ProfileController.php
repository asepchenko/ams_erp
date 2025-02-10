<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use App\Rules\MatchOldPassword;
use Illuminate\Support\Facades\Hash;
use App\User;

class ProfileController
{
    public function index()
    {
        abort_unless(\Gate::allows('profile_access'), 403);
        $name = auth()->user()->name;
        $dept = auth()->user()->kode_departemen; 
        $jab = auth()->user()->jabatan; 
        return view('admin.profile.index',compact('name','jab','dept'));
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'pass_lama' => ['required', new MatchOldPassword],
            'pass_baru' => ['required'],
            'pass_confirm' => ['same:pass_baru'],
        ]);
   
        User::find(auth()->user()->id)->update(['password'=> Hash::make($request->pass_baru)]);
        return redirect('admin/profile')->withSuccess('berhasil mengubah password');
    }
}
