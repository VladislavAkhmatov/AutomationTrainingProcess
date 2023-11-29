<?php
require_once 'secure.php';
if (!Helper::can('admin') && !Helper::can('manager')) {
    header('Location: 404.php');
    exit();
}
if (isset($_GET['id'])) {
    $id = Helper::clearInt($_GET['id']);
} else {
    header('Location: 404.php');
}
$header = 'Профиль родителя';
$teacher = (new TeacherMap())->findProfileById($id);
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Профиль родителя</h1>
                <ol class="breadcrumb">
                    <li><a href="index.php"><i class="fa fa-dashboard"></i> Главная</a></li>

                    <li><a href="list-parent.php">Родители</a></li>

                    <li class="active">Профиль</li>
                </ol>
            </section>
            <div class="box-body">

                <a class="btn btn-success" href="add-parent.php?id=<?= $id; ?>">Изменить</a>

            </div>
            <div class="box-body">

                <table class="table table-bordered table-
hover">

                    <?php require_once '_profile.php'; ?>

                </table>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'template/footer.php';
?>