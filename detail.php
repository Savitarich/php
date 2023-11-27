<!DOCTYPE html>
<html lang="sk">

<?php
define('BASE_URL', 'C:/OpenServer/domains/biopaliva/');
define('BASE_URL_HTTP', 'http://biopaliva/');
require_once(BASE_URL . 'controllers/records/ashRetrievalRecords/ashRetrievalRecordsController.php');
require_once(BASE_URL . 'database/database.php');
require_once(BASE_URL . 'templates/head.php');


?>

<body>
    <?php require_once(BASE_URL . 'templates/header.php') ?>

    <div class="main">
        <?php if (!isset($_SESSION['userName'])) : ?>
            <?php require(BASE_URL . 'pages/log.php'); ?>
        <?php elseif (!$showAshRetrievalRecords) : ?>
            <?php require(BASE_URL . 'controllers/user/logout.php'); ?>
        <?php else : ?>
            <div class="table-detail">
                <div class="container">
                    <div class="table-detail__row">
                        <h2 class="table-detail__title">Záznam o doprave popola</h2>
                        <nav class="table-detail__nav">
                            <ul class="table-detail__nav-list">
                                <li class="table-detail__nav-item"><a href="index.php" class="table-detail__table-button button">Všetky záznamy</a></li>
                                <li class="table-detail__nav-item">
                                    <a href="edit.php?record_id=<?= $id ?>" class="table-detail__table-button button">Upraviť</a>
                                </li>
                                <li class="table-detail__nav-item">
                                    <a href="edit.php?delete_id=<?= $id ?>" class="table-detail__table-button table-detail__table-delete button" onclick="return confirm('Naozaj chcete odstrániť záznam?')">Odstrániť</a>
                                </li>
                            </ul>
                        </nav>
                        <table class="table-detail__table">
                            <tbody>
                                <tr>
                                    <td>Názov</td>
                                    <td><?= $oneRecord['Name'] ?></td>
                                </tr>
                                <tr>
                                    <td>ID</td>
                                    <td><?= $oneRecord['StringId'] ?></td>
                                </tr>
                                <tr>
                                    <td>Šofér</td>
                                    <td><?php $driver = selectOne('aspnetusers', ['id' => $oneRecord['DriverId']]) ?>
                                        <?php if ($driver) : ?>
                                            <?= $driver['Surname'] . ' ' . $driver['FirstName'] ?>
                                        <?php endif ?></td>
                                </tr>
                                <tr>
                                    <td>EČV</td>
                                    <td><?= selectOne('machines', ['id' => $oneRecord['VehicleId']])['LicensePlateNumber'] ?></td>
                                </tr>
                                <tr>
                                    <td>Odvozné miesto</td>
                                    <td>
                                        <table>
                                            <tbody>
                                                <tr>
                                                    <td><?= selectOne('transporttargetlocations', ['id' => $oneRecord['TargetLocationId']])['name'] ?></td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        <div class="create__form-map-row">
                                                            <div class="create__form-map" id="map" style="height: 200px; width: 300px"></div>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Miesto nakládky</td>
                                    <td><?= selectOne('warehouses', ['id' => $oneRecord['WarehouseId']])['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Dátum</td>
                                    <td><?= substr($oneRecord['Date'], 0, 10) ?></td>
                                </tr>
                                <tr>
                                    <td>Cieľová poloha</td>
                                    <td><?= selectOne('ashsporttargetlocations', ['id' => $oneRecord['TargetLocationId']])['name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Jednotka objemu</td>
                                    <td><?= selectOne('customenumvalues', ['id' => $oneRecord['UnitId']])['Name'] ?></td>
                                </tr>
                                <tr>
                                    <td>Objem</td>
                                    <td><?= $oneRecord['Amount'] ?></td>
                                </tr>
                                <tr>
                                    <td>Prideleného užívateľa</td>
                                    <td><?= selectOne('aspnetusers', ['id' => $oneRecord['AssignedUserId']])['UserName'] ?></td>
                                </tr>
                                <tr>
                                    <td>Aktualizované</td>
                                    <td><?= substr($oneRecord['Updated'], 0, 16) ?></td>
                                </tr>
                                <tr>
                                    <td>Vytvorené</td>
                                    <td><?= substr($oneRecord['Created'], 0, 16) ?></td>
                                </tr>
                                <tr>
                                    <td>Poznámka</td>
                                    <td><?= $oneRecord['Note'] ?></td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>


    <?php require_once(BASE_URL . 'templates/footer.php') ?>
    <?php $transportTargetLocation = selectOne('transporttargetlocations', ['id' => $oneRecord['TargetLocationId']]) ?>
    <script>
        const transportTargetLocation = <?php echo json_encode($transportTargetLocation); ?>;
        const BASE_URL_HTTP = <?php echo json_encode(BASE_URL_HTTP); ?>;
    </script>
    <?php require_once(BASE_URL . 'templates/scripts.php') ?>
    <script src="<?= BASE_URL_HTTP ?>assets/js/mapForDetail.js"></script>
</body>

</html>