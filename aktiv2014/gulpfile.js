'use strict';
// based on generator-gulp-webapp

var gulp = require('gulp');

// load plugins
var $ = require('gulp-load-plugins')();
var autoprefixer = require('autoprefixer');
var browserSync = require('browser-sync');
var cssnano = require('cssnano');

gulp.task('styles', function () {
    return gulp.src('app/styles/main.scss')
        .pipe($.sass({
            includePaths: ['node_modules/foundation-sites/scss'],
            outputStyle: 'nested',
            precision: 10,
            onError: console.error.bind(console, 'Sass error:')
        }))
        .pipe($.postcss([
            autoprefixer({browsers: ['last 1 version']})
        ]))
        .pipe(gulp.dest('dist/styles'))
        .pipe(browserSync.stream());
});

gulp.task('diststyles', function () {
    return gulp.src('app/styles/main.scss')
        .pipe($.sass({
            includePaths: ['node_modules/foundation-sites/scss'],
            precision: 10,
            onError: console.error.bind(console, 'Sass error:')
        }))
        .pipe($.postcss([
            autoprefixer({browsers: ['last 1 version']}),
            cssnano()
        ]))
        .pipe(gulp.dest('dist/styles'));
});

gulp.task('vendorscripts', function () {
    var vendorFiles = [
        'node_modules/jquery/dist/jquery.min.js',
        'node_modules/chosen-js/chosen.jquery.min.js',
        'node_modules/list.js/dist/list.min.js',
        'node_modules/moment/min/moment.min.js',
        'node_modules/moment/locale/nb.js',
        'node_modules/underscore/underscore-min.js'
    ];
    return gulp.src(vendorFiles)
        .pipe($.concat('vendor.js'))
        .pipe(gulp.dest('dist/scripts'));
});

gulp.task('scripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe(gulp.dest('dist/scripts'));
});

gulp.task('distscripts', function () {
    return gulp.src('app/scripts/**/*.js')
        .pipe($.jshint())
        .pipe($.jshint.reporter(require('jshint-stylish')))
        .pipe($.uglify())
        .pipe(gulp.dest('dist/scripts'));
});

gulp.task('images', function () {
    return gulp.src('app/images/**/*')
        .pipe($.imagemin({
            progressive: true,
            interlaced: true
        }))
        .pipe(gulp.dest('dist/images'));
});

gulp.task('fonts', function () {
    return gulp.src('app/fonts/**/*')
        .pipe($.filter('**/*.{eot,svg,ttf,woff,woff2}'))
        .pipe($.flatten())
        .pipe(gulp.dest('dist/fonts'));
});

gulp.task('extras', function () {
    return gulp.src(['app/*.*', '!app/*.php'], { dot: true })
        .pipe(gulp.dest('dist'));
});

gulp.task('clean', require('del').bind(null, ['dist']));

gulp.task('build', ['vendorscripts','distscripts', 'diststyles', 'images', 'fonts', 'extras'], function () {
  return gulp.src('dist/**/*').pipe($.size({title: 'build', gzip: true}));
});

gulp.task('default', ['clean'], function () {
    gulp.start('build');
});

gulp.task('watch', ['styles'], function () {
    browserSync.init({
        proxy: "aktiv-web.dev"
    });

    // watch for changes
    gulp.watch([
        '*.php',
        'dist/styles/**/*.css',
        'dist/scripts/**/*.js',
        'dist/images/**/*'
    ]).on('change', browserSync.reload);

    gulp.watch('app/styles/**/*.scss', ['styles']);
    gulp.watch('app/scripts/**/*.js', ['scripts']);
    gulp.watch('app/images/**/*', ['images']);
    gulp.watch('package.json', ['vendorscripts']);
});
