@if($updateListing == 0)
<div class="yk-navigationLayout1-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
      <div class="modal-dialog modal-lg modal-dialog-centered" data-yk=""  role="yk-document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_NAVIGATION_MENU')}}</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body" style="max-height: 400px;overflow-y: auto;">
          <div class="form-group row">
            <label class="col-md-4 col-form-label" for="validationCustom01">{{__('LBL_WELCOME_TEXT')}}</label>
            <div class="col-md-8">
                <input type="text" class="form-control" name="welcome_text" value="{{$welcome_text ?? __('LBL_WELCOME_TO_THE_STORE')}}" required>
            </div>
          </div>
            <form id="yk-formNavbar">
            @php
                  $highestOrder = 0;
                  if (!empty($data) && count($data)>0) {
                    $lastElement = last($data->toArray());
                    $highestOrder = $lastElement['navmenu_display_order']; 
                  }
                @endphp
                <div class="accordion yk-menuItems yk-sortable nav-popup-wrapper" id="accordionNavBar" data-highest-order="{{$highestOrder}}">
                  @if(!empty($data))
                    @foreach($data as $item)
                      @php
                        $menuNumber = $item->navmenu_id;
                      @endphp
                      @include('themes.'.config('theme').'.widgets.settings.partials.navigationLayout1', ['item'=>$item, 'types'=>$types, 'menuNumber'=>$menuNumber])
                    @endforeach
                  @else
                  @include('themes.'.config('theme').'.widgets.settings.partials.navigationLayout1', ['item'=>null, 'types'=>$types, 'menuNumber'=>1])
                  @endif
                </div>
            </form>
          </div>
          <div class="modal-footer">
            <button  type="button" class="yk-addMenu btn btn-secondary mr-auto">{{__('BTN_ADD_MENU')}}</button>
            <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addNavigationLayout1Widget">{{__('BTN_EMBED')}}</button>
          </div>
      </div>
    </div>
  </div>
</div>
@else
  @if(!empty($data))
    @foreach($data as $item)
      @php
        $menuNumber = $item->navmenu_id;
      @endphp
      @include('themes.'.config('theme').'.widgets.settings.partials.navigationLayout1', ['item'=>$item, 'types'=>$types, 'menuNumber'=>$menuNumber])
    @endforeach
  @endif
@endif