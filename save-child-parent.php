<?php
require_once 'secure.php';
if (!Helper::can('admin')) {
    header('Location: 404.php');
    exit();
}
if (isset($_POST['saveChildParent'])) {
    $parent = new Procreator();
    $parent->user_id = Helper::clearInt($_POST['user_id']);
    $parent->child_id = Helper::clearInt($_POST['child_id']);
    $parentMap = new ProcreatorMap();
    if ($parentMap->saveChild($parent)) {

        header('Location: list-parent.php');
    } else {
        header('Location: add-child-parent.php');
    }
}
