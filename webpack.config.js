const Encore = require('@symfony/webpack-encore');

// Manually configure the runtime environment if not already configured yet by the "encore" command.
// It's useful when you use tools that rely on webpack.config.js file.
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')

    .addEntry('app', './assets/js/app.js')
    .addEntry('import', './assets/js/import.js')
    .addEntry('checkout', './assets/js/checkout.js')
    .addEntry('studentselection', './assets/js/studentselection.js')
    .addEntry('bulkaction', './assets/js/bulkaction.js')
    .addStyleEntry('simple', './assets/css/simple.scss')

    .configureBabel(() => {}, {
        useBuiltIns: 'usage',
        corejs: 3
    })

    .disableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableSourceMaps(!Encore.isProduction())

    .enableSassLoader()
    .enablePostCssLoader()
    .enableVersioning(Encore.isProduction());

module.exports = Encore.getWebpackConfig();
