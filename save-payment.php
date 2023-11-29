<?php
require_once 'secure.php';
if (!Helper::can('procreator')) {
    header('Location: 404.php');
    exit();
}
if (isset($_POST['savePayment'])) {
    $student = new Student();
    $student->user_id = Helper::clearInt($_POST['user_id']);
    $student->subject_id = Helper::clearInt($_POST['subject_id']);
    $student->subject_count = Helper::clearInt($_POST['subject_count']);
    $student->subject_price = Helper::clearInt($_POST['subject_price']);
    $student->tab = time() . $_FILES["fileToUpload"]["name"];
    $fileTmpName = $_FILES["fileToUpload"]["tmp_name"];

    move_uploaded_file($fileTmpName, "uploads/" . $student->tab);

    if ((new StudentMap())->savePayment($student)) {
        header('Location: check-child.php');
    } else {
        if ($student->user_id) {

            header('Location: check-child.php');

        } else {
            header('Location: check-child.php');
        }
    }
}
