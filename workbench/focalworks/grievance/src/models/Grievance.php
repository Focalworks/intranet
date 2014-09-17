<?php

class Grievance extends Eloquent
{

    protected $table = 'grievances';
    
    public function getGrievance($id)
    {
        $table = $this->table;
        $arrSelect = array(
            $table.'.id', $table.'.title', $table.'.description', $table.'.category',
            $table.'.urgency', $table.'.user_id', $table.'.status', $table.'.created_at', $table.'.updated_at',
            'file_managed.id as fid', 'file_managed.url', 'file_managed.filemime', 'file_managed.filesize'
        );
        $FileManaged = new FileManaged;
        $query = DB::table($this->table)->where($this->table.'.id', $id);
        $query->select($arrSelect);
        $query->join('file_managed', 'file_managed.entity_id', '=', $this->table . '.id', 'left');
        return $query;
    }
    
    public function deleteGrievance($id)
    {
        $Grievance = Grievance::find($id);
        
        $Grievance->delete();
        
        $FileManaged = FileManaged::find($Grievance->id);
        
        File::delete($FileManaged->url);
    }

    /**
     * Mapping the status with their DB ids.
     * If id is not passed, then the full array is returned 
     * which can be used for dropdown and other uses. 
     * @param unknown $id
     * @return Status name <string>
     */
    public static function getStatusName($id = null)
    {
        $arrStatus = array(
            '1' => 'Submitted',
            '2' => 'In progress',
            '3' => 'Closed',
            '4' => 'Re opened',
        );
        
        if ($id != null) {
            return $arrStatus[$id];
        } else {
            return $arrStatus;
        }
    }

    /**
     * This is the array of Categories for the grievance / suggestion
     * The array is used to map and also generate the drop downs.
     * 
     * @param string $id            
     * @return Ambigous <string>|multitype:string
     */
    public static function getGrievanceCategories($id = null)
    {
        $arrGrievance = array(
            'complain' => 'Complain',
            'rules' => 'Rules',
            'facility' => 'Facility',
            'hygiene' => 'Hygiene'
        );
        
        if ($id != null) {
            return $arrGrievance[$id];
        } else {
            return $arrGrievance;
        }
    }
    
    public static function getUrgencies($id = null)
    {
        $arrUrgencies = array(
            '3' => 'High',
            '2' => 'Medium',
            '1' => 'Low'
        );
        
        if ($id != null) {
            return $arrUrgencies[$id];
        } else {
            return $arrUrgencies;
        }
    }

    public static function sortColumnLinkHelper($sortArray, $key, $sortBy)
    {
        $columnName = ucwords($key);

        // special case of column names
        switch ($columnName) {
            case 'Created_at':
                $columnName = 'Created at';
                break;
        }

        $linkString = url('grievance/list?sortby=' . $key . '&order=' . $sortArray[$key]);

        if ($sortBy == $key) {
          //$aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'<span class="glyphicon glyphicon-sort"></span></a>';
          if($sortArray[$sortBy] == 'asc') {
            $aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-arrow-down"></span></a>';
          } else {
            $aLink = '<a href="'.$linkString.'" class="'.$sortArray[$sortBy].'">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-arrow-up"></span></a>';
          }

        } else {
            $aLink = '<a href="'.$linkString.'" class="normal">'.$columnName.'&nbsp;<span class="glyphicon glyphicon-sort"></span></a>';
        }

        return $aLink;
    }
}