@extends('layouts.app')

@section('title', 'Kedanku Coffee - Testimonials')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/testimoni.css') }}">
@endpush

@section('content')

{{-- HERO SECTION --}}
<section class="testimoni-hero">
    <div class="testimoni-hero-content">
        <h1>Customer Testimonials</h1>
        <p>What do they say about Kedanku Coffee?</p>
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

    {{-- ADD TESTIMONIAL FORM --}}
    @auth
    @if(Auth::user()->role === 'customer')

        @if(!$hasConfirmedOrder)
            <div class="alert alert-info">
                You don't have any orders yet.<br>
                <small style="opacity:.8;">Place an order first to submit a testimonial</small>
                <br>
                <a href="{{ route('guest.menu') }}">View Menu & Order Now →</a>
            </div>

        @else
        {{-- Form is only shown if the user has a confirmed order --}}
        <div class="form-testimoni">
            <h2>✍️ Submit a Testimonial</h2>

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
                    <textarea name="content" rows="4"
                        placeholder="Share your experience at Kedanku Coffee..."
                        required>{{ old('content') }}</textarea>
                </div>

                <div class="form-group">
                    <select name="rating" required>
                        <option value="5" {{ old('rating', '5') == '5' ? 'selected' : '' }}>⭐⭐⭐⭐⭐ - Excellent!</option>
                        <option value="4" {{ old('rating') == '4' ? 'selected' : '' }}>⭐⭐⭐⭐ - Good</option>
                        <option value="3" {{ old('rating') == '3' ? 'selected' : '' }}>⭐⭐⭐ - Average</option>
                        <option value="2" {{ old('rating') == '2' ? 'selected' : '' }}>⭐⭐ - Poor</option>
                        <option value="1" {{ old('rating') == '1' ? 'selected' : '' }}>⭐ - Bad</option>
                    </select>
                </div>

                <div class="form-group">
                    <input type="file" name="photo" accept="image/*" onchange="previewImage(event)" required>
                    <img id="preview" class="preview-foto" alt="Photo preview" style="display:none;">
                </div>

                <button type="submit">
                    <span>📝</span> Submit Testimonial
                </button>
            </form>
        </div>
        @endif

    @endif
    @endauth

    {{-- TESTIMONIAL GRID --}}
    <div class="testimoni-grid">

        @forelse($testimonials as $t)

        <div class="testimoni-card">

            {{-- PHOTO --}}
            @if($t->photo)
                <div class="foto-wrapper">
                    <img src="{{ asset('storage/'.$t->photo) }}"
                         class="foto-testimoni"
                         alt="Photo {{ $t->user->username }}">
                </div>
            @endif

            {{-- VIEW MODE --}}
            <div id="lihat{{ $t->id }}" class="card-content">

                <p class="isi-testimoni">"{{ $t->content }}"</p>

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

                    {{-- Delete using popuphapus.js --}}
                    <button type="button" class="btn-hapus btn-delete-testimoni"
                        data-url="{{ route('testimoni.destroy', $t->id) }}"
                        data-method="DELETE"
                        data-title="Delete Testimonial"
                        data-message="Are you sure you want to delete this testimonial?"
                        data-item-name="Your testimonial">
                        🗑️ Delete
                    </button>

                </div>
                @endif
                @endauth

            </div>

            {{-- EDIT MODE --}}
            <div id="edit{{ $t->id }}" class="edit-mode" style="display:none;">

                <form action="{{ route('testimoni.update', $t->id) }}"
                      method="POST"
                      enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <textarea name="content" rows="3" required>{{ $t->content }}</textarea>

                    <select name="rating">
                        <option value="5" {{ $t->rating == 5 ? 'selected' : '' }}>⭐⭐⭐⭐⭐</option>
                        <option value="4" {{ $t->rating == 4 ? 'selected' : '' }}>⭐⭐⭐⭐</option>
                        <option value="3" {{ $t->rating == 3 ? 'selected' : '' }}>⭐⭐⭐</option>
                        <option value="2" {{ $t->rating == 2 ? 'selected' : '' }}>⭐⭐</option>
                        <option value="1" {{ $t->rating == 1 ? 'selected' : '' }}>⭐</option>
                    </select>

                    <input type="file" name="photo" accept="image/*" required>

                    <div class="aksi-testimoni">
                        <button type="submit" class="btn-edit">💾 Save</button>
                        <button type="button" onclick="batalEdit({{ $t->id }})" class="btn-cancel">❌ Cancel</button>
                    </div>

                </form>
            </div>

        </div>

        @empty

        <div class="no-testimoni">
            <p>✨ No customer testimonials yet.</p>
            <p style="font-size:0.9rem;margin-top:5px;">Be the first to leave a testimonial!</p>
        </div>

        @endforelse

    </div>
</div>
</section>

@endsection

@push('scripts')

<script src="{{ asset('plugins/js/testimoni.js') }}"></script>

@endpush