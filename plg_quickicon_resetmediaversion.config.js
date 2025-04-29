const entry = {
	"resetmediaversion": {
		import: ['./media/src/js/resetmediaversion.js'],
		filename: 'resetmediaversion.js',
	},
};

const webpackConfig = require('./webpack.config.js');
const publicPath = './media';
const production = webpackConfig(entry, publicPath);
const development = webpackConfig(entry, publicPath, 'development');

module.exports = [production, development]