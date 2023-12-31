<?php

class TeacherMap extends BaseMap
{
    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT user_id, otdel_id
        FROM teacher WHERE user_id = $id");
            $teacher = $res->fetchObject("Teacher");
            if ($teacher) {
                return $teacher;
            }
        }
        return new Teacher();
    }

    public function save($user = User, $teacher = Teacher)
    {
        if ($user->validate() && $teacher->validate() && (new UserMap())->save($user)) {
            if ($teacher->user_id == 0) {
                $teacher->user_id = $user->user_id;
                return $this->insert($teacher);
            } else {
                return $this->update($teacher);
            }
        }
        return false;
    }

    private function insert($teacher = Teacher)
    {
        if (
            $this->db->exec("INSERT INTO teacher(user_id,
        otdel_id) VALUES($teacher->user_id, $teacher->otdel_id)") == 1
        ) {
            return true;
        }
        return false;
    }

    private function update($teacher = Teacher)
    {
        if ($this->db->exec("UPDATE teacher SET otdel_id = $teacher->otdel_id WHERE user_id=" . $teacher->user_id) == 1) {
            return true;
        }
        return false;
    }
    public function findAll($ofset = 0, $limit = 30)
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT user.user_id,  CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, user.birthday, gender.name AS gender, otdel.name AS otdel, role.name AS role, branch.id AS branch FROM user 
        INNER JOIN teacher ON user.user_id=teacher.user_id 
        INNER JOIN gender ON user.gender_id=gender.gender_id 
        INNER JOIN otdel ON teacher.otdel_id=otdel.otdel_id
        INNER JOIN role ON user.role_id=role.role_id
        INNER JOIN branch ON branch.id=user.branch_id
        WHERE branch.id = {$_SESSION['branch']}
        LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        } else {
            $res = $this->db->query("SELECT user.user_id,  CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, user.birthday, gender.name AS gender, otdel.name AS otdel, role.name AS role, branch.id AS branch, branch.branch AS branch_name FROM user 
            INNER JOIN teacher ON user.user_id=teacher.user_id 
            INNER JOIN gender ON user.gender_id=gender.gender_id 
            INNER JOIN otdel ON teacher.otdel_id=otdel.otdel_id
            INNER JOIN role ON user.role_id=role.role_id
            INNER JOIN branch ON branch.id=user.branch_id
            LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
    }
    public function count()
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM teacher
        INNER JOIN user ON user.user_id = teacher.user_id
        WHERE user.branch_id = {$_SESSION['branch']}");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        } else {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM teacher");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        }
    }
    public function findProfileById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT teacher.user_id, otdel.name AS otdel, user.user_id, branch.branch FROM teacher 
            INNER JOIN user ON user.user_id=teacher.user_id 
            INNER JOIN branch ON branch.id=user.branch_id
            INNER JOIN otdel ON teacher.otdel_id=otdel.otdel_id WHERE teacher.user_id = $id");
            return $res->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }

    public function findOtdel()
    {
        $res = $this->db->query("SELECT user.user_id, otdel.otdel_id AS otdel_id FROM user 
        INNER JOIN teacher ON user.user_id=teacher.user_id 
        INNER JOIN otdel ON teacher.otdel_id=otdel.otdel_id WHERE teacher.user_id = {$_SESSION['id']}");
        return $res->fetch(PDO::FETCH_OBJ);
    }
}