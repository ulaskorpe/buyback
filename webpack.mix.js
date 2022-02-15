const mix = require('laravel-mix');

/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |


// mix.js('resources/js/app.js', 'public/js')
//     .react()
//     .sass('resources/sass/app.scss', 'public/css');

mix.options({
    postCss:[require('autoprefixer'),
    ],
})

mix.setPublicPath('public');

mix.webpackConfig({
   resolve:{
       extensions:['.js','.vue'],
       alias:{
            '@': __dirname+'resources'
       }
   } ,
    output:{
       chunkFilename:'js/chunks/[name].js',
    }
}).react();*/

mix.js('resources/js/app.js','public/js').react().postCss('resources/css/app.css','public/css');
//mix.copy('resources/react-site/public','public');
