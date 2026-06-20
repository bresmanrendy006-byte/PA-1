@extends('layouts.admin')

@section('title', 'Kelola Testimoni')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolatestimoni.css') }}">
@endpush

@section('content')

<div class="admin-container">

    <h2>Testimoni</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-wrapper">
        <table class="table-testimoni">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Pelanggan</th>
                    <th>Foto</th>
                    <th>Isi Testimoni</th>
                    <th>Rating</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>

                @forelse($testimonials as $t)
                <tr>
                    <td>{{ $loop->iteration }}</td>

                    <td>{{ $t->user->username }}</td>

                    <td>
                        @if($t->photo)
                            <img src="{{ asset('storage/'.$t->photo) }}"
                                 width="80"
                                 style="border-radius:6px;"
                                 alt="Foto {{ $t->user->username }}">
                        @else
                            <span style="color:#aaa;font-size:12px;">Tidak ada</span>
                        @endif
                    </td>

                    <td>{{ Str::limit($t->content, 80) }}</td>

                    <td>{{ $t->rating }} ⭐</td>

                    <td>
                        @if(auth()->user()->isOwner())
                            <button class="btn-hapus btn-delete"
                                data-url="{{ route('admin.testimoni.destroy', $t->id) }}"
                                data-method="DELETE"
                                data-title="Hapus Testimoni"
                                data-message="Apakah Anda yakin ingin menghapus testimoni ini?"
                                data-item-name="{{ $t->user->username }}">
                                Hapus
                            </button>
                        @endif
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