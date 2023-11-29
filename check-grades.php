<?php
require_once 'secure.php';

if (!Helper::can('admin') && !Helper::can('manager') && !Helper::can('teacher')) {
    header('Location: 404.php');
    exit();
}

$size = 10;

if (isset($_GET['page'])) {
    $page = Helper::clearInt($_GET['page']);
} else {
    $page = 1;
}

if (isset($_GET['id'])) {
    $id = Helper::clearInt($_GET['id']);
} else {
    $id = 1;
}

$studentMap = new StudentMap();
$count = $studentMap->count();
$students = $studentMap->checkGrades();
$header = 'Список студентов';
require_once 'template/header.php';

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Список студентов</h1>
                <ol class="breadcrumb">
                    <li><a href="/index.php"><i class="fa fa-dashboard"></i> Главная</a></li>
                    <li class="active">Список студентов</li>

                </ol>
            </section>
            <div class="box-body">

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php if ($students) { ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Ф.И.О</th>
                                <th>Предмет</th>
                                <th>Оценка</th>
                                <th>Дата</th>
                                <th>Посеща-емость</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($students as $student) {
                                echo "<tr>";
                                echo "<td>" . $student->fio . "</td>";
                                echo "<td>" . $student->subject . "</td>";
                                echo "<td>" . $student->grade . "</td>";
                                echo "<td>" . $student->date . "</td>";
                                echo "<td>" . $student->attend . "</td>";
                                echo "<td>" . '<form action="save-grades.php" method="post">
                                                        <input type="hidden" name="grade_id" value="' . $student->id . '">
                                                        <input type="hidden" name="user_id" value="' . $student->user_id . '">
                                                        <input type="hidden" name="subject_id" value="' . $student->subject_id . '">
                                                        <input type="hidden" name="grade" value="' . $student->grade . '">
                                                        <input type="hidden" name="date" value="' . $student->date . '">
                                                        <input type="hidden" name="attend" value="' . $student->attend_id . '">
                                                        <input class="btn btn-success" type="submit" name="gradeSubmit" value="Подтвердить">
                                                        <input class="btn btn-danger" type="submit" name="gradeDelete" value="Отклонить">
                                                        </form>' . "</td>";

                                echo "</tr>";
                            }
                } else {
                    echo 'Ни одного студента не найдено';
                }

                ?>
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</div>

<?php
require_once 'template/footer.php';
?>