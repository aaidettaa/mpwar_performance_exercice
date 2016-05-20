var path = require('path');
var webpack = require('webpack');

module.exports = {
    entry: './web/assets/index.js',
    output: {
        path: path.join(__dirname, 'web/assets/js'),
        filename: 'bundle.min.js'
    },
    module: {
        loaders: [
            {
                test: /\.css$/,
                loader: "style-loader!css-loader"
            },
            {
                test: /\.(eot|svg|ttf|woff|woff2)$/,
                loader: "file?name=public/fonts/[name].[ext]"
            }
        ]
    },
    plugins: [
        // Avoid publishing files when compilation fails
        new webpack.NoErrorsPlugin(),
        new webpack.optimize.UglifyJsPlugin({minimize: true})
    ],
    stats: {
        // Nice colored output
        colors: true
    },
    // Create Sourcemaps for the bundle
    devtool: 'source-map',
};