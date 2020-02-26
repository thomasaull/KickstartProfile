const path = require('path')
const webpack = require('webpack')
const HtmlWebpackPlugin = require('html-webpack-plugin')
const HtmlWebpackHarddiskPlugin = require('html-webpack-harddisk-plugin')
const { CleanWebpackPlugin } = require('clean-webpack-plugin')
const svgoplugins = require('./build/svgoplugins')
const FriendlyErrorsWebpackPlugin = require('friendly-errors-webpack-plugin')
const CopyWebpackPlugin = require('copy-webpack-plugin')
const WriteFilePlugin = require('write-file-webpack-plugin')
const chalk = require('chalk')
const pkg = require('./package.json')
const ip = require('ip')
const StyleLintPlugin = require('stylelint-webpack-plugin')
const { VueLoaderPlugin } = require('vue-loader')
const MiniCssExtractPlugin = require('mini-css-extract-plugin')
const TerserPlugin = require('terser-webpack-plugin')
const OptimizeCSSAssetsPlugin = require('optimize-css-assets-webpack-plugin')
const BundleAnalyzerPlugin = require('webpack-bundle-analyzer')
  .BundleAnalyzerPlugin

// CONFIG
const useLocalIpAddress = false

module.exports = env => {
  let publicPath = useLocalIpAddress
    ? `http://${ip.address()}:8080/`
    : 'http://localhost:8080/'
  // publicPath = 'http://192.168.178.47:8080/dist/' // für lokales Netzwerk-Testen
  // if(env.NODE_ENV === 'production') publicPath = '<?=$config->urls->templates?>dist/'
  if (env.NODE_ENV === 'production') publicPath = '/site/templates/dist/'

  console.log(chalk`For local testing, use: {green ${publicPath}}`)

  const sassResourceLoader = {
    loader: 'sass-resources-loader',
    options: {
      resources: [
        path.resolve(
          __dirname,
          './node_modules/include-media/dist/_include-media.scss'
        ),
        path.resolve(__dirname, './scss/easing.scss'),
        path.resolve(__dirname, './scss/interpolate.scss'),
        path.resolve(
          __dirname,
          './node_modules/modularscale-sass/stylesheets/_modularscale.scss'
        ),
        path.resolve(
          __dirname,
          './node_modules/family.scss/source/src/_family.scss'
        ),
        path.resolve(__dirname, './scss/constants.scss'),
        path.resolve(__dirname, './scss/mixins.scss')
      ]
    }
  }

  let cssLoader =
    env.NODE_ENV === 'development'
      ? ['style-loader', 'css-loader', 'postcss-loader']
      : [MiniCssExtractPlugin.loader, 'css-loader', 'postcss-loader']

  let scssLoader = [...cssLoader, 'sass-loader', sassResourceLoader]
  let vueLoader = ['vue-style-loader', ...scssLoader]

  const distRoot = path.resolve(__dirname, '../')

  let config = {
    context: __dirname,
    devtool: '#cheap-module-eval-source-map',

    entry: {
      errorTracking: [path.resolve(__dirname, './js/errorTracking.js')],
      critical: [path.resolve(__dirname, './js/critical.js')],
      bundle: [
        '@babel/polyfill',
        path.resolve(__dirname, './build/svgs.js'),
        path.resolve(__dirname, './js'),
        path.resolve(__dirname, './scss')
      ]
    },

    output: {
      path: distRoot + '/site/templates/dist',
      publicPath: publicPath,
      filename: 'js/[name].[hash:6].js'
    },

    devServer: {
      quiet: true,
      host: '0.0.0.0',
      publicPath: publicPath,
      proxy: {
        '**': {
          target: pkg.urls.dev,
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
              scss: vueLoader
            }
          }
        },
        {
          test: /\.js$/,
          loader: 'eslint-loader',
          enforce: 'pre',
          options: {
            fix: true
          },
          exclude: /node_modules/
        },
        {
          test: /\.js$/,
          use: 'babel-loader',
          exclude: /node_modules/
        },
        {
          test: /\.css$/,
          use: cssLoader
        },
        {
          test: /\.scss$/,
          use: scssLoader
        },
        {
          test: /\.svg$/,
          oneOf: [
            {
              resourceQuery: /\?file$/,
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
              resourceQuery: /\?fileNoSvgo$/,
              use: [
                {
                  loader: 'file-loader',
                  options: {
                    name: 'icons/[name].[ext]'
                  }
                }
              ]
            },
            {
              resourceQuery: /\?inline$/,
              use: [
                {
                  loader: 'raw-loader'
                },
                {
                  loader: 'svgo-loader',
                  options: {
                    plugins: svgoplugins
                  }
                }
              ]
            }
          ]
        },
        {
          test: /\.(png|jpg|gif)$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '[name].[ext]?[hash]'
              }
            }
          ]
        },
        {
          test: /\.(eot|ttf|woff|woff2)$/,
          use: [
            {
              loader: 'file-loader',
              options: {
                name: '[name].[ext]'
              }
            }
          ]
        }
      ]
    },

    resolve: {
      extensions: ['.js', '.vue', '.json'],
      alias: {
        vue$: 'vue/dist/vue.esm.js',
        '@': path.join(__dirname)
      }
    },

    optimization: {
      minimizer: [new TerserPlugin(), new OptimizeCSSAssetsPlugin({})]
    },

    plugins: [
      new HtmlWebpackPlugin({
        title: 'My App',
        template: './index.php',
        filename: 'index.php',
        alwaysWriteToDisk: true,
        inject: false,
        publicPath: publicPath,
        env: env.NODE_ENV,
        // minify: {
        //   removeComments: true,
        //   collapseWhitespace: true,
        //   removeAttributeQuotes: true
        // },
        // necessary to consistently work with multiple chunks via CommonsChunkPlugin
        chunksSortMode: 'dependency'
      }),
      new HtmlWebpackHarddiskPlugin(),
      new FriendlyErrorsWebpackPlugin(),
      new WriteFilePlugin({
        // write assets used by php to disk
        // test: /\.(php|svg)$/
        test: /\.(?!vue|js|scss).*$/
      }),
      new CopyWebpackPlugin([
        // { from: 'modules', to: 'modules', ignore: ['!*.php'] },
        { from: 'modules', to: 'modules', ignore: ['*.scss', '*.js', '*.vue'] },
        { from: 'static', to: 'static' }
      ]),
      new CleanWebpackPlugin({
        dry: false,
        dangerouslyAllowCleanPatternsOutsideProject: true,
        cleanStaleWebpackAssets: false
      }),
      new StyleLintPlugin(),
      new VueLoaderPlugin(),

      new MiniCssExtractPlugin({
        filename: '[name].css',
        chunkFilename: '[id].css'
      })
    ]
  }

  /* DEVELOPMENT */
  if (env.NODE_ENV === 'development') {
    config.plugins.push(
      new webpack.DefinePlugin({
        'process.env.NODE_ENV': '"development"'
      })
    )
  }

  /* PRODUCTION */
  if (env.NODE_ENV === 'production') {
    config.devtool = ''

    config.plugins.push(
      new webpack.DefinePlugin({
        'process.env.NODE_ENV': '"production"'
      })
    )

    if (env.analyze) {
      config.plugins.push(
        new BundleAnalyzerPlugin({
          // https://www.npmjs.com/package/webpack-bundle-analyzer
          analyzerMode: 'static', // server|disabled|static
          openAnalyzer: false,
          generateStatsFile: false,
          statsOptions: { source: false }
        })
      )
    }
  }

  return config
}
