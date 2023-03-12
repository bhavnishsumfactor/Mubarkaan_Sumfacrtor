<div class="yk-customMap" compid="{{ $cid ?? '' }}">
  <div class="map-wrapper">
      <div class="iframe-wrapper yk-container" compid="{{ $cid ?? '' }}">
        @if(!empty($collections['map_script']))
          {!! $collections['map_script'] !!}
        @else
          <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d54900.518350452825!2d76.7189297016791!3d30.68232965993897!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x390feef5b90fc51b%3A0x7541e61fcad7e6c4!2sAbly%20Soft%20Pvt.%20Ltd.!5e0!3m2!1sen!2sin!4v1602487558902!5m2!1sen!2sin" frameborder="0" style="border:0;" allowfullscreen="" aria-hidden="false" tabindex="0">
          </iframe>
        @endif       
      </div>
  </div>
</div>