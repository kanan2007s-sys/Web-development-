<?php
require_once __DIR__ . "/../api/auth.php";

requireLogin();
requireRole("student");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard</title>
    <link rel="stylesheet" href="../CSS/dashboard.css">
</head>

<body>
<div class="dashboard">
    <a href="/Webdev/HTML/homepage.html">Home</a>
     <a href="../api/logout.php">Logout</a>

<h2 align="center">
    Student Name: <?= htmlspecialchars($_SESSION["name"], ENT_QUOTES, "UTF-8") ?>
</h2>
    <hr>
    <p>
        <a href="attendance.html">Gross Attendance</a>
    </p>
    <h2 align="center">Certificates</h2>
    <a href="C:\Users\Suresh\Desktop\CErfiticates\25dce110_Certificate.jpg" target="_blank">
        Workshop (Cognizance)
    </a>
    <br><br>
    <a href="C:\Users\Suresh\Desktop\CErfiticates\Coursera.pdf" target="_blank">
        Coursera (C++ Object-Oriented Programming)
    </a>
    <br><br>
    <a href="C:\Users\Suresh\Desktop\CErfiticates\25dce110_supernove.jpeg" target="_blank">
        Supernova
    </a>
    <br><br>
    <a href="../Certificates/25dce110_thinkx.pdf" target="_blank">
        ThinkX Hackathon
    </a>
    <h2 align="center">Academic Achievements</h2>
    <h2 align="center">Exam Result</h2>

    <form>
        <label for="semester">Select Semester:</label>

        <select id="semester" onchange="if(this.value) window.location.href=this.value;">
            <option value="">-- Select Semester --</option>
            <option value="sem1.html">SEM 1</option>
            <option value="sem2.html">SEM 2</option>
            <option value="sem3.html">SEM 3</option>
            <option value="sem4.html">SEM 4</option>
        </select>
    </form>
    <br><br>
    <hr>
    <h4 align="center">
        <a href="FAQ.html">FAQ</a>
        &nbsp;&nbsp;&nbsp;
        <a href="Feedback.html">Feedback</a>
        &nbsp;&nbsp;&nbsp;
        <a href="about.html">About</a>
    </h4>
    </div>
    <script src="../Javascript/theme-switcher.js"></script>
<script src="../Javascript/hamburger-menu.js"></script>
<script src="../Javascript/modul-popup.js"></script>
<script src="../Javascript/practical6.js"></script>
</body>
</html>