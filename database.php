<?php
session_start();
require_once(BASE_URL . 'config/db.php');


try {
    $db_connect = new PDO("$db_driver:host=$db_host;dbname=$db_name;charset=$charset", $db_user, $db_password, $options);
} catch (PDOException $i) {
    die('Нepodarilo sa pripojiť k databáze!');
}

function dbCheckErr($query)
{
    $errInfo = $query->errorInfo();
    if ($errInfo[0] !== PDO::ERR_NONE) {
        echo $errInfo[2];
        exit;
    }
    return true;
}

function selectAll($table, $params = [], $orderBy = '')
{
    global $db_connect;
    $sql = "SELECT * FROM $table";
    $values = [];

    if (!empty($params)) {
        $whereClause = [];
        foreach ($params as $key => $value) {
            if (!is_numeric($value)) {
                // Если значение не числовое, используем подготовленный параметр
                $whereClause[] = "$key = :$key";
                $values[":$key"] = $value;
            } else {
                $whereClause[] = "$key = :$key";
                $values[":$key"] = $value;
            }
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    if (!empty($orderBy)) {
        $sql .= " $orderBy";
    }

    $query = $db_connect->prepare($sql);
    $query->execute($values);
    dbCheckErr($query);
    return $query->fetchAll();
}



function selectOne($table, $params = [])
{
    global $db_connect;
    $sql = "SELECT * FROM $table";
    $values = [];

    if (!empty($params)) {
        $whereClause = [];
        foreach ($params as $key => $value) {
            if (!is_numeric($value)) {
                // Если значение не числовое, используем подготовленный параметр
                $whereClause[] = "$key = :$key";
                $values[":$key"] = $value;
            } else {
                $whereClause[] = "$key = :$key";
                $values[":$key"] = $value;
            }
        }
        $sql .= " WHERE " . implode(" AND ", $whereClause);
    }

    $sql .= " LIMIT 1";

    $query = $db_connect->prepare($sql);
    $query->execute($values);
    dbCheckErr($query);
    return $query->fetch();
}


function insert($table, $params)
{
    global $db_connect;
    $keys = array_keys($params);
    $values = array_values($params);

    // Генерируем маску для подготовленных параметров
    $valuePlaceholders = implode(', ', array_fill(0, count($values), '?'));

    // Генерируем список полей вставки
    $fieldList = implode(', ', $keys);

    // Создаем SQL-запрос с подготовленными параметрами
    $sql = "INSERT INTO $table ($fieldList) VALUES ($valuePlaceholders)";

    $query = $db_connect->prepare($sql);
    $query->execute($values);
    dbCheckErr($query);

    return $db_connect->lastInsertId();
}


function update($table, $id, $params)
{
    global $db_connect;
    $i = 0;
    $sql = "UPDATE $table SET ";
    $setClauses = [];
    $values = [];

    foreach ($params as $key => $value) {
        if ($value === null) {
            $setClauses[] = "$key = NULL";
        } else {
            // Используем подготовленные параметры
            $setClauses[] = "$key = :$key";
            $values[":$key"] = $value;
        }
    }

    $sql .= implode(', ', $setClauses);
    $sql .= " WHERE `id` = :id";
    $values[':id'] = $id;

    $query = $db_connect->prepare($sql);
    $query->execute($values);
    dbCheckErr($query);
}




function delete($table, $id)
{
    global $db_connect;
    $sql = "DELETE FROM $table WHERE `id` = :id";

    $query = $db_connect->prepare($sql);
    $query->bindParam(':id', $id, PDO::PARAM_INT);
    $query->execute();
    dbCheckErr($query);
}


function clean($table)
{
    global $db_connect;
    $sql = "TRUNCATE TABLE $table";

    $query = $db_connect->prepare($sql);
    $query->execute();
    dbCheckErr($query);
}

function getBiggestOne($table, $sortBy)
{
    global $db_connect;
    $sql = "SELECT * FROM " . $table . " ORDER BY " . $sortBy . " DESC LIMIT 1;";
    $query = $db_connect->prepare($sql);
    $query->execute();
    dbCheckErr($query);
    return $data = $query->fetch();
}

function getLowestOne($table, $sortBy)
{
    global $db_connect;
    $sql = "SELECT * FROM " . $table . " ORDER BY " . $sortBy . " ASC LIMIT 1;";
    $query = $db_connect->prepare($sql);
    $query->execute();
    dbCheckErr($query);
    return $data = $query->fetch();
}

function checkIsEmptyTable($table)
{
    global $db_connect;
    $sql = "SELECT COUNT(*) FROM $table";
    $query = $db_connect->prepare($sql);
    $query->execute();
    dbCheckErr($query);
    return $rowCount = $query->fetchColumn();
}
