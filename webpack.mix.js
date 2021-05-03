const mix = require('laravel-mix');
require("laravel-mix-vue-css-modules");
require("laravel-mix-alias");

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel applications. By default, we are compiling the CSS
 | file for the application as well as bundling up all the JS files.
 |
 */

// mix.options({
//     extractVueStyles: true,
// });

if (mix.inProduction()) {
    mix.options({
        terser: {
            terserOptions: {
                compress: {
                    warnings: false,
                    drop_console: true // 去除控制台输出代码
                },
                output: {
                    comments: false // 去除所有注释
                }
            }
        }
    });
}


mix
    .js('resources/js/app/home.js', 'public/js/app.home.min.js')
    .js('resources/js/app/user.js', 'public/js/app.user.min.js')
    .extract()
    .styles('resources/css/app.css', 'public/css/app.css')
    // .vueCssModules()
    .version()
