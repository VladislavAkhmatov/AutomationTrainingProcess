<?php
require_once 'secure.php';
if (!Helper::can('admin') && !Helper::can('manager')) {
    header('Location: 404.php');
    exit();
}
$size = 5;
if (isset($_GET['page'])) {
    $page = Helper::clearInt($_GET['page']);
} else {
    $page = 1;
}
$classroomMap = new ClassroomMap();
$count = $classroomMap->count();
$arrClassrooms = $classroomMap->findAll($page * $size - $size, $size);
$header = 'Список аудиторий';
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>
                    <?= $header; ?>
                </h1>
                <ol class="breadcrumb">
                    <li><a href="/index.php"><i class="fafa-dashboard"></i> Главная</a></li>
                    <li class="active">
                        <?= $header; ?>
                    </li>
                </ol>
            </section>
            <div class="box-body">
                <?php if (Helper::can('admin')) { ?>
                    <a class="btn btn-success" href="add-classroom.php">Добавить аудиторию</a>
                <?php }
                ; ?>
            </div>
            <div class="box-body">
                <?php
                if ($arrClassrooms) {
                    ?>
                    <table id="example2" class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th>Название</th>
                                <?php if (Helper::can('manager')) { ?>
                                    <th>Филиал</th>
                                <?php } ?>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($arrClassrooms as $classroom) {
                                echo '<tr>';
                                if (Helper::can('admin')) {
                                    echo '<td><a href="view-classroom.php?id=' . $classroom->classroom_id . '">' . $classroom->name . '</a> '
                                        . '<a href="add-classroom.php?id=' . $classroom->classroom_id . '"><i class="fa fa-pencil"></i></a></td>';
                                } elseif (Helper::can('manager')) {
                                    echo '<td><a href="view-classroom.php?id=' . $classroom->classroom_id . '">' . $classroom->name . '</a> '
                                        . '<a href="add-classroom.php?id=' . $classroom->classroom_id . '"></a></td>';
                                    echo '<td>' . $classroom->branch . '</td>';
                                }
                                echo '</tr>';

                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo 'Ни одной аудитории не найдено';
                } ?>
            </div>
            <div class="box-body">
                <?php Helper::paginator(
                    $count,
                    $page,
                    $size
                ); ?>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'template/footer.php';
?>