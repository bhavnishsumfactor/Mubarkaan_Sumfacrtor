@extends('installer::layouts.master-update')

@section('title', trans('Finished'))
@section('container')
    <p class="paragraph text-center">{{ session('message')['message'] }}</p>
    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('Click here to exit') }}</a>
    </div>
@stop
