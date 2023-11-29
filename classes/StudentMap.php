<?php

class StudentMap extends BaseMap
{
    public function arrStudents()
    {
        $res = $this->db->query("SELECT user.user_id AS id, CONCAT(user.lastname, ' ', user.firstname, ' ', user.patronymic) AS value, branch.id AS branch FROM user
        INNER JOIN branch ON branch.id = user.branch_id
        WHERE user.role_id = 5 and user.branch_id = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function arrAttends()
    {
        $res = $this->db->query("SELECT attend.id as id, attend.attend as value FROM attend");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function arrSubjectFromBranch()
    {
        $teacherMap = new TeacherMap();
        $teacher = $teacherMap->findOtdel();
        $res = $this->db->query("SELECT subject.subject_id as id, subject.name as value FROM subject WHERE subject.otdel_id = $teacher->otdel_id");
        return $res->fetchAll(PDO::FETCH_ASSOC);
    }

    public function findById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT user_id, gruppa_id
        FROM student WHERE user_id = $id");
            $student = $res->fetchObject("Student");
            if ($student) {
                return $student;
            }
        }
        return new Student();
    }
    public function save($user = User, $student = Student)
    {
        if ($user->validate() && $student->validate() && (new UserMap())->save($user)) {
            if ($student->user_id == 0) {
                $student->user_id = $user->user_id;
                return $this->insert($student);
            } else {
                return $this->update($student);
            }
        }
        return false;
    }
    public function savePayment($student = Student)
    {
        return $this->insertPayment($student);
    }

    private function insertPayment($student = Student)
    {
        if (
            $this->db->exec("INSERT INTO payment(parent_id, child_id, subject_id, count, tab, price) VALUES({$_SESSION['id']}, $student->user_id, 
            $student->subject_id, $student->subject_count, '$student->tab', $student->subject_price)") == 1
        ) {
            return true;
        }
        return false;
    }

    private function insert($student = Student)
    {
        if (
            $this->db->exec("INSERT INTO student(user_id,
        gruppa_id, num_zach) VALUES($student->user_id, $student->gruppa_id, $student->num_zach)") == 1
        ) {
            return true;
        }
        return false;
    }

    public function savePaymentArchive($student = Student)
    {

        return $this->insertPaymentArchive($student);

    }

    public function deletePayment($student = Student)
    {
        $res = $this->db->query("DELETE FROM payment WHERE id = $student->id");
    }

    public function saveUpdatePaymentArchive($student = Student)
    {

        return $this->updatePaymentArchive($student);

    }


    private function insertPaymentArchive($student = Student)
    {
        if (
            $this->db->exec("INSERT INTO 
            payment_archive (parent_id, child_id, subject_id, count, tab, price, attend) 
            VALUES($student->parent_id, $student->user_id, 
            $student->subject_id, $student->count, '$student->tab', $student->price, $student->attend)
            ") == 1
        ) {
            $res = $this->db->query("DELETE FROM payment WHERE id = $student->id");
            return true;
        }
        return false;
    }

    public function checkPaymentArchive()
    {
        $res = $this->db->query("SELECT payment_archive.child_id as child_id, payment_archive.subject_id as subject_id FROM payment_archive");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    private function updatePaymentArchive($student = Student)
    {
        if ($this->db->exec("UPDATE payment_archive SET count = count + $student->count WHERE child_id=" . $student->user_id . " and subject_id=" . $student->subject_id) == 1) {
            $res = $this->db->query("DELETE FROM payment WHERE id = $student->id");
            return true;
        }
        return false;
    }


    private function update($student = Student)
    {
        if ($this->db->exec("UPDATE student SET gruppa_id = $student->gruppa_id WHERE user_id=" . $student->user_id) == 1) {
            return true;
        }
        return false;
    }
    public function findAll($ofset = 0, $limit = 30)
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT user.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, user.birthday, gender.name AS gender, gruppa.name AS gruppa, role.name AS role, branch.id AS branch FROM user 
            INNER JOIN student ON user.user_id=student.user_id 
            INNER JOIN gender ON user.gender_id=gender.gender_id 
            INNER JOIN gruppa ON student.gruppa_id=gruppa.gruppa_id 
            INNER JOIN role ON user.role_id=role.role_id
            INNER JOIN branch ON user.branch_id = branch.id
            WHERE branch.id = {$_SESSION['branch']}
            LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        } else {
            $res = $this->db->query("SELECT user.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, user.birthday, gender.name AS gender, gruppa.name AS gruppa, role.name AS role, branch.id AS branch, branch.branch AS branch_name FROM user 
            INNER JOIN student ON user.user_id=student.user_id 
            INNER JOIN gender ON user.gender_id=gender.gender_id 
            INNER JOIN gruppa ON student.gruppa_id=gruppa.gruppa_id 
            INNER JOIN role ON user.role_id=role.role_id
            INNER JOIN branch ON user.branch_id = branch.id
            LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }

    }
    public function findStudentsFromGroup($id = null, $ofset = 0, $limit = 30)
    {
        if ($id) {
            $res = $this->db->query("SELECT user.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, user.birthday, gender.name AS gender, gruppa.name AS gruppa, 
            role.name AS role, branch.id as branch FROM user 
            INNER JOIN student ON user.user_id=student.user_id 
            INNER JOIN gender ON user.gender_id=gender.gender_id 
            INNER JOIN gruppa ON student.gruppa_id=gruppa.gruppa_id 
            INNER JOIN role ON user.role_id=role.role_id 
            INNER JOIN branch ON user.branch_id = branch.id 
            WHERE gruppa.gruppa_id = $id AND branch.id = {$_SESSION['branch']} LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function findStudentsFromGrades($id = null, $ofset = 0, $limit = 30)
    {
        if ($id) {
            $res = $this->db->query("SELECT user.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio,  
            branch.id as branch FROM user 
            INNER JOIN student ON user.user_id=student.user_id 
            INNER JOIN branch ON user.branch_id = branch.id 
            WHERE branch.id = {$_SESSION['branch']} LIMIT $ofset, $limit");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
    }

    public function checkGrades()
    {
        $res = $this->db->query("SELECT grades.grade_id as id, user.user_id AS user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, subject.subject_id AS subject_id, subject.name AS subject, grades.grade AS grade, grades.date AS date, attend.attend as attend, attend.id as attend_id, branch.id AS branch FROM user
            INNER JOIN grades ON user.user_id = grades.user_id
            INNER JOIN subject on subject.subject_id=grades.subject_id
            INNER JOIN attend on attend.id = grades.attend
            INNER JOIN branch on branch.id = user.branch_id
            WHERE branch.id = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function viewGrades()
    {
        $res = $this->db->query("SELECT parent.child_id as child_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, subject.name as subject, grade_accept.grade as grade, 
            grade_accept.date as date, branch.id as branch, user.user_id as user_id FROM parent
            INNER JOIN user ON user.user_id = parent.child_id
            INNER JOIN grade_accept ON grade_accept.user_id = parent.child_id
            INNER JOIN subject ON subject.subject_id = grade_accept.subject_id
            INNER JOIN branch ON branch.id = user.branch_id
            WHERE parent.user_id = {$_SESSION['id']} and grade_accept.grade != 0 and branch.id = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function selectGrades()
    {
        $res = $this->db->query("SELECT payment_archive.child_id as child_id, payment_archive.subject_id as subject_id FROM payment_archive");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function viewPerformance()
    {
        $res = $this->db->query("SELECT parent.child_id as child_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, subject.name as subject, grade_accept.date as date, attend.attend as attend, branch.id as branch, user.user_id as user_id
            FROM parent
            INNER JOIN user ON user.user_id = parent.child_id
            INNER JOIN grade_accept ON grade_accept.user_id = parent.child_id
            INNER JOIN subject ON subject.subject_id = grade_accept.subject_id
            INNER JOIN attend ON attend.id = grade_accept.attend
            INNER JOIN branch ON branch.id = user.branch_id
            WHERE parent.user_id = {$_SESSION['id']} and (grade_accept.grade = 0 or grade_accept.grade is null) and branch.id = {$_SESSION['branch']}");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }

    public function saveGrades($student = Student)
    {

        return $this->insertGrades($student);

    }

    public function saveUpdateGrades($student = Student)
    {

        return $this->updateGrades($student);

    }

    public function insertGrades($student = Student)
    {
        if (
            $this->db->exec("INSERT INTO grade_accept (user_id, subject_id, grade, date, attend) VALUES ($student->user_id, $student->subject_id, '$student->grade', '$student->date', $student->attend)
            ") == 1
        ) {
            $res = $this->db->query("DELETE FROM grades WHERE grade_id = '$student->grade_id'");
            return true;
        }
        return false;
    }

    public function updateGrades($student = Student)
    {
        if (
            $this->db->exec("UPDATE payment_archive SET count = count - 1
            WHERE child_id=" . $student->user_id . " and subject_id=" . $student->subject_id) == 1
        ) {
            $res = $this->db->query("INSERT INTO grade_accept (user_id, subject_id, grade, date, attend) VALUES ($student->user_id, $student->subject_id, '$student->grade', '$student->date', $student->attend)");
            $res2 = $this->db->query("DELETE FROM grades WHERE grade_id = '$student->grade_id'");
            return true;
        }
        return false;
    }

    public function deleteGrades($student = Student)
    {
        $res = $this->db->query("DELETE FROM grades WHERE grade_id = $student->grade_id");
    }


    public function findStudentsFromParent($ofset = 0, $limit = 30)
    {
        $res = $this->db->query("SELECT DISTINCT user.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio FROM parent
        INNER JOIN user ON user.user_id = parent.child_id
        WHERE parent.user_id = {$_SESSION['id']}
        LIMIT $ofset, $limit");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }


    public function count()
    {
        if ($_SESSION['branch'] != 999) {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM student
        INNER JOIN user ON user.user_id = student.user_id
        WHERE user.branch_id = {$_SESSION['branch']}");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        } else {
            $res = $this->db->query("SELECT COUNT(*) AS cnt FROM student");
            return $res->fetch(PDO::FETCH_OBJ)->cnt;
        }
    }
    public function findProfileById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT student.user_id, gruppa.name AS gruppa, user.user_id, branch.branch FROM student 
            INNER JOIN user ON user.user_id=student.user_id 
            INNER JOIN branch ON branch.id=user.branch_id 
            INNER JOIN gruppa ON student.gruppa_id=gruppa.gruppa_id WHERE student.user_id = $id");
            return $res->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }

    public function Payment()
    {
        $res = $this->db->query("SELECT 
        payment.id as id, 
        payment.parent_id, 
        payment.child_id as user_id, 
        CONCAT(parent.lastname,' ', parent.firstname, ' ', parent.patronymic) AS parent_fio, 
        CONCAT(child.lastname,' ', child.firstname, ' ', child.patronymic) AS child_fio, 
        subject.subject_id as subject_id,
        subject.name as subject, 
        payment.count as count, 
        payment.tab as tab, 
        payment.price as price 
        FROM payment
        INNER JOIN user AS parent ON parent.user_id = payment.parent_id
        INNER JOIN user AS child ON child.user_id = payment.child_id
        INNER JOIN subject ON payment.subject_id = subject.subject_id");
        return $res->fetchAll(PDO::FETCH_OBJ);
    }
    public function findStudentById($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT student.user_id, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio FROM student 
            INNER JOIN user ON user.user_id=student.user_id
            WHERE student.user_id = $id");
            return $res->fetch(PDO::FETCH_OBJ);
        }
        return false;
    }

    public function findStudentByControl($id = null)
    {
        if ($id) {
            $res = $this->db->query("SELECT payment_archive.id as id, payment_archive.child_id as child, CONCAT(user.lastname,' ', user.firstname, ' ', user.patronymic) AS fio, subject.name as subject, payment_archive.count FROM payment_archive
            INNER JOIN subject ON subject.subject_id = payment_archive.subject_id
            INNER JOIN user ON user.user_id=payment_archive.child_id
            WHERE payment_archive.child_id = $id");
            return $res->fetchAll(PDO::FETCH_OBJ);
        }
        return false;
    }


    public function saveAddGrades($student = Student)
    {

        return $this->addGrades($student);

    }
    public function addGrades($student = null)
    {
        if (!$student || !is_object($student)) {
            echo "Объект не существует или не является объектом.";
            return false;
        }

        $query = "INSERT INTO grades (user_id, subject_id, grade, date, attend) VALUES (:user_id, :subject_id, :grade, NOW(), :attend)";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $student->user_id, PDO::PARAM_INT);
        $stmt->bindParam(':subject_id', $student->subject_id, PDO::PARAM_INT);
        $stmt->bindParam(':grade', $student->grade);
        $stmt->bindParam(':attend', $student->attend, PDO::PARAM_INT);

        return $stmt->execute();
    }
}