@extends('layouts.app')

@section('title', 'Kedanku Coffee - Testimoni')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/testimoni.css') }}">
@endpush

@section('content')

{{-- HERO SECTION --}}
<section class="testimoni-hero">
    <div class="testimoni-hero-content">
        <h1>Testimoni Pelanggan</h1>
        <p>Apa kata mereka tentang Kedanku Coffee?</p>
    </div>
</section>

<section class="testimoni-section">
<div class="testimoni-container">

    {{-- ALERT MESSAGES --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- FORM TAMBAH TESTIMONI --}}
    @auth
    @if(Auth::user()->role === 'customer')
    <div class="form-testimoni">

        <h2>✍️ Kirim Testimoni</h2>

        @if($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('testimoni.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <textarea name="isi" rows="4"
                    placeholder="Tulis pengalaman kamu di Kedanku Coffee..."
                    required>{{ old('isi') }}</textarea>
            </div>

            <div class="form-group">
                <select name="rating" required>
                    <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ - Luar biasa!</option>
                    <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ - Bagus</option>
                    <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ - Cukup</option>
                    <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ - Kurang</option>
                    <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ - Buruk</option>
                </select>
            </div>

            <div class="form-group">
                <input type="file" name="foto" accept="image/*" onchange="previewImage(event)">
                <img id="preview" class="preview-foto" alt="Preview foto" style="display:none;">
            </div>

            <button type="submit">
                <span>📝</span> Kirim Testimoni
            </button>

        </form>
    </div>
    @endif
    @endauth


    {{-- GRID TESTIMONI --}}
    <div class="testimoni-grid">

        @forelse($testimonis as $t)

        <div class="testimoni-card">

            {{-- FOTO --}}
            @if($t->foto)
                <div class="foto-wrapper">
                    <img src="{{ asset('storage/'.$t->foto) }}"
                         class="foto-testimoni"
                         alt="Foto {{ $t->user->username }}">
                </div>
            @endif

            {{-- MODE LIHAT --}}
            <div id="lihat{{ $t->id }}" class="card-content">

                <p class="isi-testimoni">"{{ $t->isi }}"</p>

                <div class="customer">
                    <strong>{{ $t->user->username }}</strong>
                    <div class="rating">
                        @for($i = 1; $i <= 5; $i++)
                            {{ $i <= $t->rating ? '⭐' : '☆' }}
                        @endfor
                    </div>
                </div>

                @auth
                @if(Auth::id() === $t->user_id)
                <div class="aksi-testimoni">

                    <button onclick="editTestimoni({{ $t->id }})" class="btn-edit">
                        ✏️ Edit
                    </button>

                    {{-- Hapus pakai popuphapus.js --}}
                    <button type="button" class="btn-hapus btn-delete-testimoni"
                        data-url="{{ route('testimoni.destroy', $t->id) }}"
                        data-method="DELETE"
                        data-title="Hapus Testimoni"
                        data-message="Yakin ingin menghapus testimoni ini?"
                        data-item-name="Testimoni kamu">
                        🗑️ Hapus
                    </button>

                </div>
                @endif
                @endauth

            </div>

            {{-- MODE EDIT --}}
            <div id="edit{{ $t->id }}" class="edit-mode" style="display:none;">

                <form action="{{ route('testimoni.update', $t->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <textarea name="isi" rows="3" required>{{ $t->isi }}</textarea>

                    <select name="rating">
                        <option value="5" {{ $t->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                        <option value="4" {{ $t->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                        <option value="3" {{ $t->rating == 3 ? 'selected' : '' }}>⭐⭐⭐</option>
                        <option value="2" {{ $t->rating == 2 ? 'selected' : '' }}>⭐⭐</option>
                        <option value="1" {{ $t->rating == 1 ? 'selected' : '' }}>⭐</option>
                    </select>

                    <input type="file" name="foto" accept="image/*">

                    <div class="aksi-testimoni">
                        <button type="submit" class="btn-edit">💾 Simpan</button>
                        <button type="button" onclick="batalEdit({{ $t->id }})" class="btn-cancel">❌ Batal</button>
                    </div>

                </form>
            </div>

        </div>

        @empty

        <div class="no-testimoni">
            <p>✨ Belum ada testimoni pelanggan.</p>
            <p style="font-size:0.9rem;margin-top:5px;">Jadilah yang pertama memberikan testimoni!</p>
        </div>

        @endforelse

    </div>
</div>
</section>

@endsection

@push('scripts')
<script>
function editTestimoni(id) {
    document.getElementById('lihat' + id).style.display = 'none';
    document.getElementById('edit' + id).style.display = 'block';
}

function batalEdit(id) {
    document.getElementById('lihat' + id).style.display = 'flex';
    document.getElementById('edit' + id).style.display = 'none';
}

function previewImage(event) {
    const preview = document.getElementById('preview');
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.src = e.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
    } else {
        preview.style.display = 'none';
        preview.src = '';
    }
}

// Auto-hide alerts setelah 3 detik
setTimeout(function() {
    document.querySelectorAll('.alert').forEach(function(alert) {
        alert.style.transition = 'opacity 0.3s';
        alert.style.opacity = '0';
        setTimeout(function() { alert.remove(); }, 300);
    });
}, 3000);
</script>
@endpush