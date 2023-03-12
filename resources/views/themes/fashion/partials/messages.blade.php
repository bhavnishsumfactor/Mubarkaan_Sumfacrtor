@if ($message = Session::get('success'))
<div class="alert alert-success">
  <p>{{ $message }}</p>
</div>
@endif

@if ($message = Session::get('error'))
<div class="alert alert-danger">
  <p>{{ $message }}</p>
</div>
@endif

@if ($errors->any())
     @foreach ($errors->all() as $error)
    <div class="alert alert-danger">
         <p>{{$error}}</p>
     </div>
     @endforeach
 @endif
