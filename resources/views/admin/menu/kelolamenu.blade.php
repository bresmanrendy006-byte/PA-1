@extends('layouts.admin')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolamenu.css') }}">
@endpush

@section('content')

<div class="admin-menu-container">

<div class="menu-header">

<h2>Kelola Menu</h2>

<a href="{{ route('admin.menu.create') }}" class="btn-add">
+ Tambah Menu
</a>

</div>

@if(session('success'))
<p class="success-msg">{{ session('success') }}</p>
@endif

<div class="table-wrapper">

<table class="menu-table">

<thead>
<tr>
<th>Gambar</th>
<th>Nama Menu</th>
<th>Harga</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

@forelse($menus as $menu)

<tr>

<td>

@if($menu->image)

<img src="{{ asset('storage/'.$menu->image) }}" class="menu-img">

@else

<span class="no-img">Tidak ada</span>

@endif

</td>

<td class="menu-name">
{{ $menu->name }}
</td>

<td class="menu-price">
Rp {{ number_format($menu->price,0,',','.') }}
</td>

<td class="menu-actions">

<a href="{{ route('admin.menu.edit',$menu->id) }}" class="btn-edit">
Edit
</a>

<form action="{{ route('admin.menu.delete',$menu->id) }}" method="POST" class="delete-form">

@csrf
@method('DELETE')

<button type="submit" class="btn-delete">
Hapus
</button>

</form>

</td>

</tr>

@empty

<tr>

<td colspan="4" class="empty-text">
Belum ada menu ditambahkan
</td>

</tr>

@endforelse

</tbody>

</table>

</div>

</div>

@endsection