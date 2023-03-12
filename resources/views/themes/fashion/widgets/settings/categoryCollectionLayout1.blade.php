
@if($updateListing == 0)

<div class="yk-categoryCollectionLayout1-settings" data-comp="{{$cid}}">
  <div class="modal fade" id="settingsModal" tabindex="-1" data-yk=""  role="yk-dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" data-yk=""  role="yk-document">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="exampleModalLabel">{{__('LBL_CATEGORY_LAYOUT1')}}</h5>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="alert alert-secondary" data-yk=""  role="yk-alert">
            <div class="alert-icon"><i class="flaticon-questions-circular-button"></i></div>
            <div class="alert-text">{{__('LBL_CATEGORY_LAYOUT1_INSTRUCTIONS')}}</div>
          </div>
        <div class="product">
          <ul class="nav nav-tabs nav-tabs-line nav-tabs-line-2x" data-yk=""  role="yk-tablist">
          @php $categories = []; @endphp
            @if(!empty($data))
            @php $categories = $data->pluck('cat_name', 'cat_id')->toArray(); 
            $k = 0;
            @endphp
            @foreach($categories as  $item)
            <li class="nav-item" data-id="categorywidget-tab-{{$k+1}}"><a class="nav-link {{($k==0)?'active':''}}" data-toggle="tab" href="#categorywidget-tab-{{$k+1}}"><span>{{__("LBL_CATEGORY")}} {{$k+1}}</span>
              <i class="fa fa-remove ml-2 yk-removeCategory"></i></a></li>
              @php $k++; @endphp
            @endforeach
            @else
            <li class="nav-item" data-id="categorywidget-tab-1"><a class="nav-link active" data-toggle="tab" href="#categorywidget-tab-1"><span>{{__("LBL_CATEGORY")}} 1</span><i class="fa fa-remove ml-2 yk-removeCategory"></i></a></li>
            @endif
            <li class="nav-item" ><a  class="nav-link yk-addNewCategory" href="javascript:;"><i class="fa fa-plus ml-2"></i></a></li>
          </ul>

          <div class="tab-content">
            @if(!empty($data))
            @php $k = 0;@endphp
            @foreach($categories as $key =>$item)
            @php
            $products = [];
            @endphp
            <div class="tab-pane fade {{($k==0)?'show active':''}}" data-cat-id="{{$key}}" id="categorywidget-tab-{{$k+1}}">
              <div>
                <div class="form-group yk-categoryInputGroup" style="display:none;">
                  <div class="input-icon input-icon--left">
                    <input type="text" class="form-control yk-autocompleteCategories" placeholder="{{__('LBL_SEARCH_CATEGORY')}}..." name="cat_name" autocomplete="off">        
                    <span class="input-icon__icon input-icon__icon--left" ><span><i class="la la-search"></i></span></span>
                  </div>
                </div>

                <span class="yk-linkedCategory">{{__('LBL_CATEGORY_LINKED')}}: {{$item}}<i class="fa fa-remove yk-unlinkCategory"></i></span>
              </div>
              <div class="yk-linkedProducts">
                <div class="form-group">
                  <div class="input-icon input-icon--left">
                    <input type="text" class="form-control yk-autocompleteProducts" placeholder="{{__('LBL_SEARCH_PRODUCTS')}}..." name="prod_name" autocomplete="off">        
                    <span class="input-icon__icon input-icon__icon--left" ><span><i class="la la-search"></i></span></span>
                  </div>
                </div>
                @php
                 
                  $selectedProducts = $data->where('cat_id', $key)->sortBy('collection_display_order');
               
                @endphp
                <ul class="list-group mt-4 yk-selectedProducts yk-sortable" data-highest-order="{{$selectedProducts->max('collection_display_order')}}">
               
                  @foreach($selectedProducts as $product)
                  @php
                  $products[] = $product->prod_id;
                  @endphp
                  <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{$product->prod_id}}" data-display-order="{{$product->collection_display_order ?? ''}}"> 
                    <div class="d-flex  align-items-center">
                        <i class="icon fa fa-arrows-alt handle mr-3"></i>  
                        <span>{{$product->prod_name}}</span>
                    </div>                 
                  <div class="actions">
                      <button type="button" class="btn btn-icon yk-removeCategoryProduct">
                          <svg>   
                          <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
                          </svg>
                      </button>
                  </div>
                  </li>
                  @endforeach
                </ul>
                @php
                $products = implode(',', $products);
                @endphp
                <input type="hidden" value="{{$products}}" name="selected_products"/>
              </div>
            </div>
            @php $k++;@endphp
            @endforeach
            @endif
          </div>
        </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-brand gb-btn gb-btn-primary yk-addcategoryCollectionLayout1Widget">{{__('BTN_EMBED')}}</button>
        </div>
      </div>
    </div>
  </div>
</div>
@else
  @if(!empty($data['products']))
    @foreach($data['products'] as $product)
      <li class="list-group-item d-flex justify-content-between align-items-center" data-id="{{$product->prod_id}}" data-display-order="{{$data['recordIds'][$product->prod_id]}}">
        <div class="d-flex  align-items-center">
            <i class="icon fa fa-arrows-alt handle mr-3"></i>  
            <span>{{$product->prod_name}}</span>
        </div>
        <div class="actions">
          <button type="button" class="btn btn-icon yk-removeCategoryProduct">
              <svg>   
              <use xlink:href="{{asset('admin/images/retina/sprite.svg')}}#delete-icon" ></use>                               
              </svg>
          </button>
      </div>
      </li>
    @endforeach
  @endif
@endif