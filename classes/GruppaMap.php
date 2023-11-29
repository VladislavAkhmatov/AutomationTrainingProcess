<?php
class GruppaMap extends BaseMap
{
    public function arrGruppas()
    {
        $res = $this->db->query("SELECT gruppa.gruppa_id AS id, gruppa.name AS value, branch.id AS branch FROM gruppa
        INNER JOIN branch ON branch.id = gruppa.branch
        WHERE branch.id = {$_SESSION['branch']}
        ");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }
    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT gruppa_id, name, special_id, date_begin, date_end, branch FROM gruppa WHERE gruppa_id = $id and branch = {$_SESSION['branch']}");
            return $res->fetchObject("Gruppa");
        }
        return new Gruppa();
    }
    public function save($gruppa = Gruppa)
    {
        if ($gruppa->validate()) {
            if ($gruppa->gruppa_id == 0) {
                return $this->insert($gruppa);
            } else {
                return $this->update($gruppa);
            }
        }
        return false;
    }
    private function insert($gruppa = Gruppa)
    {
        $name = $this->db->quote($gruppa->name);
        $date_begin = $this->db->quote($gruppa->date_begin);
        $date_end = $this->db->quote($gruppa->date_end);
        if (
            $this->db->exec("INSERT INTO gruppa(name, special_id,
        date_begin, date_end, branch)"
                . " VALUES($name, $gruppa->special_id, $date_begin, $date_end, {$_SESSION['branch']})") == 1
        ) {
            $gruppa->gruppa_id = $this->db->lastInsertId();
            return true;
        }
        return false;
    }
    private function update($gruppa = Gruppa)
    {
        $name = $this->db->quote($gruppa->name);
        $date_begin = $this->db->quote($gruppa->date_begin);
        $date_end = $this->db->quote($gruppa->date_end);
        if (
            $this->db->exec("UPDATE gruppa SET name = $name,
        special_id = $gruppa->special_id,"
                . " date_begin = $date_begin, date_end = $date_end WHERE gruppa_id = " . $gruppa->gruppa_id) == 1
        ) {
            return true;
        }
        return false;
    }
    public function findAll($ofset = 0, $limit = 30)
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT gruppa.gruppa_id, gruppa.name, special.name AS special, gruppa.date_begin, gruppa.date_end, branch.id FROM gruppa 
        INNER JOIN special ON gruppa.special_id=special.special_id 
        INNER JOIN branch ON gruppa.branch=branch.id 
        WHERE branch.id = {$_SESSION['branch']} LIMIT $ofset,
        $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        } else {
            $res = $this->db->query("SELECT gruppa.gruppa_id, gruppa.name, special.name AS special, gruppa.date_begin, gruppa.date_end, branch.id, branch.branch FROM gruppa 
            INNER JOIN special ON gruppa.special_id=special.special_id 
            INNER JOIN branch ON gruppa.branch=branch.id 
            LIMIT $ofset,
            $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
    }
    public function count()
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM gruppa
            WHERE branch = {$_SESSION['branch']}");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        } else{
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM gruppa");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        }

    }
    public function findViewById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT gruppa.gruppa_id, gruppa.name, special.name AS special, gruppa.date_begin, gruppa.date_end, branch.branch FROM gruppa 
            INNER JOIN special ON gruppa.special_id=special.special_id
            INNER JOIN branch ON branch.id=gruppa.branch WHERE gruppa_id = $id");
            return $res->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }
}