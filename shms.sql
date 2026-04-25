SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+06:00";

CREATE TABLE `admin` (
    `id` int(11) NOT NULL,
    `name` varchar(250) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(250) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@shms.com', '12345');

CREATE TABLE `users` (
    `id` int(11) NOT NULL,
    `name` varchar(50) DEFAULT NULL,
    `email` varchar(50) DEFAULT NULL,
    `password` varchar(100) DEFAULT NULL,
    `role` enum('patient','doctor') NOT NULL DEFAULT 'patient',
    `timestamp` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `departments` (
    `id` int(11) NOT NULL,
    `dept_name` varchar(250) NOT NULL,
    `short_desc` varchar(250) NOT NULL,
    `long_desc` varchar(500) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `doctors` (
    `id` int(11) NOT NULL,
    `dept_id` int(10) NOT NULL,
    `name` varchar(50) NOT NULL,
    `email` varchar(50) NOT NULL,
    `image` varchar(250) NOT NULL,
    `specialization` varchar(250) NOT NULL,
    `fee` int(255) NOT NULL,
    `available_start` varchar(20) DEFAULT '09:00',
    `available_end` varchar(20) DEFAULT '17:00'
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

CREATE TABLE `appointments` (
    `id` int(11) NOT NULL,
    `patient_name` varchar(100) DEFAULT NULL,
    `patient_email` varchar(100) DEFAULT NULL,
    `doctor_id` int(11) DEFAULT NULL,
    `dept_id` int(11) DEFAULT NULL,
    `appointment_date` varchar(100) DEFAULT NULL,
    `appointment_time` varchar(100) DEFAULT NULL,
    `status` varchar(50) DEFAULT 'pending',
    `notes` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `admin` ADD PRIMARY KEY (`id`);
ALTER TABLE `users` ADD PRIMARY KEY (`id`);
ALTER TABLE `departments` ADD PRIMARY KEY (`id`);
ALTER TABLE `doctors` ADD PRIMARY KEY (`id`);
ALTER TABLE `appointments` ADD PRIMARY KEY (`id`);

ALTER TABLE `admin` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
ALTER TABLE `users` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `departments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `doctors` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
ALTER TABLE `appointments` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

CREATE TABLE `prescriptions` (
    `id` int(11) NOT NULL,
    `doctor_id` int(11) NOT NULL,
    `patient_email` varchar(100) NOT NULL,
    `medications` varchar(500) NOT NULL,
    `instructions` varchar(1000) DEFAULT NULL,
    `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `billings` (
    `id` int(11) NOT NULL,
    `prescription_id` int(11) NOT NULL,
    `patient_email` varchar(100) NOT NULL,
    `amount` int(11) NOT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `status` varchar(50) DEFAULT 'pending',
    `paid_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

ALTER TABLE `prescriptions` ADD PRIMARY KEY (`id`);
ALTER TABLE `prescriptions` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `billings` ADD PRIMARY KEY (`id`);
ALTER TABLE `billings` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

-- Dummy Data Insertion

-- Patients
INSERT INTO `users` (`name`, `email`, `password`, `role`, `timestamp`) VALUES
('Md. Rahim Uddin', 'rahim@example.com', '12345', 'patient', '25:04:2026 12:00:00am'),
('Kazi Fatema Begum', 'fatema@example.com', '12345', 'patient', '25:04:2026 12:00:00am'),
('Syed Ali Ahmed', 'ali@example.com', '12345', 'patient', '25:04:2026 12:00:00am');

-- Departments
INSERT INTO `departments` (`dept_name`, `short_desc`, `long_desc`) VALUES
('Cardiology', 'Heart and cardiovascular system care', 'Specialized treatment for all heart-related conditions and diseases.'),
('Neurology', 'Brain and nervous system disorders', 'Expert care for neurological conditions including brain and nerve disorders.'),
('Orthopedics', 'Bone and joint care', 'Comprehensive treatment for musculoskeletal issues, bones, and joints.'),
('Pediatrics', 'Child healthcare services', 'Dedicated healthcare services for infants, children, and adolescents.'),
('General Medicine', 'Primary care and general health', 'Holistic healthcare approach for diagnosis and non-surgical treatment of adult diseases.');

-- Doctors in users table
INSERT INTO `users` (`name`, `email`, `password`, `role`, `timestamp`) VALUES
('Dr. Hasan Mahmud', 'hasan@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Md. Raiyan', 'raiyan@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Tariqul Islam', 'tariqul@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Nusrat Jahan', 'nusrat@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Shafiqur Rahman', 'shafiqur@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Ayesha Siddiqa', 'ayesha@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Kamal Hossain', 'kamal@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Roksana Akter', 'roksana@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Arifur Rahman', 'arifur@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
('Dr. Salma Khatun', 'salma@example.com', '12345', 'doctor', '25:04:2026 12:00:00am');

-- Doctors table
INSERT INTO `doctors` (`dept_id`, `name`, `email`, `image`, `specialization`, `fee`, `available_start`, `available_end`) VALUES
(1, 'Dr. Hasan Mahmud', 'hasan@example.com', 'doctor1.jpg', 'Cardiologist', 1500, '09:00', '14:00'),
(1, 'Dr. Md. Raiyan', 'raiyan@example.com', 'doctor2.jpg', 'Heart Surgeon', 2000, '15:00', '20:00'),
(2, 'Dr. Tariqul Islam', 'tariqul@example.com', 'default-doctor.jpg', 'Neurologist', 1200, '10:00', '16:00'),
(2, 'Dr. Nusrat Jahan', 'nusrat@example.com', 'default-doctor.jpg', 'Neurosurgeon', 1800, '14:00', '19:00'),
(3, 'Dr. Shafiqur Rahman', 'shafiqur@example.com', 'default-doctor.jpg', 'Orthopedic Surgeon', 1500, '08:00', '13:00'),
(3, 'Dr. Ayesha Siddiqa', 'ayesha@example.com', 'default-doctor.jpg', 'Joint Replacement Specialist', 1600, '14:00', '18:00'),
(4, 'Dr. Kamal Hossain', 'kamal@example.com', 'default-doctor.jpg', 'Pediatrician', 1000, '09:00', '15:00'),
(4, 'Dr. Roksana Akter', 'roksana@example.com', 'default-doctor.jpg', 'Child Specialist', 1000, '15:00', '21:00'),
(5, 'Dr. Arifur Rahman', 'arifur@example.com', 'default-doctor.jpg', 'General Physician', 800, '09:00', '17:00'),
(5, 'Dr. Salma Khatun', 'salma@example.com', 'default-doctor.jpg', 'Internal Medicine', 1000, '10:00', '18:00');

-- Appointments
INSERT INTO `appointments` (`patient_name`, `patient_email`, `doctor_id`, `dept_id`, `appointment_date`, `appointment_time`, `status`, `notes`) VALUES
('Md. Rahim Uddin', 'rahim@example.com', 3, 2, '2026-04-24', '10:00', 'completed', 'Headache issues'),
('Kazi Fatema Begum', 'fatema@example.com', 1, 1, '2026-04-25', '11:00', 'completed', 'Follow up on previous visit'),
('Syed Ali Ahmed', 'ali@example.com', 5, 3, '2026-04-25', '08:00', 'completed', 'Severe back pain');

-- Prescriptions
INSERT INTO `prescriptions` (`doctor_id`, `patient_email`, `medications`, `instructions`, `created_at`) VALUES
(1, 'fatema@example.com', 'Amlodipine 5mg', '1 tablet daily in the morning', '2026-04-25 11:45:00'),
(5, 'ali@example.com', 'Napa Extend 665mg', '1+0+1 after meal for 5 days', '2026-04-25 08:30:00'),
(3, 'rahim@example.com', 'Paracetamol 500mg', '1 tablet when needed', '2026-04-24 10:30:00');

-- Billings
INSERT INTO `billings` (`prescription_id`, `patient_email`, `amount`, `payment_method`, `status`, `paid_at`) VALUES
(1, 'fatema@example.com', 1500, 'BKash', 'completed', '2026-04-25 12:00:00'),
(2, 'ali@example.com', 1500, NULL, 'pending', NULL),
(3, 'rahim@example.com', 1200, NULL, 'pending', NULL);

COMMIT;
