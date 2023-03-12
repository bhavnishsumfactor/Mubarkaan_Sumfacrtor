@if($updateListing == 0)
<div class="yk-productCollectionLayout1-settings" data-comp="{{$cid}}">
    <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_PRODUCT_LAYOUT1')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-secondary" data-yk=""  role="yk-alert">
                        <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
                        <div class="alert-text">{{__('LBL_PRODUCT_LAYOUT1_INSTRUCTIONS')}}</div>
                    </div>
                    <div class="form-group">
                        <div class="input-icon input-icon--left">
                            <input type="text" class="form-control yk-autocompleteProductCollectionLayout1" placeholder="{{__('LBL_SEARCH_PRODUCTS')}}..." name="prod_name" autocomplete="off">
                            <span class="input-icon__icon input-icon__icon--left"><span><i class="la la-search"></i></span></span>
                        </div>
                    </div>
                    @php
                        $highestOrder = 0;
                        if (!empty($data) && count($data)>0) {
                            $lastElement = last($data)['value'];
                            $highestOrder = $recordIds[$lastElement];
                        }
                    @endphp
                    <div class="scroll-y yk-perfectScrollbar">
                    <ul class="list-group mt-4 yk-selectedProductCollectionLayout1 yk-sortable" data-highest-order="{{$highestOrder}}">
                        @if(!empty($data))
                        @foreach($data as $item)
                        <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{$item['value']}}" data-display-order="{{$recordIds[$item['value']]}}">
                            <div class="d-flex  align-items-center">
                                <i class="icon fa fa-arrows-alt handle mr-3"></i>  
                                <span>{{$item['label']}}</span>
                            </div>
                        <div class="actions">
                            <button type="button" class="btn btn-icon yk-removeProductCollectionLayout1">
                                <svg>   
                                <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
                                </svg>
                            </button>
                        </div>
                        </li>
                        @endforeach
                        @endif
                    </ul>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addProductCollectionLayout1Widget">{{__('BTN_EMBED')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
  if (!$('.yk-perfectScrollbar').hasClass('ps')) {
    new PerfectScrollbar('.yk-perfectScrollbar');
  }
</script>   
@else
  @if(!empty($data))
    @foreach($data as $item)
      <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{$item['value']}}" data-display-order="{{$recordIds[$item['value']]}}">
        <div class="d-flex  align-items-center">
          <i class="icon fa fa-arrows-alt handle mr-3"></i>  
          <span>{{$item['label']}}</span>
        </div>
        <div class="actions">
            <button type="button" class="btn btn-icon yk-removeProductCollectionLayout1">
                <svg>   
                <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
                </svg>
            </button>
        </div>
      </li>
    @endforeach
  @endif
@endif