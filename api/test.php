<?php

require_once __DIR__ . "/db.php";

$enrollment = isset($_GET["enrollment"]) ? trim($_GET["enrollment"]) : "25DCE004";
$studentStatement = $pdo->prepare(
    "SELECT student_id, enrollment, name, email, department, year
     FROM students
     WHERE enrollment = :enrollment"
);
$studentStatement->execute(array("enrollment" => $enrollment));
$student = $studentStatement->fetch();

$registrations = array();
if ($student) {
    $registrationStatement = $pdo->prepare(
        "SELECT events.title, events.event_date, events.location, registrations.status
         FROM registrations
         INNER JOIN events ON events.event_id = registrations.event_id
         WHERE registrations.student_id = :student_id
         ORDER BY events.event_date"
    );
    $registrationStatement->execute(array("student_id" => $student["student_id"]));
    $registrations = $registrationStatement->fetchAll();
}

function escapeHtml($value)
{
    return htmlspecialchars((string) $value, ENT_QUOTES, "UTF-8");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Practical 8 - StudentHub PDO Demo</title>
    <style>
        body { font: 16px/1.5 Arial, sans-serif; margin: 2rem auto; max-width: 900px; padding: 0 1rem; color: #222; }
        h1, h2 { color: #174a7e; }
        form, section { border: 1px solid #d7dee7; border-radius: 8px; margin: 1rem 0; padding: 1rem; }
        input, button { font: inherit; padding: 0.45rem 0.6rem; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #d7dee7; padding: 0.55rem; text-align: left; }
        th { background: #edf3f9; }
        code { background: #f1f3f5; padding: 0.1rem 0.25rem; }
    </style>
</head>
<body>
    <h1>Practical 8: StudentHub Database</h1>
    <p>PDO connection successful. This page demonstrates parameterized prepared statements.</p>

    <section>
        <h2>ER model</h2>
        <p><code>students 1 &mdash; many registrations many &mdash; 1 events</code></p>
        <p>A student can register for many events, and each event can have many student registrations.
            The registrations table connects the two entities and prevents duplicate registrations.</p>
    </section>

    <form method="get">
        <label for="enrollment">Find a student by enrollment:</label>
        <input id="enrollment" name="enrollment" value="<?= escapeHtml($enrollment) ?>" required>
        <button type="submit">Search</button>
    </form>

    <?php if ($student): ?>
        <section>
            <h2>Student found</h2>
            <p><strong>Name:</strong> <?= escapeHtml($student["name"]) ?></p>
            <p><strong>Enrollment:</strong> <?= escapeHtml($student["enrollment"]) ?></p>
            <p><strong>Email:</strong> <?= escapeHtml($student["email"]) ?></p>
            <p><strong>Department:</strong> <?= escapeHtml($student["department"]) ?></p>
            <p><strong>Year:</strong> <?= escapeHtml($student["year"]) ?></p>

            <h3>Event registrations</h3>
            <?php if ($registrations): ?>
                <table>
                    <thead>
                        <tr><th>Event</th><th>Date</th><th>Location</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $registration): ?>
                            <tr>
                                <td><?= escapeHtml($registration["title"]) ?></td>
                                <td><?= escapeHtml($registration["event_date"]) ?></td>
                                <td><?= escapeHtml($registration["location"]) ?></td>
                                <td><?= escapeHtml($registration["status"]) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>This student has no event registrations yet.</p>
            <?php endif; ?>
        </section>
    <?php else: ?>
        <p>No student found for enrollment <?= escapeHtml($enrollment) ?>.</p>
    <?php endif; ?>
</body>
</html>