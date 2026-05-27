@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-weight: 800; color: var(--dark-black);">Tambah Menu Baru</h3>
        <a href="{{ route('admin.menus') }}" class="btn btn-outline-secondary" style="border-radius: 8px;">
            <i class="fas fa-arrow-left me-2"></i> Kembali
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <form action="{{ route('admin.menus.store') }}" method="POST" enctype="multipart/form-data">
                @csrf 
                
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Nama Menu</label>
                        <input type="text" name="nama_menu" class="form-control" placeholder="Contoh: Bakso Urat Sobak" style="border-radius: 8px;" required>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Harga (Rp)</label>
                        <input type="number" name="harga" class="form-control" placeholder="Contoh: 15000" style="border-radius: 8px;" required>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Kategori</label>
                        <select name="kategori" class="form-select" style="border-radius: 8px;" required>
                            <option value="">-- Pilih Kategori --</option>
                            <option value="Makanan">Makanan</option>
                            <option value="Minuman">Minuman</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold text-muted">Stok Awal</label>
                        <input type="number" name="stok" class="form-control" placeholder="Contoh: 50" min="0" value="0" style="border-radius: 8px;" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold text-muted">Foto Menu</label>
                    <input type="file" name="foto" class="form-control" style="border-radius: 8px;">
                </div>

                <div class="mb-4">
                    <label class="form-label fw-bold text-muted">Deskripsi Singkat</label>
                    <textarea name="deskripsi" class="form-control" rows="3" placeholder="Deskripsi menu..." style="border-radius: 8px;"></textarea>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="submit" class="btn text-white px-4 py-2" style="background-color: var(--dark-black); border-radius: 8px;">
                        <i class="fas fa-save me-2"></i> Simpan Menu
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection