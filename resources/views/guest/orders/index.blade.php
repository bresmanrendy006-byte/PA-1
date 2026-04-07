@extends('layouts.app')

@section('title','Pesanan Saya')

@push('styles')
<link rel="stylesheet" href="{{ asset('plugins/css/orders.css') }}">
@endpush

@section('content')

<div class="order-container">

<h1 class="order-title">Pesanan Saya</h1>

@if($orders->isEmpty())

<div class="empty">
Belum ada pesanan.
</div>

@else

@foreach($orders as $order)

<div class="order-card">

<div class="order-info">

<div class="order-id">
Pesanan #{{ $order->id }}
</div>

<div>
Status :
<span class="order-status {{ $order->status }}">
{{ ucfirst($order->status) }}
</span>
</div>

<div class="order-total">
Total : {{ $order->formatted_total }}
</div>

</div>

<a href="{{ route('orders.show',$order->id) }}" class="order-btn">
Lihat Detail
</a>

</div>

@endforeach

@endif

</div>

@endsection