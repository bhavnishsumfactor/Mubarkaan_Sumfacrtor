<?php

namespace App\Models;

use App\Models\YokartModel;
use App\Models\Theme;
use Illuminate\Support\Facades\File;
use Artisan;
use App\Helpers\ThemeHelper;
use Database\Seeders\ThemeTableSeeder;

class DatabaseRestore extends YokartModel
{
    public static function reset()
    {
        Artisan::call('db:seed', ['--class' => 'ConfigurationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UrlRewriteTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AdminTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductContentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionNameTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductOptionVarientTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductSpecificationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductToCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserRewardPointBreakupTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserRewardPointTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserCardTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AttachedTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'HomePageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AboutPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PrivacyPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TermsPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ContactPageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserAddressTableSeeder']);
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
        Artisan::call('db:seed', ['--class' => 'ReasonTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductReviewHelpfulTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ProductReviewTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductTaxLogTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductChargeTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductAdditionInfoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderMessageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostContentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostToCategoryTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogPostToCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlogReplyToCommentTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BrandTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BuyTogetherProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CurrencyTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'MetaTagTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'DiscountCouponRecordTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'DiscountCouponTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmailTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SmsTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'LanguageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TodoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'TestimonialTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OptionTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OptionToVarientSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderInvoiceTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PackageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RegionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RelatedProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderAdditionInfoTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderReturnRequestTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'OrderReturnAmountTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShareEarnRecordTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ThreadMessageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ThreadTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserSavedProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'InstagramFeedTokenTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'UserFavouriteProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationToAdminTableSeeder']);
        \Artisan::call("cache:clear");
        \Artisan::call("config:clear");
        \Artisan::call("route:clear");
        \Artisan::call("view:clear");
        \Artisan::call("storage:link");
        File::deleteDirectory(storage_path('app/public'));
        File::copyDirectory(base_path('git-ignored-files/storage/app/public'), storage_path('app/public'));
    }
    public static function blankDataBaseInstall()
    {
        
        Artisan::call('db:seed', ['--class' => 'BlankUrlRewritesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CountriesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'RegionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreAddressTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'StoreTimingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'PackageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingToLocationTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingRateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileZoneTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingProfileToProductTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ShippingTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ReasonTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'EmailTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'SmsTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'NotificationTemplateTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'ResourceTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AppCollectionsTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AppCollectionRecordToDataTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AppNotificationTemplatesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlankPagesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AppPagesTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'AdminTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'CurrencyTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'LanguageTableSeeder']);
        Artisan::call('db:seed', ['--class' => 'BlankConfigurationTableSeeder']);
    }
}
