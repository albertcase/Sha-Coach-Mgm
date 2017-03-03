// Include gulp
var gulp = require('gulp');

// Include Our Plugins
var minify = require('gulp-minify-css');
var concat = require('gulp-concat');
var uglify = require('gulp-uglify');
var rename = require('gulp-rename'),
    sourcemaps = require("gulp-sourcemaps"),
    del = require('del'),
    babel = require("gulp-babel"),
    browserSync = require('browser-sync').create();

//Define the app path
var path = {
    all:['./template/*.html','./src/assets/css/*.css','./src/assets/js/*.js','./src/assets/js/lib/*.js'],
    template:['./src/*.html'],
    css:['./src/assets/css/*.css'],
    js:['./src/assets/js/lib/zepto.min.js','./src/assets/js/lib/pre-loader.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/wxshare.js','./src/assets/js/api.js','./src/assets/js/home.js'],
    welcomejs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/rem.js','./src/assets/js/common.js'],
    joinjs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/api.js','./src/assets/js/join.js'],
    exchangejs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/api.js','./src/assets/js/exchange.js'],
    images:['./src/assets/images/*'],
};
// Browser-sync
gulp.task('browser-sync', function() {
    browserSync.init(path.all,{
        server: {
            baseDir: "./",
            startPath: ''
        }
    });
});

// Not all tasks need to use streams
// A gulpfile is just another node program and you can use any package available on npm
gulp.task('clean', function() {
    // You can use multiple globbing patterns as you would with `gulp.src`
    return del(['build']);
});


//css
gulp.task('css',['clean'],function () {
    // 1. 找到文件
    gulp.src(path.css)
        //.pipe(concat('style.css'))
        // 2. 压缩文件
        .pipe(minify())
        // 3. 另存为压缩文件
        .pipe(gulp.dest('./src/dist/css'));
});

// Concatenate & Minify
gulp.task('scripts_welcome',['clean'], function() {
    return gulp.src(path.welcomejs)
        .pipe(concat('welcome_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('welcome_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});
gulp.task('scripts_join',['clean'], function() {
    return gulp.src(path.joinjs)
        .pipe(concat('join_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('join_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});
gulp.task('scripts_exchange',['clean'], function() {
    return gulp.src(path.exchangejs)
        .pipe(concat('exchange_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('exchange_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});


// Watch Files For Changes
gulp.task('watch', ['clean'],function() {
    gulp.watch(path.welcomejs, ['scripts_welcome']);
    gulp.watch(path.joinjs, ['scripts_join']);
    gulp.watch(path.exchangejs, ['scripts_exchange']);
    gulp.watch(path.css,['css']);
});

// Default Task
gulp.task('default', ['watch', 'scripts_welcome','scripts_join','scripts_exchange','css','browser-sync']);


