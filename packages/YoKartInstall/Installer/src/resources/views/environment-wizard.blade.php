@extends('installer::layouts.master')

@section('template_title')
    {{ trans('Step 3 | Environment Settings | Guided Wizard') }}
@endsection

@section('title')
    <i class="fa fa-magic fa-fw" aria-hidden="true"></i>
    {!! trans('Guided <code>.env</code> Wizard') !!}
@endsection

@section('container')
    <div class="tabs tabs-full">

        <input id="tab1" type="radio" name="tabs" class="tab-input" checked />
        <label for="tab1" class="tab-label">
            <i class="fa fa-cog fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('Environment') }}
        </label>

        <input id="tab2" type="radio" name="tabs" class="tab-input" />
        <label for="tab2" class="tab-label">
            <i class="fa fa-database fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('Database') }}
        </label>
        <input id="tab3" type="radio" name="tabs" class="tab-input" />
        <label for="tab3" class="tab-label">
            <i class="fa fa-user fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('Admin') }}
        </label>

        <!--<input id="tab4" type="radio" name="tabs" class="tab-input" />
        <label for="tab4" class="tab-label">
            <i class="fa fa-cogs fa-2x fa-fw" aria-hidden="true"></i>
            <br />
            {{ trans('Application') }}
        </label>-->

        <form method="post" action="{{ route('Installer::environmentSaveWizard') }}" class="form tabs-wrap" enctype ='multipart/form-data'>
            <div class="tab" id="tab1content">
                <input type="hidden" name="_token" value="{{ csrf_token() }}">

                <div class="form-group {{ $errors->has('app_name') ? ' has-error ' : '' }}">
                    <label for="app_name">
                        {{ trans('App Name') }}
                    </label>
                    <input type="text" name="app_name" id="app_name" value="{{ old('app_name') }}" placeholder="{{ trans('App Name') }}" />
                    @if ($errors->has('app_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_name') }}
                        </span>
                    @endif
                </div>

                {{--<div class="form-group {{ $errors->has('environment') ? ' has-error ' : '' }}">
                    <label for="environment">
                        {{ trans('App Environment') }}
                    </label>
                    <select name="environment" id="environment" value="{{ old('environment') }}" onchange='checkEnvironment(this.value);'>
                        <option value="local" @if(old('environment') == "local") {{ 'selected' }} @else {{ 'selected' }} @endif>{{ trans('Local') }}</option>
                        <option value="development" @if(old('environment') == "development") {{ 'selected' }} @endif>{{ trans('Development') }}</option>
                        <option value="qa" @if(old('environment') == "qa") {{ 'selected' }} @endif>{{ trans('Qa') }}</option>
                        <option value="production" @if(old('environment') == "production") {{ 'selected' }} @endif>{{ trans('Production') }}</option>
                        <option value="other" @if(old('environment') == "other") {{ 'selected' }} @endif>{{ trans('Other') }}</option>  
                    </select>
                    <div id="environment_text_input" style="display: none;">
                        <input type="text" name="environment_custom" id="environment_custom" placeholder="{{ trans('Enter your environment') }}..."/>
                    </div>
                    @if ($errors->has('app_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('app_debug') ? ' has-error ' : '' }}">
                    <label for="app_debug">
                        {{ trans('App Debug') }}
                    </label>
                    <div class="row-action">
                        <label class="radio" for="app_debug_true">
                            <input type="radio" name="app_debug" id="app_debug_true" value=true checked />
                            {{ trans('True') }}
                        </label>
                        <label class="radio" for="app_debug_false">
                            <input type="radio" name="app_debug" id="app_debug_false" value=false />
                            {{ trans('False') }}
                        </label>
                    </div>
                    @if ($errors->has('app_debug'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_debug') }}
                        </span>
                    @endif
                </div>--}}

                
                {{--<div class="form-group {{ $errors->has('app_log_level') ? ' has-error ' : '' }}">
                    <label for="app_log_level">
                        {{ trans('App Log Level') }}
                    </label>
                    <select name="app_log_level" id="app_log_level" value="{{ old('app_log_level') }}">
                        <option value="debug" selected>{{ trans('debug') }}</option>
                        <option value="info">{{ trans('info') }}</option>
                        <option value="notice">{{ trans('notice') }}</option>
                        <option value="warning">{{ trans('warning') }}</option>
                        <option value="error">{{ trans('error') }}</option>
                        <option value="critical">{{ trans('critical') }}</option>
                        <option value="alert">{{ trans('alert') }}</option>
                        <option value="emergency">{{ trans('emergency') }}</option>
                    </select>
                    @if ($errors->has('app_log_level'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_log_level') }}
                        </span>
                    @endif
                </div>--}}

                <div class="form-group {{ $errors->has('app_url') ? 'has-error' : '' }}">
                    <label for="app_url">
                        {{ trans('App Url') }}
                    </label>
                    <input type="url" name="app_url" id="app_url" value="{{ !empty(old('app_url')) ? old('app_url') : 'http://localhost' }} " placeholder="{{ trans('App Url') }}" />
                    @if ($errors->has('app_url'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('app_url') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('dummy_data') ? ' has-error ' : '' }}">
                    <label for="dummy_data">
                        {{ trans('Install Dummy Data') }}
                    </label>
                    <div class="row-action">
                        <label class="radio" for="dummy_data_true">
                            <input type="radio" name="dummy_data" id="dummy_data_true" value=true checked />
                            {{ trans('True') }}
                        </label>
                        <label class="radio" for="dummy_data_false">
                            <input type="radio" name="dummy_data" id="dummy_data_false" value=false />
                            {{ trans('False') }}
                        </label>
                    </div>
                    @if ($errors->has('dummy_data'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('dummy_data') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showDatabaseSettings();return false">
                        {{ trans('Setup Database') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="tab" id="tab2content">

                {{-- <div class="form-group {{ $errors->has('database_connection') ? ' has-error ' : '' }}">
                    <label for="database_connection">
                        {{ trans('Database Connection') }}
                    </label>
                    <select name="database_connection" id="database_connection" value="{{ old('database_connection') }}">
                        <option value="mysql" selected>{{ trans('mysql') }}</option>
                        <option value="sqlite">{{ trans('sqlite') }}</option>
                        <option value="pgsql">{{ trans('pgsql') }}</option>
                        <option value="sqlsrv">{{ trans('sqlsrv') }}</option>
                    </select>
                    @if ($errors->has('database_connection'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_connection') }}
                        </span>
                    @endif
                </div>--}}

                <div class="form-group {{ $errors->has('database_hostname') ? ' has-error ' : '' }}">
                    <label for="database_hostname">
                        {{ trans('Database Host') }}
                    </label>
                    <input type="text" name="database_hostname" id="database_hostname" value="127.0.0.1" placeholder="{{ trans('Database Host') }}" />
                    @if ($errors->has('database_hostname'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_hostname') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_port') ? ' has-error ' : '' }}">
                    <label for="database_port">
                        {{ trans('Database Port') }}
                    </label>
                    <input type="number" name="database_port" id="database_port" value="{{ !empty(old('database_port')) ? old('database_port') : '3306' }}" placeholder="{{ trans('Database Port') }}" />
                    @if ($errors->has('database_port'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_port') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_name') ? ' has-error ' : '' }}">
                    <label for="database_name">
                        {{ trans('Database Name') }}
                    </label>
                    <input type="text" name="database_name" id="database_name" value="{{ old('database_name') }}" placeholder="{{ trans('Database Name') }}" />
                    @if ($errors->has('database_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_name') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_username') ? ' has-error ' : '' }}">
                    <label for="database_username">
                        {{ trans('Database User Name') }}
                    </label>
                    <input type="text" name="database_username" id="database_username" value="{{ old('database_username') }}" placeholder="{{ trans('Database User Name') }}" />
                    @if ($errors->has('database_username'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_username') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('database_password') ? ' has-error ' : '' }}">
                    <label for="database_password">
                        {{ trans('Database Password') }}
                    </label>
                    <input type="password" name="database_password" id="database_password" value="{{ old('database_password') }}" placeholder="{{ trans('Database Password') }}" />
                    @if ($errors->has('database_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('database_password') }}
                        </span>
                    @endif
                </div>

                <div class="buttons">
                    <button class="button" onclick="showAdminSettings();return false">
                        {{ trans('Setup Admin') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <div class="tab" id="tab3content">
                <div class="form-group {{ $errors->has('business_name') ? ' has-error ' : '' }}">
                    <label for="business_name">
                        {{ trans('Business Name') }}
                    </label>
                    <input type="text" name="business_name" id="business_name" value="" placeholder="{{ trans('Please Enter Business Name') }}" />
                    @if ($errors->has('business_name'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('business_name') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('brand_color') ? ' has-error ' : '' }}">
                    <label for="brand_color">
                        {{ trans('Brand Color') }}
                    </label>
                    <div class=" colorpicker colorpicker-component"> 
                        <input type="text" value="#00AABB" name="brand_color" class="form-control input-group-addon" /> 
                        <!-- <span class="input-group-addon"><i></i></span>  -->
                    </div>
                    @if ($errors->has('brand_color'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('brand_color') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('admin_username') ? ' has-error ' : '' }}">
                    <label for="admin_username">
                        {{ trans('Admin Username') }}
                    </label>
                    <input type="text" name="admin_username" id="admin_username" value="{{ old('admin_username') }}" placeholder="{{ trans('Please Enter Admin Username') }}" />
                    @if ($errors->has('admin_username'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_username') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('admin_email') ? ' has-error ' : '' }}">
                    <label for="admin_email">
                        {{ trans('Admin Email') }}
                    </label>
                    <input type="text" name="admin_email" id="admin_email" value="{{ old('admin_email') }}" placeholder="{{ trans('Please Enter Admin Email') }}" />
                    @if ($errors->has('admin_email'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_email') }}
                        </span>
                    @endif
                </div>

                <div class="form-group {{ $errors->has('admin_password') ? ' has-error ' : '' }}">
                    <label for="admin_password">
                        {{ trans('Admin Password') }}
                    </label>
                    <input type="password" name="admin_password" id="admin_password" value="" placeholder="{{ trans('Please Enter Admin Password') }}" />
                    @if ($errors->has('admin_password'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_password') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('admin_site_logo') ? ' has-error ' : '' }}">
                    <label for="admin_site_logo_label">
                        {{ trans('Site Logo') }}
                    </label>
                    <input type="file" name="admin_site_logo" id="admin_site_logo" value="" />
                    <span>{{ trans('Please Select Logo 1:1 with 400 x 400 px') }}</span>
                    @if ($errors->has('admin_site_logo'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_site_logo') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('admin_default_currency') ? ' has-error ' : '' }}">
                    <label for="admin_default_currency">
                        {{ trans('Select Default Currency') }}
                    </label>
                    <select name="admin_default_currency" id="admin_default_currency" value="{{ old('admin_default_currency') }}">
                        @foreach($currencies as $key => $currency)
                            <option value="{{ $currency['currency_id'] }}">{{ $currency['currency_name'] }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('admin_default_currency'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_default_currency') }}
                        </span>
                    @endif
                </div>
                <div class="form-group {{ $errors->has('admin_default_lang') ? ' has-error ' : '' }}">
                    <label for="admin_default_lang">
                        {{ trans('Select Default Lang') }}
                    </label>
                    <select name="admin_default_lang" id="admin_default_lang" value="{{ old('admin_default_lang') }}">
                        @foreach($languages as $key => $language)
                            <option value="{{ $language['lang_id'] }}">{{ $language['lang_name'] }}</option>
                        @endforeach
                    </select>
                    @if ($errors->has('admin_default_lang'))
                        <span class="error-block">
                            <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                            {{ $errors->first('admin_default_lang') }}
                        </span>
                    @endif
                </div>
                <div class="buttons">
                    <button class="button" type="submit">
                        {{ trans('Install') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>
            <!--<div class="tab" id="tab4content">
                <div class="block">
                    <input type="radio" name="appSettingsTabs" id="appSettingsTab1" value="null" checked />
                    <label for="appSettingsTab1">
                        <span>
                            {{ trans('Broadcasting, Caching, Session, and Queue') }}
                        </span>
                    </label>
                    <div class="info">
                        <div class="form-group {{ $errors->has('broadcast_driver') ? ' has-error ' : '' }}">
                            <label for="broadcast_driver">{{ trans('Broadcast Driver') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/broadcasting" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="broadcast_driver" id="broadcast_driver" value="log" placeholder="{{ trans('Broadcast Driver') }}" />
                            @if ($errors->has('broadcast_driver'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('broadcast_driver') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('cache_driver') ? ' has-error ' : '' }}">
                            <label for="cache_driver">{{ trans('Cache Driver') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/cache" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="cache_driver" id="cache_driver" value="file" placeholder="{{ trans('Cache Driver') }}" />
                            @if ($errors->has('cache_driver'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('cache_driver') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('session_driver') ? ' has-error ' : '' }}">
                            <label for="session_driver">{{ trans('Session Driver') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/session" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="session_driver" id="session_driver" value="file" placeholder="{{ trans('Session Driver') }}" />
                            @if ($errors->has('session_driver'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('session_driver') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('queue_driver') ? ' has-error ' : '' }}">
                            <label for="queue_driver">{{ trans('Queue Driver') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/queues" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="queue_driver" id="queue_driver" value="sync" placeholder="{{ trans('Queue Driver') }}" />
                            @if ($errors->has('queue_driver'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('queue_driver') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="block">
                    <input type="radio" name="appSettingsTabs" id="appSettingsTab2" value="null"/>
                    <label for="appSettingsTab2">
                        <span>
                            {{ trans('Redis Driver') }}
                        </span>
                    </label>
                    <div class="info">
                        <div class="form-group {{ $errors->has('redis_hostname') ? ' has-error ' : '' }}">
                            <label for="redis_hostname">
                                {{ trans('Redis Host') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/redis" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="redis_hostname" id="redis_hostname" value="127.0.0.1" placeholder="{{ trans('Redis Host') }}" />
                            @if ($errors->has('redis_hostname'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('redis_hostname') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('redis_password') ? ' has-error ' : '' }}">
                            <label for="redis_password">{{ trans('Redis Password') }}</label>
                            <input type="password" name="redis_password" id="redis_password" value="null" placeholder="{{ trans('Redis Password') }}" />
                            @if ($errors->has('redis_password'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('redis_password') }}
                                </span>
                            @endif
                        </div>

                        <div class="form-group {{ $errors->has('redis_port') ? ' has-error ' : '' }}">
                            <label for="redis_port">{{ trans('Redis Port') }}</label>
                            <input type="number" name="redis_port" id="redis_port" value="6379" placeholder="{{ trans('Redis Port') }}" />
                            @if ($errors->has('redis_port'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('redis_port') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="block">
                    <input type="radio" name="appSettingsTabs" id="appSettingsTab3" value="null"/>
                    <label for="appSettingsTab3">
                        <span>
                            {{ trans('Mail') }}
                        </span>
                    </label>
                    <div class="info">
                        <div class="form-group {{ $errors->has('mail_driver') ? ' has-error ' : '' }}">
                            <label for="mail_driver">
                                {{ trans('Mail Driver') }}
                                <sup>
                                    <a href="https://laravel.com/docs/5.4/mail" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="mail_driver" id="mail_driver" value="smtp" placeholder="{{ trans('Mail Driver') }}" />
                            @if ($errors->has('mail_driver'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_driver') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('mail_host') ? ' has-error ' : '' }}">
                            <label for="mail_host">{{ trans('Mail Host') }}</label>
                            <input type="text" name="mail_host" id="mail_host" value="smtp.mailtrap.io" placeholder="{{ trans('Mail Host') }}" />
                            @if ($errors->has('mail_host'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_host') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('mail_port') ? ' has-error ' : '' }}">
                            <label for="mail_port">{{ trans('Mail Port') }}</label>
                            <input type="number" name="mail_port" id="mail_port" value="2525" placeholder="{{ trans('Mail Port') }}" />
                            @if ($errors->has('mail_port'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_port') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('mail_username') ? ' has-error ' : '' }}">
                            <label for="mail_username">{{ trans('Mail Username') }}</label>
                            <input type="text" name="mail_username" id="mail_username" value="null" placeholder="{{ trans('Mail Username') }}" />
                            @if ($errors->has('mail_username'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_username') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('mail_password') ? ' has-error ' : '' }}">
                            <label for="mail_password">{{ trans('Mail Password') }}</label>
                            <input type="text" name="mail_password" id="mail_password" value="null" placeholder="{{ trans('Mail Password') }}" />
                            @if ($errors->has('mail_password'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_password') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('mail_encryption') ? ' has-error ' : '' }}">
                            <label for="mail_encryption">{{ trans('Mail Encryption') }}</label>
                            <input type="text" name="mail_encryption" id="mail_encryption" value="null" placeholder="{{ trans('Mail Encryption') }}" />
                            @if ($errors->has('mail_encryption'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('mail_encryption') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="block margin-bottom-2">
                    <input type="radio" name="appSettingsTabs" id="appSettingsTab4" value="null"/>
                    <label for="appSettingsTab4">
                        <span>
                            {{ trans('Pusher') }}
                        </span>
                    </label>
                    <div class="info">
                        <div class="form-group {{ $errors->has('pusher_app_id') ? ' has-error ' : '' }}">
                            <label for="pusher_app_id">
                                {{ trans('Pusher App Id') }}
                                <sup>
                                    <a href="https://pusher.com/docs/server_api_guide" target="_blank" title="{{ trans('More Info_info') }}">
                                        <i class="fa fa-info-circle fa-fw" aria-hidden="true"></i>
                                        <span class="sr-only">{{ trans('More Info_info') }}</span>
                                    </a>
                                </sup>
                            </label>
                            <input type="text" name="pusher_app_id" id="pusher_app_id" value="" placeholder="{{ trans('Pusher App Id') }}" />
                            @if ($errors->has('pusher_app_id'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('pusher_app_id') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('pusher_app_key') ? ' has-error ' : '' }}">
                            <label for="pusher_app_key">{{ trans('Pusher App Key') }}</label>
                            <input type="text" name="pusher_app_key" id="pusher_app_key" value="" placeholder="{{ trans('Pusher App Key') }}" />
                            @if ($errors->has('pusher_app_key'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('pusher_app_key') }}
                                </span>
                            @endif
                        </div>
                        <div class="form-group {{ $errors->has('pusher_app_secret') ? ' has-error ' : '' }}">
                            <label for="pusher_app_secret">{{ trans('Pusher App Secret') }}</label>
                            <input type="password" name="pusher_app_secret" id="pusher_app_secret" value="" placeholder="{{ trans('Pusher App Secret') }}" />
                            @if ($errors->has('pusher_app_secret'))
                                <span class="error-block">
                                    <i class="fa fa-fw fa-exclamation-triangle" aria-hidden="true"></i>
                                    {{ $errors->first('pusher_app_secret') }}
                                </span>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="buttons">
                    <button class="button" type="submit">
                        {{ trans('Install') }}
                        <i class="fa fa-angle-right fa-fw" aria-hidden="true"></i>
                    </button>
                </div>
            </div>-->
        </form>

    </div>
@endsection

@section('scripts')

    <script src="{{ asset('admin/js/jquery.min.js') }}"></script>
    
  <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/css/bootstrap-colorpicker.min.css" rel="stylesheet">
<style>
div.colorpicker.dropdown-menu.colorpicker-with-alpha.colorpicker-right.colorpicker-visible{
    position:absolute;
}
.colorpicker input{color:white;}
.row-action {
    display: flex;
}

.row-action label {
    margin-right: 10px;
    margin-bottom: 0;
}

.row-action label input[type=radio] {
    margin-right: 4px;
}
</style>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-colorpicker/2.5.1/js/bootstrap-colorpicker.min.js"></script>  
<script >
        $('.colorpicker').colorpicker();
        function checkEnvironment(val) {
            var element=document.getElementById('environment_text_input');
            if(val=='other') {
                element.style.display='block';
            } else {
                element.style.display='none';
            }
        }
        function showDatabaseSettings() {
            document.getElementById('tab2').checked = true;
        }
        function showAdminSettings() {
            document.getElementById('tab3').checked = true;
        }        
    </script>
@endsection
