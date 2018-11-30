var Encore = require('@symfony/webpack-encore');
const webpack = require('webpack');
const UglifyJsPlugin = require('uglifyjs-webpack-plugin')

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/build/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    // .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/easy_admin', './assets/js/easy_admin.js')
    // .addEntry('js/frontend', './assets/js/frontend.js')
    .addEntry('js/login', './assets/js/login.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
    // .addStyleEntry('css/frontend', './assets/css/frontend.scss')


    // uncomment if you use Sass/SCSS files
    // .enableSassLoader()
    // .enableSassLoader(function(sassOptions) {}, {
    //     resolveUrlLoader: false
    // })

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

// build the first configuration
const devConfig = Encore.getWebpackConfig();

// Set a unique name for the config (needed later!)
devConfig.name = 'devConfig';

// reset Encore to build the second config
Encore.reset();

Encore
    // the project directory where compiled assets will be stored
    .setOutputPath('public/buildproduction/')
    // the public path used by the web server to access the previous directory
    .setPublicPath('/buildproduction')
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())
    // uncomment to create hashed filenames (e.g. app.abc123.css)
    .enableVersioning(Encore.isProduction())

    // uncomment to define the assets of the project
    .addEntry('js/easy_admin', './assets/js/easy_admin.js')
    // .addEntry('js/frontend', './assets/js/frontend.js')
    .addEntry('js/login', './assets/js/login.js')
    // .addStyleEntry('css/app', './assets/css/app.scss')
    // .addStyleEntry('css/frontend', './assets/css/frontend.scss')


    // uncomment if you use Sass/SCSS files
    // .enableSassLoader()
    // .enableSassLoader(function(sassOptions) {}, {
    //     resolveUrlLoader: false
    // })

    // uncomment for legacy applications that require $/jQuery as a global variable
    // .autoProvidejQuery()
;

// build the second configuration
const prodConfig = Encore.getWebpackConfig();

// Remove the old version first
prodConfig.plugins = prodConfig.plugins.filter(
    plugin => !(plugin instanceof webpack.optimize.UglifyJsPlugin)
);

// Add the new one
prodConfig.plugins.push(new UglifyJsPlugin());

// Set a unique name for the config (needed later!)
prodConfig.name = 'prodConfig';

// export the final configuration as an array of multiple configurations
if (Encore.isProduction()) {
    //production
    module.exports = prodConfig;
} else {
    //dev
    module.exports = devConfig;
}