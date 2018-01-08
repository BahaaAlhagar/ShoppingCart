@extends('Shop/layouts/master')

@section('title')
    - Shop Page
@endsection

@section('content')

    @if(Session::has('success'))
        <div class="alert alert-success col-xs-12">
            {{ session::get('success') }}
        </div>
    @endif
    
    @foreach($products as $product)
        <div class="col-xs-6 col-md-3 text-center">
            <div class="thumbnail clearfix">
                <img alt="{{ $product->name }}" src="{{ Storage::url($product->imagePath) }}">
                <div class="caption">
                    <h3>{{ $product->name }}</h3>
                </div>
                <div>
                    <p>{{ str_limit($product->description, 30) }}</p>
                    <span class="pull-right">
                        <a href="{{ route('product.addToCart', $product) }}"><button class="btn btn-success">Add to Cart</button></a>
                    </span>
                    <span class="pull-left">
                        <b>${{ $product->price }}</b>
                    </span>
                </div>
            </div>
        </div>
    @endforeach

    <div class="col-xs-12 text-center">
    {{ $products->links() }}
    </div>

@endsection