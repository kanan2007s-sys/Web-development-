
<?php
require_once __DIR__ . "/db.php";

$message = "";
$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["add_event"])) {
    $eventName = trim($_POST["event_name"] ?? "");
    $eventDate = trim($_POST["event_date"] ?? "");
    $venue = trim($_POST["venue"] ?? "");

    if ($eventName === "" || $eventDate === "" || $venue === "") {
        $error = "Please fill in all event fields.";
    } else {
        try {
            $sql = "INSERT INTO events (event_name, event_date, venue)
                    VALUES (:name, :date, :venue)";

            $stmt = $pdo->prepare($sql);
            $stmt->execute([
                ":name" => $eventName,
                ":date" => $eventDate,
                ":venue" => $venue
            ]);

            $message = "Event added successfully!";
        } catch (PDOException $e) {
            $error = "Could not add the event.";
        }
    }
}

$enrollment = trim($_GET["enrollment"] ?? "");

$student = false;
$registrations = [];

if ($enrollment !== "") {
    $stmt = $pdo->prepare(
        "SELECT id, name, enrollment, email, course, year
         FROM students
         WHERE enrollment = :enrollment"
    );

    $stmt->execute([":enrollment" => $enrollment]);
    $student = $stmt->fetch();

    if ($student) {
        $stmt = $pdo->prepare(
            "SELECT e.event_name, e.event_date, e.venue,
                    r.registration_date
             FROM registrations r
             INNER JOIN events e ON e.event_id = r.event_id
             WHERE r.student_id = :student_id
             ORDER BY e.event_date"
        );

        $stmt->execute([":student_id" => $student["id"]]);
        $registrations = $stmt->fetchAll();
    }
}

function escapeHtml($value) {
    return htmlspecialchars((string)$value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 - StudentHub</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 900px;
            margin: 30px auto;
            padding: 0 15px;
            color: #222;
        }
        h1, h2 { color: #174a7e; }
        section, form {
            border: 1px solid #d7dee7;
            border-radius: 8px;
            padding: 18px;
            margin: 18px 0;
        }
        input, button {
            font: inherit;
            padding: 8px;
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid #d7dee7;
            padding: 8px;
            text-align: left;
        }
        th { background: #edf3f9; }
        .success { color: green; }
        .error { color: red; }
        label { display: block; margin-top: 10px; }
    </style>
</head>
<body>

<h1>Practical 8: StudentHub Database</h1>

<p>PDO connectivity, database schema and prepared statements.</p>

<?php if ($message !== ""): ?>
    <p class="success"><?= escapeHtml($message) ?></p>
<?php endif; ?>

<?php if ($error !== ""): ?>
    <p class="error"><?= escapeHtml($error) ?></p>
<?php endif; ?>

<section>
    <h2>ER Model</h2>
    <p>students (1) → (many) registrations (many) ← (1) events</p>
    <p>The registrations table connects students and events using
       foreign keys. A student can register for multiple events.</p>
</section>

<section>
    <h2>Add an Event</h2>

    <form method="post">
        <label for="event_name">Event Name</label>
        <input id="event_name" name="event_name" required>

        <label for="event_date">Event Date</label>
        <input id="event_date" name="event_date"
               type="date" required>

        <label for="venue">Venue</label>
        <input id="venue" name="venue" required>

        <button type="submit" name="add_event" value="1">
            Add Event
        </button>
    </form>
</section>

<section>
    <h2>Search Student</h2>

    <form method="get">
        <label for="enrollment">Enrollment Number</label>
        <input id="enrollment" name="enrollment"
               value="<?= escapeHtml($enrollment) ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($enrollment !== ""): ?>
        <?php if ($student): ?>
            <h3>Student Details</h3>
            <p><strong>Name:</strong>
                <?= escapeHtml($student["name"]) ?></p>
            <p><strong>Enrollment:</strong>
                <?= escapeHtml($student["enrollment"]) ?></p>
            <p><strong>Email:</strong>
                <?= escapeHtml($student["email"]) ?></p>
            <p><strong>Course:</strong>
                <?= escapeHtml($student["course"]) ?></p>
            <p><strong>Year:</strong>
                <?= escapeHtml($student["year"]) ?></p>

            <h3>Event Registrations</h3>

            <?php if ($registrations): ?>
                <table>
                    <tr>
                        <th>Event</th>
                        <th>Date</th>
                        <th>Venue</th>
                        <th>Registration Date</th>
                    </tr>

                    <?php foreach ($registrations as $r): ?>
                        <tr>
                            <td><?= escapeHtml($r["event_name"]) ?></td>
                            <td><?= escapeHtml($r["event_date"]) ?></td>
                            <td><?= escapeHtml($r["venue"]) ?></td>
                            <td><?= escapeHtml($r["registration_date"]) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            <?php else: ?>
                <p>This student has no event registrations yet.</p>
            <?php endif; ?>

        <?php else: ?>
            <p>No student found with that enrollment number.</p>
        <?php endif; ?>
    <?php endif; ?>
</section>

</body>
</html>
