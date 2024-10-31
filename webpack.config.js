const Encore = require('@symfony/webpack-encore');
const path = require('path'); // Ajoute `path` pour que l'alias fonctionne

// Configure l'environnement runtime si nécessaire
if (!Encore.isRuntimeEnvironmentConfigured()) {
    Encore.configureRuntimeEnvironment(process.env.NODE_ENV || 'dev');
}

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .addEntry('app', './assets/app.js')
    .splitEntryChunks()
    .enableSingleRuntimeChunk()
    .enableStimulusBridge('./assets/controllers.json') // Garde cette ligne pour utiliser Stimulus
    .cleanupOutputBeforeBuild()
    .enableBuildNotifications()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .configureBabelPresetEnv((config) => {
        config.useBuiltIns = 'usage';
        config.corejs = '3.23';
    });

// Crée la configuration Webpack et ajoute l'alias
const config = Encore.getWebpackConfig();
config.resolve.alias['@symfony/stimulus-bridge/controllers.json'] = path.resolve(__dirname, 'assets/controllers.json');

module.exports = config;
