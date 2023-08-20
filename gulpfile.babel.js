import gulp from 'gulp';
import webpackConfig from "./webpack.config.js";
import webpack from 'webpack-stream';
import notify from 'gulp-notify';
import plumber from 'gulp-plumber';
const cleancss = require('gulp-clean-css');
const sass = require('gulp-sass')(require('sass'));



gulp.task('scss', function(done){
  gulp.src('resources/scss/app.scss')
  .pipe(sass())
  .pipe(cleancss())
  .pipe(gulp.dest('public/build/assets'));
  done();
});

gulp.task('scss_watch', function(done){
  gulp.watch('resources/scss', gulp.series('scss'));
  done();
});


gulp.task('js', function(done){
  gulp.src('resources/js/app.js')
  .pipe(plumber({
    errorHandler: notify.onError("Error: <%= error.message %>")
  }))
  .pipe(webpack(webpackConfig))
  .pipe(gulp.dest('public/build/assets'));
  done();
});

gulp.task('js_watch', function(done){
  gulp.watch('resources/js', gulp.series('js'));
  done();
});