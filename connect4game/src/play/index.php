<?php
// Include necessary files for game logic
require_once "Board.php";
require_once "MoveStrategy.php";
require_once "RandomStrategy.php";
require_once "SmartStrategy.php";

// Set the HTTP response header to indicate JSON content
header('Content-Type: application/json');

// Check if 'pid' (game session ID) is provided
if (!isset($_GET['pid'])) {
    echo json_encode(array("response" => false, "reason" => "Pid not specified"));
    exit; // Stop execution
}

// Check if 'move' (column number) is provided
if (!isset($_GET['move'])) {
    echo json_encode(array("response" => false, "reason" => "Move not specified"));
    exit; // Stop execution
}

$pid = $_GET['pid']; // Get the game ID
$move = (int)$_GET['move']; // Convert move input to integer

// Validate move: must be between 0 and 6 (valid column numbers)
if ($move < 0 || $move >= 7) {
    echo json_encode(array("response" => false, "reason" => "Invalid slot, $move"));
    exit; // Stop execution if move is invalid
}

// Read existing games data from JSON file
$games = file_exists("../../games.json") ? json_decode(file_get_contents("../../games.json"), true) : array();

// Validate if the given PID exists in stored games
if (!isset($games[$pid])) {
    echo json_encode(array("response" => false, "reason" => "Unknown pid"));
    exit; // Stop execution if PID is not found
}

// Load game state from the stored data
$gameData = $games[$pid];
$board = new Board($gameData["board"]); // Initialize Board object with current board state

// **Player move**
$board->dropToken($move, 1); // Player 1 makes their move

// Check if player won or if the game is a draw
$winner = $board->checkWin();
$isWin = ($winner === 1); // True if Player 1 wins
$isDraw = $board->isDraw(); // True if the board is full (draw)
$winningRow = $board->getWinningRow(); // Get the winning row if applicable

// Prepare response for Player's move
$response = array(
    "response" => true,
    "ack_move" => array(
        "slot" => $move, // The move made by the player
        "isWin" => $isWin, // Did the player win?
        "isDraw" => $isDraw, // Is the game a draw?
        "row" => ($isWin) ? $winningRow : array() // Winning row if applicable
    )
);

// **AI Move (if game is not over)**
if (!$isWin && !$isDraw) {
    // Determine AI move using SmartStrategy
    $aiMove = (new SmartStrategy())->getMove($board);
    $board->dropToken($aiMove, 2); // AI (Player 2) makes its move

    // Check if AI won or if the game is a draw
    $winner = $board->checkWin();
    $isWin = ($winner === 2); // True if AI wins
    $winningRow = $board->getWinningRow(); // Get AI's winning row if applicable

    // Add AI's move to the response
    $response["move"] = array(
        "slot" => $aiMove, // AI's move
        "isWin" => $isWin, // Did the AI win?
        "isDraw" => $board->isDraw(), // Is the game a draw?
        "row" => ($isWin) ? $winningRow : array() // Winning row if applicable
    );
}

// Save the updated game state back to JSON file
$gameData["board"] = $board->getBoard(); // Update the board state
$gameData["winningRow"] = $winningRow; // Store winning row if applicable
$games[$pid] = $gameData; // Update game entry
file_put_contents("../../games.json", json_encode($games, JSON_PRETTY_PRINT)); // Save to file

//**Send JSON response**
echo json_encode($response);
flush(); // Ensure response is sent immediately
?>
