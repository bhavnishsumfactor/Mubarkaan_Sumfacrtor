<?php

$arr = [
    /*sections*/
    'YOKART_ADMINS' => 1,
    'YOKART_ROLES' => 2,
    'YOKART_USERS' => 3,
    'YOKART_BLOGS' => 4,
    'YOKART_BLOG_CATEGORIES' => 5,
    'YOKART_BANNERS' => 6,
    'YOKART_PAGES' => 7,
    'YOKART_COUNTRIES' => 8,
    'YOKART_STATES' => 9,
    'YOKART_CURRENCIES' => 10,
    'YOKART_EMAIL_TEMPLATES' => 11,
    'YOKART_BRANDS' => 12,
    'YOKART_COUPONS' => 13,
    'YOKART_TESTIMONIALS' => 14,
    'YOKART_FAQCATEGORIES' => 15,
    'YOKART_FAQS' => 16,
    'YOKART_PRODUCT_CATEGORIES' => 17,

	//'YOKART_PRODUCTS' => 18,
	//'YOKART_OPTIONS'

    /*Permissions*/
    'PERMISSION_NONE' => 0,
    'PERMISSION_READ' => 1,
    'PERMISSION_WRITE' => 2,
    'YES' => 1,
    'NO' => 0,
];


return [
    'THEME' => 'fashion',
    /*Permissions*/
    'PERMISSION_NONE' => $arr['PERMISSION_NONE'],
    'PERMISSION_READ' => $arr['PERMISSION_READ'],
    'PERMISSION_WRITE' => $arr['PERMISSION_WRITE'],
    'permissions' => [
        $arr['PERMISSION_NONE'] => 'None',
        $arr['PERMISSION_READ'] => 'Read',
        $arr['PERMISSION_WRITE'] => 'Write'
    ],
    'YES_OR_NO' => [
        $arr['YES'] => 'Yes',
        $arr['NO'] => 'No',
    ],
    'YES' => $arr['YES'],
    'NO' => $arr['NO'],
    /*sections*/
    'YOKART_ADMINS'=> $arr['YOKART_ADMINS'],
    'YOKART_ROLES'=> $arr['YOKART_ROLES'],
    'YOKART_USERS'=> $arr['YOKART_USERS'],
    'YOKART_BLOGS'=> $arr['YOKART_BLOGS'],
    'YOKART_BLOG_CATEGORIES'=> $arr['YOKART_BLOG_CATEGORIES'],
    'YOKART_BANNERS'=> $arr['YOKART_BANNERS'],
    'YOKART_PAGES'=> $arr['YOKART_PAGES'],
    'YOKART_COUNTRIES'=> $arr['YOKART_COUNTRIES'],
    'YOKART_STATES'=> $arr['YOKART_STATES'],
    'YOKART_CURRENCIES'=> $arr['YOKART_CURRENCIES'],
    'YOKART_EMAIL_TEMPLATES'=> $arr['YOKART_EMAIL_TEMPLATES'],
    'YOKART_BRANDS'=> $arr['YOKART_BRANDS'],
    'YOKART_COUPONS'=> $arr['YOKART_COUPONS'],
    'YOKART_TESTIMONIALS'=> $arr['YOKART_TESTIMONIALS'],
    'YOKART_FAQCATEGORIES'=> $arr['YOKART_FAQCATEGORIES'],
    'YOKART_FAQS'=> $arr['YOKART_FAQS'],
    'YOKART_PRODUCT_CATEGORIES'=> $arr['YOKART_PRODUCT_CATEGORIES'],

    'sections' => [
        $arr['YOKART_ADMINS'] => 'Admins',
        $arr['YOKART_ROLES'] => 'Roles',
        $arr['YOKART_USERS'] => 'Users',
        $arr['YOKART_BLOGS'] => 'Blogs',
        $arr['YOKART_BLOG_CATEGORIES'] => 'Blog Categories',
        $arr['YOKART_BANNERS'] => 'Banners',
        $arr['YOKART_PAGES'] => 'Pages',
        $arr['YOKART_COUNTRIES'] => 'Countries',
        $arr['YOKART_STATES'] => 'States',
        $arr['YOKART_CURRENCIES'] => 'Currencies',
        $arr['YOKART_EMAIL_TEMPLATES'] => 'Email Templates',
        $arr['YOKART_BRANDS'] => 'Brands',
        $arr['YOKART_COUPONS'] => 'Coupons',
        $arr['YOKART_TESTIMONIALS'] => 'Testimonials',
        $arr['YOKART_FAQCATEGORIES'] => 'Faq categories',
        $arr['YOKART_FAQS'] => 'Faqs',
        $arr['YOKART_PRODUCT_CATEGORIES'] => 'Product categories',
		/* Tabs */


    ],
    'SUCCESS' => 200,
    'ERROR' => 400,
    'UNAUTHORIZED' => 401
];
