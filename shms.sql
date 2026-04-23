SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

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
    `role` enum('patient','doctor','receptionist') NOT NULL DEFAULT 'patient',
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
    `image` varchar(250) NOT NULL,
    `specialization` varchar(250) NOT NULL,
    `fee` int(255) NOT NULL
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
COMMIT;
