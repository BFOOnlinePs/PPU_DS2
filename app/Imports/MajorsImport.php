<?php

namespace App\Imports;

use App\Models\Major;
use Maatwebsite\Excel\Concerns\ToModel;

class MajorsImport implements ToModel
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    protected $additionalData , $startRow , $cnt , $majors_array;
    public function __construct($additionalData)
    {
        $this->additionalData = $additionalData;
        $this->startRow = 1;
        $this->cnt = 0;
        $this->majors_array = array();
    }
    public function model(array $row)
    {
        return 'Mohamad Maraqa';
        if ($this->startRow == 1) {
            $this->startRow++;
            return null;
        }
        $major = Major::where('m_reference_code' , $row[$this->additionalData['major_id']])
                        ->where('m_name' , $row[$this->additionalData['major_name']])
                        ->exists();
        if ($major || empty($row[$this->additionalData['major_id']]) || empty($row[$this->additionalData['major_name']])) {
            return null; // Skip this row
        }
        $return_major = new Major([
            'm_name' => $row[$this->additionalData['major_name']],
            'm_description' => '',
            'm_reference_code' => $row[$this->additionalData['major_id']],
        ]);
        if($return_major->save()) {
            $this->cnt++;
            array_push($this->majors_array , $row[$this->additionalData['major_id']]);
            array_push($this->majors_array , $row[$this->additionalData['major_name']]);
            return $return_major;
        }
        else {
            return null;
        }
    }
    public function getCount()
    {
        return $this->cnt;
    }
    public function getMajorsArray()
    {
        return $this->majors_array;
    }
}
