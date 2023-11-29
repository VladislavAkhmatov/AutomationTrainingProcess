<?php
require_once 'secure.php';
if (!Helper::can('procreator')) {
    header('Location: 404.php');
    exit();
}
require_once 'template/header.php';

?>

<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <ol class="breadcrumb">
                    <li><i class="fa fa-dashboard"></i> Главная</li>
                </ol>
            </section>
            <div class="box-body">
                <a class="btn btn-success" style="width: 150px;" href="view-grades.php">Оценки</a>
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <a class="btn btn-success" style="width: 150px;" href="view-performance.php">Посещаемость</a>
            </div>

        </div>
    </div>
</div>

<?php
require_once 'template/footer.php';
?>