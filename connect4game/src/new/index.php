<?php
// Set the HTTP response header to indicate JSON content
header('Content-Type: application/json');

// Check if the 'strategy' parameter is provided in the request
if (!isset($_GET['strategy'])) {
    echo json_encode(["response" => false, "reason" => "Strategy not specified"]);
    exit; // Stop execution if strategy is missing
}

// Get the strategy value from the request
$strategy = $_GET['strategy'];

// Validate if the provided strategy is allowed
if (!in_array($strategy, ["Smart", "Random"])) {
    echo json_encode(["response" => false, "reason" => "Unknown strategy"]);
    exit; // Stop execution if strategy is invalid
}

// Generate a unique game ID (PID)
$pid = uniqid();

// Create the initial game data
$gameData = [
    "pid" => $pid, // Unique game identifier
    "strategy" => $strategy, // Selected strategy
    "board" => array_fill(0, 6, array_fill(0, 7, 0)), // Empty 6x7 board
    "turn" => 1 // Player 1 starts
];

// Check if the games file exists and read existing games
$games = file_exists("../../games.json") ? json_decode(file_get_contents("../../games.json"), true) : [];

// Add the new game to the list
$games[$pid] = $gameData;

// Save the updated game data to the file
file_put_contents("../../games.json", json_encode($games, JSON_PRETTY_PRINT));

// Respond with the generated game ID
echo json_encode(["response" => true, "pid" => $pid]);
?>
