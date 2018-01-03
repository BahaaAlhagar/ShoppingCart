@extends('ShoppingCart/layouts/master')

@section('title')
    - Shopping Cart
@endsection

@section('content')
    @if(!isset($cart) || !$cart->totalPrice)
        <div class="text-center">
            <strong>Your Shopping Cart is empty!</strong>
        </div>
    @else
        <div>
            <ul class="list-group col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
                @foreach($cart->items as $product)
                    <li class="list-group-item">
                        <strong>{{ $product['item']['name'] }}</strong>
                        <span class="badge">{{ $product['qty'] }}</span>
                        <span class="label label-success">
                            ${{ $product['price'] }}
                        </span> 
                        <div class="btn-group">
                            <button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if($product['qty'] > 1)
                                    <li><a href="{{ route('product.reduceOneItem', $product['item']['id']) }}">Reduce 1 item</a></li>
                                    <li><a href="{{ route('product.RemovefromCart', $product['item']['id']) }}">Remove this items</a></li>
                                @else
                                    <li><a href="{{ route('product.RemovefromCart', $product['item']['id']) }}">Remove item</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <strong>Total price: {{ $cart->totalPrice }}</strong>
            <hr>
            <a href="{{ route('order.checkOut') }}" class="btn btn-success">Check out!</a>
        </div>
    @endif
    

@endsection