<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MenuController extends Controller
{
    // [GET] Tampilkan halaman kelola menu (Tabel)
    public function index()
    {
        // Ambil semua data menu dari database
        $menus = Menu::all();
        // Arahkan ke file resources/views/admin/menus.blade.php
        return view('admin.menus', compact('menus'));
    }

    // [GET] Tampilkan form tambah menu baru
    public function create()
    {
        // Arahkan ke file resources/views/admin/create_menu.blade.php
        return view('admin.create_menu');
    }

    // [POST] Simpan data menu baru dari form ke database
    public function store(Request $request)
    {
        // 1. Validasi input dari form
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'kategori'  => 'required|string',
            'stok'      => 'required|integer|min:0',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048', // Maksimal 2MB
            'deskripsi' => 'nullable|string'
        ]);

        // 2. Proses upload foto (jika ada)
        $imagePath = null;
        if ($request->hasFile('foto')) {
            // Simpan foto ke folder storage/app/public/menus
            $imagePath = $request->file('foto')->store('menus', 'public');
        }

        // 3. Simpan ke database
        Menu::create([
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'kategori'  => $request->kategori,
            'stok'      => $request->stok,
            'foto'      => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        // 4. Kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('admin.menus')->with('success', 'Menu berhasil ditambahkan!');
    }

    // [GET] Tampilkan form edit menu
    public function edit($id)
    {
        $menu = Menu::findOrFail($id);
        return view('admin.edit_menu', compact('menu'));
    }

    // [PUT] Simpan perubahan data menu
    public function update(Request $request, $id)
    {
        $menu = Menu::findOrFail($id);

        // 1. Validasi input
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga'     => 'required|numeric',
            'kategori'  => 'required|string',
            'stok'      => 'required|integer|min:0',
            'foto'      => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'deskripsi' => 'nullable|string'
        ]);

        $imagePath = $menu->foto;

        // 2. Cek apakah admin mengupload foto baru
        if ($request->hasFile('foto')) {
            // Hapus foto lama dari penyimpanan jika ada
            if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
                Storage::disk('public')->delete($menu->foto);
            }
            // Simpan foto baru
            $imagePath = $request->file('foto')->store('menus', 'public');
        }

        // 3. Update data di database
        $menu->update([
            'nama_menu' => $request->nama_menu,
            'harga'     => $request->harga,
            'kategori'  => $request->kategori,
            'stok'      => $request->stok,
            'foto'      => $imagePath,
            'deskripsi' => $request->deskripsi,
        ]);

        // 4. Kembali ke halaman tabel dengan pesan sukses
        return redirect()->route('admin.menus')->with('success', 'Menu berhasil diperbarui!');
    }

    // [DELETE] Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::findOrFail($id);

        // Hapus foto dari penyimpanan sebelum menghapus data database
        if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
            Storage::disk('public')->delete($menu->foto);
        }

        // Hapus data dari tabel
        $menu->delete();

        return redirect()->route('admin.menus')->with('success', 'Menu berhasil dihapus!');
    }
}