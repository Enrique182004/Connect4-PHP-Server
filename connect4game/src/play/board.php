<?php
// Define the Board class
class Board {
    private $board; // 2D array representing the game board
    private $rows = 6; // Number of rows in the board
    private $cols = 7; // Number of columns in the board

    // Constructor: Initializes the board with the given state
    public function __construct($board) {
        $this->board = $board;
    }

    // Checks if a move is valid (column is within bounds and not full)
    public function isValidMove($col) {
        return $col >= 0 && $col < $this->cols && $this->board[0][$col] == 0;
    }

    // Drops a player's token in the selected column
    public function dropToken($col, $player) {
        for ($row = $this->rows - 1; $row >= 0; $row--) { // Start from the bottom row
            if ($this->board[$row][$col] == 0) { // Find the first empty spot
                $this->board[$row][$col] = $player; // Place the token
                return;
            }
        }
    }

    // Checks if the board is full (a draw)
    public function isDraw() {
        foreach ($this->board[0] as $cell) { // Check only the top row
            if ($cell == 0) return false; // If any cell is empty, not a draw
        }
        return true;
    }

    // Checks if a player has won
    public function checkWin() {
        for ($r = 0; $r < $this->rows; $r++) {
            for ($c = 0; $c < $this->cols; $c++) {
                $player = $this->board[$r][$c]; // Get the current cell's player
                if ($player == 0) continue; // Skip empty cells

                // Check different directions for a winning sequence
                if ($this->checkDirection($r, $c, 0, 1, $player, true)) return $player; // Horizontal wrap
                if ($this->checkDirection($r, $c, 1, 0, $player, true)) return $player; // Vertical wrap
                if ($this->checkDirection($r, $c, 1, 1, $player, false)) return $player; // Diagonal \
                if ($this->checkDirection($r, $c, 1, -1, $player, false)) return $player; // Diagonal /
            }
        }
        return false; // No winner found
    }

    // Checks if a player has four in a row in a given direction
    private function checkDirection($r, $c, $dr, $dc, $player, $wrap) {
        $count = 0;
        for ($i = 0; $i < 4; $i++) { // Check 4 positions in a row
            $newR = ($r + $i * $dr) % $this->rows; // Wrap vertically if needed
            $newC = ($c + $i * $dc) % $this->cols; // Wrap horizontally if needed

            // Ensure positive wraparound for negative values
            if ($newR < 0) $newR += $this->rows;
            if ($newC < 0) $newC += $this->cols;

            // Prevent wraparound when not allowed
            if (!$wrap) { 
                if ($newC < $c && $dc == 1) break; // Stop if wrap not allowed
                if ($newR < $r && $dr == 1) break; // Stop if wrap not allowed
            }

            // Check if the current position belongs to the same player
            if ($this->board[$newR][$newC] == $player) {
                $count++; // Increase count for consecutive tokens
            } else {
                break; // Stop if the sequence is broken
            }
        }
        return ($count == 4); // Return true if four in a row
    }

    // Returns the winning row (currently not storing winning sequence)
    public function getWinningRow() {
        return isset($this->winningRow) ? $this->winningRow : [];
    }

    // Returns the current state of the board
    public function getBoard() {
        return $this->board;
    }
}
?>
