<?php
class GradeMap extends BaseMap
{
    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT grade_id, user_id,
        subject_id, grade, date"
                . "FROM grades WHERE grade_id = $id");
            return $res->fetchObject("Grade");
        }
        return new Grade();
    }
}