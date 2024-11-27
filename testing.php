<?php

include "conn.php";

$quiz_title = "Dinosaur Quiz";
$questions = [
    [
        "question" => "1. Which dinosaur is known as the 'King of the Dinosaurs'?",
        "options" => ["Triceratops", "Tyrannosaurus Rex", "Stegosaurus", "Velociraptor"],
        "answer" => 1,
    ],
    [
        "question" => "2. What does the name 'Triceratops' mean?",
        "options" => ["Three-horned face", "Swift thief", "Roofed lizard", "Thick-headed lizard"],
        "answer" => 0,
    ],
    [
        "question" => "3. Which dinosaur had a long neck and was known for being one of the largest land animals?",
        "options" => ["Brachiosaurus", "Spinosaurus", "Allosaurus", "Diplodocus"],
        "answer" => 0,
    ],
    [
        "question" => "4. What era is known as the 'Age of Dinosaurs'?",
        "options" => ["Paleozoic", "Mesozoic", "Cenozoic", "Precambrian"],
        "answer" => 1,
    ],
    [
        "question" => "5. Which dinosaur had a distinctive row of plates along its back?",
        "options" => ["Ankylosaurus", "Stegosaurus", "Parasaurolophus", "Gallimimus"],
        "answer" => 1,
    ]
];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $score = 0;

    foreach ($questions as $index => $question) {
        if (isset($_POST['question' . $index]) && $_POST['question' . $index] == $question['answer']) {
            $score++;
        }
    }

    // Save the score in the database
    $stmt = $conn->prepare("INSERT INTO rankings (username, score) VALUES (?, ?)");
    $stmt->bind_param("si", $username, $score);
    $stmt->execute();

    // Display the results and rankings
    echo "<h2>Your Score: $score/" . count($questions) . "</h2>";
    echo "<a href='index.php'>TRY AGAIN?</a><br><br>";

    echo "<h3>Rankings:</h3>";
    $result = $conn->query("SELECT username, score FROM rankings ORDER BY score DESC, id ASC");
    echo "<ul>";
    while ($row = $result->fetch_assoc()) {
        echo "<li>" . htmlspecialchars($row['username']) . ": " . $row['score'] . "</li>";
    }
    echo "</ul>";

    exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $quiz_title; ?></title>
</head>
<body>
    <h1><?php echo $quiz_title; ?></h1>

    <form action="" method="post">
        <label for="username">Enter your name:</label>
        <input type="text" id="username" name="username" required><br><br>

        <?php foreach ($questions as $index => $question): ?>
            <fieldset>
                <legend><?php echo $question['question']; ?></legend>
                <?php foreach ($question['options'] as $optionsIndex => $option): ?>
                    <label>
                        <input type="radio" name="question<?php echo $index; ?>" value="<?php echo $optionsIndex; ?>" required>
                        <?php echo $option; ?>
                    </label><br>
                <?php endforeach; ?>
            </fieldset>
        <?php endforeach; ?>

        <input type="submit" value="Submit">
    </form>
</body>
</html>
