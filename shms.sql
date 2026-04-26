SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET foreign_key_checks = 0;
START TRANSACTION;
SET time_zone = "+06:00";

CREATE TABLE `admin` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `name` varchar(250) NOT NULL,
    `email` varchar(50) NOT NULL,
    `password` varchar(250) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `users` (
    `id` int(11) NOT NULL,
    `name` varchar(100) NOT NULL,
    `email` varchar(100) NOT NULL,
    `password` varchar(100) NOT NULL,
    `role` enum('patient','doctor') NOT NULL DEFAULT 'patient',
    `timestamp` varchar(100) DEFAULT NULL,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `departments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `dept_name` varchar(250) NOT NULL,
    `short_desc` varchar(250) NOT NULL,
    `long_desc` varchar(500) NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `doctors` (
    `id` int(11) NOT NULL,
    `dept_id` int(11) NOT NULL,
    `image` varchar(250) NOT NULL DEFAULT 'default-doctor.jpg',
    `specialization` varchar(250) NOT NULL,
    `fee` int(11) NOT NULL,
    `available_start` varchar(20) NOT NULL DEFAULT '09:00',
    `available_end` varchar(20) NOT NULL DEFAULT '17:00',
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_doctor_user` FOREIGN KEY (`id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_doctor_dept` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE RESTRICT ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `appointments` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `patient_id` int(11) NOT NULL,
    `doctor_id` int(11) NOT NULL,
    `dept_id` int(11) NOT NULL,
    `appointment_date` varchar(20) DEFAULT NULL,
    `appointment_time` varchar(10) DEFAULT NULL,
    `status` varchar(50) NOT NULL DEFAULT 'pending',
    `notes` varchar(500) DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_appt_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_appt_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_appt_dept` FOREIGN KEY (`dept_id`) REFERENCES `departments` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `prescriptions` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `doctor_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `medications` varchar(500) NOT NULL,
    `instructions` varchar(1000) DEFAULT NULL,
    `created_at` datetime NOT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_presc_doctor` FOREIGN KEY (`doctor_id`) REFERENCES `doctors` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_presc_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `billings` (
    `id` int(11) NOT NULL AUTO_INCREMENT,
    `prescription_id` int(11) NOT NULL,
    `patient_id` int(11) NOT NULL,
    `amount` int(11) NOT NULL,
    `payment_method` varchar(50) DEFAULT NULL,
    `status` varchar(50) NOT NULL DEFAULT 'pending',
    `paid_at` datetime DEFAULT NULL,
    PRIMARY KEY (`id`),
    CONSTRAINT `fk_bill_prescription` FOREIGN KEY (`prescription_id`) REFERENCES `prescriptions` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
    CONSTRAINT `fk_bill_patient` FOREIGN KEY (`patient_id`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

SET foreign_key_checks = 1;

INSERT INTO `admin` (`id`, `name`, `email`, `password`) VALUES
(1, 'Admin', 'admin@shms.com', '12345');

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `timestamp`) VALUES
(100, 'Md. Rahim Uddin', 'rahim@example.com', '12345', 'patient', '25:04:2026 12:00:00am'),
(101, 'Kazi Fatema Begum', 'fatema@example.com', '12345', 'patient', '25:04:2026 12:00:00am'),
(102, 'Syed Ali Ahmed', 'ali@example.com', '12345', 'patient', '25:04:2026 12:00:00am'),
(103, 'Tashfin Shakeer Rhythm', 'rhythm@gmail.com', '12345', 'patient', '25:04:2026 12:00:00am');

INSERT INTO `departments` (`dept_name`, `short_desc`, `long_desc`) VALUES
('Cardiology', 'Heart and cardiovascular system care', 'Specialized treatment for all heart-related conditions.'),
('Neurology', 'Brain and nervous system disorders', 'Expert care for neurological conditions.'),
('Orthopedics', 'Bone and joint care', 'Comprehensive treatment for musculoskeletal issues.'),
('Pediatrics', 'Child healthcare services', 'Dedicated healthcare for infants, children, and adolescents.'),
('General Medicine', 'Primary care and general health', 'Holistic healthcare for diagnosis and non-surgical treatment.');

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`, `timestamp`) VALUES
(1000, 'Dr. Hasan Mahmud', 'hasan@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1001, 'Dr. Md. Raiyan', 'raiyan@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1002, 'Dr. Tariqul Islam', 'tariqul@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1003, 'Dr. Nusrat Jahan', 'nusrat@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1004, 'Dr. Shafiqur Rahman', 'shafiqur@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1005, 'Dr. Ayesha Siddiqa', 'ayesha@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1006, 'Dr. Kamal Hossain', 'kamal@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1007, 'Dr. Roksana Akter', 'roksana@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1008, 'Dr. Arifur Rahman', 'arifur@example.com', '12345', 'doctor', '25:04:2026 12:00:00am'),
(1009, 'Dr. Salma Khatun', 'salma@example.com', '12345', 'doctor', '25:04:2026 12:00:00am');

INSERT INTO `doctors` (`id`, `dept_id`, `image`, `specialization`, `fee`, `available_start`, `available_end`) VALUES
(1000, 1, 'doctor1.jpg', 'Cardiologist', 1500, '09:00', '14:00'),
(1001, 1, 'doctor2.jpg', 'Heart Surgeon', 2000, '15:00', '20:00'),
(1002, 2, 'default-doctor.jpg', 'Neurologist', 1200, '10:00', '16:00'),
(1003, 2, 'default-doctor.jpg', 'Neurosurgeon', 1800, '14:00', '19:00'),
(1004, 3, 'default-doctor.jpg', 'Orthopedic Surgeon', 1500, '08:00', '13:00'),
(1005, 3, 'default-doctor.jpg', 'Joint Replacement Specialist', 1600, '14:00', '18:00'),
(1006, 4, 'default-doctor.jpg', 'Pediatrician', 1000, '09:00', '15:00'),
(1007, 4, 'default-doctor.jpg', 'Child Specialist', 1000, '15:00', '21:00'),
(1008, 5, 'default-doctor.jpg', 'General Physician', 800, '09:00', '17:00'),
(1009, 5, 'default-doctor.jpg', 'Internal Medicine', 1000, '10:00', '18:00');

INSERT INTO `appointments` (`patient_id`, `doctor_id`, `dept_id`, `appointment_date`, `appointment_time`, `status`, `notes`) VALUES
(100, 1002, 2, '2026-04-24', '10:00', 'completed', 'Gastric and acidity'),
(101, 1000, 1, '2026-04-25', '11:00', 'completed', 'Follow up on previous visit'),
(102, 1004, 3, '2026-04-25', '08:00', 'completed', 'Severe back pain'),
(100, 1008, 5, '2026-04-26', '10:00', 'completed', 'Fever for 2 days'),
(100, 1009, 5, '2026-04-26', '14:00', 'completed', 'Allergy issues'),
(101, 1000, 1, '2026-04-26', '09:00', 'completed', 'Heart checkup'),
(101, 1001, 1, '2026-04-26', '11:00', 'completed', 'Blood pressure monitoring'),
(102, 1004, 3, '2026-04-26', '12:00', 'completed', 'Calcium deficiency'),
(102, 1005, 3, '2026-04-26', '15:00', 'completed', 'Vitamin check'),
(103, 1006, 4, '2026-04-26', '10:30', 'completed', 'Asthma trouble'),
(103, 1007, 4, '2026-04-26', '16:00', 'completed', 'Shortness of breath'),
(103, 1008, 5, '2026-04-26', '17:00', 'pending', 'General checkup'),
(101, 1002, 2, '2026-04-26', '18:00', 'pending', 'Persistent headache'),
(102, 1000, 1, '2026-04-26', '19:00', 'pending', 'Chest discomfort'),
(101, 1001, 1, '2026-04-26', '12:00', 'pending', 'Heart palpitation'),
(103, 1003, 2, '2026-04-26', '13:00', 'pending', 'Memory issues'),
(100, 1004, 3, '2026-04-26', '14:30', 'pending', 'Knee pain'),
(101, 1005, 3, '2026-04-26', '15:30', 'pending', 'Joint stiffness'),
(102, 1006, 4, '2026-04-26', '16:30', 'pending', 'Fever in child'),
(103, 1007, 4, '2026-04-26', '17:30', 'pending', 'Cough and cold'),
(101, 1009, 5, '2026-04-26', '18:30', 'pending', 'Fatigue'),
(100, 1004, 3, '2026-04-27', '09:00', 'pending', 'Joint pain in knee');

INSERT INTO `prescriptions` (`doctor_id`, `patient_id`, `medications`, `instructions`, `created_at`) VALUES
(1000, 101, 'Camlod 5mg', '1 tablet daily in the morning', '2026-04-25 11:45:00'),
(1004, 102, 'Xeldrin 500mg', '1+0+1 after meal for 5 days', '2026-04-25 08:30:00'),
(1002, 100, 'Sergel 20mg', '1 tablet before meal', '2026-04-24 10:30:00'),
(1008, 100, 'Zithrin 500mg', '1 daily for 3 days', '2026-04-26 10:00:00'),
(1009, 100, 'Fexo 120mg', '1+0+1 after meal', '2026-04-26 14:00:00'),
(1000, 101, 'Ancor 10mg', '0+0+1 before sleep', '2026-04-26 09:00:00'),
(1001, 101, 'Preloc 25mg', '1+0+0 after meal', '2026-04-26 11:00:00'),
(1004, 102, 'Coralcal 500mg', '0+0+1 after meal', '2026-04-26 12:00:00'),
(1005, 102, 'D-Rise 20000iu', '1 weekly', '2026-04-26 15:00:00'),
(1006, 103, 'Monas 10mg', '0+0+1 before sleep', '2026-04-26 10:30:00'),
(1007, 103, 'Azmasol Inhaler', '2 puffs when needed', '2026-04-26 16:00:00');

INSERT INTO `billings` (`prescription_id`, `patient_id`, `amount`, `payment_method`, `status`, `paid_at`) VALUES
(1, 101, 1500, 'BKash', 'completed', '2026-04-25 12:00:00'),
(2, 102, 1500, NULL, 'pending', NULL),
(3, 100, 1200, NULL, 'pending', NULL),
(4, 100, 800, 'BKash', 'completed', '2026-04-26 10:30:00'),
(5, 100, 1000, NULL, 'pending', NULL),
(6, 101, 1500, 'BKash', 'completed', '2026-04-26 09:30:00'),
(7, 101, 2000, 'Debit Card', 'completed', '2026-04-26 11:30:00'),
(8, 102, 1500, NULL, 'pending', NULL),
(9, 102, 1600, 'BKash', 'completed', '2026-04-26 15:30:00'),
(10, 103, 1000, 'Cash', 'completed', '2026-04-26 11:00:00'),
(11, 103, 1000, NULL, 'pending', NULL);

COMMIT;
