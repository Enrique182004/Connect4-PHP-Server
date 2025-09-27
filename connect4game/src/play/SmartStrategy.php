<?php
require_once "MoveStrategy.php";

class SmartStrategy implements MoveStrategy {
    public function getMove($board) {
        $cols = 7;

        // Step 1: Check if the AI can win immediately by dropping a token in any column.
        // If a winning move is found, return that column.
        for ($col = 0; $col < $cols; $col++) {
            if ($board->isValidMove($col)) {
                $tempBoard = clone $board;
                $tempBoard->dropToken($col, 2); // AI plays as player 2
                if ($tempBoard->checkWin()) {
                    return $col; // AI wins immediately
                }
            }
        }

        // Step 2: Check if the opponent can win on their next move.
        // If the opponent has a winning move, block it by returning that column.
        for ($col = 0; $col < $cols; $col++) {
            if ($board->isValidMove($col)) {
                $tempBoard = clone $board;
                $tempBoard->dropToken($col, 1); // Simulate opponent's move (player 1)
                if ($tempBoard->checkWin()) {
                    return $col; // Block opponent from winning
                }
            }
        }

        // Step 3: Check if the opponent is close to creating a three-in-a-row setup.
        // If so, block them from achieving an advantageous position.
        for ($col = 0; $col < $cols; $col++) {
            if ($board->isValidMove($col)) {
                $tempBoard = clone $board;
                $tempBoard->dropToken($col, 1); // Simulate opponent's move
                if ($this->countConnectedTokens($tempBoard, 1) >= 3) {
                    return $col; // Block the setup
                }
            }
        }

        // Step 4: Attempt to build a future winning opportunity.
        // If placing a token in a certain column creates a strong setup, choose that column.
        for ($col = 0; $col < $cols; $col++) {
            if ($board->isValidMove($col)) {
                $tempBoard = clone $board;
                $tempBoard->dropToken($col, 2); // AI move
                if ($this->countConnectedTokens($tempBoard, 2) >= 3) {
                    return $col; // Set up a future win
                }
            }
        }

        // Step 5: If no immediate or strategic move is found, pick a random valid column.
        do {
            $col = rand(0, $cols - 1);
        } while (!$board->isValidMove($col));

        return $col;
    }

    // This function calculates the maximum number of connected tokens for a given player.
    private function countConnectedTokens($board, $player) {
        $maxConnected = 0;
        for ($r = 0; $r < 6; $r++) {
            for ($c = 0; $c < 7; $c++) {
                if ($board->getBoard()[$r][$c] == $player) {
                    // Check in all possible directions (horizontal, vertical, and diagonal)
                    $maxConnected = max($maxConnected, $this->countDirection($board, $r, $c, 0, 1, $player)); // Horizontal
                    $maxConnected = max($maxConnected, $this->countDirection($board, $r, $c, 1, 0, $player)); // Vertical
                    $maxConnected = max($maxConnected, $this->countDirection($board, $r, $c, 1, 1, $player)); // Diagonal \
                    $maxConnected = max($maxConnected, $this->countDirection($board, $r, $c, 1, -1, $player)); // Diagonal /
                }
            }
        }
        return $maxConnected;
    }

    // This function counts the number of connected tokens in a specific direction.
    // It checks up to four positions in the given direction.
    private function countDirection($board, $r, $c, $dr, $dc, $player) {
        $count = 0;
        for ($i = 0; $i < 4; $i++) {
            $newR = $r + ($i * $dr);
            $newC = $c + ($i * $dc);
            
            // Stop if the position is out of bounds
            if ($newR < 0 || $newR >= 6 || $newC < 0 || $newC >= 7) break;

            // Count consecutive tokens of the same player
            if ($board->getBoard()[$newR][$newC] == $player) {
                $count++;
            } else {
                break; // Stop counting if the sequence is broken
            }
        }
        return $count;
    }
}
?>
