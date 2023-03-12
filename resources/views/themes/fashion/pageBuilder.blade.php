@php
header('Access-Control-Allow-Origin: *');

header('Access-Control-Allow-Methods: GET, POST');

header("Access-Control-Allow-Headers: X-Requested-With");

$configurations = getConfigValues([
    'SYSTEM_MESSAGE_STATUS', 'SYSTEM_MESSAGE_CLOSE_TIME', 'SYSTEM_MESSAGE_POSITION', 
    'FRONTEND_FONT_FAMILY',
    'PRIMARY_COLOR',
    'PRIMARY_COLOR_INVERSE', 'BUSINESS_NAME'
]);
config(['toastr.options.positionClass' => $configurations['SYSTEM_MESSAGE_POSITION']]);
if ($configurations['SYSTEM_MESSAGE_STATUS']=='1') {
    config(['toastr.options.timeOut' => $configurations['SYSTEM_MESSAGE_CLOSE_TIME'] * 1000]);
}
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light" dir="ltr">
<!-- begin::Head -->

<head>
    <meta charset="utf-8" />
    <title>{{ $configurations['BUSINESS_NAME'] ?? '' }}</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <meta name="csrf-token" content="{{ csrf_token() }}">
    @if(Request::isSecure())
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">
    @endif

    <link rel="shortcut icon" href="{{ getFavicon() }}" /> 
    <link rel="stylesheet" href="{{ asset('admin/vendors/grapesjs/dist/css/grapes.min.css') }}">
    <link rel="stylesheet" href="{{ asset('admin/vendors/jqueryUi/jquery-ui.css') }}">
    <script src="{{ asset('admin/vendors/jquery.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/bootstrap.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapes.min.js') }}" ></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapesjs-preset-webpage.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapesjs-custom-code.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapesjs-touch.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapesjs-parser-postcss.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/grapesjs/dist/grapesjs-tooltip.min.js') }}"></script>
    <script src="{{ asset('admin/vendors/jqueryUi/jquery-ui.js') }}"></script>
    <link  href="{{ asset('admin/vendors/grapesjs.css') }}" rel="stylesheet">
    <link  href="{{ asset('vendors/css/cropper.css') }}" rel="stylesheet">
    <script src="{{ asset('vendors/js/cropper.js') }}"></script>
    <script src="{{ asset('vendors/js/jquery-cropper.min.js') }}"></script>    
    <script src="{{asset('admin/vendors/comboTreePlugin/comboTreePlugin.js')}}"></script>
    <link href="{{ asset('admin/css/main-ltr.css') }}" rel="stylesheet">
    <link href="{{ asset('cmsEditor/css/main-ltr.css') }}" rel="stylesheet">
    <link href="{{ asset('vendors/css/toastr.min.css') }}" rel="stylesheet">
    <script src="{{ asset('vendors/js/toastr.min.js') }}"></script>
    <link href="{{ asset('admin/vendors/comboTreePlugin/comboTreePlugin.css') }}" rel="stylesheet">
    <link href="{{ asset('admin/vendors/custom.css') }}" rel="stylesheet">
    @toastr_render
    <script>
        let baseUrl = "{{url('/')}}";
        let widgetsJs = "{{url('yokart/'.config('theme').'/js/collections') }}";
        let adminBaseUrl = "{{url('/admin')}}";
        let spriteUrl = "{{asset('admin/images/retina/sprite.svg')}}";
        let pagesUrl = "{{url('/admin#/pages')}}";
        let embedSuccessMessage = "{{__('NOTI_EMBED_SUCCESSFUL')}}";
        let imageSizeValidationMessage = "{{__('LBL_IMAGE_SIZE_VALIDATION')}}";
        let atleastXProductsMustBeSelectedMessage = "{{__('NOTI_ATLEAST_X_PRODUCTS_MUST_BE_SELECTED')}}";
        let atmostXProductsCanBeAddedPerCategoryMessage = "{{__('NOTI_X_PRODUCTS_CAN_BE_ADDED_PER_CATEGORY')}}";
        let exactlyXProductsNeedToBeSelectedMessage = "{{__('NOTI_EXACTLY_X_PRODUCTS_NEED_TO_BE_SELECTED')}}";
        let atmostXProductsCanBeAddedMessage = "{{__('NOTI_ATMOST_X_PRODUCTS_CAN_BE_ADDED')}}";
        let atmostXCategoryCanBeSelectedMessage = "{{__('NOTI_ATMOST_X_CATEGORIES_CAN_BE_SELECTED')}}";
        let pleaseFillAllFieldsMessage = "{{__('NOTI_PLEASE_FILL_ALL_FIELDS')}}";
        let pleaseEnterValidUrlMessage = "{{__('NOTI_PLEASE_ENTER_VALID_URL')}}";
        let xBlogPostsCanBeAddedMessage = "{{__('NOTI_X_BLOGPOSTS_CAN_BE_ADDED')}}";
        let oneOrTwoBlogPostsCanBeAddedMessage = "{{__('NOTI_1_2_BLOGPOSTS_CAN_BE_ADDED')}}";
        let atmostXBlogPostsCanBeAddedMessage = "{{__('NOTI_ATMOST_3_BLOGPOSTS_CAN_BE_ADDED')}}";
        let atmost2BlogPostsCanBeAddedMessage = "{{__('NOTI_ATMOST_2_BLOGPOSTS_CAN_BE_ADDED')}}";
        let atmost6CategoriesCanBeSelectedMessage = "{{__('NOTI_ATMOST_6_CATEGORIES_CAN_BE_SELECTED')}}";
        let exactly3CategoriesRequiredMessage = "{{__('NOTI_EXACTLY_3_CATEGORIES_REQUIRED')}}";
        let exactly6CategoriesRequiredMessage = "{{__('NOTI_EXACTLY_6_CATEGORIES_REQUIRED')}}";
        let between3To6TestimonialsRequiredMessage = "{{__('NOTI_BETWEEN_3TO6_TESTIMONIALS_REQUIRED')}}";
        let between2To6BlogsRequiredMessage = "{{__('NOTI_BETWEEN_2TO6_BLOGS_REQUIRED')}}";
        let categoryAlreadyAddedMessage = "{{__('NOTI_CATEGORY_ALREADY_ADDED')}}";
        let templateSavedMessage = "{{__('NOTI_TEMPLATE_SAVED')}}";
        let displayOrderUpdatedMessage = "{{__('NOTI_DISPLAY_ORDER_UPDATED')}}";
        let somethingWentWrongMessage = "{{__('NOTI_SOMETHING_WENT_WRONG')}}";
        let atmost6FaqsRequiredMessage = "{{__('NOTI_ATMOST_6_FAQS_CAN_BE_ADDED')}}";
        let allBannersRequiredMessage = "{{__('NOTI_ALL_BANNER_NEED_TO_BE_UPLOADED')}}";
        let atleast4TestimonialsRequiredMessage = "{{__('NOTI_ATLEAST_4_TESTIMONIALS_MUST_BE_SELECTED')}}";
        let sameCategoryCannotBeAddedAgainMessage = "{{__('NOTI_SAME_CATEGORY_CANNOT_BE_ADDED_AGAIN')}}";
        
        async function getFileMimeType(url) {
          var mimeType = 'image/jpeg';
          let result = await makeRequest("GET", url);
          if (typeof result.type != 'undefined') {
            mimeType = result.type;
          }
          return mimeType;
        }
        function makeRequest(method, url) {
          return new Promise(function (resolve, reject) {
            let xhr = new XMLHttpRequest();
            xhr.open(method, url);
            xhr.responseType = "blob";
            xhr.onload = function () {
              resolve(xhr.response);
            };
            xhr.send();
          });
        }
    </script>
</head>
<!-- end::Head -->
<!-- begin::Body -->

<body class="editorloading">
    <div id="gjs" style="height:0px; overflow:hidden;">
  </div>
<div class="modalsContent"></div>

<script>
  let pageId = {{$page_id}};
  var headers = {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
    'pageid': {{$page_id}}
  };
  $.ajaxSetup({
    headers: headers
  });

  setTimeout(() => {
    $('body').removeClass("editorloading");
  }, 2000);

  loader = function(display = true) {
    if(display){
      $('body').addClass("editorloading");
    }else{
      $('body').removeClass("editorloading");
    }
  }
    
    getSvgIcon = function(type, size = "24") {
        return `<svg width="` + size + `px" height="` + size + `px">
            <use xlink:href="` + spriteUrl + `#yk-` + type + `" href="` + spriteUrl + `#yk-` + type + `"></use>
        </svg>`;
    }

    let editor  = grapesjs.init({
      avoidInlineStyle: 1, //must have for dropping component on editor and show it selected
      // forceClass: false,
      height: '100%',
      container : '#gjs',
      fromElement: 1,
      showOffsets: 1,
      allowScripts: 1,
      dragMode: 'none',//none, absolute, translate
      canvas: {
        styles: [
          "{{ getJsCssPath('yokart/'.config('theme').'/css/main-ltr.css') }}",
          "{{ getJsCssPath('vendors/css/dev.css') }}"
        ],
        scripts: [
          "{{ getJsCssPath('admin/vendors/jquery.min.js') }}",
          "{{ getJsCssPath('admin/vendors/jquery.fillcolor.js') }}",
          // "{{ getJsCssPath('admin/vendors/popper.min.js') }}",
          // "{{ getJsCssPath('admin/vendors/bootstrap.min.js') }}",
          // "{{ getJsCssPath('admin/vendors/nav.js') }}",
          // "{{ getJsCssPath('admin/vendors/ui-functions.js') }}"
        ]
        },
      storageManager: {
         // id: 'gjs-',             // Prefix identifier that will be used on parameters
         type: 'remote',          // Type of the storage - local, remote
         autosave: false,         // Store data automatically
         autoload: false,         // Autoload stored data on init
         // stepsBeforeSave: 3,     // If autosave enabled, indicates how many changes are necessary before store method is triggered
         params: { '_token': '{{ csrf_token() }}' },
         urlStore: adminBaseUrl+'/savetemplate/{{$page_id}}',
         urlLoad: adminBaseUrl+'/loadtemplate/{{$page_id}}',
      },
      selectorManager: { componentFirst: 1 },
      styleManager: { clearProperties: 0 },
      plugins: [
        'gjs-preset-webpage',
        'grapesjs-custom-code',
        'grapesjs-touch',
        'grapesjs-parser-postcss',
        'grapesjs-tooltip'
      ],
      pluginsOpts: {
        'grapesjs-custom-code': {
          blockCustomCode: { 
            label: getSvgIcon('custom-code', '24') + `<span class="mt-2 d-block">Custom Code</span>`,
            attributes: {class: "d-flex align-items-center justify-content-center"},
            content: {
              type: "custom-code",
              draggable: "div.js-body",
              classes: ["content-min-height"]
            }
          }
        },
        'gjs-preset-webpage': {
          modalImportTitle: 'Import Template',
          modalImportLabel: '<div style="margin-bottom: 10px; font-size: 13px;">Paste here your HTML/CSS and click Import</div>',
          modalImportContent: function(editor) {
            return editor.getHtml() + '<style>'+editor.getCss()+'</style>'
          },
          filestackOpts: null, //{ key: 'AYmqZc2e8RLGLE7TGkX3Hz' },
          aviaryOpts: false,
          blocksBasicOpts: { flexGrid: 1 },
          customStyleManager: [{
            name: 'General',
            buildProps: ['float', 'display', 'position', 'top', 'right', 'left', 'bottom'],
            properties:[{
                name: 'Alignment',
                property: 'float',
                type: 'radio',
                defaults: 'none',
                list: [
                  { value: 'none', className: 'fa fa-times'},
                  { value: 'left', className: 'fa fa-align-left'},
                  { value: 'right', className: 'fa fa-align-right'}
                ],
              },
              { property: 'position', type: 'select'}
            ],
          },{
              name: 'Dimension',
              open: false,
              buildProps: ['width', 'flex-width', 'height', 'max-width', 'min-height', 'margin', 'padding'],
              properties: [{
                id: 'flex-width',
                type: 'integer',
                name: 'Width',
                units: ['px', '%'],
                property: 'flex-basis',
                toRequire: 1,
              },{
                property: 'margin',
                properties:[
                  { name: 'Top', property: 'margin-top'},
                  { name: 'Right', property: 'margin-right'},
                  { name: 'Bottom', property: 'margin-bottom'},
                  { name: 'Left', property: 'margin-left'}
                ],
              },{
                property  : 'padding',
                properties:[
                  { name: 'Top', property: 'padding-top'},
                  { name: 'Right', property: 'padding-right'},
                  { name: 'Bottom', property: 'padding-bottom'},
                  { name: 'Left', property: 'padding-left'}
                ],
              }],
            },{
              name: 'Typography',
              open: false,
              buildProps: ['font-family', 'font-size', 'font-weight', 'letter-spacing', 'color', 'line-height', 'text-align', 'text-decoration', 'text-shadow'],
              properties:[
                { name: 'Font', property: 'font-family'},
                { name: 'Weight', property: 'font-weight'},
                { name:  'Font color', property: 'color'},
                {
                  property: 'text-align',
                  type: 'radio',
                  defaults: 'left',
                  list: [
                    { value : 'left',  name : 'Left',    className: 'fa fa-align-left'},
                    { value : 'center',  name : 'Center',  className: 'fa fa-align-center' },
                    { value : 'right',   name : 'Right',   className: 'fa fa-align-right'},
                    { value : 'justify', name : 'Justify',   className: 'fa fa-align-justify'}
                  ],
                },{
                  property: 'text-decoration',
                  type: 'radio',
                  defaults: 'none',
                  list: [
                    { value: 'none', name: 'None', className: 'fa fa-times'},
                    { value: 'underline', name: 'underline', className: 'fa fa-underline' },
                    { value: 'line-through', name: 'Line-through', className: 'fa fa-strikethrough'}
                  ],
                },{
                  property: 'text-shadow',
                  properties: [
                    { name: 'X position', property: 'text-shadow-h'},
                    { name: 'Y position', property: 'text-shadow-v'},
                    { name: 'Blur', property: 'text-shadow-blur'},
                    { name: 'Color', property: 'text-shadow-color'}
                  ],
              }],
            },
            @if($cmsSkillLevel==3)
            {
              name: 'Decorations',
              open: false,
              buildProps: ['opacity', 'background-color', 'border-radius', 'border', 'box-shadow', 'background'],
              properties: [{
                type: 'slider',
                property: 'opacity',
                defaults: 1,
                step: 0.01,
                max: 1,
                min:0,
              },{
                property: 'border-radius',
                properties  : [
                  { name: 'Top', property: 'border-top-left-radius'},
                  { name: 'Right', property: 'border-top-right-radius'},
                  { name: 'Bottom', property: 'border-bottom-left-radius'},
                  { name: 'Left', property: 'border-bottom-right-radius'}
                ],
              },{
                property: 'box-shadow',
                properties: [
                  { name: 'X position', property: 'box-shadow-h'},
                  { name: 'Y position', property: 'box-shadow-v'},
                  { name: 'Blur', property: 'box-shadow-blur'},
                  { name: 'Spread', property: 'box-shadow-spread'},
                  { name: 'Color', property: 'box-shadow-color'},
                  { name: 'Shadow type', property: 'box-shadow-type'}
                ],
              },{
                property: 'background',
                properties: [
                  { name: 'Image', property: 'background-image'},
                  { name: 'Repeat', property:   'background-repeat'},
                  { name: 'Position', property: 'background-position'},
                  { name: 'Attachment', property: 'background-attachment'},
                  { name: 'Size', property: 'background-size'}
                ],
              },],
            },{
              name: 'Extra',
              open: false,
              buildProps: ['transition', 'perspective', 'transform'],
              properties: [{
                property: 'transition',
                properties:[
                  { name: 'Property', property: 'transition-property'},
                  { name: 'Duration', property: 'transition-duration'},
                  { name: 'Easing', property: 'transition-timing-function'}
                ],
              },{
                property: 'transform',
                properties:[
                  { name: 'Rotate X', property: 'transform-rotate-x'},
                  { name: 'Rotate Y', property: 'transform-rotate-y'},
                  { name: 'Rotate Z', property: 'transform-rotate-z'},
                  { name: 'Scale X', property: 'transform-scale-x'},
                  { name: 'Scale Y', property: 'transform-scale-y'},
                  { name: 'Scale Z', property: 'transform-scale-z'}
                ],
              }]
            },{
              name: 'Flex',
              open: false,
              properties: [{
                name: 'Flex Container',
                property: 'display',
                type: 'select',
                defaults: 'block',
                list: [
                  { value: 'block', name: 'Disable'},
                  { value: 'flex', name: 'Enable'}
                ],
              },{
                name: 'Flex Parent',
                property: 'label-parent-flex',
                type: 'integer',
              },{
                name      : 'Direction',
                property  : 'flex-direction',
                type    : 'radio',
                defaults  : 'row',
                list    : [{
                          value   : 'row',
                          name    : 'Row',
                          className : 'icons-flex icon-dir-row',
                          title   : 'Row',
                        },{
                          value   : 'row-reverse',
                          name    : 'Row reverse',
                          className : 'icons-flex icon-dir-row-rev',
                          title   : 'Row reverse',
                        },{
                          value   : 'column',
                          name    : 'Column',
                          title   : 'Column',
                          className : 'icons-flex icon-dir-col',
                        },{
                          value   : 'column-reverse',
                          name    : 'Column reverse',
                          title   : 'Column reverse',
                          className : 'icons-flex icon-dir-col-rev',
                        }],
              },{
                name      : 'Justify',
                property  : 'justify-content',
                type    : 'radio',
                defaults  : 'flex-start',
                list    : [{
                          value   : 'flex-start',
                          className : 'icons-flex icon-just-start',
                          title   : 'Start',
                        },{
                          value   : 'flex-end',
                          title    : 'End',
                          className : 'icons-flex icon-just-end',
                        },{
                          value   : 'space-between',
                          title    : 'Space between',
                          className : 'icons-flex icon-just-sp-bet',
                        },{
                          value   : 'space-around',
                          title    : 'Space around',
                          className : 'icons-flex icon-just-sp-ar',
                        },{
                          value   : 'center',
                          title    : 'Center',
                          className : 'icons-flex icon-just-sp-cent',
                        }],
              },{
                name      : 'Align',
                property  : 'align-items',
                type    : 'radio',
                defaults  : 'center',
                list    : [{
                          value   : 'flex-start',
                          title    : 'Start',
                          className : 'icons-flex icon-al-start',
                        },{
                          value   : 'flex-end',
                          title    : 'End',
                          className : 'icons-flex icon-al-end',
                        },{
                          value   : 'stretch',
                          title    : 'Stretch',
                          className : 'icons-flex icon-al-str',
                        },{
                          value   : 'center',
                          title    : 'Center',
                          className : 'icons-flex icon-al-center',
                        }],
              },{
                name: 'Flex Children',
                property: 'label-parent-flex',
                type: 'integer',
              },{
                name:     'Order',
                property:   'order',
                type:     'integer',
                defaults :  0,
                min: 0
              },{
                name    : 'Flex',
                property  : 'flex',
                type    : 'composite',
                properties  : [{
                        name:     'Grow',
                        property:   'flex-grow',
                        type:     'integer',
                        defaults :  0,
                        min: 0
                      },{
                        name:     'Shrink',
                        property:   'flex-shrink',
                        type:     'integer',
                        defaults :  0,
                        min: 0
                      },{
                        name:     'Basis',
                        property:   'flex-basis',
                        type:     'integer',
                        units:    ['px','%',''],
                        unit: '',
                        defaults :  'auto',
                      }],
              },{
                name      : 'Align',
                property  : 'align-self',
                type      : 'radio',
                defaults  : 'auto',
                list    : [{
                          value   : 'auto',
                          name    : 'Auto',
                        },{
                          value   : 'flex-start',
                          title    : 'Start',
                          className : 'icons-flex icon-al-start',
                        },{
                          value   : 'flex-end',
                          title    : 'End',
                          className : 'icons-flex icon-al-end',
                        },{
                          value   : 'stretch',
                          title    : 'Stretch',
                          className : 'icons-flex icon-al-str',
                        },{
                          value   : 'center',
                          title    : 'Center',
                          className : 'icons-flex icon-al-center',
                        }],
              }]
            }
            @endif
          ],
        },
      },
    });
    let bm = editor.BlockManager;

    {!!$widgets!!}

    let themeWidgets = ['{!!$themeWidgetNames!!}'];
    let generalWidgets = ['{!!$generalWidgetNames!!}'];
    let productsHtml = {!!json_encode($productsHtml)!!};   
    let imagesConfig = {!!json_encode(imageConfig())!!};  
    
    let previewLink = `${baseUrl}/preview/{{$page_id}}`;
    window.comboTreePages = '';
    window.comboTreeCategories = '';
    
    let pagesData = {!!json_encode($pages)!!};
    let categoriesData = {!!json_encode($categories)!!};
    let logoHtml = {!!json_encode($logoHtml)!!};

    // function sleep(ms) {
    //   return new Promise(resolve => setTimeout(resolve, ms));
    // }
    // sleep(2000).then(() => { console.log("World!");  });

    $(document).ready(function(){      
      $(document).on('click', '.closeCropper', function(){ 
          $('#modal_cropper').modal('hide');
          $('#settingsModal').modal('show');
      });
      $(document).on('click', '#modal_cropper', function(event){     
        if ($(this).hasClass('show') && !$(event.target).closest('.modal-content').length) {
          $('#modal_cropper').modal('hide');
          $('#settingsModal').modal('show');
        }
      });  
    });
    
    var pn = editor.Panels;

    let viewPanelsToRemove = ['{!!$viewPanelsToRemove!!}'];    
    pn.removeButton('views', viewPanelsToRemove); //open-blocks
    
    let cmsSkillLevel = "{{$cmsSkillLevel}}";
    let cmsSkillLevelTitle = "{{$cmsSkillLevelTitle}}";

    if (!viewPanelsToRemove.includes("open-sm")) {
        pn.getButton('views', 'open-sm').set('label', getSvgIcon('open-sm', '24'));
    }
    if (!viewPanelsToRemove.includes("open-tm")) {
          pn.getButton('views', 'open-tm').set('label', getSvgIcon('open-tm', '24'));
    }
    if (!viewPanelsToRemove.includes("open-layers")) {
        pn.getButton('views', 'open-layers').set('label', getSvgIcon('open-layers', '24'));
    }
    if (!viewPanelsToRemove.includes("open-blocks")) {
      pn.getButton('views', 'open-blocks').set('label', getSvgIcon('open-blocks', '24'));
    }
    @if($cmsSkillLevel==1)
      pn.removePanel('views');
    @endif
    @if($cmsSkillLevel!=3)
      bm.remove(generalWidgets);
    @endif
    
    bm.remove(['column3-7', 'map', 'link-block', 'quote', 'h-navbar', 'countdown', 'lory-slider', 'tabs', 'tooltip', 'form', 'input', 'textarea',
    'select', 'checkbox', 'radio', 'text-basic', 'button', 'label', 'text', 'column1', 'column2', 'column3', 'image', 'video', 'link']);

    let fontFamily = "{{$configurations['FRONTEND_FONT_FAMILY']}}";

    let fontFamilyUrl = "";
    if(fontFamily == 'Mulish'){
      fontFamilyUrl = "https://fonts.googleapis.com/css2?family=Mulish:wght@300;400;500;600;700;800&display=swap";
    }else if(fontFamily == 'Roboto'){
      fontFamilyUrl = "https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;500;700&display=swap";
    }else if(fontFamily == 'Poppins'){
      fontFamilyUrl = "https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap";
    }else if(fontFamily == 'opensans'){
      fontFamilyUrl = "https://fonts.googleapis.com/css2?family=Open+Sans:wght@300;400;600;700;800&display=swap";
    }

    let primaryColor = "{{ !empty($configurations['PRIMARY_COLOR']) ? $configurations['PRIMARY_COLOR'] : '#ff0055'}}";
    let primaryColorInverse = "{{ !empty($configurations['PRIMARY_COLOR_INVERSE']) ? $configurations['PRIMARY_COLOR_INVERSE'] : '#FFFFFF'}}";

    var disableHeaderFooter  = ``;
    if(pageId != 1){
      disableHeaderFooter = `header[data-gjs-type="default"], footer[data-gjs-type="default"]{
        pointer-events: none;
      }`;
    }
    const head = editor.Canvas.getDocument().head;
    head.insertAdjacentHTML('beforeend', `
    <link href="${ fontFamilyUrl }" rel="stylesheet">
    <style>
    .yk-header{width:100%;min-height: 30px;}
    .header-top,.header-middle,.header-bottom,.header{min-height: 30px;} 
    ${ disableHeaderFooter }
    :root { 
      --brand-color: ${ primaryColor } !important;
      --brand-color-inverse: ${ primaryColorInverse } !important;
    }
    body{
      font-family: "${ fontFamily }", sans-serif !important;
    }
    </style>
    `);

</script>
<link href="{{ asset('vendors/css/perfect-scrollbar.css') }}" rel="stylesheet">
<script src="{{ asset('vendors/js/perfect-scrollbar.min.js') }}"></script>  
<script src="{{ asset('vendors/js/require.min.js') }}"></script>    
<script>
  require.config({
    waitSeconds: 0
  });
</script>    
<script src="{{ asset('yokart/'.config('theme').'/js/grapesjs.js') }}"></script> 
<div class="modal fade" id="resetConfirmationModal" role="dialog" aria-labelledby="exampleModalLabel" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Confirmation Alert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-row align-items-center">
          <div class="col-md-12 mb-3">
            <p>Are you sure you want to reset this page? Confirming this action will revert all your changes on this page and reset it to the default version.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-brand gb-btn gb-btn-primary" onclick="resetPage(event)">Yes</button>
      </div>
    </div>
  </div>
</div>
<div class="modal fade" id="deleteConfirmationModal" role="dialog" aria-labelledby="exampleModalLabel1" style="display: none;" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel1">Deletion Alert</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="form-row align-items-center">
          <div class="col-md-12 mb-3">
            <p>Are you sure you want to remove this component? Confirming this action will remove all data linked with this collection.</p>
          </div>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">No</button>
        <button type="button" class="btn btn-brand gb-btn gb-btn-primary YK-deleteComponent" data-cid="" onclick="deleteComponent(event)">Yes</button>
      </div>
    </div>
  </div>
</div>
<style> 
  .gjs-pn-btn.fa.fa-square-o:before, .gjs-pn-btn.fa.fa-undo:before, .gjs-pn-btn.fa.fa-repeat:before, .gjs-pn-btn.fa.fa-paint-brush:before, .gjs-pn-btn.fa.fa-cog:before, .gjs-pn-btn.fa.fa-bars:before, .gjs-pn-btn.fa.fa-th-large:before{display:none;}
  #sortable { list-style-type: none; margin: 0; padding: 0; width: 60%; }
  #sortable li { margin: 0 5px 5px 5px; padding: 5px; font-size: 1.2em; height: 1.5em; }
  html>body #sortable li { height: 1.5em; line-height: 1.2em; }
  .ui-state-highlight { height: 1.5em; line-height: 1.2em; }
  .yk-perfectScrollbar { position: relative;max-height: 400px; }
  .yk-sortable{    position: relative;
    overflow: hidden;}
  .ps> .ps__rail-y{
      opacity: 0.6;
  }
  .ui-autocomplete, .comboTreeDropDownContainer {/* ul:first-child */
    max-height: 180px;
    overflow-x: hidden;
  }
  .gjs-pn-buttons span.gjs-pn-btn {width: auto;} /*to show the left panel tabs in full width if they are lessa than 4 shown  */
  .gjs-block-categories{
    padding-top: 2.5rem;
  }
  .gjs-pn-panel.gjs-pn-devices-c{margin-left: 40%;}
  .gjs-pn-commands {
      width: 0;
  }
  .gjs-pn-commands .gjs-pn-buttons .gjs-pn-btn:first-child{display:none;}
  .gjs-pn-commands{
    box-shadow:none;
  }
  
  @if($cmsSkillLevel==1)
  .gjs-pn-views-container {
    padding: 0px;
  }
  @endif
  .gjs-mdl-title{    font-size: 1.3rem;}
  .gjs-btn-import__custom-code, .gjs-btn-import__custom-code:not(:disabled):not(.disabled):active, .gjs-btn-import__custom-code:not(:disabled):not(.disabled).active, .show > .gjs-btn-import__custom-code.dropdown-toggle {
    margin-top: 20px;
    float: right;
    color: #fff;
    background-color: #ff098f;
    padding: 0.575rem 0.75rem;
    border-radius: 0.25rem;
    }
  </style>
</body>

</html>
