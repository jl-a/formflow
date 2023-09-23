const path = require( 'path' )
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' )

module.exports = {
    entry: {
        admin: path.join( __dirname, '/assets/src/admin.tsx' ),
        frontend: path.join( __dirname, '/assets/src/frontend.ts' ),
    },
    output: {
        path: path.join( __dirname, '/assets/build' ),
        filename: '[name].js',
    },
    plugins: [],
    module: {
        rules: [
            {
                test: /\.(js|jsx|ts|tsx)$/,
                exclude: /node_modules/,
                loader: 'ts-loader'
            },
            {
                test: /\.(sa|sc|c)ss$/,
                use: [
                    MiniCssExtractPlugin.loader,
                    'css-loader',
                    'sass-loader'
                ],
            },
        ],
    },
    plugins: [
        new MiniCssExtractPlugin()
    ],
    resolve: {
        extensions: [ '.tsx', '.ts', '.jsx', '.js' ],
    }
}
