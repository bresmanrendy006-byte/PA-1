@extends('layouts.admin')

@section('title','History Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/adminCSS/kelolapemesanan.css') }}">
@endpush

@section('content')

<div class="order-container">

<h1 class="order-title">History Pembelian Customer</h1>

@if($orders->isEmpty())

<div class="empty-order">
Belum ada history pesanan.
</div>

@else

@foreach($orders as $order)

<div class="order-card">

{{-- HEADER PESANAN --}}
<div class="order-header">

<div class="order-id">
Pesanan #{{ $order->id }}
</div>

<div class="order-user">
Customer : {{ $order->user->username }}
</div>

<div class="order-date">
Tanggal : {{ $order->created_at->format('d M Y H:i') }}
</div>

<div class="order-status {{ $order->status }}">
Status : {{ $order->status_label }}
</div>

</div>


{{-- TABLE MENU --}}
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

<td class="menu-name">
{{ $item->menu->name }}
</td>

<td>
Rp {{ number_format($item->price,0,',','.') }}
</td>

<td>
{{ $item->quantity }}
</td>

<td>
Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>


{{-- TOTAL PESANAN --}}
<div class="order-footer">

<div class="order-total">
Total Pembayaran :
<strong>
Rp {{ number_format($order->total_price,0,',','.') }}
</strong>
</div>

</div>

</div>

@endforeach

@endif

</div>

@endsection