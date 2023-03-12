@section('title', __('Access Denied'))
<link href="{{ asset('yokart/'.config('theme').'/css/main-ltr.css') }}" id="YK-mainCss" rel="stylesheet" type="text/css">


<section class="section">
    <div class="container">
        <div class="row  justify-content-center">
            <div class="col-md-12">
                <div class="access-denied">
                    <img src="{{url('yokart/'.config('theme'))}}/media/retina/403.svg" data-yk="" alt="">
                    <h3>OPPSSS!!!! Sorry...</h3>
                    <p>Sorry, your access is refused due to security reasons of our server and also our sensitive data.
                                Please go back to the previous page to continue browsing.</p>
                    <div class="action"> <a class="btn btn-outline-primary btn-wide" href="">Go Back</a></div>
                </div>

            </div>

        </div>
    </div>
</section>