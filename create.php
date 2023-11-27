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
                        <form id="createPageForm" class="create__form" action="create.php" method="post">
                            <h2 class="create__title">Záznam o doprave popola</h2>
                            <div class="create__form-block">
                                <label class="create__form-label">Šofér:</label>
                                <select class="create__form-select" id="driver" name="driverId">
                                    <option></option>
                                    <?php foreach ($drivers as $driver) : ?>
                                        <option <?php if (isset($driver_id)) : ?> <?php if ($driver['id'] == $driver_id) : echo 'selected';
                                                                                    endif  ?> <?php endif  ?> value="<?= $driver['id'] ?>"><?= $driver['Surname'] . ' ' . $driver['FirstName'] ?></option>
                                    <?php endforeach ?>
                                </select><br>

                                <label class="create__form-label">EČV vozidla:</label>
                                <select class="create__form-select" id="vehicle" name="vehicleId">
                                    <option></option>
                                    <?php foreach ($vehicles as $vehicle) : ?>
                                        <option <?php if (isset($vehicle_id)) : ?> <?php if ($vehicle['id'] == $vehicle_id) : echo 'selected';
                                                                                    endif  ?> <?php endif  ?> value="<?= $vehicle['id'] ?>"><?= $vehicle['LicensePlateNumber'] ?></option>
                                    <?php endforeach ?>
                                </select><br>
                                <label class="create__form-label">Miesto nakládky:</label>
                                <select class="create__form-select" name="warehouse_id">
                                    <option></option>
                                    <?php foreach ($warehouses as $warehouse) : ?>
                                        <option value="<?= $warehouse['id'] ?>"><?php echo   $warehouse['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                                <label class="create__form-label">Dátum:</label>
                                <input class="create__form-input" value="<?php echo isset($date) ? $date : ''; ?>" type="datetime-local" id="date_load" name="date" lang="en"><br>
                            </div>
                            <div class="create__form-block">
                                <label class="create__form-label">Miesto vykládky:</label> <br>

                                <label class="create__form-label">
                                    <input class="create__form-radio" checked type="radio" name="target-location_switch-radio" value="target_location">Pridajte nové miesto
                                </label>

                                <label class="create__form-label">
                                    <input class="create__form-radio" type="radio" name="target-location_switch-radio" value="target_location">Zoznam existujúcich miest
                                </label>


                                <div class="target-location__form-subblock">
                                    <div class="create__form-map-row">

                                        <div class="create__form-map" id="map" style="height: 260px;"></div>

                                        <input class="create__form-input" type="text" name="map_name" value="<?php echo isset($map_name) ? $map_name : ''; ?>" maxlength="255" placeholder="/Mesto/Názov/"><br>
                                        <input name="map_latitude" class="create__form-input" type="text" id="latitude" placeholder="Zemepisná šírka">
                                        <input name="map_longitude" class="create__form-input" type="text" id="longitude" placeholder="Zemepisná dĺžka">
                                    </div>
                                </div>
                                <div class="target-location__form-subblock hidden">
                                    <select class="create__form-select" name="load_target_locations-select">
                                        <option></option>
                                        <?php foreach ($sourceTargetLocations as $sourceTargetLocation) : ?>
                                            <option value="<?= $sourceTargetLocation['id'] ?>"><?= $sourceTargetLocation['name'] ?></option>
                                        <?php endforeach ?>
                                    </select>
                                </div>
                            </div>
                            <div class="create__form-block">
                                <label class="create__form-label">Jednotka objemu:</label>
                                <select class="create__form-select" name="unit_id">
                                    <option value="1">t</option>
                                    <option value="2">prm</option>
                                </select>

                                <label class="create__form-label">Objem:</label>
                                <input class="create__form-input" type="number" step="0.01" value="<?php echo isset($amount) ? $amount : '0.00'; ?>" name="amount" min="0" max="99999"><br>

                                <label class="create__form-label">Poznámky:</label>
                                <input class="create__form-input" type="text" name="note" value="<?php echo isset($note) ? $note : ''; ?>"><br>
                            </div>
                            <?php if ($errorMessage) : ?>
                                <p class="create__form-error"><?= $errorMessage ?></p>
                            <?php endif ?>
                            <input type="submit" name="create" class="button create__form-button" value="Pridať">
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>

    </div>

    <?php require_once(BASE_URL . 'templates/footer.php') ?>

    <?php require_once(BASE_URL . 'templates/scripts.php') ?>
    <script src="<?= BASE_URL_HTTP ?>assets/js/mapForCreate.js"></script>
</body>

</html>