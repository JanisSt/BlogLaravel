
@extends('layouts.app')

@section('content')
    <div class="container">
        <div class="justify-content-center">
            <h1>{{ $product->name }}</h1>
            <p>
                Price: <b>{{ $product->getPrice() }}</b>
                Size: <b>{{ $product->getSize() }}</b>
                Weight: <b>{{ $product->getWeight() }}</b>
            </p>
            <ul>
                @foreach($product->deliveries as $delivery)
                    <li>{{ $delivery->name }} {{ $delivery->getPrice() }}</li>
                @endforeach
            </ul>
        </div>
    </div>
@endsection
