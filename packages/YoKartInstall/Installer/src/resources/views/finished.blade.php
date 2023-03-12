@extends('installer::layouts.master')

@section('template_title')
    {{ trans('Installation Finished') }}
@endsection

@section('title')
    <i class="fa fa-flag-checkered fa-fw" aria-hidden="true"></i>
    {{ trans('Installation Finished') }}
@endsection

@section('container')

	@if(session('message')['dbOutputLog'])
		<p><strong><small>{{ trans('Migration and Seed Console Output:') }}</small></strong></p>
		<pre><code>{{ session('message')['dbOutputLog'] }}</code></pre>
	@endif

	<p><strong><small>{{ trans('Application Console Output:') }}</small></strong></p>
	<pre><code>{{ $finalMessages }}</code></pre>

	<p><strong><small>{{ trans('Installation Log Entry:') }}</small></strong></p>
	<pre><code>{{ $finalStatusMessage }}</code></pre>

	{{--<p><strong><small>{{ trans('Final .env File:') }}</small></strong></p>
	<pre><code>{{ $finalEnvFile }}</code></pre>--}}

    <div class="buttons">
        <a href="{{ url('/') }}" class="button">{{ trans('Click here to exit') }}</a>
    </div>

@endsection
