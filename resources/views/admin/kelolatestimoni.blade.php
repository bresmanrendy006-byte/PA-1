@extends('layouts.admin')

@section('title', 'Kelola Testimoni')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolatestimoni.css') }}">
@endpush

@section('content')

<div class="admin-container">

    <h2>Kelola Testimoni</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrapper">
        <table class="table-testimoni">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Customer</th>
                    <th>Foto</th>
                    <th>Isi Testimoni</th>
                    <th>Rating</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($testimonis as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $t->user->username }}</td>

                    <td>
                        @if($t->foto)
                            <img src="{{ asset('storage/'.$t->foto) }}"
                                 width="80"
                                 style="border-radius:6px;"
                                 alt="Foto {{ $t->user->username }}">
                        @else
                            <span style="color:#aaa;font-size:12px;">Tidak ada</span>
                        @endif
                    </td>

                    <td>{{ Str::limit($t->isi, 80) }}</td>

                    <td>{{ $t->rating }} ⭐</td>

                    <td>
                        {{-- Semua role admin bisa hapus testimoni --}}
                        <button class="btn-hapus btn-delete"
                            data-url="{{ route('admin.testimoni.destroy', $t->id) }}"
                            data-method="DELETE"
                            data-title="Hapus Testimoni"
                            data-message="Yakin ingin menghapus testimoni ini?"
                            data-item-name="{{ $t->user->username }}">
                            Hapus
                        </button>
                    </td>
                </tr>

                @empty
                <tr>
                    <td colspan="6" class="empty-text">Belum ada testimoni</td>
                </tr>
                @endforelse

            </tbody>
        </table>
    </div>

</div>

@endsection