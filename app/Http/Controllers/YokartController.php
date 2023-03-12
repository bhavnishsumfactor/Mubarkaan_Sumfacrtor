<?php
namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use App\Models\Theme;
use App\Models\Configuration;
use Auth;
use Session;
use Cache;
use Cookie;
use Illuminate\Http\Request;
use App\Models\Language;
use App\Models\Currency;
use Illuminate\Support\Facades\URL;

class YokartController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $cartSession;
    protected $googleVerification = 'https://www.google.com/recaptcha/api/siteverify';
    private $systemPrefix;
        
    public function __construct()
    {
        $this->systemPrefix = 'yk-';
        header('X-Powered-By: Yo!Kart');
        $this->middleware(function ($request, $next) {
            /*Theme settings*/
            if (empty($request->session()->get('theme'))) {
                $theme = Theme::getActiveTheme();
                $request->session()->put('theme', config('constants.THEME'));
            }
            config(['theme' => $request->session()->get('theme')]);

            config(['systemPrefix' => $this->systemPrefix]);
            
            $this->user = Auth::user();
            $this->admin_signed_in = Auth::guard('admin')->check();
            $this->signed_in = Auth::check();
            if (empty($request->session()->get('cartSession'))) {
                $request->session()->put('cartSession', $request->session()->get('_token'));
            }
            $this->cartSession = $request->session()->get('cartSession');
            
            if (!empty($request->input('currency'))) { /* if user changes currency on frontend */
                $currency = Currency::getRecordByCode($request->input('currency'));
                
                $this->setCurrencySession($request, $currency);
            }
        
            if (empty($request->input('currency'))) { // Commented because admin change the default currency need to check everytime
                
                if ($this->signed_in && !empty($this->user->user_currency_id)) {
                    $currency = Currency::getRecordById($this->user->user_currency_id);
                    $this->setCurrencySession($request, $currency);
                } elseif (empty($request->session()->get('currency'))) {
                    /* setting default view currency */
                    $viewDefaultCurrency = Currency::getDefault();
                    $this->setCurrencySession($request, $viewDefaultCurrency);
                }
            }
            
            if (!empty($request->get('language'))) { /* if user changes language on frontend */
                $language = Language::getLanguages(['lang_name', 'lang_code', 'lang_direction'], ['lang_code' => $request->get('language')])->first();
                $this->setLanguageSession($request, $language);
            } else {
                if ($this->signed_in && !empty($this->user->user_language_id) && empty($request->session()->get('language'))) { /* if loggedin user with language preferences */
                    $language = Language::getLanguages(['lang_name', 'lang_code', 'lang_direction'], ['lang_id' => $this->user->user_language_id])->first();
                    $this->setLanguageSession($request, $language);
                } elseif (empty($request->session()->get('language'))) {
                    /* setting default view language */
                    $viewDefaultLanguage = Language::getLanguages(['lang_name', 'lang_code', 'lang_direction'], ['lang_view_default' => config('constants.YES')])->first();
                    $this->setLanguageSession($request, $viewDefaultLanguage);
                }
            }
            
            if (!empty($this->user->user_cookie_preferences)) {
                config(['acceptcookie' => unserialize($this->user->user_cookie_preferences)]);
            } elseif (!empty($this->retrieveCookie('AcceptCookie'))) {
                config(['acceptcookie' => unserialize($this->retrieveCookie('AcceptCookie'))]);
            }

            Cache::rememberForever('configuration', function () {
                return Configuration::whereNotIn('conf_name', ['EMAIL_FOOTER_DEFAULT_HTML', 'EMAIL_FOOTER_HTML', 'PACKING_SLIP_HTML', '	
                PACKING_SLIP_HTML_ORIGINAL', 'PACKING_SLIP_HTML_REPLACEMENT'])->get()->pluck('conf_val', 'conf_name');
            });
            return $next($request);
        });
    }

    private function setLanguageSession($request, $language)
    {
        if (!empty($language)) {
            $languageData = [
                'code' => $language->lang_code,
                'name' => $language->lang_name,
                'direction' => $language->lang_direction
            ];
            $request->session()->put('language', $languageData);
        }
    }
    private function setCurrencySession($request, $currency)
    {
        if (!empty($currency)) {
            $currencyData = [
                'currency_code' => $currency->currency_code,
                'currency_symbol' => $currency->currency_symbol,
                'currency_position' => $currency->currency_position,
                'currency_value' => $currency->currency_value
            ];
            $request->session()->put('currency', $currencyData);
        }
    }

    public function googleRecaptchaVerification($captcha, $ip, $secretKey)
    {
        $data = [
            'secret' =>   $secretKey,
            'response' => $captcha,
            'remoteip' => $ip
        ];
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        $context = stream_context_create($options);
        $result = file_get_contents($this->googleVerification, false, $context);
        $resultJson = json_decode($result);
        return $resultJson;
    }

    public function makeCookie($key, $value, $options = [])
    {
        if (is_array($options) && count($options) > 0) {
            return call_user_func_array(array($this, "makeCookieWithOptions"), array_merge([$key, $value], $options));
        } else {
            return Cookie::queue($this->systemPrefix . $key, $value, config('app.expiredRemberToken'));
        }
    }

    public function makeCookieWithOptions($key, $value, $minutes, $path, $domain, $secure, $httpOnly)
    {
        return Cookie::make($this->systemPrefix . $key, $value, $minutes, $path, $domain, $secure, $httpOnly);
    }

    public function retrieveCookie($key)
    {
        return Cookie::get($this->systemPrefix . $key);
    }

    public function removeCookie($key)
    {
        return Cookie::forget($this->systemPrefix . $key);
    }

    public function ping()
    {
        $data = [
            'url' => URL::current()
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_URL, "https://google.com/");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
        ));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        $result = curl_exec($ch);
        if (!$result) {
            die("Connection Failure");
        }
        curl_close($ch);
    }

    public function setLanguage($request)
    {
        $language = \App\Models\Language::getLanguages(['lang_name', 'lang_code', 'lang_direction'], ['lang_code' => $request->get('language')])->first();
        $this->setLanguageSession($request, $language);
    }
}
