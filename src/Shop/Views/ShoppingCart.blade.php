@extends('Shop/layouts/master')

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
                @foreach($cart->items as $index => $product)
                    <li class="list-group-item" style="padding: 15px;">
                        <strong>{{ $product['item']['name'] }}</strong>
                        <span class="badge">{{ $product['qty'] }}</span>
                        <span class="pull-right">
                            <form action="{{ route('product.modify', $index) }}" method="post">
                                {{ csrf_field() }}
                                {{ method_field('PATCH') }}
                                <span class="form-group input-group">
                                        <input type="text" name="qty" class="form-control" value="{{ $product['qty'] }}">
                                        <span class="input-group-btn">
                                          <button class="btn btn-success" type="submit">
                                              <i class="fa fa-pencil-square-o"></i>
                                          </button>
                                        </span>
                                </span>
                            </form>
                        </span>
                        <span class="label label-success">
                            ${{ $product['price'] }}
                        </span> 
                        <div class="btn-group">
                            <button class="btn btn-xs btn-primary dropdown-toggle" data-toggle="dropdown">
                                Action <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                @if($product['qty'] > 1)
                                    <li><a href="{{ route('product.reduceOneItem', $index) }}">Reduce 1 item</a></li>
                                    <li><a href="{{ route('product.RemovefromCart', $index) }}">Remove this items</a></li>
                                @else
                                    <li><a href="{{ route('product.RemovefromCart', $index) }}">Remove item</a></li>
                                @endif
                            </ul>
                        </div>
                    </li>
                @endforeach
            </ul>
        </div>
        @if(count($errors))
            <div class="alert-danger col-md-offset-4 col-sm-offest-3 col-md-4 col-sm-6">
                <ul>
                    @foreach($errors->all() as $error)
                        <li><strong>{{ $error }}</strong></li>
                    @endforeach
                </ul>
            </div>
        @endif
        <div class="col-sm-6 col-md-6 col-sm-offset-3 col-md-offset-3">
            <strong>Total price: {{ $cart->totalPrice }}</strong>
            <hr>
            <a href="{{ route('order.checkOut') }}" class="btn btn-success">Check out!</a>
        </div>
    @endif
    

@endsection