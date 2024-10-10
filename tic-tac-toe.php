<?php
session_start();

include('tic-tac-toe-functions.php');

for ($row = 1; $row <= 3; $row++) {
    for ($col = 1; $col <= 3; $col++) {
        if (!isset($_SESSION["$col-$row"])) {
            $_SESSION["$col-$row"] = null; 
        }
    }
}

if (!isset($_SESSION['player'])) {
    $_SESSION['player'] = 'X';
}


if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['move'])) {
    $move = $_POST['move'];


    if ($_SESSION[$move] === null) {

        $_SESSION[$move] = $_SESSION['player'];

        $winner = whoIsWinner();

        if ($winner !== null) {

            echo "<p>Player $winner wins!</p>";
            session_destroy(); 
            echo "<p><a href='tic-tac-toe.php'>Start New Game</a></p>";
            exit();
        }
        $_SESSION['player'] = ($_SESSION['player'] == 'X') ? 'O' : 'X';
    } else {
        echo "<p>That spot is already taken.</p>";
    }
}

function renderBoard() {
    echo "<form method='POST' action=''>";
    echo "<table>";
    for ($row = 1; $row <= 3; $row++) {
        echo "<tr>";
        for ($col = 1; $col <= 3; $col++) {
            $position = "$col-$row";
            $value = $_SESSION[$position];
            echo "<td>";
            if ($value !== null) {

                $class = ($value == 'X') ? 'x-color' : 'o-color';
                echo "<button type='button' class='$class' disabled>$value</button>";
            } else {
                echo "<button type='submit' name='move' value='$position'></button>";
            }
            echo "</td>";
        }
        echo "</tr>";
    }
    echo "</table>";
    echo "</form>";
}

function checkDraw() {
    foreach ($_SESSION as $spot) {
        if ($spot === null) {
            return false; 
        }
    }
    return true; 
}

if (checkDraw()) {
    echo "<p>It's a draw!</p>";
    session_destroy(); 
    echo "<p><a href='tic-tac-toe.php'>Start New Game</a></p>";
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tic Tac Toe</title>
    <style>
        button {
            background-color: #3498db;
            height: 100%;
            width: 100%;
            text-align: center;
            font-size: 20px;
            color: white;
            vertical-align: middle;
            border: 0px;
        }

        table td {
            text-align: center;
            vertical-align: middle;
            padding: 0px;
            margin: 0px;
            width: 75px;
            height: 75px;
            font-size: 20px;
            border: 3px solid #040404;
        }
        .x-color {
            background-color: green;
        }
        .o-color {
            background-color: red;
        }
        button:hover {
            background-color: #04469d;
            text-decoration: none;
            outline: none;
        }
    </style>
</head>

<body>

    <h1>Tic Tac Toe</h1>

    <p>Current Turn: <?php echo $_SESSION['player']; ?></p>

    <?php
    renderBoard();
    ?>

    <p><a href="tic-tac-toe.php?reset=true">Reset Game</a></p>

    <?php
    if (isset($_GET['reset'])) {
        session_destroy();
        header("Location: tic-tac-toe.php"); 
    }
    ?>

</body>

</html>
