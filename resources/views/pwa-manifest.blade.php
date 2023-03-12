@php

$metatags = getDefalutMetaTags();

$config = getConfigValues([
	'BUSINESS_NAME',
	'PRIMARY_COLOR',
    'PRIMARY_COLOR_INVERSE'
	]);


$sizes = [
	"36x36",
	"48x48",
	"57x57",
	"60x60",
	"70x70",
	"72x72",
	"76x76",
	"96x96",
	"114x114",
	"120x120",
	"144x144",
	"192x192",
	"152x152",
	"180x180",
	"150x150",
	"310x310",
	"512x512"
];
$images = [];
foreach($sizes as $size) {

	$images[] = [
		'src' => url('image/apple-touch-icon/frontendLogo/0/0/'. str_replace('x', '-', $size)),
		'sizes' => $size,
		'type' => 'image/png',
		'purpose'=> 'any maskable'
	];
}
$lang = "en";
 if($language = getDefaultLanguage()){
	$lang = $language->lang_code;	
 }
echo json_encode([
	"display" => "standalone",
	"name" => $config['BUSINESS_NAME'],
	"short_name" => $config['BUSINESS_NAME'],
	"description" => ($metatags && $metatags->meta_description !='') ? $metatags->meta_description : $config['BUSINESS_NAME'],
	"lang" => $lang,
	"start_url" => '/',
	"background_color" => $config['PRIMARY_COLOR'],
	"theme_color" => $config['PRIMARY_COLOR_INVERSE'],
	"icons" => $images,
	]);
@endphp


