@extends('ShoppingCart/layouts/master')

@section('title')
    - Check out
@endsection


@section('content')
		<div class="col-md-offset-4 col-sm-offest-3 col-md-4 col-sm-6">
			<h1>Check out!</h1>
			<h4>Total Price: {{ $cart->totalPrice }}</h4>
			<form action="{{ route('order.payment') }}" method="post" id="payment-form">
				{{ csrf_field() }}
				<div class="row">
					<div class="col-xs-12">
						<div class="form-group">
							<label for="name">Name:</label>
							<input class="form-control" type="text" name="name" id="name" required>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="address">Address:</label>
							<input class="form-control" type="text" name="address" id="address" required>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="card-name">Card holder name:</label>
							<input class="form-control" type="text" name="card-name" id="card-name" required>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="card-number">Credit Card number:</label>
							<input class="form-control" type="text" name="card-number" id="card-number" required>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="card-expiry-month">Expiration Month:</label>
							<input class="form-control" type="text" name="card-expiry-month" id="card-expiry-month" required>
						</div>
					</div>
					<div class="col-xs-6">
						<div class="form-group">
							<label for="card-expiry-year">Expiration Year:</label>
							<input class="form-control" type="text" name="card-expiry-year" id="card-expiry-year" required>
						</div>
					</div>
					<div class="col-xs-12">
						<div class="form-group">
							<label for="card-cvc">CVC:</label>
							<input class="form-control" type="text" name="card-cvc" id="card-cvc" required>
						</div>
					</div>
				</div>
				<button type="submit" class="btn btn-success">Buy Now</button>
			</form>
		</div>
		@if(count($errors))
			<div class="alert-danger col-md-offset-4 col-sm-offest-3 col-md-4 col-sm-6">
				<ul>
					@foreach($errors->all() as $error)
						<li>{{ $error }}</li>
					@endforeach
				</ul>
			</div>
		@endif
@endsection