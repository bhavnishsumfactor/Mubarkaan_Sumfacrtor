<div class="card" data-id="{{$item->navmenu_id ?? ''}}" data-display-order="@php if(!empty($item->navmenu_display_order)){ echo $item->navmenu_display_order; } elseif(!empty($displayOrder)){ echo $displayOrder; }else {} @endphp">
  <div class="card-header d-flex justify-content-between align-items-center yk-cardHeading collapsed" id="heading-{{$menuNumber}}"  data-toggle="collapse" data-target="#collapse-{{$menuNumber}}" aria-expanded="true" aria-controls="collapse-{{$menuNumber}}">
    <h6 class="mb-0"><i class="icon fa fa-arrows-alt handle"></i> <span class="yk-menuHeading">{{$item->navmenu_label ?? 'Menu'}}</span> </h6>    
    <button type="button" class="btn btn-sm btn-icon"><i class="fa fa-trash yk-removeMenu"></i></button>
  </div>
  <div id="collapse-{{$menuNumber}}" class="collapse" aria-labelledby="heading-{{$menuNumber}}" data-parent="#accordionNavBar">
    <div class="card-body yk-cardBody">        
        <div class="form-group row">       
          <label class="col-md-3 col-form-label">{{__("LBL_TYPE")}}</label>
          <div class="col-md-9">
            <select class="custom-select my-1 mr-sm-2 required" name="type">
              <option value='' readonly disabled @if(empty($item->navmenu_type)) selected @endif>{{__("LBL_CHOOSE")}}...</option>
              @if(!empty($types))
                @foreach($types as $typeId=>$typeName)
                  <option value="{{$typeId}}" @if(!empty($item->navmenu_type) && $item->navmenu_type == $typeId) selected @endif >{{ucwords($typeName)}}</option>
                @endforeach
              @endif
            </select>          
          </div>
        </div>
        
        <input type="hidden" name="selected_category_id" @if(!empty($item->navmenu_type) && $item->navmenu_type=='1') val="@php echo $item->navmenu_record_id; @endphp" @endif>        
        <input type="hidden" name="selected_page_id" @if(!empty($item->navmenu_type) && $item->navmenu_type=='2') val="@php echo $item->navmenu_record_id; @endphp" @endif>        

        <div class="yk-categoriesList" @if(!empty($item->navmenu_type) && $item->navmenu_type == '1') style="display:block;" @else style="display:none;" @endif>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">{{__('LBL_CATEGORY')}}</label>
            <div class="col-md-9 combotree-wrapper">
              <input type="text" placeholder="{{__('LBL_SELECT')}}" name="category_id" class="form-control required yk-getCategories" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="yk-pagesList" @if(!empty($item->navmenu_type) && $item->navmenu_type == '2') style="display:block;" @else style="display:none;" @endif>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">{{__('LBL_PAGE')}}</label>
            <div class="col-md-9 combotree-wrapper">
              <input type="text" placeholder="{{__('LBL_SELECT')}}" name="page_id" class="form-control required yk-getPages" autocomplete="off">
            </div>
          </div>
        </div>
        <div class="yk-menuLabel"> 
          <div class="form-group row">
            <label class="col-md-3 col-form-label">{{__('LBL_LABEL')}}</label>
            <div class="col-md-9">
              <input type="text" class="form-control required" name="label" value="{{$item->navmenu_label ?? ''}}">
            </div>
          </div>
        </div>
        <div class="yk-menuUrl" @if(!empty($item->navmenu_type) && $item->navmenu_type == '3') style="display:block;" @else style="display:none;" @endif>
          <div class="form-group row">
            <label class="col-md-3 col-form-label">{{__('LBL_URL')}}</label>
            <div class="col-md-9">
              <input type="text" class="form-control required" name="url" value="{{$item->navmenu_url ?? ''}}">
            </div>
          </div> 
          <div class="form-group row">
            <label class="col-md-3 col-form-label">{{__('LBL_TARGET')}}</label>
            <div class="col-md-9">
              <select class="custom-select my-1 mr-sm-2 required" name="target">
                <option value="_self" @if(empty($item->navmenu_target)) selected @endif @if(!empty($item->navmenu_target) && $item->navmenu_target == "_self") selected @endif >{{__('LBL_SAME_TAB')}}</option>
                <option value="_blank" @if(!empty($item->navmenu_target) && $item->navmenu_target == "_blank") selected @endif >{{__('LBL_NEW_TAB')}}</option>
              </select>
            </div>
          </div>
        </div>
    </div>
  </div>
</div>