CREATE DATABASE IF NOT EXISTS studenthub
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE studenthub;

CREATE TABLE IF NOT EXISTS students (
    student_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    enrollment VARCHAR(20) NOT NULL UNIQUE,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    phone VARCHAR(20),
    department VARCHAR(100) NOT NULL,
    year TINYINT UNSIGNED NOT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    CONSTRAINT chk_students_year CHECK (year BETWEEN 1 AND 6)
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS events (
    event_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(150) NOT NULL,
    category VARCHAR(60) NOT NULL,
    event_date DATE NOT NULL,
    location VARCHAR(150) NOT NULL,
    description TEXT,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE IF NOT EXISTS registrations (
    registration_id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    student_id INT UNSIGNED NOT NULL,
    event_id INT UNSIGNED NOT NULL,
    registered_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    status ENUM('registered', 'cancelled', 'attended') NOT NULL DEFAULT 'registered',
    CONSTRAINT uq_registration_student_event UNIQUE (student_id, event_id),
    CONSTRAINT fk_registrations_student
        FOREIGN KEY (student_id) REFERENCES students (student_id)
        ON UPDATE CASCADE ON DELETE CASCADE,
    CONSTRAINT fk_registrations_event
        FOREIGN KEY (event_id) REFERENCES events (event_id)
        ON UPDATE CASCADE ON DELETE CASCADE
) ENGINE=InnoDB;

INSERT IGNORE INTO students (enrollment, name, email, phone, department, year)
VALUES ('25DCE004', 'Kanan Sharma', 'kanan@charusat.edu.in', NULL, 'Computer Engineering', 3);

INSERT IGNORE INTO events (title, category, event_date, location, description)
VALUES
    ('Cognizance Workshop', 'Workshop', '2026-09-20', 'CHARUSAT', 'A practical technical workshop.'),
    ('Tech Fest', 'Festival', '2026-10-08', 'CHARUSAT', 'The annual student technology festival.');

INSERT IGNORE INTO registrations (student_id, event_id)
SELECT students.student_id, events.event_id
FROM students
CROSS JOIN events
WHERE students.enrollment = '25DCE004'
  AND events.title = 'Cognizance Workshop';

