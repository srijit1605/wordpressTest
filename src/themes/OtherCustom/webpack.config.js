const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const RemoveEmptyScriptsPlugin = require('webpack-remove-empty-scripts')
const BrowserSyncPlugin = require('browser-sync-webpack-plugin')
var path = require('path')
const jspath = './js'
const sasspath = './sass'
const csspath = './css'
const outputpath = 'js/dist'
const localdomain = 'http://localhost'
const entrypoints = {
    'index': jspath + '/index.js',
    'style': sasspath + '/style.scss'
}

module.exports = {
    entry: entrypoints,
    output: {
        path: path.resolve(__dirname, outputpath),
        filename: '[name].min.js'
    },
    plugins: [
        new RemoveEmptyScriptsPlugin(),
        new MiniCssExtractPlugin({
            filename: '../../css/[name].min.css'
        }),
        new BrowserSyncPlugin({
            proxy: localdomain,
            files: [
                csspath + '**/*.css',
                outputpath + '/**/*.js',
                './**/*.php'
            ],
            injectCss: true,
            notify: false,
            open: true
        },
        {
            reload: true
        })
    ],
    watchOptions: {
        poll: 1000
    },
    module: {
        rules: [
            {
                test: /\.s?[c]ss$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ]
            },
            {
                test: /\.sass$/i,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    {
                        loader: 'sass-loader',
                        options: {
                            indentedSyntax: true
                        },

                    }
                ]
            }
        ]
    }
}