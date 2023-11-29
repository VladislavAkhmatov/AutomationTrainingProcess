<?php
class Student extends Table
{
    public $id = 0;
    public $user_id = 0;
    public $parent_id = 0;
    public $gruppa_id = 0;
    public $subject_id = 0;
    public $subject_count = 0;
    public $count = 0;
    public $price = 0;
    public $grade_id = 0;
    public $date = date;
    public $tab = '';
    public $grade = NULL;
    public $subject_price = 0;
    public $attend = 0;
    public $num_zach = 0;
    function validate()
    {
        if (!empty($this->gruppa_id)) {
            return true;
        }
        return false;
    }
}
