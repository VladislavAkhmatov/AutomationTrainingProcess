<?php
require_once 'secure.php';
if (!Helper::can('admin') && !Helper::can('manager') && !Helper::can('teacher') && !Helper::can('parent')) {
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
$header = 'Список родителей';
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Список родителей</h1>
                <ol class="breadcrumb">
                    <li><a href="/index.php"><i class="fa
fa-dashboard"></i> Главная</a></li>
                    <li class="active">Список
                        родителей</li>
                </ol>
            </section>
            <div class="box-body">
                <?php if (Helper::can('admin') || Helper::can('manager')) { ?>
                    <a class="btn btn-success" href="add-parent.php">Добавить родителя</a>
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
                                <th>Пол</th>
                                <th>Дата рождения</th>
                                <th>Роль</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($teachers as $teacher) {
                                echo '<tr>';
                                if (Helper::can('manager') || Helper::can('admin'))
                                    echo '<td><a href="profile-teacher.php?id=' . $teacher->user_id . '">' . $teacher->fio . '</a> ' . '<a href="add-teacher.php?id=' . $teacher->user_id . '"><i class="fa fa-pencil"></i></a></td>';
                                else
                                    echo '<td><p>' . $teacher->fio . '</p> ' . '<a href="add-teacher.php?id=' . $teacher->user_id . '"></a></td>';
                                echo '<td>' . $teacher->gender . '</td>';
                                echo '<td>' . $teacher->birthday . '</td>';
                                echo '<td>' . $teacher->otdel . '</td>';
                                echo '<td>' . $teacher->role . '</td>';
                                echo '</tr>';

                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo 'Ни одного родителя не найдено';
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