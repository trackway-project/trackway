// Includes

var browserSync = require('browser-sync'),
    concat = require('gulp-concat'),
    declare = require('gulp-declare'),
    del = require('del'),
    gulp = require('gulp'),
    filter = require('gulp-filter'),
    handlebars = require('gulp-handlebars'),
    inject = require('gulp-inject'),
    insert = require('gulp-insert'),
    less = require('gulp-less'),
    mainBowerFiles = require('main-bower-files'),
    minifyCSS = require('gulp-minify-css'),
    rename = require('gulp-rename'),
    replace = require('gulp-replace'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch'),
    wrap = require('gulp-wrap'),
    livereload = require('gulp-livereload');

// Configuration

var buildDirectory = 'web',
    sourceDirectory = 'src/AppBundle/Resources/private',
    bowerDirectory = 'bower_components',
    nodeDirectory = 'node_modules',
    asyncDirectory = 'async';

// Default task

gulp.task('default', [
        'less:build',
        'handlebars:build',
        'fonts:copy',
        'images:copy',
        'js:copy',
        'js:copyAsync',
        'js:compress',
        'js:compressAsync',
        'css:compress'],
    function () {
        gulp.start('finish');
    }
);

// Tasks

gulp.task('clean', function (cb) {
    del([
        buildDirectory + '/css/',
        buildDirectory + '/fonts/',
        buildDirectory + '/images/',
        buildDirectory + '/js/'
    ], cb);
});

gulp.task('bower', ['clean'], function () {
    return gulp.src(mainBowerFiles(), {base: bowerDirectory})
        .pipe(gulp.dest(buildDirectory + '/lib/'));
});

gulp.task('admin-lte:prepare', ['bower'], function () {
    // Fix image paths
    gulp.src(buildDirectory + '/lib/admin-lte/plugins/iCheck/square/blue.css')
        .pipe(replace('url(', 'url(../images/'))
        .pipe(gulp.dest(buildDirectory + '/lib/admin-lte/plugins/iCheck/square/'));
    // Remove imports of the integrated bootstrap files - we're going to use the real ones
    gulp.src(buildDirectory + '/lib/admin-lte/build/less/AdminLTE.less')
        .pipe(replace('@import "../bootstrap-less/mixins.less";', ''))
        .pipe(replace('@import "../bootstrap-less/variables.less";', ''))
        .pipe(gulp.dest(buildDirectory + '/lib/admin-lte/build/less/'));
    return gulp.src(buildDirectory + '/lib/admin-lte/build/less/skins/*.less')
        .pipe(replace('@import "../../bootstrap-less/mixins.less";', ''))
        .pipe(replace('@import "../../bootstrap-less/variables.less";', ''))
        .pipe(gulp.dest(buildDirectory + '/lib/admin-lte/build/less/skins/'));
});

gulp.task('bootstrap:prepare', ['admin-lte:prepare'], function () {
    // Append additional imports
    return gulp.src(buildDirectory + '/lib/bootstrap/less/bootstrap.less')
        .pipe(insert.append('\n// Font Awesome'))
        .pipe(insert.append('\n@import "../../font-awesome/less/font-awesome.less";'))
        .pipe(insert.append('\n\n// Admin-LTE'))
        .pipe(insert.append('\n@import "../../admin-lte/build/less/AdminLTE.less";'))
        .pipe(insert.append('\n@import "../../admin-lte/build/less/skins/skin-red.less";'))
        .pipe(insert.append('\n\n// Custom'))
        .pipe(insert.append('\n@import "../../../../'+sourceDirectory+'/less/main.less";'))
        .pipe(insert.append('\n@import "../../../../'+sourceDirectory+'/less/variables.less";'))
        .pipe(gulp.dest(buildDirectory + '/lib/bootstrap/less/'));
});

gulp.task('less:build', ['bootstrap:prepare'], function () {
    return gulp.src(buildDirectory + '/lib/bootstrap/less/bootstrap.less')
        .pipe(less())
        .pipe(rename('boostrap.css'))
        .pipe(gulp.dest(buildDirectory + '/css/'));
});

gulp.task('css:compress', ['less:build', 'admin-lte:prepare'], function () {
    return gulp.src([
        buildDirectory + '/lib/admin-lte/plugins/iCheck/square/blue.css',
        buildDirectory + '/css/*.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('combined.css'))
        .pipe(gulp.dest(buildDirectory + '/css/'))
        .pipe(livereload());
});

gulp.task('fonts:copy', ['bower'], function () {
    return gulp.src([
        buildDirectory + '/lib/bootstrap/fonts/*.{eot,svg,ttf,woff,woff2}',
        buildDirectory + '/lib/font-awesome/fonts/*.{eot,svg,ttf,woff,woff2}',
        sourceDirectory + '/fonts/*.{eot,svg,ttf,woff,woff2}'
    ])
        .pipe(gulp.dest(buildDirectory + '/fonts/'));
});

gulp.task('handlebars:build', ['bower'], function () {
    return gulp.src(sourceDirectory + '/js/templates/*.hbs')
        .pipe(handlebars())
        .pipe(wrap('Handlebars.template(<%= contents %>)'))
        .pipe(declare({
            namespace: 'kui.templates',
            noRedeclare: true
        }))
        .pipe(concat('templates.js'))
        .pipe(gulp.dest(buildDirectory + '/js/'));
});

gulp.task('images:copy', ['bower'], function () {
    return gulp.src([
        buildDirectory + '/lib/admin-lte/dist/img/**/*',
        buildDirectory + '/lib/admin-lte/plugins/iCheck/square/blue*',
        sourceDirectory + '/images/*'
    ])
        .pipe(gulp.dest(buildDirectory + '/images/'));
});

gulp.task('js:copy', ['handlebars:build'], function () {
    return gulp.src([
        buildDirectory + '/lib/jquery/dist/jquery.js',
        buildDirectory + '/lib/bootstrap/js/dropdown.js',
        buildDirectory + '/lib/bootstrap/js/tooltip.js',
        buildDirectory + '/lib/admin-lte/dist/js/app.js',
        buildDirectory + '/lib/admin-lte/plugins/fastclick/fastclick.js',
        buildDirectory + '/lib/admin-lte/plugins/iCheck/icheck.js',
        nodeDirectory + '/gulp-handlebars/node_modules/handlebars/dist/handlebars.runtime.js',
        buildDirectory + '/js/templates.js',
        sourceDirectory + '/js/*.js'
    ])
        .pipe(gulp.dest(buildDirectory + '/js'));
});

gulp.task('js:copyAsync', ['bower'], function () {
    return gulp.src([
        sourceDirectory + '/js/' + asyncDirectory + '/*.js',
        buildDirectory + '/lib/bootstrap/js/*.js'
    ])
        .pipe(gulp.dest(buildDirectory + '/js/' + asyncDirectory));
});

gulp.task('js:compress', ['js:copy'], function () {
    return gulp.src([
        buildDirectory + '/js/jquery.js',
        buildDirectory + '/js/*.js',
        buildDirectory + '/js/templates.js',
        buildDirectory + '/js/application.js'
    ])
        .pipe(uglify())
        .pipe(concat('combined.js'))
        .pipe(gulp.dest(buildDirectory + '/js/'));
});

gulp.task('js:compressAsync', ['js:copyAsync'], function () {
    return gulp.src(buildDirectory + '/js/' + asyncDirectory + '/*.js')
        .pipe(uglify())
        .pipe(gulp.dest(buildDirectory + '/js/' + asyncDirectory));
});

gulp.task('finish', function () {
    // remove all css files except combined.css
    del([
        buildDirectory + '/css/*.css',
        '!' + buildDirectory + '/css/combined.css'
    ]);

    // remove all js files except combined.css
    del([
        buildDirectory + '/js/*.js',
        '!' + buildDirectory + '/js/combined.js'
    ]);

    // remove lib folder
    del(buildDirectory + '/lib/');
});

// Watchers

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch([
        sourceDirectory + '/fonts/*',
        sourceDirectory + '/images/*',
        sourceDirectory + '/js/*.js',
        sourceDirectory + '/js/' + asyncDirectory + '/*.js',
        sourceDirectory + '/js/templates/*.hbs',
        sourceDirectory + '/less/*.less'
    ], [
        'default'
    ]);
});
