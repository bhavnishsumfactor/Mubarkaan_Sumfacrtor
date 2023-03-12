const mix = require('laravel-mix')
const path = require('path')
const argv = require('yargs').argv;

/*
|--------------------------------------------------------------------------
| Mix Asset Management
|--------------------------------------------------------------------------
|
| Mix provides a clean, fluent API for defining some Webpack build steps
| for your Laravel application. By default, we are compiling the Sass
| file for the application as well as bundling up all the JS files.
|
*/
if (argv.user) {

    mix.js('resources/js/dashboard.js', 'js/build/app.js')
        .setPublicPath(path.normalize('public/dashboard')).autoload({
            toastr: 'toastr',
            'vue-toastr-2': ['VueToastr2', 'window.VueToastr2'],
        }).webpackConfig({
            output: {
                chunkFilename: 'js/build/[name].[chunkhash].js'
            },
            resolve: {
                alias: {
                    vue$: 'vue/dist/vue.runtime.esm.js',
                    '@': path.resolve('resources/js'),
                },
            },
        });

} else if (argv.admin) {
    mix.js(['resources/js/app.js'], 'public/admin/js/build/app.js')
        .setPublicPath(path.normalize('public/admin')).autoload({
            jquery: ['$', 'jQuery', 'jquery', 'window.jQuery'],
            moment: 'moment',
            vue: ['Vue', 'window.Vue'],
            toastr: 'toastr',
            'vue-resource': ['VueResource', 'window.VueResource'],
            'vue-toastr-2': ['VueToastr2', 'window.VueToastr2'],
            'vee-validate': ['VeeValidate', 'window.VeeValidate']
        }).version().webpackConfig({
            output: {
                chunkFilename: 'js/build/[name].[chunkhash].js'
            },
        });
} else if (argv.css) {

    mix.sass('resources/sass/installer/main-ltr.scss', 'public/installer/css/main-ltr.css')
        .sass('resources/sass/installer/main-rtl.scss', 'public/installer/css/main-rtl.css')
        .sass('resources/sass/admin/main-ltr.scss', 'public/admin/css/main-ltr.css')
        .sass('resources/sass/admin/main-rtl.scss', 'public/admin/css/main-rtl.css')
        .sass('resources/sass/dashboard/main-ltr.scss', 'public/dashboard/css/main-ltr.css')
        .sass('resources/sass/dashboard/main-rtl.scss', 'public/dashboard/css/main-rtl.css')
        .sass('resources/sass/blogs/main-ltr.scss', 'public/blogs/css/main-ltr.css')
        .sass('resources/sass/blogs/main-rtl.scss', 'public/blogs/css/main-rtl.css')
        .sass('resources/sass/cmsEditor/main-ltr.scss', 'public/cmsEditor/css/main-ltr.css')
        .sass('resources/sass/cmsEditor/main-rtl.scss', 'public/cmsEditor/css/main-rtl.css')
        .sass('resources/sass/themes/fashion/main-ltr.scss', 'public/yokart/fashion/css/main-ltr.css')
        .sass('resources/sass/themes/fashion/main-rtl.scss', 'public/yokart/fashion/css/main-rtl.css')
        .options({
            processCssUrls: false,
        });

} else if (argv.installer) {
    mix.js(['resources/js/installer.js'], 'public/installer/js/build/installer.js').autoload({
        jquery: ['$', 'jQuery', 'jquery', 'window.jQuery'],
        moment: 'moment',
        vue: ['Vue', 'window.Vue'],
        toastr: 'toastr',
        'vue-resource': ['VueResource', 'window.VueResource'],
        'vue-toastr-2': ['VueToastr2', 'window.VueToastr2'],
        'vee-validate': ['VeeValidate', 'window.VeeValidate']
    }).version();
}
else if(argv.mix){   
   mix.combine([
      
       'public/admin/vendors/popper.min.js',
       'public/vendors/js/bootstrap.min.js',
       'public/vendors/js/nav.js',
       'public/vendors/js/slick.min.js',
       'public/vendors/js/sliders.js',
       'public/vendors/js/form-validation.js',
       'public/vendors/js/dev.js',
       'public/vendors/js/cart.js',
       'public/vendors/js/ui-functions.js',
       'public/vendors/js/toastr.min.js',
       'public/vendors/js/jquery.validate.min.js',
       'public/vendors/js/nprogress.min.js',
       'public/vendors/js/perfect-scrollbar.min.js',
       'public/vendors/js/intlTelInput.min.js',
       'public/vendors/js/jquery.inputmask.js',
   ], 'public/vendors/js/combined.js');    
   mix.combine([
       'public/vendors/css/dev.css',
       'public/vendors/css/toastr.min.css',
       'public/vendors/css/perfect-scrollbar.css',
       'public/vendors/css/jquery-ui.css',
       'public/vendors/css/intlTelInput.min.css'
   ], 'public/vendors/css/combined.css');
}

const TerserPlugin = require('terser-webpack-plugin');

module.exports = {
    optimization: {
        minimizer: [
            new TerserPlugin({
                cache: true,
                parallel: true,
                sourceMap: false
            }),
        ],
    }
};