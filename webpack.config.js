import path from 'path';
import { VueLoaderPlugin } from 'vue-loader';

module.exports = {
  mode: 'production',//production or development
  entry: path.join(__dirname, 'resources/js/app.js'),
  output: {
    path: path.join(__dirname, 'public/build/assets'),
    filename: 'app.js'
  },
  module: {
    rules: [
      {
        test: /\.js$/,
        exclude: /node_modules/,
        use: {
          loader: 'babel-loader',
          options: {
            presets: ['@babel/preset-env']
          }
        }
      },
      {
        test: /\.vue$/,
        loader: "vue-loader",
      },
    ]
  },
  resolve: {
    modules: [path.join(__dirname, 'resources/js'), 'node_modules'],
    extensions: ['.js', '.vue'],
    alias: {
      vue: 'vue/dist/vue.esm-bundler.js'
    }
  },
  plugins: [
    new VueLoaderPlugin(),
  ],
  target: ["web", "es5"],
};