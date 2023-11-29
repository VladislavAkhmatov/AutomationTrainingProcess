<?php
require_once 'secure.php';
if (!Helper::can('manager') && !Helper::can('teacher')) {
    header('Location: 404.php');
    exit();
}
$size = 5;
if (isset($_GET['page'])) {
    $page = Helper::clearInt($_GET['page']);
} else {
    $page = 1;
}
$gruppaMap = new GruppaMap();
$count = $gruppaMap->count();
$gruppas = $gruppaMap->findAll($page * $size - $size, $size);
$header = 'Список групп';
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
                    <li><a href="/index.php"><i class="fa fa-dashboard"></i> Главная</a></li>
                    <li class="active">
                        <?= $header; ?>
                    </li>
                </ol>
            </section>
            <div class="box-body">
                <?php
                if ($gruppas) {
                    ?>

                    <table id="example2" class="table table-bordered table-hover">

                        <thead>
                            <tr>
                                <th>Название</th>
                                <th>Просмотр</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            foreach ($gruppas as $gruppa) {
                                echo '<tr>';
                                if (Helper::can('teacher') || Helper::can('manager'))
                                    echo '<td><p href="view-grades.php?id=' . $gruppa->gruppa_id . '">' . $gruppa->name . '</p> ' . '<p href="add-gruppa.php?id=' . $gruppa->gruppa_id . '"></p></td>';
                                echo '<td><a class="btn btn-primary" href="add-grades.php?id=' . $gruppa->gruppa_id . '">Выставить оценки</a> ' . '<p href="add-gruppa.php?id=' . $gruppa->gruppa_id . '"></p></td>';
                            }
                            ?>
                        </tbody>
                    </table>
                <?php } else {
                    echo 'Ни одной группы не найдено';
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