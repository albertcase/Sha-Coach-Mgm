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
    welcomejs: ['./src/assets/js/lib/zepto.min.js',"./src/assets/js/lib/swiper.min.js",'./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/wxshare.js','./src/assets/js/api.js','./src/assets/js/welcome.js'],
    orderjs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/region.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/wxshare.js','./src/assets/js/api.js','./src/assets/js/order.js'],
    payjs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/wxshare.js','./src/assets/js/api.js','./src/assets/js/pay.js'],
    reservationjs: ['./src/assets/js/lib/zepto.min.js','./src/assets/js/rem.js','./src/assets/js/common.js','./src/assets/js/wxshare.js','./src/assets/js/api.js','./src/assets/js/reservation.js'],
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
gulp.task('scripts_order',['clean'], function() {
    return gulp.src(path.orderjs)
        .pipe(concat('order_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('order_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});
gulp.task('scripts_pay',['clean'], function() {
    return gulp.src(path.payjs)
        .pipe(concat('pay_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('pay_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});

gulp.task('scripts_reservation',['clean'], function() {
    return gulp.src(path.reservationjs)
        .pipe(concat('reservation_all.js'))
        .pipe(gulp.dest('./src/dist'))
        .pipe(rename('reservation_all.min.js'))
        .pipe(uglify())
        .pipe(gulp.dest('./src/dist/js'));
});

//reservationjs


// Watch Files For Changes
gulp.task('watch', ['clean'],function() {
    gulp.watch(path.welcomejs, ['scripts_welcome']);
    gulp.watch(path.orderjs, ['scripts_order']);
    gulp.watch(path.payjs, ['scripts_pay']);
    gulp.watch(path.reservationjs, ['scripts_reservation']);
    gulp.watch(path.css,['css']);
});

// Default Task
gulp.task('default', ['watch', 'scripts_welcome','scripts_order','scripts_pay','scripts_reservation','css','browser-sync']);


