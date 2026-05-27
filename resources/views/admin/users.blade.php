@extends('layouts.admin')

@section('content')
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 style="font-weight: 800; color: var(--dark-black);">Data Pengguna (Pelanggan & Admin)</h3>
        <a href="{{ route('admin.users.create') }}" class="btn text-white border-0 shadow-sm" style="background-color: var(--dark-black); border-radius: 8px;">
            <i class="fas fa-user-plus me-2"></i>Tambah Pengguna Baru
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
                            <th width="30%">Nama Lengkap</th>
                            <th width="30%">Alamat Email</th>
                            <th width="15%">Hak Akses (Role)</th>
                            <th width="20%">Tanggal Bergabung</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $index => $user)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td class="fw-bold text-start">{{ $user->name }}</td>
                                <td class="text-start text-muted">{{ $user->email }}</td>
                                <td>
                                    @if($user->role == 'admin')
                                        <span class="badge bg-danger px-3 py-2" style="border-radius: 6px;">
                                            <i class="fas fa-user-shield me-1"></i> Admin
                                        </span>
                                    @else
                                        <span class="badge bg-info text-dark px-3 py-2" style="border-radius: 6px;">
                                            <i class="fas fa-user me-1"></i> Pelanggan
                                        </span>
                                    @endif
                                </td>
                                <td>
                                    {{ $user->created_at ? $user->created_at->translatedFormat('d F Y') : 'Tidak Diketahui' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-muted py-5">
                                    <i class="fas fa-users-slash fs-2 mb-3"></i><br>
                                    Belum ada pengguna atau pelanggan yang terdaftar di database.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>
@endsection