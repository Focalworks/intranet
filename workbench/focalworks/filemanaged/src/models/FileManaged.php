<?php 

class FileManaged extends Eloquent
{    
    protected $table = 'file_managed';
    
    /**
     * This function is saving a new entry to the FileManaged table.
     * @param unknown $data
     */
    public function saveFileInfo($data)
    {
        $userObj = Session::get('userObj');
        $data['user_id'] = $userObj->id;
        $data['created_at'] = date('Y-m-d G:H:s', time());
        $data['updated_at'] = date('Y-m-d G:H:s', time());
        
        DB::table($this->table)->insert($data);
    }
    
    /**
     * This function is used to update any entry in the FileManaged table.
     * @param unknown $id
     * @param unknown $data
     */
    public function updateFileInfo($id, $data)
    {
        $data['updated_at'] = date('Y-m-d G:H:s', time());
        DB::table($this->table)->where('id', $id)->update($data);
    }
    
    public function getFileFromURL($url, $destination)
    {
        $arrFileInfo = pathinfo($url);
        
        // getting the file extension
        $fileExt = $arrFileInfo['extension'];
    }
}