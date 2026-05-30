@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4 fw-bold">Pengaturan Akun</h3>
    
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif
    @if($errors->any())
        <div class="alert alert-danger pb-0">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4"><i class="fas fa-user-edit me-2"></i> Ubah Profil Admin</h5>
                    
                    <form action="{{ route('admin.settings.updateProfile') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted">Nama Lengkap</label>
                            <input type="text" name="name" class="form-control" value="{{ auth()->user()->name ?? '' }}" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Email</label>
                            <input type="email" name="email" class="form-control" value="{{ auth()->user()->email ?? '' }}" required>
                        </div>
                        <button type="submit" class="btn btn-dark w-100 py-2">Simpan Perubahan</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-body p-4">
                    <h5 class="card-title fw-bold mb-4"><i class="fas fa-lock me-2"></i> Ubah Kata Sandi</h5>
                    
                    <form action="{{ route('admin.settings.updatePassword') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label text-muted">Password Lama</label>
                            <input type="password" name="current_password" class="form-control" placeholder="Masukkan password lama" required>
                        </div>
                        <div class="mb-4">
                            <label class="form-label text-muted">Password Baru</label>
                            <input type="password" name="new_password" class="form-control" placeholder="Masukkan password baru" required>
                        </div>
                        <button type="submit" class="btn btn-danger w-100 py-2">Update Password</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection