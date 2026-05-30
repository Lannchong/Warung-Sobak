@extends('layouts.admin')

@section('content')
    <h3 class="mb-4" style="font-weight: 800; color: var(--dark-black);">Dashboard Ringkasan</h3>

    <div class="row">
        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid var(--primary-red) !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #ffeaea; color: var(--primary-red); font-size: 1.5rem;">
                        <i class="fas fa-shopping-cart"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Pesanan</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalPesanan }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid var(--accent-yellow) !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #fff8e1; color: #ffb300; font-size: 1.5rem;">
                        <i class="fas fa-utensils"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Menu</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalMenu }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid var(--dark-black) !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #e2e8f0; color: var(--dark-black); font-size: 1.5rem;">
                        <i class="fas fa-users"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Pengguna</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalPengguna }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #FF9800 !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #fff3e0; color: #FF9800; font-size: 1.5rem;">
                        <i class="fas fa-star"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Ulasan</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalUlasan ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card border-0 shadow-sm" style="border-radius: 12px; border-left: 5px solid #E91E63 !important;">
                <div class="card-body d-flex align-items-center">
                    <div class="rounded-circle d-flex justify-content-center align-items-center" style="width: 60px; height: 60px; background-color: #fce4ec; color: #E91E63; font-size: 1.5rem;">
                        <i class="fas fa-heart"></i>
                    </div>
                    <div class="ms-3">
                        <h6 class="text-muted mb-1">Total Favorit</h6>
                        <h3 class="mb-0 fw-bold">{{ $totalFavorit ?? 0 }}</h3>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm mt-2" style="border-radius: 12px; height: 300px;">
        <div class="card-body d-flex justify-content-center align-items-center text-muted">
            <h5><i class="fas fa-chart-line me-2"></i> Area Grafik Penjualan (Akan datang)</h5>
        </div>
    </div>
@endsection