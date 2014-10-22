<?php

class MailTracker extends Eloquent
{
    // defining the name of the table
    protected $table = 'mail_tracker';

    /**
     * This is the public function to send the email.
     *
     * Rest of the internal activities will be handled by this function.
     *
     * @param
     *          email address to send the email $mail_to_address
     * @param
     *          email address to from which we want to send the email
     *          $mail_from_address
     * @param
     *          email subject $mail_subject
     * @param
     *          email body content (typically a view) $mail_body
     * @param
     *          the name which will come in the to field $mail_to_name
     * @param
     *          the name which will come in from field $mail_from_name
     */
    public function sendMail ($mail_to_address, $mail_from_address, $mail_subject,
        $mail_body, $mail_to_name = null, $mail_from_name = null)
    {
        Log::info('Mail send function');
        // adding the entry to the table
        $mail_id = $this->addMailTrackerEntry($mail_to_address, $mail_from_address,
            $mail_subject, $mail_body, $mail_to_name, $mail_from_name);

        // sending the email after the entry is made to the table
        $this->triggerMail($mail_id);
    }

    /**
     * This function will add an entry in the Mail tracker table about the email
     * which is being sent.
     * This function will return the mail_id which is being added into the table.
     * This is an internal
     * function which is called by the public function. After send mail the status
     * and send time is
     * getting updated.
     * @param $mail_to_address
     * @param $mail_from_address
     * @param $mail_subject
     * @param $mail_body
     * @param $mail_to_name
     * @param $mail_from_name
     *
     * @return mixed
     */
    protected function addMailTrackerEntry ($mail_to_address, $mail_from_address,
        $mail_subject, $mail_body, $mail_to_name, $mail_from_name)
    {
        $row_data = array(
            'mail_to_address' => $mail_to_address,
            'mail_to_name' => ($mail_to_name == null) ? $mail_to_address : $mail_to_name,
            'mail_from_address' => $mail_from_address,
            'mail_from_name' => ($mail_from_name == null) ? $mail_from_address : $mail_from_name,
            'mail_subject' => $mail_subject,
            'mail_body' => $mail_body,
            'mail_created' => time()
        );

        return DB::table($this->table)->insertGetId($row_data);
    }

    /**
     * This function will udate the sent time of the email and status.
     * This is an internal function and it is being called by the public function.
     *
     * @param unknown $mail_id
     */
    protected function updateMailStatus ($mail_id)
    {
        DB::table($this->table)->where('mail_id', $mail_id)->update(
            array(
                'mail_sent' => time(),
                'mail_status' => 1
            ));
    }

    /**
     * This function will initiate the send email function.
     *
     * @param unknown $mail_id
     */
    protected function triggerMail ($mail_id)
    {
        if ($this->decideSendingMethod($mail_id))
        {
            // once the mail has been sent, updating the mail status and sent time
            $this->updateMailStatus($mail_id);
        }
    }

    /**
     * This function will decide based on the application setting
     * which method to use for sending mails based on the config.
     *
     * @param unknown $mail_id
     *
     * @return bool
     */
    protected function decideSendingMethod ($mail_id)
    {
        $mail_config = Config::get('mailing::mail.method');

        switch ($mail_config)
        {
            case 'smtp':
            {
                $smtp_config = Config::get('mailing::mail.' . $mail_config);
                if ($this->sendMailSMTP($mail_id, $smtp_config))
                    return true;
            }
        }
    }

    /**
     * This function will send the email using SMTP
     *
     * @param unknown $mail_id
     *
     * @param         $smtp_config
     *
     * @return bool
     */
    protected function sendMailSMTP ($mail_id, $smtp_config)
    {
        $mail_row = DB::table($this->table)->where('mail_id', $mail_id)->first();

        // setting the server, port and encryption
        $transport = Swift_SmtpTransport::newInstance($smtp_config['server'],
            $smtp_config['port'], $smtp_config['encryption'])->setUsername(
                $smtp_config['username'])->setPassword($smtp_config['password']);

        // creating the Swift_Mailer instance and pass the config settings
        $mailer = Swift_Mailer::newInstance($transport);

        // configuring the Swift mail instance with all details
        $message = Swift_Message::newInstance($mail_row->mail_subject)->setFrom(
            array(
                $mail_row->mail_from_address => $mail_row->mail_from_name
            ))
            ->setTo(
                array(
                    $mail_row->mail_to_address => $mail_row->mail_to_name
                ))
            ->setBody($mail_row->mail_body, 'text/html');
        try
        {
            $mailer->send($message);
            return true;
        } catch (Exception $e)
        {
            die('Error sending email. ' . $e);
        }
    }

    public function getMailEntries()
    {
        return DB::table($this->table)->orderBy('mail_created', 'desc');
    }

    /**
     * This function will trim a string based on the length supplied.
     * It takes care of any incomplete word and trims the sentence.
     * @param $string
     * @param $limit
     * @return string
     */
    public static function trim_text($string, $limit)
    {
        $bound = $limit;
        // if string length less than 70 then return without modification
        if (strlen($string) <= $bound)
        {
            return $string;
        }

        $string_in_bounds = substr($string, 0, $bound); // getting the string inside allowed char limit
        $string_array = explode(" ", $string_in_bounds); // exploding to an array and getting rid of last word.
        $number_words = count($string_array) - 1;

        $final_string = '';
        foreach ($string_array as $key => $word)
        {
            if ($key != $number_words)
            {
                $final_string .= $word . ' ';
            }
        }

        $final_string = substr($final_string, 0, -1); // removing the trailing space.

        return $final_string;
    }

    /**
     * Extending the trim text function this function will add dots to the text.
     * @param $string
     * @param $limit
     * @return string
     */
    public static function trim_text_with_dots($string, $limit)
    {
        $string = MailTracker::trim_text($string, $limit);
        return $string . '&#8230;';
    }
}