@extends('layouts.admin')

@section('content')
    <h3 class="mb-4" style="font-weight: 800; color: var(--dark-black);">Kelola Order (Pesanan Masuk)</h3>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert" style="border-radius: 8px; background-color: #e8f5e9; color: #2e7d32;">
            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="card border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body p-4">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-light" style="background-color: #f8f9fa;">
                        <tr>
                            <th class="py-3 px-3">No</th>
                            <th class="py-3">Pelanggan</th>
                            <th class="py-3">Menu Pesanan</th>
                            <th class="py-3 text-center">Jumlah</th>
                            <th class="py-3">Total Harga</th>
                            <th class="py-3 text-center">Status</th>
                            <th class="py-3 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($orders as $key => $order)
                            <tr>
                                <td class="px-3 fw-bold">{{ $key + 1 }}</td>
                                <td>
                                    <div class="fw-bold">{{ $order->user->name ?? 'Pelanggan' }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('d M Y, H:i') }} WIB</small>
                                </td>
                                <td>
                                    <div class="fw-bold">{{ $order->menu->nama_menu ?? 'Menu Dihapus' }}</div>
                                    <small class="text-muted">Rp {{ number_format($order->menu->harga ?? 0, 0, ',', '.') }}</small>
                                </td>
                                <td class="text-center fw-bold">{{ $order->jumlah }}</td>
                                <td class="fw-bold text-danger">Rp {{ number_format($order->total_harga, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    @if($order->status == 'pending')
                                        <span class="badge bg-warning text-dark px-3 py-2" style="border-radius: 20px;">Menunggu</span>
                                    @elseif($order->status == 'diproses')
                                        <span class="badge bg-info text-white px-3 py-2" style="border-radius: 20px;">Dimasak</span>
                                    @elseif($order->status == 'selesai')
                                        <span class="badge bg-success text-white px-3 py-2" style="border-radius: 20px;">Selesai</span>
                                    @else
                                        <span class="badge bg-danger text-white px-3 py-2" style="border-radius: 20px;">Batal</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <form action="{{ route('orders.updateStatus', $order->id) }}" method="POST" class="d-inline-flex gap-2">
                                        @csrf
                                        <select name="status" class="form-select form-select-sm" style="border-radius: 8px; width: 120px;">
                                            <option value="pending" {{ $order->status == 'pending' ? 'selected' : '' }}>Menunggu</option>
                                            <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>Dimasak</option>
                                            <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>Selesai</option>
                                            <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>Batal</option>
                                        </select>
                                        <button type="submit" class="btn btn-sm btn-dark px-2" style="border-radius: 8px;">
                                            <i class="fas fa-save"></i>
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="text-center text-muted py-4">
                                    <i class="fas fa-inbox fa-2x mb-2 d-block"></i> Belum ada pesanan masuk saat ini.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection