module.exports = function(grunt) {
    grunt.loadNpmTasks('grunt-contrib-uglify');
    grunt.loadNpmTasks('grunt-contrib-concat');
    grunt.loadNpmTasks('grunt-contrib-watch');
    grunt.loadNpmTasks('grunt-contrib-clean');
    grunt.loadNpmTasks('grunt-contrib-less');
    grunt.loadNpmTasks('grunt-contrib-cssmin');

    var devPath = 'public/js/dev';
    var prodPath = 'public/js/prod';

    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        uglify: {
            my_target: {
                files: [{
                    expand: true,
                    cwd: devPath,
                    src: '**/*.js',
                    dest: prodPath,
                    ext: '.min.js'
                }]
            }
        },
        less:{
            development:{
                options:{
                    cleancss:true
                },
                files: [{
                    expand: true,
                    cwd: 'css',
                    src: '*.less',
                    dest: 'css',
                    ext: '.css'
                }]
            }
        },
        cssmin: {
          add_banner: {
            options: {
              banner: '/* My minified css file */'
            },
            files: {
              'css/styles.min.css': ['css/*.css','!css/*.min.css']
            }
          }
        },
        clean: {
            before: [prodPath + '/*'],
            after: [prodPath + '/*','!'+prodPath+'/*.min.js']
        },
        watch: {
            scripts: {
                files: [devPath + '/**/*.js','css/*.less'],
                tasks: ['clean:before','uglify','customConcat','clean:after']
                /*tasks: ['less','cssmin','clean:before','uglify','customConcat','clean:after']*/
            }
        }
    });
    grunt.registerTask('customConcat', 'prepare all files', function() {
        
        grunt.file.expand(prodPath + '/*').forEach(function(dir) {
            var folderArr = dir.split("/");
            var fileName = folderArr[folderArr.length - 1];
            grunt.log.writeln('fileName: ' + fileName);

            // get the current concat config
            var concat = grunt.config.get('concat') || {};
            
            concat[dir] = {
                src: dir + '/*.min.js',
                dest: prodPath + '/' + fileName + '.min.js'
            };

            // save the new concat config
            grunt.config.set('concat', concat);
        });
        
        grunt.task.run('concat');
    });
}