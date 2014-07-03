'use strict';
// generated on 2014-07-02 using generator-gulp-webapp 0.1.0

var gulp = require('gulp');


// load plugins
var $ = require('gulp-load-plugins')();
var gutil = require('gulp-util');

var onError = function (err) {
    gutil.beep();
    gutil.log( gutil.colors.red(err));
};

gulp.task('styles', function () {
    return gulp.src('app/styles/main.scss')
        //.pipe($.plumber(onError))
        .pipe($.sass({
            includePaths: ['app/bower_components/foundation/scss'],
            errLogToConsole: true
        }))
        .pipe($.autoprefixer('last 1 version'))
        .pipe($.plumber.stop())
        .pipe(gulp.dest('dist/styles'))
        .pipe($.size());
});

gulp.task('diststyles', function () {
    return gulp.src('app/styles/main.scss')
        .pipe($.sass({
            includePaths: ['bower_components/foundation/scss']
        }))
        .pipe($.autoprefixer('last 1 version'))
        .pipe($.csso())
        .pipe(gulp.dest('dist/styles'))
        .pipe($.size());
});

gulp.task('scripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe($.size());
});

gulp.task('distscripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe($.uglify())
        .pipe(gulp.dest('dist/scripts'))
        .pipe($.size());
});

gulp.task('images', function () {
    return gulp.src('app/images/**/*')
        .pipe($.cache($.imagemin({
            optimizationLevel: 3,
            progressive: true,
            interlaced: true
        })))
        .pipe(gulp.dest('dist/images'))
        .pipe($.size());
});

gulp.task('fonts', function () {
    return $.bowerFiles()
        .pipe($.filter('**/*.{eot,svg,ttf,woff}'))
        .pipe($.flatten())
        .pipe(gulp.dest('dist/fonts'))
        .pipe($.size());
});

gulp.task('extras', function () {
    return gulp.src(['app/*.*', '!app/*.php'], { dot: true })
        .pipe(gulp.dest('dist'));
});

gulp.task('clean', function () {
    return gulp.src(['.tmp', 'dist'], { read: false }).pipe($.clean());
});

gulp.task('build', ['distscripts', 'diststyles', 'images', 'fonts', 'extras']);

gulp.task('default', ['clean'], function () {
    gulp.start('build');
});

gulp.task('serve', ['styles'], function () {
    require('opn')('http://aktiv.dev');
});

// inject bower components
gulp.task('wiredep', function () {
    var wiredep = require('wiredep').stream;

    gulp.src('app/styles/*.scss')
        .pipe(wiredep({
            directory: 'app/bower_components'
        }))
        .pipe(gulp.dest('app/styles'));

    gulp.src('*.php')
        .pipe(wiredep({
            directory: 'app/bower_components'
        }))
        .pipe(gulp.dest('app'));
});

gulp.task('watch', ['serve'], function () {
    var server = $.livereload();

    // watch for changes

    gulp.watch([
        '*.php',
        'dist/styles/**/*.css',
        'dist/scripts/**/*.js',
        'dist/images/**/*'
    ]).on('change', function (file) {
        server.changed(file.path);
    });

    gulp.watch('app/styles/**/*.scss', ['styles']);
    gulp.watch('app/scripts/**/*.js', ['scripts']);
    gulp.watch('app/images/**/*', ['images']);
    gulp.watch('bower.json', ['wiredep']);
});
