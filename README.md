# Connect4Game

A web-based Connect 4 game built with PHP featuring AI opponents with different difficulty levels, real-time gameplay, and persistent game sessions.

## Features

### Core Gameplay
- **Classic Connect 4**: 7x6 grid with gravity-based token dropping
- **Win Detection**: Horizontal, vertical, and diagonal win checking with wraparound support
- **Draw Detection**: Automatic draw detection when board is full
- **Session Management**: Persistent game states using JSON storage

### AI Opponents
- **Smart Strategy**: Advanced AI with strategic decision making
  - Immediate win detection and execution
  - Opponent win blocking
  - Three-in-a-row threat prevention
  - Strategic setup for future wins
  - Fallback to random moves when no strategic move exists
- **Random Strategy**: Unpredictable opponent for casual play

### Technical Features
- **RESTful API**: Clean endpoint structure for game operations
- **JSON-based Storage**: Lightweight game state persistence
- **Object-Oriented Design**: Modular architecture with strategy pattern
- **Input Validation**: Comprehensive move and session validation
- **Error Handling**: Detailed error responses for debugging

## Technologies Used

### Backend
- **PHP 7.0+**: Core server-side logic
- **JSON**: Game state storage and API responses
- **Object-Oriented Programming**: Clean class-based architecture

### Architecture Patterns
- **Strategy Pattern**: Interchangeable AI difficulty levels
- **MVC-like Structure**: Separation of game logic, board state, and API endpoints
- **RESTful Design**: Intuitive API endpoint organization

## Project Structure

```
connect4game/
├── src/
│   ├── info/
│   │   └── index.php              # Game configuration endpoint
│   ├── new/
│   │   └── index.php              # New game creation
│   └── play/
│       ├── index.php              # Main gameplay endpoint
│       ├── Board.php              # Board state and game logic
│       ├── MoveStrategy.php       # Strategy interface
│       ├── RandomStrategy.php     # Random AI implementation
│       └── SmartStrategy.php      # Advanced AI implementation
├── games.json                     # Game session storage (auto-generated)
└── README.md                      # Project documentation
```

## API Endpoints

### Game Information
```
GET /src/info/
Response: {"width": 7, "height": 6, "strategies": ["Smart", "Random"]}
```

### Create New Game
```
GET /src/new/?strategy={Smart|Random}
Response: {"response": true, "pid": "unique_game_id"}
```

### Make Move
```
GET /src/play/?pid={game_id}&move={0-6}
Response: {
  "response": true,
  "ack_move": {
    "slot": 0,
    "isWin": false,
    "isDraw": false,
    "row": []
  },
  "move": {
    "slot": 3,
    "isWin": false,
    "isDraw": false,
    "row": []
  }
}
```

## Getting Started

### Prerequisites
- PHP 7.0 or higher
- Web server (Apache, Nginx, or PHP built-in server)
- Write permissions for game session storage

### Installation

1. Clone the repository:
```bash
git clone https://github.com/YOUR_USERNAME/Connect4Game.git
cd Connect4Game
```

2. Start a local web server:
```bash
# Using PHP built-in server
php -S localhost:8000

# Or place files in your web server directory
# Apache: /var/www/html/
# Nginx: /usr/share/nginx/html/
```

3. Test the API:
```bash
# Get game info
curl http://localhost:8000/src/info/

# Create new game
curl "http://localhost:8000/src/new/?strategy=Smart"

# Make a move (replace PID with actual game ID)
curl "http://localhost:8000/src/play/?pid=YOUR_GAME_ID&move=3"
```

## Game Logic

### Board Representation
- **6 rows x 7 columns** grid stored as 2D array
- **Player 1**: Human player (value: 1)
- **Player 2**: AI opponent (value: 2)
- **Empty cells**: Represented by 0

### Win Conditions
- **Four in a row**: Horizontal, vertical, or diagonal
- **Wraparound support**: Configurable for unique gameplay variants
- **Immediate detection**: Win checking after each move

### AI Strategy Implementation

#### Smart Strategy Algorithm
1. **Offensive**: Check for immediate winning moves
2. **Defensive**: Block opponent's winning moves
3. **Tactical**: Prevent opponent three-in-a-row setups
4. **Strategic**: Create own advantageous positions
5. **Fallback**: Random valid move if no strategy applies

#### Random Strategy
- Simple random column selection with move validation
- Ensures all moves are legal (column not full)

## Usage Examples

### Creating a Game Session
```php
// GET /src/new/?strategy=Smart
$response = json_decode(file_get_contents($url), true);
$gameId = $response['pid'];
```

### Making Moves
```php
// GET /src/play/?pid={gameId}&move=3
$response = json_decode(file_get_contents($url), true);
$playerMove = $response['ack_move'];
$aiMove = $response['move'];
```

### Checking Game State
```php
$games = json_decode(file_get_contents('games.json'), true);
$currentGame = $games[$gameId];
$board = $currentGame['board'];
```

## Development Notes

This project demonstrates several important programming concepts:
- **Interface-based design** with the MoveStrategy pattern
- **Game state management** using file-based persistence
- **API design** with proper HTTP response codes and JSON formatting
- **Algorithm implementation** for game AI with multiple difficulty levels
- **Object-oriented PHP** with proper encapsulation and inheritance

## Future Enhancements

- **Web frontend**: HTML/CSS/JavaScript interface for browser play
- **Database storage**: Replace JSON files with MySQL/PostgreSQL
- **User accounts**: Player registration and game history
- **Multiplayer**: Real-time player vs player functionality
- **Advanced AI**: Machine learning-based opponent strategies
- **Tournament mode**: Multiple game brackets and scoring

## License

This project was developed by [Your Name]. All rights reserved.
