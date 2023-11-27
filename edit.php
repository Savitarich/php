<!DOCTYPE html>
<html lang="sk">

<?php
define('BASE_URL', 'C:/OpenServer/domains/biopaliva/');
define('BASE_URL_HTTP', 'http://biopaliva/');
require_once(BASE_URL . 'controllers/records/ashRetrievalRecords/ashRetrievalRecordsController.php');
require_once(BASE_URL . 'templates/head.php');
require_once(BASE_URL . 'database/database.php');
?>

<body>
    <?php require_once(BASE_URL . 'templates/header.php') ?>

    <div class="main">
        <?php if (!isset($_SESSION['userName'])) : ?>
            <?php require(BASE_URL . 'pages/log.php'); ?>
        <?php elseif (!$showAshRetrievalRecords) : ?>
            <?php require(BASE_URL . 'controllers/user/logout.php'); ?>
        <?php else : ?>
            <div class="create">
                <div class="container">
                    <div class="create__row">
                        <ul class="create__nav-list table-detail__nav-list">
                            <li class="create__nav-item table-detail__nav-item"><a href="index.php" class="table-detail__table-button button">Všetky záznamy</a></li>
                        </ul>
                        <form id="createPageForm" class="create__form" action="edit.php" method="post">
                            <h2 class="create__title">Záznam o doprave popola</h2>
                            <input name="id" type="hidden" value="<?php echo $id; ?>">

                            <div class="create__form-block">
                                <label for="driver" class="create__form-label">Šofér:</label>
                                <select class="create__form-select" id="driver" name="driver_id">
                                    <option></option>
                                    <?php foreach ($drivers as $driver) : ?>
                                        <option <?php if ($driver['id'] == $oneRecord['DriverId']) : echo 'selected';
                                                endif  ?> value="<?= $driver['id'] ?>"><?= $driver['Surname'] . ' ' . $driver['FirstName'] ?></option>
                                    <?php endforeach ?>
                                </select><br>

                                <label for="vehicle" class="create__form-label">EČV vozidla:</label>
                                <select class="create__form-select" id="vehicle" name="vehicle_id">
                                    <option></option>
                                    <?php foreach ($vehicles as $vehicle) : ?>
                                        <option <?php if ($vehicle['id'] == $oneRecord['VehicleId']) : echo 'selected';
                                                endif  ?> value="<?= $vehicle['id'] ?>"><?= $vehicle['LicensePlateNumber'] ?></option>
                                    <?php endforeach ?>
                                </select><br>
                                <label class="create__form-label">Miesto nakládky:</label>
                                <select class="create__form-select" name="warehouse_id">
                                    <option></option>
                                    <?php foreach ($warehouses as $warehouse) : ?>
                                        <option <?php if ($warehouse['id'] == $oneRecord['WarehouseId']) : echo 'selected';
                                                endif  ?> value="<?= $warehouse['id'] ?>"><?= $warehouse['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <label class="create__form-label">Miesto vykládky:</label> <br>
                                <select class="create__form-select" name="load_target_locations-select">
                                    <option></option>
                                    <?php foreach ($sourceTargetLocations as $sourceTargetLocation) : ?>
                                        <option <?php if ($sourceTargetLocation['id'] === $oneRecord['TargetLocationId']) : ?> selected <?php endif ?> value="<?= $sourceTargetLocation['id'] ?>"><?= $sourceTargetLocation['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <label class="create__form-label">Dátum:</label>
                                <input class="create__form-input" value="<?php echo $oneRecord['Date'] ?>" type="datetime-local" name="date" lang="en"><br>
                            </div>
                            <div class="create__form-block">
                                <label class="create__form-label">Jednotka objemu:</label>
                                <select class="create__form-select" name="unit_id">
                                    <option <?php if ($oneRecord['UnitId'] == 1) : echo 'selected' ?> <?php endif ?> value="1">t</option>
                                    <option <?php if ($oneRecord['UnitId'] == 2) : echo 'selected' ?> <?php endif ?> value="2">prm</option>
                                </select>

                                <label class="create__form-label">Objem:</label>
                                <input class="create__form-input" type="number" step="0.01" value="<?= $oneRecord['Amount'] ?>" name="amount" min="0" max="99999"><br>

                                <label class="create__form-label">Poznámky:</label>
                                <input class="create__form-input" type="text" name="note" value="<?= $oneRecord['Note'] ?>"><br>
                            </div>
                            <?php if ($errorMessage) : ?>
                                <p class="create__form-error"><?= $errorMessage ?></p>
                            <?php endif ?>
                            <input type="submit" name="update" class="button create__form-button" value="Uložiť zmeny">

                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php require_once(BASE_URL . 'templates/footer.php') ?>

    <?php require_once(BASE_URL . 'templates/scripts.php') ?>
</body>

</html>