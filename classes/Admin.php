<?php
class Admin extends Table
{
    public $user_id = 0;
    public $branch_id = 0;
    function validate()
    {
        if (!empty($this->branch_id)) {
            return true;
        }
        return false;
    }
}
