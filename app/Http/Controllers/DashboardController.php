<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User; 
use App\Models\Menu;
use App\Models\Order; 
use App\Models\Ulasan; 
use App\Models\Favorite; 
use Illuminate\Support\Facades\Hash; 
use Illuminate\Support\Facades\Storage; 

class DashboardController extends Controller
{
    // 1. HALAMAN UTAMA DASHBOARD 
    public function dashboard(Request $request) 
    {
        $totalPengguna = User::count();
        $totalMenu = Menu::count();
        $totalPesanan = Order::count();
        $totalUlasan = Ulasan::count(); 
        $totalFavorit = Favorite::count(); 

        $search = $request->input('search');
        if ($search) {
            $users = User::where('name', 'LIKE', "%{$search}%")
                         ->orWhere('email', 'LIKE', "%{$search}%")
                         ->get();
        } else {
            $users = User::all(); 
        }
        
        return view('admin.dashboard', compact('users', 'totalPengguna', 'totalMenu', 'totalPesanan', 'totalUlasan', 'totalFavorit'));
    }

    // 2. HALAMAN DATA PENGGUNA dan ADMIN 
    public function users()
    {
        $users = User::all();
        return view('admin.users', compact('users'));
    }

    // 3. HALAMAN KELOLA MENU 
    public function menus()
    {
        $menus = Menu::orderBy('created_at', 'desc')->get(); 
        return view('admin.menus', compact('menus'));
    }

    // 4. HALAMAN KELOLA ORDER (Pesanan Masuk) 
    public function orders()
    {
        $orders = Order::with(['user'])->orderBy('created_at', 'desc')->get();
        return view('admin.orders', compact('orders'));
    }

    // -- 5. HALAMAN MELIHAT ULASAN PELANGGAN DI WEB ADMIN
    public function ulasans()
    {
        // Ambil ulasan dari database, sertakan data user, urutkan dari terbaru
        $ulasans = Ulasan::with(['user'])->orderBy('created_at', 'desc')->get();
        return view('admin.ulasan', compact('ulasans')); 
    }

    // FUNGSI UNTUK TAMBAH USER 
    public function create()
    {
        return view('admin.create_user');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password), 
            'role' => 'admin' 
        ]);
        
        return redirect()->route('admin.users')->with('success', 'Admin baru berhasil ditambahkan!');
    }

    // FUNGSI UTAMA KELOLA MENU (CRUD) 
    public function createMenu()
    {
        return view('admin.create_menu');
    }

    public function storeMenu(Request $request)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0', 
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048', 
        ]);

        $menu = new Menu();
        $menu->nama_menu = $request->nama_menu;
        $menu->harga = $request->harga;
        $menu->kategori = $request->kategori;
        $menu->stok = $request->stok; 
        $menu->deskripsi = $request->deskripsi;
        
        if ($request->hasFile('foto')) {
            $fotoPath = $request->file('foto')->store('menus', 'public');
            $menu->foto = $fotoPath;
        }
        
        $menu->save();
        return redirect()->route('admin.menus')->with('success', 'Menu baru berhasil ditambahkan!');
    }

    public function editMenu($id)
    {
        $menu = Menu::findOrFail($id); 
        return view('admin.edit_menu', compact('menu'));
    }

    public function updateMenu(Request $request, $id)
    {
        $request->validate([
            'nama_menu' => 'required|string|max:255',
            'harga' => 'required|numeric',
            'kategori' => 'required|string',
            'stok' => 'required|integer|min:0', 
            'deskripsi' => 'nullable|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $menu = Menu::findOrFail($id);
        $menu->nama_menu = $request->nama_menu;
        $menu->harga = $request->harga;
        $menu->kategori = $request->kategori;
        $menu->stok = $request->stok; 
        $menu->deskripsi = $request->deskripsi;

        if ($request->hasFile('foto')) {
            if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
                Storage::disk('public')->delete($menu->foto);
            }
            $fotoPath = $request->file('foto')->store('menus', 'public');
            $menu->foto = $fotoPath;
        }

        $menu->save();
        return redirect()->route('admin.menus')->with('success', 'Menu berhasil diperbarui!');
    }

    public function destroyMenu($id)
    {
        $menu = Menu::findOrFail($id);
        
        if ($menu->foto && Storage::disk('public')->exists($menu->foto)) {
            Storage::disk('public')->delete($menu->foto);
        }

        $menu->delete();
        return redirect()->route('admin.menus')->with('success', 'Menu berhasil dihapus!');
    }

    // --- FUNGSI UPDATE STATUS PESANAN 
    public function updateOrderStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|string'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status; 
        $order->save();

        return redirect()->route('admin.orders')->with('success', 'Status pesanan berhasil diperbarui!');
    }
}