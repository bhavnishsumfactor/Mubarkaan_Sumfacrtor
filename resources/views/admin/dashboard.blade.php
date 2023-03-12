@extends('layouts.admin.loggedin')

@section('content')
	<router-view name="dashboard"></router-view>
    <router-view></router-view>
@endsection
