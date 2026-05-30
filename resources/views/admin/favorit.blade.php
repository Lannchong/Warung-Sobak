@extends('layouts.admin')

@section('content')
<h3 class="mb-4" style="font-weight: 800;">Daftar Menu Terfavorit</h3>
<div class="card border-0 shadow-sm" style="border-radius: 12px;">
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Menu</th>
                    <th>Disukai Oleh</th>
                    <th>Tanggal Ditambahkan</th>
                </tr>
            </thead>
            <tbody>
                @foreach($favorits as $index => $fav)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td><strong>{{ $fav->menu->nama_menu ?? 'Menu Dihapus' }}</strong></td>
                    <td>{{ $fav->user->name ?? 'Misterius' }}</td>
                    <td>{{ $fav->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection