@extends('installer::layouts.master')

@section('template_title')
    {{ trans('Welcome') }}
@endsection

@section('title')
    {{ trans('Tribe Installer') }}
@endsection

@section('container')
    <p class="text-center">
      {{ trans('Easy Installation and Setup Wizard.') }}
    </p>
    <p class="text-center">
      <a href="{{ route('Installer::requirements') }}" class="button">
        {{ trans('Check Requirements') }}
        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
      </a>
    </p>
@endsection
