<?php
require_once(BASE_URL . 'database/database.php');

$sourceTargetLocations = selectAll('ashsporttargetlocations', [], 'ORDER BY name');

$drivers_id = selectAll('aspnetuserroles', ['RoleId' => 4]);
$drivers = [];
foreach ($drivers_id as $driver_id) {
    $driver = selectOne('aspnetusers', ['id' => $driver_id['UserId']]);
    $drivers[] = $driver;
}
function compareBySurname($a, $b)
{
    return strcmp($a["Surname"], $b["Surname"]);
}
usort($drivers, 'compareBySurname');

$vehicles = selectAll('machines');
$warehouses = selectAll('warehouses', ['Type' => 1], 'ORDER BY name');

$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create'])) {

    if ($_POST['load_target_locations-select']) {
        $target_location_id = $_POST['load_target_locations-select'];
    } elseif ($_POST['map_latitude'] && $_POST['map_name'] !== '') {
        $map_name = $_POST['map_name'];
        $map_latitude = round($_POST['map_latitude'], 5);
        $map_longitude = round($_POST['map_longitude'], 5);
        $transportTargetLocations = [
            'name' => $map_name,
            'GPSLocalization' => $map_latitude . ', ' . $map_longitude,
        ];
        $target_location_id = insert('ashsporttargetlocations', $transportTargetLocations);
    }
    $driver_id = $_POST['driverId'];
    $vehicle_id = $_POST['vehicleId'];
    $warehouse_id = $_POST['warehouse_id'];
    $date = $_POST['date'];
    $unit_id = $_POST['unit_id'];
    $amount = number_format($_POST['amount'], 2);
    $updated = date("Y-m-d H:i:s.u");
    $string_id = "ZOP" . date("y") . '-' . (checkIsEmptyTable('ashretrievalrecords') ? sprintf("%05d", getBiggestOne('ashretrievalrecords', 'id')['id'] + 1) : sprintf("%05d", 1));
    $assigned_user_id = $_SESSION['id'];
    $note = trim($_POST['note']);

    if ($driver_id === '' || $vehicle_id === '' || $warehouse_id === '' || $date === '' || $amount === '' || $target_location_id === '') {
        $errorMessage = 'Nie sú vyplnené všetky polia!';
    } else {
        $ashRetrievalRecords = [
            'DriverId' => $driver_id,
            'VehicleId' => $vehicle_id,
            'WarehouseId' => $warehouse_id,
            'Date' => $date,
            'TargetLocationId' => $target_location_id,
            'UnitId' => $unit_id,
            'Amount' => $amount,
            'Updated' => $updated,
            'StringId' => $string_id,
            'AssignedUserId' => $assigned_user_id,
            'Note' => $note,
        ];

        insert('ashretrievalrecords', $ashRetrievalRecords);

        header("location:" . BASE_URL_HTTP . 'pages/records/ash-retrieval-records/');
        exit();
    }
};

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $oneRecord = selectOne('chippingrecords', ['id' => $id]);

    $target_location_id = $_POST['load_target_locations-select'];
    $driver_id = $_POST['driver_id'];
    $vehicle_id = $_POST['vehicle_id'];
    $warehouse_id = $_POST['warehouse_id'];
    $date = $_POST['date'];
    $unit_id = $_POST['unit_id'];
    $amount = number_format($_POST['amount'], 2);
    $updated = date("Y-m-d H:i:s.u");
    $note = trim($_POST['note']);

    if ($driver_id === '' || $vehicle_id === '' || $warehouse_id === '' || $date === '' || $amount === '' || $target_location_id === '') {
        $errorMessage = 'Nie sú vyplnené všetky polia!';
    } else {
        $ashRetrievalRecords = [
            'DriverId' => $driver_id,
            'VehicleId' => $vehicle_id,
            'WarehouseId' => $warehouse_id,
            'Date' => $date,
            'TargetLocationId' => $target_location_id,
            'UnitId' => $unit_id,
            'Amount' => $amount,
            'Updated' => $updated,
            'Note' => $note,
        ];

        update('ashretrievalrecords', $id, $ashRetrievalRecords);
        header("location:" . BASE_URL_HTTP . 'pages/records/ash-retrieval-records/');
        exit();
    }
};

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['record_id'])) {
    $id = $_GET['record_id'];
    $oneRecord = selectOne('ashretrievalrecords', ['id' => $id]);
}

if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    delete('ashretrievalrecords', $id);
    header("location:" . BASE_URL_HTTP . 'pages/records/ash-retrieval-records/');
    exit();
}
