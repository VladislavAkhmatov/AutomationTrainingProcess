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
$header = 'Профиль студента';
$student = (new StudentMap())->findProfileById($id);
require_once 'template/header.php';
?>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <section class="content-header">
                <h1>Профиль студента</h1>
                <ol class="breadcrumb">
                    <li><a href="index.php"><i class="fa
fa-dashboard"></i> Главная</a></li>

                    <li><a href="list-student.php">Студенты</a></li>

                    <li class="active">Профиль</li>
                </ol>
            </section>
            <div class="box-body">
                <?php if (Helper::can('admin')) { ?>
                    <a class="btn btn-success" href="add-student.php?id=<?= $id; ?>">Изменить</a>
                <?php } ?>

            </div>
            <div class="box-body">

                <table class="table table-bordered table-
hover">

                    <?php require_once '_profile.php'; ?>

                    <tr>

                        <th>Группа</th>

                        <td>
                            <?= $student->gruppa; ?>
                        </td>


                    </tr>

                    <?php if (Helper::can('manager')) { ?>

                        <tr>

                            <th>Филиал</th>

                            <td>
                                <?= $student->branch; ?>
                            </td>


                        </tr>
                    <?php } ?>

                </table>
            </div>
        </div>
    </div>
</div>
<?php
require_once 'template/footer.php';
?>