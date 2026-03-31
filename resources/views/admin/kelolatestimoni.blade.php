@extends('layouts.admin')

@section('title','Kelola Testimoni')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolatestimoni.css') }}">
@endpush

@section('content')

<div class="admin-container">

<h2>Kelola Testimoni</h2>

@if(session('success'))
<p class="success-msg">{{ session('success') }}</p>
@endif

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

@foreach($testimonis as $t)

<tr>

<td>{{ $loop->iteration }}</td>

<td>{{ $t->user->name }}</td>

<td>
@if($t->foto)
<img src="{{ asset('storage/'.$t->foto) }}" width="80">
@endif
</td>

<td>{{ $t->isi }}</td>

<td>{{ $t->rating }} ⭐</td>

<td>

@if(auth()->user()->role == 'owner')

<form action="{{ route('admin.testimoni.delete',$t->id) }}" method="POST">

@csrf
@method('DELETE')

<button class="btn-delete">
Hapus
</button>

</form>

@else

<span>-</span>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection