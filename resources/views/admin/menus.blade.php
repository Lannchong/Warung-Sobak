@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-weight: 800; color: var(--dark-black);">Kelola Menu (Soto & Bakso)</h3>
        <a href="{{ route('admin.menus.create') }}" class="btn text-white border-0 shadow-sm" style="background-color: var(--dark-black); border-radius: 8px;">
            <i class="fas fa-plus me-2"></i>Tambah Menu Baru
        </a>
    </div>

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            <div class="table-responsive">
                <table class="table table-hover align-middle text-center" style="border-radius: 8px; overflow: hidden;">
                    <thead class="table-light">
                        <tr>
                            <th width="5%">No</th>
                            <th width="10%">Foto</th>
                            <th width="25%">Nama Menu</th>
                            <th width="15%">Kategori</th>
                            <th width="15%">Harga</th>
                            <th width="10%">Stok</th> 
                            <th width="20%">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($menus as $index => $menu)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>
                                    @if($menu->foto)
                                        <img src="{{ asset('storage/' . $menu->foto) }}" alt="{{ $menu->nama_menu }}" class="img-fluid rounded shadow-sm" style="max-height: 60px; width: 60px; object-fit: cover;">
                                    @else
                                        <span class="text-muted small">Tidak ada foto</span>
                                    @endif
                                </td>
                                <td class="fw-bold text-start">{{ $menu->nama_menu }}</td>
                                <td>
                                    <span class="badge bg-secondary">{{ $menu->kategori }}</span>
                                </td>
                                <td>Rp {{ number_format($menu->harga, 0, ',', '.') }}</td>
                                
                                <td>
                                    @if($menu->stok > 0)
                                        <span class="badge bg-success">{{ $menu->stok }} Porsi</span>
                                    @else
                                        <span class="badge bg-danger">Habis</span>
                                    @endif
                                </td>

                                <td>
                                    <a href="{{ route('admin.menus.edit', $menu->id) }}" class="btn btn-sm btn-warning text-white me-1" title="Edit Menu">
                                        <i class="fas fa-edit"></i> Edit
                                    </a>
                                    
                                    <form action="{{ route('admin.menus.destroy', $menu->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Apakah kamu yakin ingin menghapus menu ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Hapus Menu">
                                            <i class="fas fa-trash"></i> Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-muted py-5">
                                    <i class="fas fa-box-open fs-2 mb-3"></i><br>
                                    Belum ada menu yang ditambahkan.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection