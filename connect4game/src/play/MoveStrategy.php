<?php
// Define an interface for different move strategies
interface MoveStrategy {
    // Method that must be implemented by any strategy class
    public function getMove($board);
}
?>
