@if (session('success'))
<div class="alert alert-success" data-yk=""  role="yk-alert">
     <div class="alert-text">{{ session('success') }}</div>    
</div>
@endif
@if (session('error'))
<div class="alert alert-danger" data-yk=""  role="yk-alert">
    <div class="alert-text">{{ session('error') }}</div>
</div>
@endif