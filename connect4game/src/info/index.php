<?php
header('Content-Type: application/json');

echo json_encode(["width" => 7, "height" => 6, "strategies" => ["Smart", "Random"]]);
?>
