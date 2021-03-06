<?php
/**
 * Created by PhpStorm.
 * User: amitav
 * Date: 16/9/14
 * Time: 12:12 PM
 */

class ApiController extends BaseController
{
    private $user;

    public function __construct()
    {
        /*header("Access-Control-Allow-Origin: *"); // this is required for cross domain.
        if (isset($_SERVER['HTTP_EMAIL']))
        {
            $user = Sentry::findUserByLogin($_SERVER['HTTP_EMAIL']);
            if ($user) {
                $this->user = $user;
            } else {
                App::abort(500, 'Access denied');
            }
        }*/
    }

    public function getGrievanceList()
    {
        $Grievance = new Grievance;

        $ids = DB::table('grievances')->where('user_id', $this->user->id)->lists('id');

        $grievances = array();

        foreach ($ids as $id) {
            $grievances[] = $Grievance->getGrievance($id);
        }

        return $grievances;
    }

    public function postGrievanceSave()
    {
        $title = Input::get('title');
        $body = Input::get('body');
        $category = Input::get('category');
        $urgency = Input::get('urgency');

        $Grievance = new Grievance;

        $Grievance->title = $title;
        $Grievance->description = $body;
        $Grievance->category = $category;
        $Grievance->urgency = $urgency;
        $Grievance->user_id = $this->user->id;
        $Grievance->status = 1;

        $Grievance->save();

        if (Input::get('image')) {
            $image_binary_data = base64_decode(Input::get('image'));
            $folder_path = 'grievance/' . $this->user->id . '/';
            $filename = time() . '.jpg';
            $file_with_path = $folder_path . $filename;
            $source = imagecreatefromstring($image_binary_data);

            if (!file_exists($folder_path)) {
                mkdir($folder_path, 0777, true);
            }

            $imageSave = imagejpeg($source,$file_with_path,60);
            imagedestroy($source);

            // building the data before saving
            $fileManagedData = array(
              'user_id' => $this->user->id,
              'entity' => GRIEVANCE,
              'entity_id' => $Grievance->id,
              'filename' => $filename,
              'url' => $folder_path . $filename,
              'filemime' => 'image/jpeg',
              'filesize' => Input::get('file_size'),
            );

            $image = Image::make(public_path($folder_path . $filename));

            $image->resize(null, 240, function ($constraint)
            {
                $constraint->aspectRatio();
            });
            $image->save(public_path($folder_path . $filename));

            $FileManaged = new FileManaged;
            $FileManaged->saveFileInfo($fileManagedData);
        }

        return $Grievance;
    }

    public function getQuestionsList()
    {
        $data = array(
          array(
            'question' => 'How many days, months are there in a year?',
            'correct_response' => 'There are twenty-four hours in a day, 30 days in a month, and 12 months in the calendar year.',
          ),
          array(
            'question' => 'Which sentense has all alphabets in it?',
            'correct_response' => 'The quick brown fox jumps over the lazy dog.',
          ),
        );
        return $data;
    }

    public function getQuizQuestions()
    {
        $questions = array(
          array(
            'id' => 22,
            'question' => 'What does PHP stand for',
            'options' => array('Paragraph Hypertext processor', 'PHP', 'Point Hypertext Processor', 'Print'),
            'correct' => 'PHP'
          ),
          array(
            'id' => 25,
            'question' => 'Which function runs when a class is executed?',
            'options' => array('__init()', '__construct', 'As as class name', 'None'),
            'correct' => '__construct'
          ),
        );

        return $questions;
    }
}