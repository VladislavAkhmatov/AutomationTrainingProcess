<?php
require_once 'secure.php';
if (!Helper::can('admin') && !Helper::can('manager')) {
    header('Location: 404.php');
    exit();
}
if (isset($_POST['user_id'])) {
    $user = new User();
    $user->lastname = Helper::clearString($_POST['lastname']);
    $user->user_id = Helper::clearInt($_POST['user_id']);
    $user->firstname = Helper::clearString($_POST['firstname']);
    $user->patronymic = Helper::clearString($_POST['patronymic']);
    $user->birthday = Helper::clearString($_POST['birthday']);
    $user->login = Helper::clearString($_POST['login']);
    $user->pass = password_hash(
        Helper::clearString($_POST['password']),
        PASSWORD_BCRYPT
    );
    $user->gender_id = Helper::clearInt($_POST['gender_id']);
    $user->role_id = Helper::clearInt($_POST['role_id']);
    if (Helper::can('manager')) {
        $user->branch_id = Helper::clearInt($_POST['branch_id']);
    } else {
        $user->branch_id = $_SESSION['branch'];
    }
    $user->active = Helper::clearInt($_POST['active']);

    if (isset($_POST['saveTeacher'])) {
        $teacher = new Teacher();
        $teacher->otdel_id = Helper::clearInt($_POST['otdel_id']);
        $teacher->user_id = $user->user_id;
        if ((new TeacherMap())->save($user, $teacher)) {

            header('Location: profile-teacher.php?id=' . $teacher->user_id);

        } else {
            if ($teacher->user_id) {

                header('Location: add-teacher.php?id=' . $teacher->user_id);

            } else {
                header('Location: add-teacher.php');
            }
        }
        exit();
    }

    if (isset($_POST['saveParent'])) {
        $parent = new Procreator();
        $parent->user_id = $user->user_id;
        if ((new ProcreatorMap())->save($user, $parent)) {

            header('Location: profile-parent.php?id=' . $parent->user_id);

        } else {
            if ($parent->user_id) {

                header('Location: add-parent.php?id=' . $parent->user_id);

            } else {
                header('Location: add-parent.php');
            }
        }
        exit();
    }

    if (isset($_POST['saveStudent'])) {
        $student = new Student();
        $student->gruppa_id = Helper::clearInt($_POST['gruppa_id']);
        $student->user_id = $user->user_id;
        if ((new StudentMap())->save($user, $student)) {

            header('Location: profile-student.php?id=' . $student->user_id);

        } else {
            if ($student->user_id) {

                header('Location: add-student.php?id=' . $student->user_id);

            } else {
                header('Location: add-student.php');
            }
        }
        exit();
    }

    if (isset($_POST['saveAdmin'])) {
        $admin = new Admin();
        $admin->branch_id = Helper::clearInt($_POST['branch_id']);
        $admin->user_id = $user->user_id;
        if ((new AdminMap())->save($user, $admin)) {

            header('Location: profile-admin.php?id=' . $admin->user_id);

        } else {
            if ($admin->user_id) {

                header('Location: add-admin.php?id=' . $admin->user_id);

            } else {
                header('Location: add-admin.php');
            }
        }
        exit();
    }
}