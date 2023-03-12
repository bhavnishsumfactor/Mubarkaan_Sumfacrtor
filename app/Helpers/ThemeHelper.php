<?php
namespace App\Helpers;

use App\Helpers\YokartHelper;
use App\Models\Collection;
use App\Models\CollectionMeta;
use App\Models\AttachedFile;
use Illuminate\Support\Facades\Artisan;
use Database\Seeders\AdminTableSeeder;
use Database\Seeders\LanguageTableSeeder;
use Database\Seeders\CurrencyTableSeeder;
use Database\Seeders\BlankConfigurationTableSeeder;

class ThemeHelper extends YokartHelper
{
    public const WIDGETS = [
        120 => 'section',
        101 => 'container',
        102 => 'columnOne',
        103 => 'columnTwo',
        104 => 'columnThree',
        105 => 'column4By8',
        121 => 'h1Tag',
        122 => 'h2Tag',
        123 => 'h3Tag',
        124 => 'h4Tag',
        125 => 'h5Tag',
        126 => 'h6Tag',
        127 => 'pTag',
        128 => 'ulTag',
        129 => 'divTag',
        130 => 'spanTag',
        107 => 'linkTag',
        108 => 'imageTag',
        109 => 'videoTag',
        110 => 'titleDescription',
        111 => 'customcode',
        112 => 'button1',
        114 => 'twitter',
        115 => 'facebook',
        116 => 'instagram',
        117 => 'google',
        118 => 'socialIcons',
        119 => 'newsletter',
    ];
    
    public static function get()
    {
        $html = array_filter(array_map(function ($widget) {
            if (method_exists('App\Helpers\ThemeHelper', $widget)) {
                return self::$widget();
            }
        }, self::WIDGETS));
        return implode(' ', $html);
    }

    public static function getWidgetNames()
    {
        $widgetNames = self::WIDGETS;
        $widgetNames[111] = 'custom-code';
        return $widgetNames;
    }

    protected static function getBlock($layout, $title, $params = [])
    {
        return view('admin.partials.widgetElement', compact('layout', 'title', 'params'))->render();
    }

    public static function imageTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_IMAGE");
        return self::getBlock($layout, $title);
    }

    public static function imageTagContent($pageid, $cid, $layout, $param)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $layoutName = self::WIDGETS[$layout];
        $collections['image'] = AttachedFile::getFileUrl($layoutName, $collection_id, 0, 'thumb');
        return view('themes.' . config('theme') . '.widgets.' . $layoutName, compact('collections', 'cid', 'param'))->render();
    }

    public static function videoTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_VIDEO");
        return self::getBlock($layout, $title);
    }

    public static function videoTagContent($pageid, $cid, $layout, $param)
    {
        $collections = [];
        $collection_id = Collection::generateCollectionId($pageid, $cid, $layout);
        $collections['provider_type'] = CollectionMeta::getValue($collection_id, 'VIDEO_PROVIDER_TYPE');
        $videoUrl = CollectionMeta::getValue($collection_id, 'VIDEO_URL');
        $collections['autoplay'] = CollectionMeta::getValue($collection_id, 'VIDEO_AUTOPLAY');
        if ($collections['provider_type'] == '1') { //youtube
            preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $videoUrl, $matches);
            $videoUrl = "https://www.youtube.com/embed/" . $matches[1] . "?autoplay=" . $collections['autoplay'] . "&showinfo=0&controls=0&rel=0&modestbranding=1&enablejsapi=1";
        } else { //vimeo
            preg_match('%^https?:\/\/(?:www\.|player\.)?vimeo.com\/(?:channels\/(?:\w+\/)?|groups\/([^\/]*)\/videos\/|album\/(\d+)\/video\/|video\/|)(\d+)(?:$|\/|\?)(?:[?]?.*)$%im', $videoUrl, $matches);
            $videoUrl = "https://player.vimeo.com/video/" . $matches[3] . "?autoplay=" . $collections['autoplay'] . "&color=ef0800&title=0&byline=0&portrait=0&fun=0&badge=0&dnt=1&api=1";
        }
        $collections['video_url'] = $videoUrl;
        return view('themes.' . config('theme') . '.widgets.videoTag', compact('collections', 'cid', 'param'))->render();
    }

    public static function columnOne()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_1COLUMN");
        return self::getBlock($layout, $title);
    }

    public static function columnTwo()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_2COLUMN");
        return self::getBlock($layout, $title);
    }

    public static function columnThree()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_3COLUMN");
        return self::getBlock($layout, $title);
    }

    public static function column4By8()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_4BY8COLUMN");
        return self::getBlock($layout, $title);
    }

    public static function linkTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_LINK");
        return self::getBlock($layout, $title);
    }

    public static function titleDescription()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TITLE_DESCRIPTION");
        return self::getBlock($layout, $title);
    }
    public static function button1()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_BUTTON");
        return self::getBlock($layout, $title);
    }

    public static function container()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_CONTAINER");
        return self::getBlock($layout, $title);
    }

    public static function twitter()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_TWITTER");
        return self::getBlock($layout, $title);
    }

    public static function facebook()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_FACEBOOK");
        return self::getBlock($layout, $title);
    }

    public static function instagram()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_INSTAGRAM");
        return self::getBlock($layout, $title);
    }

    public static function google()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_GOOGLE");
        return self::getBlock($layout, $title);
    }

    public static function socialIcons()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_SOCIAL_ICONS");
        return self::getBlock($layout, $title);
    }

    public static function newsletter()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_NEWSLETTER");
        return self::getBlock($layout, $title);
    }

    public static function customcode()
    {
        $layout = "custom-code";
        $title = __("LBL_WIDGETNAME_CUSTOM_CODE");
        return self::getBlock($layout, $title);
    }

    public static function section()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_SECTION");
        return self::getBlock($layout, $title);
    }

    public static function h1Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H1TAG");
        return self::getBlock($layout, $title);
    }

    public static function h2Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H2TAG");
        return self::getBlock($layout, $title);
    }

    public static function h3Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H3TAG");
        return self::getBlock($layout, $title);
    }

    public static function h4Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H4TAG");
        return self::getBlock($layout, $title);
    }

    public static function h5Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H5TAG");
        return self::getBlock($layout, $title);
    }

    public static function h6Tag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_H6TAG");
        return self::getBlock($layout, $title);
    }

    public static function pTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_PTAG");
        return self::getBlock($layout, $title);
    }

    public static function ulTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_ULTAG");
        return self::getBlock($layout, $title);
    }

    public static function divTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_DIVTAG");
        return self::getBlock($layout, $title);
    }

    public static function spanTag()
    {
        $layout = __FUNCTION__;
        $title = __("LBL_WIDGETNAME_SPANTAG");
        return self::getBlock($layout, $title);
    }

    public static function migrateAndSeed($request)
    {
        Artisan::call('migrate:fresh', ["--force" => true]);
        if ($request->input('install_dummy')) {
            self::withDataSeeds($request);

          //  File::deleteDirectory(storage_path('app/public'));
         //   File::copyDirectory(base_path('git-ignored-files/storage/app/public'), storage_path('app/public'));
        } else {
            self::withoutDataSeeds($request);
        }
    }
    public static function withDataSeeds($request)
    {
      
        Artisan::call('db:seed', ['--class' => 'UserRewardPointBreakupTableSeeder']);
       
        Artisan::call('db:seed', ['--class' => 'UserRewardPointTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserCardTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UrlRewriteTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderReturnAmountTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderAdditionInfoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderReturnRequestTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TransactionTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TaxTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreTimingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SpecialPriceIncludeTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SpecialPriceTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingToLocationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingRateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileZoneTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileToProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TestimonialTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ReasonTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductReviewHelpfulTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductReviewTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductTaxLogTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductChargeTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductAdditionInfoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderMessageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AdminTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AttachedTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostContentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostToCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostToCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogReplyToCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BrandTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BuyTogetherProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CountriesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CurrencyTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'DiscountCouponRecordTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'DiscountCouponTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmailTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SmsTemplateTableSeeder']);
       Artisan::call('db:seed', ['--class' => 'NotificationTemplateTableSeeder']);
    //    Artisan::call('db:seed', ['--class' => 'LanguageTableSeeder']);

        $seeder = new LanguageTableSeeder;
        $seeder->callWith(LanguageTableSeeder::class, [$request->input('admin_language')]);
        
        $seeder = new CurrencyTableSeeder;
        $seeder->callWith(CurrencyTableSeeder::class, [$request->input('admin_currency')]);

        $seeder = new AdminTableSeeder;
        $seeder->callWith(AdminTableSeeder::class, [$request->only(['admin_name', 'admin_email', 'admin_password'])]);
        Artisan::call('db:seed', ['--class' => 'OptionTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OptionToVarientSeeder']);
        Artisan::call('db:seed', ['--class' => 'TodoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderInvoiceTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PackageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'HomePageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AboutPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PrivacyPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TermsPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ContactPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductContentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionNameTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionVarientTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductSpecificationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductToCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RegionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RelatedProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShareEarnRecordTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ThreadMessageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ThreadTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserSavedProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserFavouriteProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'InstagramFeedTokenTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationToAdminTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ConfigurationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ResourceTableSeeder']);
    }

    public static function withoutDataSeeds($request)
    {
        Artisan::call('db:seed', ['--class' => 'BlankUrlRewritesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CountriesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RegionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlankPagesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreTimingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PackageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingToLocationTableSeeder']);
        $seeder = new AdminTableSeeder;
        $seeder->callWith(AdminTableSeeder::class, [$request->only(['admin_name', 'admin_email', 'admin_password'])]);
        $seeder = new CurrencyTableSeeder;
        $seeder->callWith(CurrencyTableSeeder::class, [$request->input('admin_currency')]);
        $seeder = new LanguageTableSeeder;
        $seeder->callWith(LanguageTableSeeder::class, [$request->input('admin_language')]);
        $seeder = new BlankConfigurationTableSeeder;
        $seeder->callWith(BlankConfigurationTableSeeder::class, [$request->input('config'), $request->input('install_dummy')]);
        Artisan::call('db:seed', ['--class' => 'ShippingRateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileZoneTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileToProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ReasonTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmailTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SmsTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ResourceTableSeeder']);
        
    }
}
