// Includes

var browserSync = require('browser-sync'),
    concat = require('gulp-concat'),
    declare = require('gulp-declare'),
    del = require('del'),
    favicons = require('favicons'),
    gulp = require('gulp'),
    handlebars = require('gulp-handlebars'),
    insert = require('gulp-insert'),
    less = require('gulp-less'),
    livereload = require('gulp-livereload'),
    mainBowerFiles = require('main-bower-files'),
    minifyCSS = require('gulp-minify-css'),
    path = require('path'),
    rename = require('gulp-rename'),
    replace = require('gulp-replace'),
    uglify = require('gulp-uglify'),
    watch = require('gulp-watch'),
    wrap = require('gulp-wrap');

// Configuration

var buildDirectory = 'web',
    sourceDirectory = 'src/AppBundle/Resources/private',
    bowerDirectory = 'bower_components',
    nodeDirectory = 'node_modules',
    asyncDirectory = 'async';

// Default task

gulp.task('default', ['css', 'fonts', 'images', 'js', 'js:async'], function () { 
    gulp.start('finish');
});

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

gulp.task('admin-lte', ['bower'], function () {
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

gulp.task('less', ['admin-lte'], function () {
    return gulp.src(buildDirectory + '/lib/bootstrap/less/bootstrap.less')
        .pipe(insert.append('\n@import "../../font-awesome/less/font-awesome.less";'))
        .pipe(insert.append('\n@import "../../admin-lte/build/less/AdminLTE.less";'))
        .pipe(insert.append('\n@import "../../admin-lte/build/less/skins/skin-red.less";'))
        .pipe(insert.append('\n@import "../../../../'+sourceDirectory+'/less/main.less";'))
        .pipe(insert.append('\n@import "../../../../'+sourceDirectory+'/less/variables.less";'))
        .pipe(less())
        .pipe(rename('boostrap.css'))
        .pipe(gulp.dest(buildDirectory + '/css/'));
});

gulp.task('css', ['less'], function () {
    return gulp.src([
            buildDirectory + '/lib/admin-lte/plugins/daterangepicker/daterangepicker-bs3.css',
            buildDirectory + '/lib/admin-lte/plugins/iCheck/square/blue.css',
        buildDirectory + '/css/*.css'
    ])
        .pipe(minifyCSS())
        .pipe(concat('combined.css'))
        .pipe(gulp.dest(buildDirectory + '/css/'))
        .pipe(livereload());
});

gulp.task('fonts', ['bower'], function () {
    return gulp.src([
        buildDirectory + '/lib/bootstrap/fonts/*.{eot,svg,ttf,woff,woff2}',
        buildDirectory + '/lib/font-awesome/fonts/*.{eot,svg,ttf,woff,woff2}',
        sourceDirectory + '/fonts/*.{eot,svg,ttf,woff,woff2}'
    ])
        .pipe(gulp.dest(buildDirectory + '/fonts/'));
});

gulp.task('handlebars', ['bower'], function () {
    return gulp.src(sourceDirectory + '/js/templates/*.hbs')
        .pipe(handlebars())
        .pipe(wrap('Handlebars.template(<%= contents %>)'))
        .pipe(declare({
            namespace: 'trackway.templates',
            noRedeclare: true
        }))
        .pipe(concat('templates.js'))
        .pipe(gulp.dest(buildDirectory + '/js/'));
});

gulp.task('images', ['bower'], function () {
    return gulp.src([
        buildDirectory + '/lib/admin-lte/dist/img/boxed-bg.jpg',
        buildDirectory + '/lib/admin-lte/plugins/iCheck/square/blue*',
        sourceDirectory + '/images/*'
    ])
        .pipe(gulp.dest(buildDirectory + '/images/'));
});

gulp.task('js', ['handlebars'], function () {
    return gulp.src([
        buildDirectory + '/lib/jquery/dist/jquery.js',
        buildDirectory + '/lib/bootstrap/js/dropdown.js',
        buildDirectory + '/lib/bootstrap/js/tooltip.js',
        buildDirectory + '/lib/uri.js/src/URI.js',
        buildDirectory + '/lib/admin-lte/dist/js/app.js',
        buildDirectory + '/lib/admin-lte/plugins/daterangepicker/daterangepicker.js',
        buildDirectory + '/lib/admin-lte/plugins/fastclick/fastclick.js',
        buildDirectory + '/lib/admin-lte/plugins/iCheck/icheck.js',
        nodeDirectory + '/gulp-handlebars/node_modules/handlebars/dist/handlebars.runtime.js',
        buildDirectory + '/js/templates.js',
        sourceDirectory + '/js/*.js'
    ])
        .pipe(uglify())
        .pipe(concat('combined.js'))
        .pipe(gulp.dest(buildDirectory + '/js/'));
});

gulp.task('js:async', ['bower'], function () {
    return gulp.src([
        buildDirectory + '/lib/bootstrap/js/*.js',
        sourceDirectory + '/js/' + asyncDirectory + '/*.js'
    ])
        .pipe(uglify())
        .pipe(gulp.dest(buildDirectory + '/js/' + asyncDirectory));
});

gulp.task('favicons', function (cb) {
    del([
        sourceDirectory + '/../views/favicons.html.twig',
        buildDirectory + '/*.png',
        buildDirectory + '/*.ico',
        buildDirectory + '/*.xml',
        buildDirectory + '/*.json',
        buildDirectory + '/*.webapp'
    ], function() {
		favicons({
			files: {
				src: path.resolve(sourceDirectory + '/favicon.png'),
				dest: path.resolve(buildDirectory),
				html: path.resolve(sourceDirectory + '/../views/favicons.html.twig')
			},
			settings: {
				appName: 'Trackway',
				appDescription: 'The simple on-premise open source time tracker.',
				developerURL: 'http://trackway.org/',
				background: '#d73925',
				silhouette: true
			}
		}, function () {
			return gulp.src(sourceDirectory + '/../views/favicons.html.twig')
				.pipe(replace(/\.\.\/\.\.\/\.\.\/\.\.\/web/gi, '')) // Unix
				.pipe(replace(/\.\.\\\.\.\\\.\.\\\.\.\\web/gi, '')) // Windows
				.pipe(gulp.dest(sourceDirectory + '/../views/'));

		});
	});
});

gulp.task('finish', function () {
    del([
        buildDirectory + '/css/*.css',
        '!' + buildDirectory + '/css/combined.css',
        buildDirectory + '/js/*.js',
        '!' + buildDirectory + '/js/combined.js',
		buildDirectory + '/lib/'
    ]);
});

// Watchers

gulp.task('watch', function () {
    livereload.listen();
    gulp.watch([
        sourceDirectory + '/**/*',
        '!' + sourceDirectory + '/favicon.png'
    ], ['default']);
});
