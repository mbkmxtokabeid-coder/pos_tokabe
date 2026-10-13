<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\RedirectController;
use Illuminate\Support\Facades\Hash;
use RealRashid\SweetAlert\Facades\Alert;

class UserController extends Controller
{
  public function index()
  {
    $users = User::orderByDesc('created_at')->get();
    return view('pages.user.daftar-user', compact('users'));
  }


  function addUser()
  {
    return view('pages.user.addUser');
  }


  function storeUser(Request $request)
  {
    $request->validate([
      'role' => 'required|in:Pemilik,Admin',
    ]);

    if ($request->re_password != $request->password) {
      Alert::error('Password tidak sama');
      return redirect()->back()->withInput();
    } else {
      User::create([
        'nama' => $request->namaKaryawan,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nomor_telepon' => $request->no_telp,
        'role' => $request->role,
        'status' => 'Aktif',
      ]);
      Alert::success('User Berhasil ditambahkan');
      return redirect()->route('user.index');
    }
  }

  function status($id)
  {
    $user = User::find($id);
    if (!$user) {
      Alert::error('User tidak ditemukan');
      return redirect()->back();
    } else {
      if ($user->status == 'Aktif') {
        $user->status = 'Non Aktif';
        $user->save();
        Alert::success('User Di Non-Aktifkan');
        return redirect()->route('user.index');
      } else {
        $user->status = 'Aktif';
        $user->save();
        Alert::success('User Di Aktifkan');
        return redirect()->route('user.index');
      }
    }
  }

  function edit($id)
  {
    $user = User::find($id);
    $roles = ['Pemilik', 'Admin'];
    return view('pages.user.edit-user', compact('user', 'roles'));
  }

  function update(Request $request, $id)
  {
    $request->validate([
      'role' => 'required|in:Pemilik,Admin',
    ]);

    $user = User::find($id);
    if ($request->re_password != $request->password) {
      Alert::error('Password tidak sama');
      return redirect()->back();
    } else {
      $user->update([
        'nama' => $request->namaKaryawan,
        'email' => $request->email,
        'password' => Hash::make($request->password),
        'nomor_telepon' => $request->no_telp,
        'role' => $request->role,
        'status' => 'Aktif',
      ]);
      Alert::success('User Berhasil diubah');
      return redirect('daftar-user');
    }
  }

  function profiles()
  {
    return view('pages.user.profile');
  }

  function updateProfile(Request $request, $id)
  {
    $user = User::find($id);
    $user->update([
      'nama' => $request->namaKaryawan,
      'email' => $request->email,
      'password' => Hash::make($request->password),
      'nomor_telepon' => $request->no_telp,
      'status' => 'Aktif',
    ]);
    Alert::success('Profile berhasil di update');
    return redirect('/profile');
  }
}
