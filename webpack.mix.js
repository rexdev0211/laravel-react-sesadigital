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
 */

// mix.react('resources/js/app.js', 'public/js')
//     .sass('resources/sass/app.scss', 'public/css');

// updates
// mix.alias({
//     '@': '/resources/assets/js/src',
//     // '~': '/resources/assets/js/src',
//     // '@components': '/resources/assets/js/components',
// });

mix.webpackConfig({
    resolve: {
        alias: {
            '@': path.resolve(__dirname, 'resources/js/src/')
        }
    },
    output: {
        //filename: 'js/main/[name].js',
        chunkFilename: 'js/chunks/[name].js',
    },
    node: { fs: 'empty' }
});

mix.react('resources/js/app.js', 'public/js')
    .sass('resources/sass/app.scss', 'public/css');

mix.copy('resources/js/src/assets/images/', 'public/images/', false); // Don't flatten!


