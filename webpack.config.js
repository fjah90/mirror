const path = require('path');
const ExtractTextPlugin = require('extract-text-webpack-plugin');
const webpack = require('webpack');

module.exports = {
    entry: {
        app: './resources/assets/js/app.js',
        vendor: ['jquery', 'bootstrap-sass', 'sweetalert2', 'jszip', 'pdfmake/build/pdfmake.js', 'pdfmake/build/vfs_fonts.js', 'datatables.net-dt', 'datatables.net-buttons', 'datatables.net-buttons/js/buttons.flash.js', 'datatables.net-buttons/js/buttons.html5.js', 'datatables.net-buttons/js/buttons.print.js', 'datatables.net-responsive-dt', 'datatables.net-rowreorder-dt', 'uiv', 'bootstrap-fileinput', 'bootstrap-fileinput/themes/fa/theme.js', 'bootstrap-fileinput/js/locales/es.js', 'object-to-formdata', 'tinymce', '@tinymce/tinymce-vue', 'select2', 'vue-phone-number-input', 'vue-the-mask']
    },
    output: {
        path: path.resolve(__dirname, 'public'),
        filename: 'js/[name].js',
        publicPath: '/'
    },
    module: {
        rules: [
            {
                test: /\.js$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader'
                }
            },
            {
                test: /\.scss$/,
                use: ExtractTextPlugin.extract({
                    fallback: 'style-loader',
                    use: [
                        'css-loader',
                        'postcss-loader',
                        'resolve-url-loader',
                        'sass-loader'
                    ]
                })
            }
        ]
    },
    plugins: [
        new ExtractTextPlugin('css/[name].css'),
        new webpack.ProvidePlugin({
            $: 'jquery',
            jQuery: 'jquery',
            'window.jQuery': 'jquery',
            'window.$': 'jquery'
        })
    ]
};