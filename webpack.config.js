var Encore = require('@symfony/webpack-encore');

Encore
        // directory where compiled assets will be stored
        .setOutputPath('public/build/')
        // public path used by the web server to access the output path
        .setPublicPath('/build')
        // only needed for CDN's or sub-directory deploy
        //.setManifestKeyPrefix('build/')

        .copyFiles([{
                from: './node_modules/bootstrap-italia/dist/fonts',
                to: 'fonts/[path][name].[ext]',
            }, {
                from: './node_modules/bootstrap-italia/dist/svg',
                to: 'svg/[path][name].[ext]'
            }])
        /*
         * ENTRY CONFIG
         *
         * Add 1 entry for each "page" of your app
         * (including one that's included on every page - e.g. "app")
         *
         * Each entry will result in one JavaScript file (e.g. app.js)
         * and one CSS file (e.g. app.css) if you JavaScript imports CSS.
         */
        .addEntry('app', './assets/js/app.js')
        .addEntry('homepage', './assets/js/homepage.js')
        .addEntry('chart', './assets/js/chart.js')
        .addEntry('maps', './assets/js/maps.js')
        .addEntry('battery', './assets/js/battery.js')

        .addEntry('bootstrapitalia', './vendor/manzolo/bicore/assets/js/bootstrapitalia.js')
        .addEntry('bicore', './vendor/manzolo/bicore/assets/js/bicore.js')
        .addEntry('login', './vendor/manzolo/bicore/assets/js/login.js')
        .addEntry('bitabella', './vendor/manzolo/bicore/assets/js/bitabella.js')
        .addEntry('bidemo', './vendor/manzolo/bicore/assets/js/bidemo.js')
        .addEntry('adminpanel', './vendor/manzolo/bicore/assets/js/adminpanel.js')

        //.addEntry('page1', './assets/js/page1.js')
        //.addEntry('page2', './assets/js/page2.js')

        // When enabled, Webpack "splits" your files into smaller pieces for greater optimization.
        .splitEntryChunks()

        // will require an extra script tag for runtime.js
        // but, you probably want this, unless you're building a single-page app
        .enableSingleRuntimeChunk()

        /*
         * FEATURE CONFIG
         *
         * Enable & configure other features below. For a full
         * list of features, see:
         * https://symfony.com/doc/current/frontend.html#adding-more-features
         */
        .cleanupOutputBeforeBuild()
        .enableBuildNotifications()
        .enableSourceMaps(!Encore.isProduction())
        // enables hashed filenames (e.g. app.abc123.css)
        .enableVersioning(Encore.isProduction())
        .enableReactPreset()
        // enables @babel/preset-env polyfills
        .configureBabel(() => {
        }, {
            useBuiltIns: 'usage',
            corejs: 3
        })

        // enables Sass/SCSS support
        .enableSassLoader()

        // uncomment if you use TypeScript
        //.enableTypeScriptLoader()

        // uncomment to get integrity="..." attributes on your script & link tags
        // requires WebpackEncoreBundle 1.4 or higher
        //.enableIntegrityHashes()

        // uncomment if you're having problems with a jQuery plugin
        .autoProvidejQuery()

        // uncomment if you use API Platform Admin (composer req api-admin)
        //.enableReactPreset()
        //.addEntry('admin', './assets/js/admin.js')
        ;

module.exports = Encore.getWebpackConfig();
