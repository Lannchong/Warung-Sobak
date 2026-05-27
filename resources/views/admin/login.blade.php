<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Warung Sobak</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            background-color: #F4F6F9;
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .login-card {
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-logo {
            font-size: 2rem;
            font-weight: 900;
            color: #D32F2F;
            text-align: center;
            margin-bottom: 30px;
        }
        .btn-primary-custom {
            background-color: #D32F2F;
            border: none;
        }
        .btn-primary-custom:hover {
            background-color: #b71c1c;
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="login-logo">
            🍜 WARUNG SOBAK
        </div>
        <h5 class="text-center mb-4">Login Admin Dashboard</h5>
        
        <form action="{{ route('login.post') }}" method="POST">
            @csrf
            
            @if ($errors->any())
                <div class="alert alert-danger pb-0">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="mb-3">
                <label class="form-label">Email / Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                    <input type="email" name="email" class="form-control" placeholder="Masukkan email admin" value="{{ old('email') }}" required>
                </div>
            </div>
            
            <div class="mb-4">
                <label class="form-label">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="fas fa-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary-custom text-white w-100 py-2 fw-bold">
                Masuk (Login)
            </button>
        </form>
    </div>

</body>
</html>