<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AttachedFile;
use App\Models\Page;
use App\Helpers\EmailHelper;
use App\Models\Admin\Admin;
use App\Models\Notification;
use App\Models\UrlRewrite;
use App\Jobs\SendNotification;
use App\Helpers\FileHelper;
use App\Models\Product;
use App\Models\Order;
use App\Models\InstagramFeedToken;
use App\Http\Controllers\YokartController;
use Cookie;
use Image;
use Redirect;
use File;
use Illuminate\Support\Facades\Crypt;
use App\Helpers\SocialHelper;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Models\Configuration;
use App\Models\UrlShortener;
use Intervention\Image\ImageManager;
use App\Models\Admin\AdminPasswordResetRequest;
use App\Models\UserAuthToken;
use App\Models\UserTempTokenRequest;
use App\Models\Package;
use App\Models\PackageConfiguration;

class HomeController extends YokartController
{
    public function __construct()
    {
        parent::__construct();
    }

    public function iosDeepLink(){
        echo File::get(base_path().'/apple-app-site-association');
        exit;
    }    


    public function viewOtp()
    {
        $adminOtps = AdminPasswordResetRequest::get();

        echo nl2br("======Admin Forget Password OTP==========");

        foreach ($adminOtps as $adminOtp) {
            echo nl2br("\nAdmin ID > " . $adminOtp->aprr_admin_id . ". OTP > " . $adminOtp->aprr_otp);
        }

        $userOtps = UserAuthToken::latest('usrauth_user_id')->get();

        echo nl2br("\n\n======User OTP==========");

        foreach ($userOtps as $userOtp) {
            echo nl2br("\nUser ID > " . $userOtp->usrauth_user_id . ". OTP > " . $userOtp->usrauth_token);
        }

        $orderOtps = UserTempTokenRequest::latest('uttr_id')->get();

        echo nl2br("\n\n======Order OTP==========");

        foreach ($orderOtps as $orderOtp) {
            echo nl2br("\nUser ID > " . $orderOtp->uttr_user_id . ". OTP > " . $orderOtp->uttr_code);
        }
    }

    public function acceptCookie(Request $request)
    {
        $acceptCookie = $request->input('yk-acceptcookie');
        if ($acceptCookie == "selective") {
            $requestData = $request->all();
            $cookie = $this->makeCookie('AcceptCookie', serialize([
                'functional' => 1,
                'analytics' => !empty($requestData['analytics']) ? 1 : 0,
                'personalized' => !empty($requestData['personalized']) ? 1 : 0,
                'advertising' => !empty($requestData['advertising']) ? 1 : 0,
            ]), [(90 * 24 * 60), null, null, false, false]);
        } elseif ($acceptCookie == "all") {
            $cookie = $this->makeCookie('AcceptCookie', serialize([
                'functional' => 1,
                'analytics' => 1,
                'personalized' => 1,
                'advertising' => 1,
            ]), [(90 * 24 * 60), null, null, false, false]);
        }
        return Redirect::back()->withCookie($cookie);
    }

    public function instaAuthCallback(Request $request)
    {
        $response = SocialHelper::getInstagramAccessData(['code' => $request['code']]);
        if ($response['status'] === false) {
            return Redirect::intended(url("/admin#/system-settings/cms/error/" . $response['message']));
        }
        InstagramFeedToken::where('iftoken_user_id', '<>', $response['data']['user_id'])->delete();
        $iftoken = InstagramFeedToken::select('iftoken_id')->where('iftoken_user_id', $response['data']['user_id'])->first();

        $expiresInTimeStamp = Carbon::now()->timestamp + $response['data']['expires_in'];
        $expiresIn = Carbon::createFromTimestamp($expiresInTimeStamp)->format('Y-m-d');

        if (!empty($iftoken)) {
            InstagramFeedToken::where('iftoken_id', $iftoken->iftoken_id)->update([
                'iftoken_access_code' => $response['data']['access_token'],
                'iftoken_expired_at' => $expiresIn
            ]);
        } else {
            InstagramFeedToken::insert([
                'iftoken_username' => $response['data']['username'],
                'iftoken_user_id' => $response['data']['user_id'],
                'iftoken_access_code' => $response['data']['access_token'],
                'iftoken_expired_at' => $expiresIn,
                'iftoken_created_at' => Carbon::now()
            ]);
        }
        return Redirect::intended(url("/admin#/system-settings/cms/success"));
    }

    public function instaAuthRequest(Request $request)
    {
        return Redirect::intended(SocialHelper::authorizeInstagram());
    }

    /**for admin preview purpose */
    public function pagePreview($pageId = 1)
    {
        return $this->fetchPageById($pageId);
    }

    /**for frontend */
    public function pageRender($pageId = 1)
    {
        // $package = Package::getActivePackage('sms');
        // $config = PackageConfiguration::getConfigurations($package->pkg_id);
        // $packageSlug = 'Twilio\TwilioManagement\Models\\' . ucwords($package->pkg_slug);
        // $number = '+919041448564';
        // $resp = $packageSlug::lookup($config, $number);
        // dd($resp);
        return $this->fetchPageById($pageId);
    }

    private function fetchPageById($pageId)
    {
        $page = Page::getPageById($pageId);
        if (empty($page)) {
            abort(404);
        }
        $businessInformation = getConfigValues([
            'BUSINESS_NAME',
            'GOOGLE_RECAPCHA_SITE_KEY',
            'GOOGLE_RECAPCHA_STATUS'
        ]);
        $baseUrl = url('') . '/';
        $overallPageContent = json_decode($page->page_content, true);
        $pageHtml = ($overallPageContent['gjs-html'] ?? '');
        $pageHtml = str_replace('BASE_URL', $baseUrl, $pageHtml);
        $logo = AttachedFile::getFileUrl('frontendLogo', 0, 0, 'thumb');
        $themeClass = 'App\Helpers\\' . ucwords(config('theme')) . 'Helper';

        if (isset($overallPageContent['gjs-css'])) {
            $overallPageContent['gjs-css'] = str_replace('BASE_URL', $baseUrl, $overallPageContent['gjs-css']);
        }

        $pageHtml = $themeClass::updateShortcodes($pageHtml, $page->page_id); //only for central body content
        return view('themes.' . config('theme') . '.pageRender')->with('page_id', $page->page_id)->with('record_id', $page->page_id)
            ->with('pageHtml', $pageHtml)
            ->with('pageCss', ($overallPageContent['gjs-css'] ?? ''))
            ->with('logo', $logo)
            ->with('businessInformation', $businessInformation);
    }

    public function showUrlRewrittenPages($rewrite_url = '')
    {
       
        if ($rewrite_url == '') {
            return $this->pageRender();
        }
        $rewriteData = UrlRewrite::getByCustomUrl($rewrite_url);
        if (empty($rewriteData)) {
            $urlShortener = UrlShortener::getFullUrl($rewrite_url);
            if (!empty($urlShortener->urlshortener_full)) {
                return Redirect::intended(url('') . $urlShortener->urlshortener_full);
            }
        }
        if (!empty($rewriteData->urlrewrite_type)) {
            
            switch ($rewriteData->urlrewrite_type) {
                case 1:
                    $controller = 'ProductController';
                    $action = 'detail';
                    $controllerClass = 'App\Http\Controllers\\' . $controller;
                    break;
                case 2:
                    $controller = 'HomeController';
                    $action = 'pageRender';
                    $controllerClass = 'App\Http\Controllers\\' . $controller;
                    break;
                case 3:
                    $controller = 'CategoryController';
                    $action = 'index';
                    $controllerClass = 'App\Http\Controllers\\' . $controller;
                    break;
                case 4:
                    $controller = 'BlogController';
                    $action = 'show';
                    $controllerClass = 'App\Http\Controllers\\' . $controller;
                    break;
                case 5:
                    $controller = 'BrandController';
                    $action = 'index';
                    $controllerClass = 'App\Http\Controllers\\' . $controller;
                    break;
                default:
                    break;
            }
            $params = explode('/', $rewriteData->urlrewrite_original);
            $recordId = end($params);
            $route = request()->route();
            $route->action['controller'] = 'App\Http\Controllers\\' . $controller . '@' . $action;
            $route->action['uses'] = 'App\Http\Controllers\\' . $controller . '@' . $action;
            $obj = new $controllerClass;
            return $obj->$action($recordId);
        }
        abort(404);
    }
 
    public function saveContactEnquiry(Request $request)
    {
        $configurationData = Configuration::getKeyValues(['GOOGLE_RECAPCHA_SECRET_KEY', 'GOOGLE_RECAPCHA_STATUS']);
        if (!empty($secretKey = $configurationData['GOOGLE_RECAPCHA_SECRET_KEY']) && $configurationData['GOOGLE_RECAPCHA_STATUS'] == 1) {
            $result = $this->googleRecaptchaVerification($request->get('recaptcha'), $request->ip(), $secretKey);
            if ($result->success != true) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
            if ($result->score < 0.3) {
                return jsonresponse(false, __('NOTI_SOMETHING_WENT_WRONG'));
            }
        }
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'title' => 'required',
            'message' => 'required'
        ]);
        $requestData = $request->all();
        $admin = Admin::select('admin_username', 'admin_email')->where('admin_role_id', null)->first();
        
        /*send email code admin*/
        $data = EmailHelper::getEmailData('contact_page_enquiry');
        $priority = $data['body']->etpl_priority;
        $data['subject'] = $data['body']->etpl_subject;
        $data['body'] = str_replace('{name}', $requestData['name'], $data['body']->etpl_body);
        $data['body'] = str_replace('{email}', $requestData['email'], $data['body']);
        $data['body'] = str_replace('{adminName}', $admin->admin_username, $data['body']);
        $data['body'] = str_replace('{title}', $requestData['title'], $data['body']);
        $data['body'] = str_replace('{message}', $requestData['message'], $data['body']);
        $data['body'] = str_replace('{baseurl}', url('/'), $data['body']);
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']);
        $data['to'] = "sales@fatbit.com"; //$admin->admin_email onlyfordemo
        dispatch(new SendNotification(array('email' => $data)))->onQueue($priority);

        /* send email code buyer */
        $buyerEmaildata = EmailHelper::getEmailData('contact_us_buyer');
        $priority = $buyerEmaildata['body']->etpl_priority;
        $buyerEmaildata['subject'] = $buyerEmaildata['body']->etpl_subject;
        $buyerEmaildata['body'] = str_replace('{name}', $requestData['name'], $buyerEmaildata['body']->etpl_body);
        $buyerEmaildata['body'] = str_replace('{title}', $requestData['title'], $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{message}', $requestData['message'], $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{baseurl}', url('/'), $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{websiteName}', env('APP_NAME'), $buyerEmaildata['body']);
        $buyerEmaildata['body'] = str_replace('{contactUsEmail}', (getConfigValueByName('BUSINESS_EMAIL') ?? ''), $buyerEmaildata['body']);
        $buyerEmaildata['to'] = $requestData['email'];
        dispatch(new SendNotification(array('email' => $buyerEmaildata)))->onQueue($priority);
        return jsonresponse(true, __('Your enquiry has been received. Our team will get back to you shortly'));
    }

    public function showImage($section, $recordid, $subrecordid, $size, $timestamp = null)
    {
        AttachedFile::showFile($section, $recordid, $subrecordid, $size, $timestamp);
    }
    public function showImageBySize($section, $recordId, $wSize, $hSize)
    {
        $attachedFile = AttachedFile::getAttachedFile(AttachedFile::getConstantVal($section), $recordId);
        FileHelper::display($attachedFile->afile_physical_path, $size, AttachedFile::getConstantVal($section));
    }

    public function showImageById(Request $request,$afileId, $size = 'original')
    {
        $timestamp="";
        if( $request->has('t') ) {
            $timestamp = $request->query('t');
        }
        AttachedFile::showFileById($afileId, $size, $timestamp);
    }
    public function showProductImage($productId, $varientCode = 0, $size = '')
    {
        $time = '';
        if (isset($_GET['t'])) {
            $time = $_GET['t'];
        }
        $image = \Cache::remember('img-' . $productId . '-' . $varientCode . '-' . $time, 604800, function () use ($productId, $varientCode) {
            $subRecordId = 0;
            if ($varientCode != 0) {
                $product = Product::productById($productId, "prod_id, pov_code, " . Product::getPrice(), [], $varientCode);

                $subRecordId = productColorId($product);
            }

            $images = getProductImages($productId, $subRecordId);

            $image = '';
            if (!empty($images->first())) {
                $image = $images->first()->afile_physical_path;
            }
            return $image;
        });
        FileHelper::display($image, $size, 'PRODUCTIMAGES');
    }
    public function showProductImageForApp($productId, $varientId = 0, $size = '')
    {
        $time = '';
        if (isset($_GET['t'])) {
            $time = $_GET['t'];
        }
        $image = \Cache::remember('img-' . $productId . '-' . $varientId . '-' . $time, 604800, function () use ($productId, $varientId) {
            $subRecordId = 0;
            if ($varientId != 0) {
                $product = Product::productByVarientId($productId, "prod_id, pov_code, " . Product::getPrice(), [], $varientId);
                $subRecordId = productColorId($product);
            }

            $images = getProductImages($productId, $subRecordId);

            $image = '';
            if (!empty($images->first())) {
                $image = $images->first()->afile_physical_path;
            }
            return $image;
        });
        FileHelper::display($image, $size, 'PRODUCTIMAGES');
    }
    public function showNoImage($path)
    {
        AttachedFile::getNoImage($path);
    }

    public function dropzone(Request $request)
    {
        return jsonresponse(true, __('done'));
    }

    public function testEmail(Request $request)
    {
        $admin = Admin::select('admin_email')->where('admin_id', 1)->first();

        $data = EmailHelper::getEmailData('blank_template');
        $data['body'] = str_replace('{websiteName}', env('APP_NAME'), $data['body']->etpl_body);
        $data['body'] = str_replace('{message}', "This is a test email.", $data['body']);
        $emailStatus = EmailHelper::sendMailTemplate($admin->admin_email, "Test Email", $data);
        if ($emailStatus == 'failed') {
            return jsonresponse(false, __('Emails not configured properly on server.'));
        }
        return jsonresponse(true, __('Email Sucessfully send to the admin'));
    }
    public function getDeletePopup(Request $request, $id, $type)
    {
        $data = view('themes.' . config('theme') . '.partials.deletePopup', compact('id', 'type'))->render();
        return jsonresponse(true, '', $data);
    }

    public function checkLogin(Request $request)
    {
        return jsonresponse(false, '', loginPopup());
    }
    public function createPayment(Request $request, $id)
    {
        try {
            $decrypted = Crypt::decryptString($id);

            $order = explode('-', str_replace(config('app.salt'), '', $decrypted));
            $orderId = current($order);
            $paymentGateway = end($order);
            $paymentGateways = Package::getActivePaymentGateways('paymentGateways');
            $isCreditCard = false;
            $creditCard = $paymentGateways->where('pkg_card_type', config('constants.YES'))->first();
            if($creditCard){
                $paymentGateway = $creditCard->pkg_slug;
                $isCreditCard = true;
            }
            $order = Order::getRecordById($orderId);
            if (isOrderPaid($order->payment_status) == true) {
                toastr()->error(__('Order already paid.'));
                return redirect('/');
            }
            if (empty($order)) {
                abort(404);
            }
            return view('publicPaymentSummary', compact('order', 'paymentGateway','isCreditCard'));
        } catch (Exception $e) {
            abort(404);
        }
    }
    public function updateVariantRecords()
    {
        \App\Models\ProductOptionVarient::select('pov_id', 'pov_code', 'pov_prod_id')->orderBy('pov_id', 'ASC')->chunk(100, function ($records) {
            foreach ($records as  $record) {
                $explode = array_filter(explode('|', $record->pov_code));
                unset($explode[0]);
                sort($explode);
                $code =   $record->pov_prod_id . '|' . implode('|', $explode) . '|';
                \App\Models\ProductOptionVarient::where('pov_id', $record->pov_id)->update(['pov_code' => $code]);
            }
        });
    }

    public function imageResize(Request $request)
    {
        echo phpinfo();
        die;
        // create an image manager instance with favored driver
        $manager = new ImageManager(array('driver' => 'imagick'));

        // to finally create image instances
        $image = $manager->make('public/1580465590-fatbit-product14.jpg')->resize(300, 200);
        dd($image);
    }
}
