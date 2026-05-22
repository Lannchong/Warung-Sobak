<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna - Warung Sobak</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <style>
        :root {
            --primary-red: #D32F2F;   /* Merah Pedas ala Gacoan */
            --dark-black: #1A1A1A;    /* Hitam Arang */
            --light-bg: #F8FAFC;      /* Background Terang Lembut */
            --border-color: #E2E8F0;
        }

        body {
            background-color: var(--light-bg);
            font-family: 'Plus Jakarta Sans', sans-serif;
            color: var(--dark-black);
        }

        .form-wrapper {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: center;
            padding: 40px 0;
        }

        .card-form {
            background-color: white;
            border-radius: 20px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.04);
            border: 1px solid var(--border-color);
            transition: transform 0.3s ease;
        }

        .card-form:hover {
            transform: translateY(-2px);
        }

        .form-label {
            font-size: 0.9rem;
            font-weight: 600;
            color: #475569;
            margin-bottom: 8px;
        }

        .input-group-custom {
            position: relative;
        }

        .input-group-custom i {
            position: absolute;
            left: 16px;
            top: 50%;
            transform: translateY(-50%);
            color: #94A3B8;
            transition: color 0.3s;
            z-index: 10;
        }

        .form-control-custom {
            padding: 14px 16px 14px 45px;
            border-radius: 12px;
            border: 1.5px solid var(--border-color);
            background-color: #F8FAFC;
            font-size: 0.95rem;
            font-weight: 500;
            transition: all 0.3s ease;
            width: 100%;
        }

        .form-control-custom:focus {
            background-color: white;
            border-color: var(--primary-red);
            box-shadow: 0 0 0 4px rgba(211, 47, 47, 0.1);
            outline: none;
        }

        .form-control-custom:focus + i {
            color: var(--primary-red);
        }

        .btn-submit {
            background-color: var(--dark-black);
            color: white;
            font-weight: 700;
            font-size: 1rem;
            padding: 14px;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(26, 26, 26, 0.15);
            transition: all 0.3s ease;
        }

        .btn-submit:hover {
            background-color: var(--primary-red);
            box-shadow: 0 6px 20px rgba(211, 47, 47, 0.3);
            color: white;
        }

        .btn-back {
            color: #64748B;
            font-weight: 600;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: color 0.3s;
            font-size: 0.95rem;
        }

        .btn-back:hover {
            color: var(--primary-red);
        }
    </style>
</head>
<body>

<div class="container">
    <div class="form-wrapper" style="max-width: 520px; margin: 0 auto;">
        
        <div class="mb-4">
            <a href="{{ route('dashboard') }}" class="btn-back">
                <i class="fas fa-arrow-left-long"></i> Kembali ke Dashboard
            </a>
        </div>

        @if ($errors->any())
            <div class="alert alert-danger border-0 shadow-sm mb-4" style="border-radius: 14px;">
                <ul class="mb-0 px-3">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card card-form p-4 p-sm-5">
            <div class="text-center mb-4">
                <div class="mb-2" style="font-size: 2.5rem;">🍜</div>
                <h4 style="font-weight: 800; color: var(--dark-black); letter-spacing: -0.5px;">
                    Tambah Pengguna Baru
                </h4>
                <p class="text-muted small">Silakan lengkapi data di bawah untuk mendaftarkan akun admin/kasir.</p>
            </div>
            
            <form id="formTambahPengguna" action="{{ route('users.store') }}" method="POST">
                @csrf 

                <div class="mb-3">
                    <label for="name" class="form-label">Nama Lengkap</label>
                    <div class="input-group-custom">
                        <input type="text" class="form-control-custom" id="name" name="name" placeholder="Masukkan nama lengkap..." value="{{ old('name') }}" required>
                        <i class="fas fa-user"></i>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label">Alamat Email</label>
                    <div class="input-group-custom">
                        <input type="email" class="form-control-custom" id="email" name="email" placeholder="contoh@warungsobak.com" value="{{ old('email') }}" required>
                        <i class="fas fa-envelope"></i>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="password" class="form-label">Password Akun</label>
                    <div class="input-group-custom">
                        <input type="password" class="form-control-custom" id="password" name="password" placeholder="Minimal 6 karakter..." required>
                        <i class="fas fa-lock"></i>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-circle-check me-2"></i>Simpan Data Pengguna
                    </button>
                </div>
            </form>
        </div>
        
    </div>
</div>

<script>
    document.getElementById('formTambahPengguna').addEventListener('submit', function(e) {
        // 1. Tahan proses kirim bawaan browser sebentar demi memunculkan animasi sukses
        e.preventDefault();
        
        // 2. Munculkan Pop-up Animasi Sukses
        SweetAlert2.fire({
            title: 'Berhasil!',
            text: 'Data berhasil disimpan ke sistem.',
            icon: 'success',
            confirmButtonColor: '#D32F2F', // Warna merah pedas gacoan
            confirmButtonText: 'Oke, Mantap!',
            timer: 2000, // Pop-up otomatis hilang setelah 2 detik jika tidak diklik
            timerProgressBar: true
        }).then((result) => {
            // 3. Setelah tombol diklik atau waktu habis, form resmi dikirimkan ke Laravel Controller
            this.submit();
        });
    });
</script>

</body>
</html>