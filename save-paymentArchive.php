<?php
require_once 'secure.php';
if (!Helper::can('manager')) {
    header('Location: 404.php');
    exit();
}

if (isset($_POST['paymentSubmit'])) {
    $student = new Student();
    $studentMap = new StudentMap();
    $paymentArchives = $studentMap->selectGrades();
    $student->parent_id = Helper::clearInt($_POST['parent_id']);
    $student->user_id = Helper::clearInt($_POST['child_id']);
    $student->subject_id = Helper::clearInt($_POST['subject_id']);
    $student->id = Helper::clearInt($_POST['id']);
    $student->count = $_POST['count'];
    $student->tab = $_POST['tab'];
    $student->price = $_POST['price'];
    $student->attend = 1;
    $flag = true;

    foreach ($paymentArchives as $paymentArchive) {
        if ($paymentArchive->child_id == $student->user_id && $paymentArchive->subject_id == $student->subject_id) {
            (new StudentMap())->saveUpdatePaymentArchive($student);
            header('Location: check-payment.php');
            exit();
        }
    }

    (new StudentMap())->savePaymentArchive($student);
    header('Location: check-payment.php');
    exit();
}




if (isset($_POST['paymentDelete'])) {
    $student = new Student();
    $student->id = Helper::clearInt($_POST['id']);


    if ((new StudentMap())->deletePayment($student)) {
        header('Location: check-payment.php');

    } else {
        if ($student->user_id) {
            header('Location: check-payment.php');
        } else {
            header('Location: check-payment.php');
        }
    }
}
