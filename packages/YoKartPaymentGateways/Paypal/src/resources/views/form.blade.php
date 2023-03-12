<li class="toggle">
        <a href="javascript:;" alt="{{$slug}}" class="toggle-title">
        <div class="payment-box">
            <i class="payment-icn">
                <img src="{{ asset(config('theme'))}}/media/payment-method/paypal.jpg" alt="">
            </i>
            <span>{{__('Paypal')}}</span>
        </div>
    </a>

    <div class="payment-from p-5 toggle-inner">
		
		<div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" name="{{$slug}}[name]" class="form-control" placeholder="First Name on card">
                </div>
            </div>
        </div>
		
        <div class="row">
            <div class="col-md-12">
                <div class="form-group">
                    <input type="text" class="form-control" name="{{$slug}}[number]" placeholder="Card number">
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="{{$slug}}[expiryMonth]" placeholder="Month (MM)">
                </div>
            </div>
            <div class="col-md-3">
                <div class="form-group">
                    <input type="text" class="form-control" name="{{$slug}}[expiryYear]" placeholder="Year (YYYY)">
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <input type="text" class="form-control" name="{{$slug}}[cvv]" placeholder="CVV">
                </div>
            </div>

        </div>
    </div>
</li>
