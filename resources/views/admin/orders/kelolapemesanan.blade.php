@extends('layouts.admin')

@section('title','Kelola Pemesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolapemesanan.css') }}">
@endpush

@section('content')

<div class="order-container">

<h1 class="order-title">Kelola Pemesanan Customer</h1>

@if(session('success'))
<div class="alert-success">
{{ session('success') }}
</div>
@endif

@if($orders->isEmpty())

<div class="empty-order">
Belum ada pesanan masuk.
</div>

@else

@foreach($orders as $order)

<div class="order-card">

<div class="order-header">

<div>Pesanan #{{ $order->id }}</div>

<div>Customer : {{ $order->user->username }}</div>

<div>Tanggal : {{ $order->created_at->format('d M Y H:i') }}</div>

<div class="status pending">
Status : Menunggu Konfirmasi
</div>

</div>


<table class="order-table">

<thead>
<tr>
<th>Menu</th>
<th>Harga</th>
<th>Jumlah</th>
<th>Subtotal</th>
</tr>
</thead>

<tbody>

@foreach($order->items as $item)

<tr>

<td>{{ $item->menu->name }}</td>

<td>Rp {{ number_format($item->price,0,',','.') }}</td>

<td>{{ $item->quantity }}</td>

<td>
Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>


<div class="order-footer">

<div class="order-total">

Total :
<strong>
Rp {{ number_format($order->total_price,0,',','.') }}
</strong>

</div>


<div class="order-actions">

<form action="{{ route('admin.orders.confirm',$order->id) }}" method="POST">
@csrf
<button class="btn-confirm">
Konfirmasi
</button>
</form>

<form action="{{ route('admin.orders.reject',$order->id) }}" method="POST">
@csrf
<button class="btn-reject">
Tolak
</button>
</form>

</div>

</div>

</div>

@endforeach

@endif

</div>

@endsection