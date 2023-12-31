<?php
class ClassroomMap extends BaseMap
{
    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT classroom_id, name FROM classroom WHERE classroom_id = $id");
            return $res->fetchObject("Classroom");
        }
        return new Classroom();
    }
    public function save($classroom = Classroom)
    {
        if ($classroom->validate()) {
            if ($classroom->classroom_id == 0) {
                return $this->insert($classroom);
            } else {
                return $this->update($classroom);
            }
        }
        return false;
    }

    private function insert($classroom = Classroom)
    {
        $name = $this->db->quote($classroom->name);
        $active = $this->db->quote($classroom->active);
        if (
            $this->db->exec("INSERT INTO classroom(name, branch, active)"
                . " VALUES($name, {$_SESSION['branch']} ,$active)") == 1
        ) {
            $classroom->classroom_id = $this->db->lastInsertId();
            return true;
        }
        return false;
    }


    private function update($classroom = Classroom)
    {
        $name = $this->db->quote($classroom->name);
        if ($this->db->exec("UPDATE classroom SET name = $name WHERE classroom_id = " . $classroom->classroom_id) == 1) {
            return true;
        }
        return false;
    }

    public function findAll($ofset = 0, $limit = 30)
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT classroom.classroom_id, classroom.name, branch.id FROM classroom
            INNER JOIN branch ON branch.id = classroom.branch
            WHERE branch.id = {$_SESSION['branch']} LIMIT $ofset,
            $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        } else {
            $res = $this->db->query("SELECT classroom.classroom_id, classroom.name, branch.id, branch.branch FROM classroom
            INNER JOIN branch ON branch.id = classroom.branch
            LIMIT $ofset,
            $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function count()
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM classroom WHERE branch = {$_SESSION['branch']}");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        } else {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM classroom WHERE branch");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        }
    }

    public function findViewById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT classroom.classroom_id, classroom.name, branch.branch FROM classroom 
            INNER JOIN branch ON branch.id = classroom.branch WHERE classroom_id = $id");
            return $res->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }
    public function arrClassrooms()
    {
        $res = $this->db->query("SELECT classroom_id AS id, name AS value, branch AS branch FROM classroom 
        WHERE active=1 and branch = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

}
