/*
 *
 *
 * Licensed under the MIT license:
 * http://www.opensource.org/licenses/MIT
 */

/*global module */

module.exports = function (grunt) {
    'use strict';

    grunt.initConfig({
        jshint: {
            all: [
                'Gruntfile.js'
            ]
        },
        less: {
            production: {
                options: {
                    cleancss: true
                },
                src: [
                    'vendor/bower/swipebox/src/css/swipebox.css'
                ],
                dest: 'web/css/photo-gallery.min.css'
            }
        },
        uglify: {
            production: {
                src: [
                    'vendor/bower/masonry/dist/masonry.pkgd.js',
                    'vendor/bower/swipebox/src/js/jquery.swipebox.js'
                ],
                dest: 'web/js/photo-gallery.min.js'
            }
        }
    });

    grunt.loadNpmTasks('grunt-contrib-jshint');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-bump-build-git');

    grunt.registerTask('test', ['jshint']);
    grunt.registerTask('default', ['test', 'less', 'uglify']);

};
