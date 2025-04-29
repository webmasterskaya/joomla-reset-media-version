//
// Web pack configuration
//
// ========================================================================

const path = require('path');
const TerserPlugin = require('terser-webpack-plugin');
const {CleanWebpackPlugin} = require('clean-webpack-plugin');

function prepareEntry(source, mode) {
	const entry = {};
	Object.keys(source).forEach((k, i) => {
		let key = JSON.parse(JSON.stringify(k)).replace(/^js\//, ''),
			data = JSON.parse(JSON.stringify(source[k]));

		let bundle = (typeof data === 'object' && data.import) ? data : {import: data};
		bundle.filename = (bundle.filename) ? bundle.filename.replace(/^js\//, '') : key
		bundle.filename = 'js/' + bundle.filename;
		if (!/\.(js|clean|delete|skip)$/.test(bundle.filename)) bundle.filename += '.js';
		if (mode === 'production') bundle.filename = bundle.filename.replace('.js', '.min.js');

		let entrypoint = key.replace(/\.(.?)*$/, '');
		if (entry[entrypoint]) entrypoint = entrypoint + '_' + i;

		entry[entrypoint] = bundle;
	});

	return entry;
}

function webpackConfig(entry, publicPath, mode) {
	if (!publicPath) publicPath = './';
	if (!mode) mode = 'production';

	return {
		mode: (mode === 'production') ? 'production' : 'development',
		entry: prepareEntry(entry, mode),
		output: {
			path: path.resolve(__dirname, publicPath),
		},
		devtool: (mode === 'production') ? false : 'inline-source-map',
		optimization: {
			minimize: (mode === 'production'),
			minimizer: (mode === 'production') ? [
				new TerserPlugin({
					test: /\.js(\?.*)?$/i,
					// cache: true,
					parallel: true,
					// sourceMap: false,
					terserOptions: {
						compress: {
							pure_getters: true,
							unsafe_comps: true,
							unsafe: true,
							passes: 2,
							keep_fargs: false,
							drop_console: true
						},
						output: {
							beautify: false,
							comments: false,
						},
					},
					extractComments: false,
				}),
			] : []
		},
		module: {
			rules: [
				{
					test: /\.(es6|js)$/,
					use: ['babel-loader'],
				}
			]
		},
		plugins: [
			new CleanWebpackPlugin({
				cleanOnceBeforeBuildPatterns: [],
				cleanAfterEveryBuildPatterns: ['./**/*.clean', './**/*.delete', './**/*.skip'],
			}),
		],
	}
}

module.exports = webpackConfig;