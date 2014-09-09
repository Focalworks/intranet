<?php

class GlobalHelper {
    /**
     * This function will be used to spit out the variable dump.
     * It takes two parameters, one is the array / variable
     * and the second is flag for exit.
     * @param  array  $var  [array or variable]
     * @param  boolean $exit [should continue or exit]
     * @return none
     */
    public static function dsm($var, $exit = false)
    {
        print '<pre>';
        print_r($var);
        print '</pre>';
    
        if ($exit ===  true)
            exit;
    }
    
    /**
     * This function will set the message in session so that when the page renders,
     * we can display a message on top of the page.
     * @param $message
     * @param string $flag
     */
    public static function setMessage($message, $flag = 'info')
    {
        $tempMessage = '';
        if (Session::get('message'))
            $tempMessage = Session::get('message');
    
        if ($tempMessage == "")
            $tempMessage = $message;
        else
            $tempMessage = $tempMessage . '<br />' . $message;
    
        Session::flash('message', $tempMessage);
        Session::flash('message-flag', $flag);
    }
    
    /**
     * This is a generic function to generate a drop down from an array.
     * @param unknown $name
     * @param unknown $array
     * @param string $selected
     * @return string
     */
    public static function getDropdownFromArray($name, $array, $selected = null)
    {
        $output = "<select name=\"{$name}\" class=\"form-control\">";
        
        $output .= "<option value=\"\">SELECT</option>";
        
        foreach ($array as $key => $value) {
            if ($selected != null && $selected == $key) {
                $output .= "<option value=\"{$key}\" selected>{$value}</option>";
            } else {
                $output .= "<option value=\"{$key}\">{$value}</option>";
            }
        }
        
        $output .= "</select>";
        
        return $output;
    }
    
    public static function formatDate($dateString, $format)
    {
        $time = strtotime($dateString);
        return date($format, $time);
    }
    
    /**
     * Convert a string to the file/URL safe "slug" form
     *
     * @param string $string the string to clean
     * @param bool $is_filename TRUE will allow additional filename characters
     * @return string
     */
    public static function sanitize($string = '', $is_filename = FALSE)
    {
        // Replace all weird characters with dashes
        $string = preg_replace('/[^\w\-'. ($is_filename ? '~_\.' : ''). ']+/u', '-', $string);
    
        // Only allow one dash separator at a time (and make string lowercase)
        return mb_strtolower(preg_replace('/--+/u', '-', $string), 'UTF-8');
    }
}