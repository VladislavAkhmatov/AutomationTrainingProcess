<?php
class ProcreatorMap extends BaseMap
{

    public function arrParents()
    {
        $res = $this->db->query("SELECT user.user_id AS id, CONCAT(user.lastname, ' ', user.firstname, ' ', user.patronymic) AS value, branch.id AS branch FROM user
        INNER JOIN branch ON branch.id = user.branch_id
        WHERE user.role_id = 6 and user.branch_id = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }



    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT user_id, child_id FROM parent WHERE user_id = $id");
            return $res->fetchObject("Procreator");
        }
        return new Procreator();
    }

    public function save($user = User, $parent = Parent)
    {
        if ($user->validate() && $parent->validate() && (new UserMap())->save($user)) {
            if ($parent->user_id == 0) {
                $parent->user_id = $user->user_id;
                return $this->insert($parent);
            }
        }
        return false;
    }


    private function insert($parent = Parent)
    {
        if (
            $this->db->exec("INSERT INTO parent(user_id) VALUES($parent->user_id)") == 1
        ) {
            return true;
        }
        return false;
    }

    public function saveChild($parent = Parent)
    {
        return $this->insertChild($parent);
    }

    private function insertChild($parent = Parent)
    {
        if (
            $this->db->exec("INSERT INTO parent(user_id, child_id) VALUES($parent->user_id, $parent->child_id)") == 1
        ) {
            return true;
        }
        return false;
    }

    public function findAll($ofset = 0, $limit = 30)
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT DISTINCT
            user.user_id,
            CONCAT(procreator.lastname,' ', procreator.firstname, ' ', procreator.patronymic) AS parent_fio, 
            CONCAT(child.lastname,' ', child.firstname, ' ', child.patronymic) AS child_fio, 
            gender.name as gender, 
            user.birthday as birthday,
            branch.branch as branch
            FROM parent
            INNER JOIN user as procreator on procreator.user_id = parent.user_id
            INNER JOIN user as child on child.user_id = parent.child_id
            INNER JOIN user on user.user_id = parent.user_id
            INNER JOIN branch on user.branch_id = branch.id
            INNER JOIN gender ON user.gender_id = gender.gender_id
            WHERE user.branch_id = {$_SESSION['branch']}
            LIMIT $ofset, $limit");
        } else {
            $res = $this->db->query("SELECT DISTINCT
            user.user_id,
            CONCAT(procreator.lastname,' ', procreator.firstname, ' ', procreator.patronymic) AS parent_fio, 
            CONCAT(child.lastname,' ', child.firstname, ' ', child.patronymic) AS child_fio, 
            gender.name as gender, 
            user.birthday as birthday,
            branch.branch as branch
            FROM parent
            INNER JOIN user as procreator on procreator.user_id = parent.user_id
            INNER JOIN user as child on child.user_id = parent.child_id
            INNER JOIN user on user.user_id = parent.user_id
            INNER JOIN branch on user.branch_id = branch.id
            INNER JOIN gender ON user.gender_id = gender.gender_id LIMIT $ofset, $limit");
        }
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function count()
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM parent
        INNER JOIN user ON user.user_id = parent.user_id
        WHERE user.branch_id = {$_SESSION['branch']}");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        } else {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM parent");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        }
    }
}