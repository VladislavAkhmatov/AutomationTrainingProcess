<?php
class Procreator extends Table
{
    public $user_id = 0;
    public $child_id = 0;
    function validate()
    {
        return true;
    }
}