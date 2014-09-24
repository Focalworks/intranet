<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 20/9/14
 * Time: 5:42 PM
 */

class GrievanceTest extends TestCase
{
    public function testSomethingIsTrue()
    {
        $user = Sentry::findUserById(3);
        if ($user->email == 'amitav.roy@focalworks.in') {
            echo "\nUser correct: " . $user->email . "\n";
            $this->assertTrue(true);
        } else {
            $this->assertFalse(true);
        }
    }

    public function testCheckGrievance()
    {
        
    }
} 