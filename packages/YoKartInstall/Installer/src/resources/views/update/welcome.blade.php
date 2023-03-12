@extends('installer::layouts.master-update')

@section('title', trans('Welcome To The Updater'))
@section('container')
    <p class="paragraph text-center">
    	{{ trans('Welcome to the update wizard.') }}
    </p>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::overview') }}" class="button">{{ trans('Next Step') }}</a>
    </div>
@stop
