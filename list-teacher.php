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
$teacherMap = new TeacherMap();
$count = $teacherMap->count();
$teachers = $teacherMap->findAll($page * $size - $size, $size);
$header = 'Список преподавателей';
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Список преподавателей</h1>
                <ol class="breadcrumb">
                    <li><a href="/index.php"><i class="fa
fa-dashboard"></i> Главная</a></li>
                    <li class="active">Список
                        преподавателей</li>
                </ol>
            </section>
            <div class="box-body">
                <?php if (Helper::can('admin')) { ?>
                    <a class="btn btn-success" href="add-teacher.php">Добавить преподавателя</a>
                <?php } ?>

            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <?php
                if ($teachers) {
                    ?>

                    <table id="example2" class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Ф.И.О</th>
                                <th>Дата рождения</th>
                                <th>Отделение</th>
                                <?php if (Helper::can('manager')) { ?>
                                    <th>Филиал</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($teachers as $teacher) {
                                echo '<tr>';
                                if (Helper::can('admin')) {
                                    echo '<td><a href="profile-teacher.php?id=' . $teacher->user_id . '">' . $teacher->fio . '</a> ' . '<a href="add-teacher.php?id=' . $teacher->user_id . '"><i class="fa fa-pencil"></i></a></td>';
                                } elseif (Helper::can('manager')) {
                                    echo '<td><a href="profile-teacher.php?id=' . $teacher->user_id . '">' . $teacher->fio . '</a> ' . '<a href="add-teacher.php?id=' . $teacher->user_id . '"></a></td>';
                                } else {
                                    echo '<td><p>' . $teacher->fio . '</p> ';
                                }
                                echo '<td>' . $teacher->birthday . '</td>';
                                echo '<td>' . $teacher->otdel . '</td>';
                                if (Helper::can('manager'))
                                    echo '<td>' . $teacher->branch_name . '</td>';
                                echo '</tr>';

                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo 'Ни одного преподавателя не найдено';
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