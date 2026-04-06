@extends('layouts.admin')

@section('title','Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/admin-order.css') }}">
@endpush

@section('content')

<div class="order-detail-container">

<h1 class="order-title">
Detail Pesanan #{{ $order->id }}
</h1>

<div class="order-box">

<div class="order-row">
<span>Customer</span>
<span>{{ $order->user->username }}</span>
</div>

<div class="order-row">
<span>Status</span>
<span class="status {{ $order->status }}">
{{ ucfirst($order->status) }}
</span>
</div>

<div class="order-row">
<span>Total</span>
<span>{{ $order->formatted_total }}</span>
</div>

</div>


<h2 class="menu-title">Daftar Pesanan</h2>

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

<td>
Rp {{ number_format($item->price,0,',','.') }}
</td>

<td>{{ $item->quantity }}</td>

<td>
Rp {{ number_format($item->price * $item->quantity,0,',','.') }}
</td>

</tr>

@endforeach

</tbody>

</table>

</div>

@endsection