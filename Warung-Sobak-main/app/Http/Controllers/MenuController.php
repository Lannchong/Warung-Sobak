<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    // [GET] Mengambil semua data makanan & minuman
    public function index()
    {
        // Menggunakan Eloquent Model (Menu::all) agar lebih rapi dan bebas error
        $menus = Menu::all();

        // Mengirimkan data ke Android dalam format JSON
        return response()->json([
            'status' => 'success',
            'message' => 'Berhasil mengambil data menu Warung Sobak',
            'data' => $menus
        ], 200);
    }

    // [POST] Menambahkan menu baru
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'price' => 'required|numeric',
        ]);

        // Simpan ke database
        $menu = new Menu();
        $menu->name = $request->name;
        $menu->price = $request->price;
        $menu->description = $request->description ?? null;
        $menu->stock = $request->stock ?? 0;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil ditambahkan!',
            'data' => $menu
        ], 201);
    }

    // [PUT] Mengubah data menu
    public function update(Request $request, $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan'], 404);
        }

        $menu->name = $request->name ?? $menu->name;
        $menu->price = $request->price ?? $menu->price;
        $menu->description = $request->description ?? $menu->description;
        $menu->stock = $request->stock ?? $menu->stock;
        $menu->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil diperbarui!',
            'data' => $menu
        ], 200);
    }

    // [DELETE] Menghapus menu
    public function destroy($id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return response()->json(['message' => 'Menu tidak ditemukan'], 404);
        }

        $menu->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Menu berhasil dihapus!'
        ], 200);
    }
}