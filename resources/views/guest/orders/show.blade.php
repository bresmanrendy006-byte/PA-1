@extends('layouts.app')

@section('title','Detail Pesanan')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/order-detail.css') }}">
@endpush

@section('content')

<div class="order-container">

<h1 class="order-title">Detail Pesanan</h1>

@if(session('error'))
<div class="alert-error">
{{ session('error') }}
</div>
@endif

<div class="order-info">

<p><strong>ID Pesanan :</strong> #{{ $order->id }}</p>

<p>
<strong>Status :</strong>

@if($order->status == 'pending')
<span class="status pending">Menunggu Konfirmasi</span>

@elseif($order->status == 'confirmed')
<span class="status confirmed">Pesanan Dikonfirmasi</span>

@else
<span class="status rejected">Pesanan Ditolak</span>
@endif

</p>

</div>


<div class="order-table">

<table>

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

</div>


<div class="order-total">

<h3>
Total Pembayaran
<span>
Rp {{ number_format($order->total_price,0,',','.') }}
</span>
</h3>

</div>


<div class="order-actions">

</div>

</div>

@endsection