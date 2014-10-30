<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 29/10/14
 * Time: 11:24 AM
 */

class AssessmentSeed extends Seeder {
    public function run()
    {
        DB::table('assessments')->truncate();
        DB::table('assessment_options')->truncate();
        DB::table('tags_in_assessment')->truncate();

        $questions = array(
            array(
                'question' => 'PHP server scripts are surrounded by delimiters, which?',
                'options' => array(
                    array(
                        'option' => '<?php>...</?>',
                        'correct' => 0,
                    ),
                    array(
                        'option' => '<?php...?>',
                        'correct' => 1,
                    ),
                    array(
                        'option' => '<script>...</script>',
                        'correct' => 0,
                    ),
                    array(
                        'option' => '<&>...</&>',
                        'correct' => 0,
                    )
                ),
                'tags' => array(1,2),
            )
        );

        $assessments = new Assessments;
        $assessments->saveQuiz($questions);

        $questions = array(
            array(
                'question' => 'How do you write "Hello World" in PHP',
                'options' => array(
                    array(
                        'option' => '"Hello World";',
                        'correct' => 0,
                    ),
                    array(
                        'option' => 'Document.Write("Hello World");',
                        'correct' => 0,
                    ),
                    array(
                        'option' => 'echo "Hello World";',
                        'correct' => 1,
                    )
                ),
                'tags' => array(1,2),
            )
        );
        $assessments->saveQuiz($questions);

        $questions = array(
            array(
                'question' => 'All variables in PHP start with which symbol?',
                'options' => array(
                    array(
                        'option' => '&',
                        'correct' => 0,
                    ),
                    array(
                        'option' => '!',
                        'correct' => 0,
                    ),
                    array(
                        'option' => '$',
                        'correct' => 1,
                    )
                ),
                'tags' => array(1,2),
            )
        );
        $assessments->saveQuiz($questions);

        $questions = array(
            array(
                'question' => 'What is the correct way to end a PHP statement?',
                'options' => array(
                    array(
                        'option' => '.',
                        'correct' => 0,
                    ),
                    array(
                        'option' => 'New Line',
                        'correct' => 0,
                    ),
                    array(
                        'option' => ';',
                        'correct' => 1,
                    ),
                    array(
                        'option' => '</php>',
                        'correct' => 0,
                    )
                ),
                'tags' => array(1,2),
            )
        );
        $assessments->saveQuiz($questions);

        $questions = array(
            array(
                'question' => 'When using the POST method, variables are displayed in the URL:',
                'options' => array(
                    array(
                        'option' => 'True',
                        'correct' => 0,
                    ),
                    array(
                        'option' => 'False',
                        'correct' => 1,
                    )
                ),
                'tags' => array(1,2),
            )
        );
        $assessments->saveQuiz($questions);
    }
}