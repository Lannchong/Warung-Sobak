@extends('layouts.app') {{-- Sesuaikan nama layout utamamu jika bukan layouts.app --}}

@section('content')
<div class="container-fluid px-4">
    <h3 class="mt-4" style="font-weight: 800;">Kritik & Saran Pelanggan</h3>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item active">Ulasan Masuk dari Aplikasi Android</li>
    </ol>

    <div class="card mb-4 border-0 shadow-sm" style="border-radius: 12px;">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Pelanggan</th>
                            <th>Rating</th>
                            <th>Kritik & Saran</th>
                            <th>Tanggal Masuk</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($ulasans as $index => $ulasan)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td><strong>{{ $ulasan->user->name ?? 'Pelanggan' }}</strong></td>
                            <td class="text-warning">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= $ulasan->rating)
                                        ★
                                    @else
                                        ☆
                                    @endif
                                @endfor
                                ({{ $ulasan->rating }}/5)
                            </td>
                            <td>{{ $ulasan->saran_kritik ?? '-' }}</td>
                            <td>{{ $ulasan->created_at->format('d M Y, H:i') }}</td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center text-muted">Belum ada ulasan dari pelanggan.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection