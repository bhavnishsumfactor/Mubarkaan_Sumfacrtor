@extends('installer::layouts.master-update')

@section('title', trans('Welcome To The Updater'))
@section('container')
    <p class="paragraph text-center">{{ trans_choice('There is 1 update.|There are :number updates.', $numberOfUpdatesPending, ['number' => $numberOfUpdatesPending]) }}</p>
    <div class="buttons">
        <a href="{{ route('LaravelUpdater::database') }}" class="button">{{ trans('Install Updates') }}</a>
    </div>
@stop
