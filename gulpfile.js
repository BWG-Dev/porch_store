var gulp = require('gulp'),
    gutil = require('gulp-util'),
    del = require('del'),
    runsequence = require('run-sequence'),
    watch = require('gulp-watch'),
    plumber = require('gulp-plumber'),
    sass = require('gulp-sass'),
    nodeSass = require('node-sass'),
    cssnano = require('gulp-cssnano'),
    rename = require('gulp-rename'),
    base64 = require('gulp-base64'),
    imagemin = require('gulp-imagemin'),
    uglify = require('gulp-uglify'),
    stripcomments = require('gulp-strip-comments'),
    gulpif = require('gulp-if'),
    concat = require('gulp-concat'),
    sourcemaps = require('gulp-sourcemaps'),
    autoprefixer = require('gulp-autoprefixer'),
    sort = require('gulp-sort'),
    RevAll = require('gulp-rev-all'),
    browserSync = require('browser-sync').create();

var THEME_DIR = './',
    SOURCE_DIR = THEME_DIR + 'src/',
    DIST_DIR = THEME_DIR + 'dist/';

var paths = {
    /*
     * All JavaScript files in order in which they should be loaded
     */
    scripts: [
        //SOURCE_DIR + 'scripts/vendor/jquery-3.3.1.min.js',
        SOURCE_DIR + 'scripts/vendor/jquery-match-height.js',
        SOURCE_DIR + 'scripts/mailchimp.js',
        SOURCE_DIR + 'scripts/legacy.js',
        SOURCE_DIR + 'scripts/matchHeights.js',
        SOURCE_DIR + 'scripts/scrollTo.js',
    ],
    /*
     * Any extra libraries or polyfills which are not always needed
     * but have to be present in dist folder.
     */
    libs: []
};

/*
 * Clean the target directory
 */
gulp.task('clean', function () {
    return del.sync([DIST_DIR]);
});

/*
 * Copy libraries to the target directory
 */
gulp.task('libs', function () {
    return gulp.src(paths.libs, {base: SOURCE_DIR + 'scripts/vendor'})
        .pipe(gulp.dest(DIST_DIR + 'js'))
});

/*
 * Optimise images
 */
img = function () {
    return gulp.src(SOURCE_DIR + '**/*.{ico,jpg,png,svg,gif}', {base: SOURCE_DIR})
        .pipe(plumber())
        .pipe(imagemin([
            imagemin.gifsicle(),
            imagemin.jpegtran(),
            imagemin.optipng(),
            imagemin.svgo({
                plugins: [{
                    // You need to manually grab https://raw.githubusercontent.com/svg/svgo/master/plugins/cleanupIDs.js
                    // to node_modules/svgo/plugins until a new version of SVGO is published!!!
                    cleanupIDs: {preserve: []}
                }]
            })
        ]))
        .pipe(gulp.dest(DIST_DIR));
};
gulp.task('img', function () {
    return img();
});

/*
 * Uglify and combine JavaScript files. In case there are already uglified, just script comments
 * (it takes several seconds to uglify libraries like jQuery so it is better to use production versions)
 */
scripts = function () {
    return gulp.src(paths.scripts)
        .pipe(plumber())
        .pipe(gulpif(['*', '!*.min.*'], uglify(), stripcomments()))
        .pipe(concat('app.min.js'))
        .pipe(gulp.dest(DIST_DIR + 'js'));
};
gulp.task('scripts', function () {
    return scripts();
});

/*
 * Compile SASS files, autoprefix, minify, inline background images up to 4kB and build sourcemaps
 */
styles = function () {
    return gulp.src([SOURCE_DIR + 'styles/app.scss'])
        .pipe(plumber())
        .pipe(sourcemaps.init())
        .pipe(sass({
            includePaths: [
                './node_modules/'
            ],
            functions: {
                'encodeBase64($string)': function ($string) {
                    var buffer = new Buffer($string.getValue());
                    return nodeSass.types.String(buffer.toString('base64'));
                }
            }
        }))
        .pipe(autoprefixer({browsers: ['> 0.25%']}))
        .pipe(cssnano({
            safe: true,
            discardComments: {removeAll: true}
        }))
        .pipe(base64({
            baseDir: SOURCE_DIR + '/styles',
            maxImageSize: 4 * 1024, // bytes
            exclude: [
                //'footer-bg-cut', // for some reason few pixel offset appears on sides when encoding these
            ],
        }))
        .pipe(rename({suffix: '.min'}))
        .pipe(sourcemaps.write('.'))
        .pipe(gulp.dest(DIST_DIR + 'css'));
};
gulp.task('styles', function () {
    return styles();
});

gulp.task('watch', function () {
    watch(SOURCE_DIR + '**/*.{ico,jpg,png,svg,gif}', {}, function () {
        img();
    });
    watch(SOURCE_DIR + 'styles/**/*.{scss,css}', {}, function () {
        styles();
    });
    watch(SOURCE_DIR + 'scripts/**/*.js', {}, function () {
        scripts();
    });
});

/*
 * Revision static files and rewrite references, excluding PHP files
 */
rev = function() {
    return gulp.src(THEME_DIR + 'dist/**', {base: THEME_DIR})
        .pipe(sort())
        .pipe(RevAll.revision({
            debug: true,
            includeFilesInManifest: ['.css', '.js', '.png', '.jpeg', '.jpg', '.gif', '.svg'],
        }))
        .pipe(gulp.dest(THEME_DIR));
};
gulp.task('rev', function () {
    return rev();
});

gulp.task('browser-sync', function () {
    browserSync.init({
        proxy: "porchco.test",
        host: "ziki.local", // can be same as proxy, but adding IP or network address allows connection from phone and devices in the same network
        open: 'external',
        open: false,
        files: [DIST_DIR + "**/*.css", DIST_DIR + "**/*.js", THEME_DIR + "**/*.php"],
        ui: { port: 8080 }
    });
});

gulp.task('default', function () {
    runsequence('clean', 'libs', 'img', ['styles', 'scripts'], 'watch', 'browser-sync');
});

gulp.task('build', function () {
    runsequence('clean', 'libs', 'img', ['styles', 'scripts'], 'rev');
});
