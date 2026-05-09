<?php
require_once __DIR__ . '/../config/database.php';

$db = getDB();
$stmt = $db->query('SHOW CREATE TABLE embudos');
$result = $stmt->fetch();
echo $result['Create Table'];
echo "\n\n";
