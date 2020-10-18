const Encore = require('@symfony/webpack-encore');
const path = require('path');

if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addAliases({
        '@': path.join(__dirname, 'assets', 'js'),
        '@theme': path.join(__dirname, 'assets', 'js', 'theme'),
        '@middleware': path.join(__dirname, 'assets', 'js', 'middleware'),
        '@store': path.join(__dirname, 'assets', 'js', 'store'),
        '@layouts': path.join(__dirname, 'assets', 'js', 'layouts'),
        '@pages': path.join(__dirname, 'assets', 'js', 'pages'),
        '@components': path.join(__dirname, 'assets', 'js', 'components')
    })
    .copyFiles({
        from: './assets/images',
        to: Encore.isProduction()
            ? 'images/[path][name].[hash:8].[ext]'
            : 'images/[path][name].[ext]',
    })
    .enableReactPreset()
    .addEntry('app', './assets/js/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = 3;
    })
;

module.exports = Encore.getWebpackConfig();
