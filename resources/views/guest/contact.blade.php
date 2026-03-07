@extends('layouts.app')

@section('title', 'Kedanku Coffee - Contact')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/contact.css') }}">
@endpush

@section('content')

<section class="contact-hero">
    <div class="contact-hero-content">
        <h1>Hubungi Kami</h1>
        <p>Kami siap melayani & menerima masukan dari Anda</p>
    </div>
</section>

<section class="contact-section">
    <div class="contact-container">

        <div class="contact-top">
            <div class="contact-left">
                <h2>Informasi Kontak</h2>
                <p><i class="fas fa-map-marker-alt"></i> Balige, Sumatera Utara</p>
                <p><i class="fas fa-phone"></i> +62 812-3456-7890</p>
                <p><i class="fas fa-envelope"></i> info@kedankucoffee.com</p>
            </div>

            <div class="contact-right">
                <form>
                    <input type="text" placeholder="Nama Anda" required>
                    <input type="email" placeholder="Email" required>
                    <textarea rows="5" placeholder="Pesan Anda" required></textarea>
                    <button type="submit">Kirim Pesan</button>
                </form>
            </div>
        </div>

<div class="contact-map">
    <h2>Lokasi Kami</h2>
    <iframe 
        src="https://www.google.com/maps?q=2.334172,99.068251&hl=id&z=17&output=embed"
        width="100%"
        height="400"
        style="border:0;"
        loading="lazy">
    </iframe>
</div>

    </div>
</section>

@endsection