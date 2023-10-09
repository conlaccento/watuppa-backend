<?php
require_once ('MysqliDb.php');

$email = $_GET["email"];

if( !$email ) {
  echo "Email parameter is required";
  return;
}

$db = new MysqliDb ('localhost', 'root', 'root', 'watuppa');

$q = "SELECT u.email, o.id AS order_id, GROUP_CONCAT(p.name ORDER BY op.id) AS products FROM users u LEFT JOIN orders o ON u.id = o.user_id LEFT JOIN order_products op ON o.id = op.order_id LEFT JOIN products p ON op.product_id = p.id WHERE u.email = '". $email ."' AND o.date >= DATE_SUB(NOW(), INTERVAL 30 DAY) GROUP BY u.email, o.id;";
$orders = $db->rawQuery($q);

if($db) {
  header('Access-Control-Allow-Origin: *');
  header('Content-Type: application/json; charset=utf-8');
  echo json_encode( $orders );
} else {
  echo "Unable to connect";
}
?>