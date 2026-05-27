@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-weight: 800; color: var(--dark-black);">Edit Menu</h3>
        <a href="{{ route('admin.menus') }}" class="btn btn-light shadow-sm border" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i>Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            
            <form action="{{ route('admin.menus.update', $menu->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama_menu" class="form-label fw-bold">Nama Menu</label>
                        <input type="text" class="form-select text-start px-3" id="nama_menu" name="nama_menu" value="{{ $menu->nama_menu }}" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="harga" class="form-label fw-bold">Harga (Rp)</label>
                        <input type="number" class="form-select text-start px-3" id="harga" name="harga" value="{{ $menu->harga }}" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kategori" class="form-label fw-bold">Kategori</label>
                        <select class="form-select px-3" id="kategori" name="kategori" required>
                            <option value="Makanan" {{ $menu->kategori == 'Makanan' ? 'selected' : '' }}>Makanan</option>
                            <option value="Minuman" {{ $menu->kategori == 'Minuman' ? 'selected' : '' }}>Minuman</option>
                            <option value="Soto" {{ $menu->kategori == 'Soto' ? 'selected' : '' }}>Soto</option>
                            <option value="Bakso" {{ $menu->kategori == 'Bakso' ? 'selected' : '' }}>Bakso</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="stok" class="form-label fw-bold">Stok Tersedia</label>
                        <input type="number" class="form-control px-3" id="stok" name="stok" value="{{ $menu->stok }}" style="border-radius: 8px;" min="0" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-12 mb-3">
                        <label for="foto" class="form-label fw-bold">Ganti Foto Menu <span class="text-muted small">(Kosongkan jika tidak diganti)</span></label>
                        <input type="file" class="form-control" id="foto" name="foto" style="border-radius: 8px;">
                        
                        @if($menu->foto)
                            <div class="mt-2">
                                <span class="text-muted small d-block mb-1">Foto saat ini:</span>
                                <img src="{{ asset('storage/' . $menu->foto) }}" class="rounded shadow-sm" style="max-height: 80px; object-fit: cover;">
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mb-4">
                    <label for="deskripsi" class="form-label fw-bold">Deskripsi Menu</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="4" style="border-radius: 8px;">{{ $menu->deskripsi }}</textarea>
                </div>

                <div class="text-end">
                    <button type="submit" class="btn text-white px-4 border-0 shadow-sm" style="background-color: var(--dark-black); border-radius: 8px;">
                        <i class="fas fa-save me-2"></i>Simpan Perubahan
                    </button>
                </div>
            </form>

        </div>
    </div>
@endsection