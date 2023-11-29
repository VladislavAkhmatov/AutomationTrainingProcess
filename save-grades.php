<?php
require_once 'secure.php';
if (!Helper::can('admin')) {
    header('Location: 404.php');
    exit();
}



if (isset($_POST['gradeSubmit'])) {
    $student = new Student();
    $studentMap = new StudentMap();
    $paymentArchives = $studentMap->selectGrades();
    $student->grade_id = Helper::clearInt($_POST['grade_id']);
    $student->user_id = Helper::clearInt($_POST['user_id']);
    $student->subject_id = Helper::clearInt($_POST['subject_id']);
    $student->grade = Helper::clearInt($_POST['grade']);
    $student->date = Helper::clearString($_POST['date']);
    $student->attend = Helper::clearInt($_POST['attend']);

    foreach ($paymentArchives as $paymentArchive) {
        if ($paymentArchive->child_id == $student->user_id && $paymentArchive->subject_id && $student->subject_id) {
            (new StudentMap())->saveUpdateGrades($student);
            header('Location: check-grades.php');
            exit();
        }
    }

    (new StudentMap())->saveGrades($student);
    header('Location: check-grades.php');
    exit();

}


if (isset($_POST['gradeDelete'])) {
    $student = new Student();
    $student->grade_id = Helper::clearInt($_POST['grade_id']);
    if ((new StudentMap())->deleteGrades($student)) {
        header('Location: check-grades.php');
    } else {
        if ($student->user_id) {

            header('Location: check-grades.php');

        } else {
            header('Location: check-grades.php');
        }
    }
}
