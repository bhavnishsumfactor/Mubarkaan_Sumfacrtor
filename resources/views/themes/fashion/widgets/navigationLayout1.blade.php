<div class="container">
    <div class="navigation-wrapper yk-container" compid="{{ $cid ?? '' }}">
      <navmenu-shortcode compid="{{ $cid ?? '' }}"> 
        @include('themes.'.config('theme').'.widgets.partials.navigationLayout1', ['collections' => ($collections ?? [])])
      </navmenu-shortcode> 
    </div>
</div>
