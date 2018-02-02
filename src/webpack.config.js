var path = require('path')
var webpack = require('webpack')
var ExtractTextPlugin = require('extract-text-webpack-plugin')
var HtmlWebpackPlugin = require('html-webpack-plugin')
var HtmlWebpackHarddiskPlugin = require('html-webpack-harddisk-plugin')
const SpriteLoaderPlugin = require('svg-sprite-loader/plugin')
// const { replaceInModuleSource, getAllModules } = require('svg-sprite-loader/lib/utils');
var OptimizeCSSPlugin = require('optimize-css-assets-webpack-plugin')
const CleanWebpackPlugin = require('clean-webpack-plugin')
var svgoplugins = require('./build/svgoplugins')
var FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const WriteFilePlugin = require('write-file-webpack-plugin')
const chalk = require('chalk')
const pkg = require('./package.json')
var ip = require('ip');

// CONFIG
const useLocalIpAddress = false;

let publicPath = useLocalIpAddress ? `http://${ip.address()}:8080/` : 'http://localhost:8080/'
// publicPath = 'http://192.168.178.47:8080/dist/' // für lokales Netzwerk-Testen
// if(process.env.NODE_ENV === 'production') publicPath = '<?=$config->urls->templates?>dist/'
if (process.env.NODE_ENV === 'production') publicPath = '/site/templates/dist/'

console.log(chalk`For local testing, use: {green ${publicPath}}`)

const sassResourceLoader = {
  loader: 'sass-resources-loader',
  options: {resources: [
    path.resolve(__dirname, './scss/constants.scss'),
    path.resolve(__dirname, './scss/easing.scss'),
    path.resolve(__dirname, './node_modules/family.scss/source/src/_family.scss'),
    path.resolve(__dirname, './node_modules/include-media/dist/_include-media.scss')
  ]}
}

// let scssLoader = 'style-loader?sourceMap!css-loader?sourceMap!postcss-loader?sourceMap!sass-loader?sourceMap'
let scssLoader = ['css-loader', 'postcss-loader', 'sass-loader', sassResourceLoader]

if (process.env.NODE_ENV === 'production') scssLoader = ExtractTextPlugin.extract({ use: scssLoader })
if (process.env.NODE_ENV === 'development') scssLoader.unshift('style-loader')

// let vueLoader = ['css-loader', 'postcss-loader', 'sass-loader', sassResourceLoader]
let vueLoader = ['css-loader', 'postcss-loader', 'sass-loader', sassResourceLoader]
if (process.env.NODE_ENV === 'production') vueLoader = ExtractTextPlugin.extract({ use: vueLoader, fallback: 'vue-style-loader' })
if (process.env.NODE_ENV === 'development') vueLoader.unshift('vue-style-loader')

// let cssLoader = 'style-loader!css-loader?sourceMap!postcss-loader?sourceMap'
let cssLoader = 'style-loader!css-loader!postcss-loader'
if (process.env.NODE_ENV === 'production') cssLoader = ExtractTextPlugin.extract({ use: ['css-loader?importLoaders=1', 'postcss-loader'] })

const distRoot = path.resolve(__dirname, '../site/templates')

let config = {
  context: __dirname,
  devtool: '#cheap-module-eval-source-map',
  // devtool: '',
  entry: {
    errorTracking: [path.resolve(__dirname, './js/errorTracking.js')],
    critical: [path.resolve(__dirname, './js/critical.js')],
    bundle: [
      'babel-polyfill',
      path.resolve(__dirname, './build/svgs.js'),
      path.resolve(__dirname, './js'),
      path.resolve(__dirname, './scss')
    ]
  },
  output: {
    path: distRoot + '/dist',
    publicPath: publicPath,
    filename: 'js/[name].[hash:6].js'
    // filename: "[name].js"
    // filename: 'js/[name].[chunkhash].js',
    // chunkFilename: 'js/[id].[chunkhash].js'
  },
  devServer: {
    quiet: true,
    // contentBase: path.resolve(__dirname, '../site/templates'),
    // watchContentBase: true,
    // watchOptions: {
      // ignored: path.resolve(__dirname, '../site/templates/dist') // greift irgendwie nicht
      // ignored: '*.js'
    // },
    host: '0.0.0.0',
    publicPath: publicPath,
    // host: ip.address(),
    // disableHostCheck: true,
    proxy: {
      '**': {
        target: pkg.urls.dev,
        // target: {
        //   host: 'pwkickstart.test',
        //   protocol: 'http:',
        //   port: 80
        // },
        changeOrigin: true
      }

    }
  },
  module: {
    rules: [
      {
        test: /\.vue$/,
        loader: 'vue-loader',
        options: {
          loaders: {
            // Since sass-loader (weirdly) has SCSS as its default parse mode, we map
            // the "scss" and "sass" values for the lang attribute to the right configs here.
            // other preprocessors should work out of the box, no loader config like this necessary.
            // scss: ['vue-style-loader', 'css-loader', 'sass-loader'],
            scss: vueLoader
          }
          // extractCSS: true
          // other vue-loader options go here
        }
      },
      { // eslint
        test: /\.js$/,
        loader: 'eslint-loader',
        enforce: 'pre',
        exclude: /node_modules/
      },
      { // javascript
        test: /\.js$/,
        exclude: /node_modules/,
        loader: 'babel-loader'
      },
      { // regular css files
        test: /\.css$/,
        loader: cssLoader
      },
      { // sass / scss loader for webpack
        test: /\.(sass|scss)$/,
        loader: scssLoader
      },
      { // svgs
        test: /\.svg$/,
        oneOf: [
          {
            resourceQuery: /file/,
            use: [
              {
                loader: 'file-loader',
                options: {
                  name: 'icons/[name].[ext]'
                }
              },
              {
                loader: 'svgo-loader',
                options: {
                  plugins: svgoplugins
                }
              }
            ]
          },
          {
            resourceQuery: /inline/,
            use: [
              {
                loader: 'raw-loader',
              },
              {
                loader: 'svgo-loader',
                options: {
                  plugins: svgoplugins
                }
              }
            ]
          },
        ]
      },
      {
        test: /\.(png|jpg|gif)$/,
        loader: 'file-loader',
        options: {
          name: '[name].[ext]?[hash]'
        }
      }
    ]
  },
  resolve: {
    extensions: ['.js', '.vue', '.json'],
    alias: {
      'vue$': 'vue/dist/vue.esm.js',
      '@': path.join(__dirname)
    }
  },
  plugins: [
    new HtmlWebpackPlugin({
      title: 'My App',
      template: './index.php',
      filename: 'index.php',
      alwaysWriteToDisk: true,
      inject: false,
      publicPath: publicPath,
      env: process.env.NODE_ENV,
      // minify: {
      //   removeComments: true,
      //   collapseWhitespace: true,
      //   removeAttributeQuotes: true
      // },
      // necessary to consistently work with multiple chunks via CommonsChunkPlugin
      chunksSortMode: 'dependency'
    }),
    new HtmlWebpackHarddiskPlugin(),
    new SpriteLoaderPlugin(),
    new FriendlyErrorsWebpackPlugin(),
    new WriteFilePlugin({ // write assets used by php to disk
      test: /\.(php|svg)$/
    }),
    new CopyWebpackPlugin([
      { from: 'modules', to: 'modules', ignore: ['!*.php'] },
      // { from: 'modules', to: '../modules' }
    ])
    /* { // replace sprite Url with hash, but this doesn't work
      apply(compiler) {
        compiler.plugin('emit', (compilation, done) => {
          const { assets } = compilation;
          const spriteFilename = Object.keys(assets)
            .find(assetName => assetName.startsWith('sprite.'));

          console.log(':::::::filename: ' + spriteFilename)

          getAllModules(compilation).forEach((module) => {
            console.log(module)
            replaceInModuleSource(module, {
              __SPRITE_URL__: spriteFilename
            });
          });

          done();
        });
      }
    } */
  ]
}

/* DEVELOPMENT */
if (process.env.NODE_ENV === 'development') {
  config.plugins.push(
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': '"development"'
    })
  )
}

/* PRODUCTION */
if (process.env.NODE_ENV === 'production') {
  config.devtool = ''
  config.plugins.push(
    new webpack.DefinePlugin({
      'process.env.NODE_ENV': '"production"'
    })
  )
  config.plugins.push(
    new CleanWebpackPlugin([
      distRoot + '/dist'
    ], { allowExternal: true })
  )
  config.plugins.push(
    new ExtractTextPlugin({ // define where to save the file
      filename: 'screen.[hash:6].css',
      // filename: "screen.css",
      allChunks: true
    })
  )
  config.plugins.push(
    new OptimizeCSSPlugin()
  )
  /* config.plugins.push(
    // split vendor js into its own file
    new webpack.optimize.CommonsChunkPlugin({
      name: 'vendor',
      minChunks: function (module, count) {
        // any required modules inside node_modules are extracted to vendor
        return (
          module.resource &&
          /\.js$/.test(module.resource) &&
          module.resource.indexOf(
            path.join(__dirname, '../node_modules')
          ) === 0
        )
      }
    })
  )*/
  /* config.plugins.push(
    // extract webpack runtime and module manifest to its own file in order to
    // prevent vendor hash from being updated whenever app bundle is updated
    new webpack.optimize.CommonsChunkPlugin({
      name: 'manifest',
      chunks: ['vendor']
    })
  )*/
  config.plugins.push(
    new webpack.optimize.UglifyJsPlugin({
      compress: {
        warnings: false
      },
      sourceMap: true
    })
  )
}

module.exports = config
