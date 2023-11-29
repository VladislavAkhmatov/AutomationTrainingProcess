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
$studentMap = new StudentMap();
$count = $studentMap->count();
$student = $studentMap->findAll($page * $size - $size, $size);
$header = 'Список студентов';
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Список студентов</h1>
                <ol class="breadcrumb">
                    <li><a href="/index.php"><i class="fa
fa-dashboard"></i> Главная</a></li>
                    <li class="active">Список
                        студента</li>
                </ol>
            </section>
            <div class="box-body">
                <?php if (Helper::can('admin')) { ?>
                    <a class="btn btn-success" href="add-student.php">Добавить студента</a>
                <?php } ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                if ($student) {
                    ?>

                    <table id="example2" class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Ф.И.О</th>
                                <th>Дата рождения</th>
                                <th>Группа</th>
                                <?php if (Helper::can('manager')) { ?>
                                    <th>Филиал</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($student as $student) {
                                echo '<tr>';
                                if (Helper::can('admin')) {
                                    echo '<td><a href="profile-student.php?id=' . $student->user_id . '">' . $student->fio . '</a> ' . '<a href="add-student.php?id=' . $student->user_id . '"><i class="fa fa-pencil"></i></a></td>';
                                } elseif (Helper::can('manager')) {
                                    echo '<td><a href="profile-student.php?id=' . $student->user_id . '">' . $student->fio . '</a> ' . '<a href="add-student.php?id=' . $student->user_id . '"></a></td>';
                                } else {
                                    echo '<td><p>' . $student->fio . '</p> ';
                                }
                                echo '<td>' . $student->birthday . '</td>';
                                echo '<td>' . $student->gruppa . '</td>';
                                if (Helper::can('manager'))
                                    echo '<td>' . $student->branch_name . '</td>';
                                echo '</tr>';

                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo 'Ни одного студента не найдено';
                } ?>
            </div>
            <div class="box-body">
                <?php Helper::paginator($count, $page, $size); ?>
            </div>
            <!-- /.box-body -->
        </div>
    </div>
</div>
<?php
require_once 'template/footer.php';
?>