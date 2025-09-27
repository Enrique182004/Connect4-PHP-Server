<?php
// Include the MoveStrategy interface
require_once "MoveStrategy.php";

// Define the RandomStrategy class that implements MoveStrategy
class RandomStrategy implements MoveStrategy {
    // Function to select a random valid move
    public function getMove($board) {
        do {
            $col = rand(0, 6); // Generate a random column between 0 and 6
        } while (!$board->isValidMove($col)); // Repeat until a valid move is found
        return $col; // Return the selected column
    }
}
?>
