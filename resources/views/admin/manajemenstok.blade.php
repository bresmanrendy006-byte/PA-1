@extends('layouts.admin')

@section('title','Manajemen Stok')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/manajemenstok.css') }}">
@endpush

@section('content')

<div class="stock-container">

<h1 class="stock-title">Manajemen Stok Bahan Baku</h1>

@if(session('success'))
<div class="alert-success">
{{ session('success') }}
</div>
@endif


<div class="stock-form">

<h3>Tambah Bahan Baru</h3>

<form action="{{ route('admin.stock.store') }}" method="POST">
@csrf

<input type="text" name="name" placeholder="Nama bahan" required>

<input type="number" name="quantity" placeholder="Jumlah stok" required>

<input type="text" name="unit" placeholder="Satuan (kg/liter/pcs)" required>

<button type="submit" class="btn-add">Tambah</button>

</form>

</div>


<h3>Daftar Stok</h3>

<table class="stock-table">

<thead>
<tr>
<th>No</th>
<th>Nama</th>
<th>Jumlah</th>
<th>Satuan</th>
<th>Aksi</th>
</tr>
</thead>

<tbody>

@foreach($stocks as $stock)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $stock->name }}</td>

<td>{{ $stock->quantity }}</td>

<td>{{ $stock->unit }}</td>

<td class="stock-action">

<form action="{{ route('admin.stock.update',$stock->id) }}" method="POST">
@csrf
@method('PUT')

<input type="hidden" name="name" value="{{ $stock->name }}">
<input type="hidden" name="unit" value="{{ $stock->unit }}">

<input type="number" name="quantity" value="{{ $stock->quantity }}">

<button class="btn-edit">Update</button>

</form>


<form action="{{ route('admin.stock.delete',$stock->id) }}" method="POST">
@csrf
@method('DELETE')

<button class="btn-delete">Hapus</button>

</form>

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection