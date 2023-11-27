<!DOCTYPE html>
<html lang="sk">

<?php
define('BASE_URL', 'C:/OpenServer/domains/biopaliva/');
define('BASE_URL_HTTP', 'http://biopaliva/');
require_once(BASE_URL . 'database/database.php');
require_once(BASE_URL . 'templates/head.php');
?>

<body>
    <?php require_once(BASE_URL . 'templates/header.php') ?>
    <?php
    if ($isAdmin) {
        $records = selectAll('ashretrievalrecords');
    } else {
        $records = selectAll('ashretrievalrecords', ['AssignedUserId' => $_SESSION['id']]);
    }
    ?>

    <div class="main">
        <?php if (!isset($_SESSION['userName'])) : ?>
            <?php require(BASE_URL . 'pages/log.php'); ?>
        <?php elseif (!$showAshRetrievalRecords) : ?>
            <?php require(BASE_URL . 'controllers/user/logout.php'); ?>
        <?php else : ?>
            <div class="table-page">
                <div class="container">
                    <div class="table-page__row">
                        <h2 class="table-page__title">Záznamy o doprave popola</h2>
                        <nav class="table-page__nav">
                            <ul class="table-page__nav-list">
                                <li class="table-page__nav-item"><a class="table-page__nav-button button" href="create.php">Vytvoriť</a></li>

                            </ul>
                        </nav>
                        <div class="table-page__rotate-msg" id="rotateDeviceMsg">
                            <p>Please rotate your device</p>
                        </div>
                        <table id="table-page_col-4" class="table table-striped" style="width:100%">
                            <thead>
                                <tr class="table-page__tr">
                                    <th class="table-page__th table-page__th-td">ID</th>
                                    <th class="table-page__th table-page__th-td">Šofér</th>
                                    <th class="table-page__th table-page__th-td">EČV vozidla</th>
                                    <th class="table-page__th table-page__th-td">Miesto nakládky</th>
                                    <th class="table-page__th table-page__th-td">Dátum</th>
                                    <th class="table-page__th table-page__th-td">Miesto vykládky</th>
                                    <th class="table-page__th table-page__th-td">Objem</th>
                                    <th class="table-page__th table-page__th-td">Jednotka objemu</th>
                                    <th class="table-page__th table-page__th-td">Aktualizované</th>
                                    <th class="table-page__th table-page__th-td">Vytvorené</th>
                                    <th class="table-page__th table-page__th-td">Akcie</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($records as $record) : ?>
                                    <tr class="table-page__tr">
                                        <td class="table-page__td table-page__th-td"><?= $record['StringId'] ?></td>
                                        <td class="table-page__td table-page__th-td">
                                            <?php $driver = selectOne('aspnetusers', ['id' => $record['DriverId']]) ?>
                                            <?php if ($driver) : ?>
                                                <?= $driver['Surname'] . ' ' . $driver['FirstName'] ?>
                                            <?php endif ?>
                                        </td>
                                        <td class="table-page__td table-page__th-td">
                                            <?= selectOne('machines', ['id' => $record['VehicleId']])['LicensePlateNumber'] ?>
                                        </td>
                                        <td class="table-page__td table-page__th-td">
                                            <?= selectOne('warehouses', ['id' => $record['WarehouseId']])['name'] ?>
                                        </td>
                                        <td class="table-page__td table-page__th-td"><?= $record['Date'] ?></td>
                                        <td class="table-page__td table-page__th-td">
                                            <?= selectOne('ashsporttargetlocations', ['id' => $record['TargetLocationId']])['name'] ?>
                                        </td>
                                        <td class="table-page__td table-page__th-td"><?= $record['Amount'] ?></td>
                                        <td class="table-page__td table-page__th-td">
                                            <?= selectOne('customenumvalues', ['id' => $record['UnitId']])['Name'] ?>
                                        </td>
                                        <td class="table-page__td table-page__th-td"><?= substr($record['Updated'], 0, 16) ?></td>
                                        <td class="table-page__td table-page__th-td"><?= substr($record['Created'], 0, 16) ?></td>
                                        <td class="table-page__td table-page__td-buttons table-page__th-td">
                                            <a href="detail.php?record_id=<?= $record['id'] ?>" class="table-page__table-button button">Viac</a>
                                            <a href="edit.php?record_id=<?= $record['id'] ?>" class="table-page__table-button button">Upraviť</a>
                                            <a href="edit.php?delete_id=<?= $record['id'] ?>" class="table-page__table-button table-page__table-delete button" onclick="return confirm('Naozaj chcete odstrániť záznam?')">Odstrániť</a>
                                        </td>
                                    </tr>
                                <?php endforeach ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>


    <?php require_once(BASE_URL . 'templates/footer.php') ?>

    <?php require_once(BASE_URL . 'templates/scripts.php') ?>
</body>

</html>