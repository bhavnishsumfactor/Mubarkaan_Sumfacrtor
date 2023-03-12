@extends('layouts.full')
@section('content')
<main role="main" id="main">
    <div class="device-preview">
        <div class="device-preview__container {{$deviceClass}}">
            <div class="device-preview__content">
                <iframe class="device-preview__iframe"
                    src="{{url('/')}}"
                    scrolling="yes" frameborder="0"
                    width="{{$width}}"
                    height="{{$height}}">
                </iframe>
            </div>
        </div>
    </div>
</main>

@endsection