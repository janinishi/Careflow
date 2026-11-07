-- =====================================================
-- CareFlow Hospital Management System - Database Schema
-- phpMyAdmin > Import অথবা SQL ট্যাবে পেস্ট করে রান করুন
-- =====================================================

CREATE DATABASE IF NOT EXISTS careflow
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE careflow;

-- ডিপেন্ডেন্সি অর্ডার ঠিক রাখতে পুরনো টেবিল উল্টো ক্রমে ড্রপ (ডেভেলপমেন্টের জন্য)
SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS fake_doctor_reports;
DROP TABLE IF EXISTS prescriptions;
DROP TABLE IF EXISTS appointments;
DROP TABLE IF EXISTS blood_donors;
DROP TABLE IF EXISTS patients;
DROP TABLE IF EXISTS doctors;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

-- -----------------------------------------------------
-- 1. users : সবার লগইন তথ্য (admin / doctor / patient)
-- -----------------------------------------------------
CREATE TABLE users (
  id            INT UNSIGNED NOT NULL AUTO_INCREMENT,
  full_name     VARCHAR(100) NOT NULL,
  email         VARCHAR(150) NOT NULL,
  password_hash VARCHAR(255) NOT NULL,           -- password_hash() দিয়ে তৈরি হ্যাশ রাখুন
  phone         VARCHAR(20)  DEFAULT NULL,
  role          ENUM('admin','doctor','patient') NOT NULL DEFAULT 'patient',
  is_active     TINYINT(1)   NOT NULL DEFAULT 1,
  created_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_users_email (email)
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 2. doctors : ডাক্তারের প্রোফাইল (users এর সাথে 1:1)
-- -----------------------------------------------------
CREATE TABLE doctors (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id          INT UNSIGNED NOT NULL,
  specialization   VARCHAR(100) NOT NULL,
  bmdc_reg_no      VARCHAR(50)  NOT NULL,        -- BMDC রেজিস্ট্রেশন নম্বর
  qualification    VARCHAR(255) DEFAULT NULL,
  experience_years TINYINT UNSIGNED NOT NULL DEFAULT 0,
  chamber_address  VARCHAR(255) DEFAULT NULL,
  hospital_name    VARCHAR(150) DEFAULT NULL,
  district         VARCHAR(80)  DEFAULT NULL,        -- জেলা (বিদেশি ডাক্তার হলে শহর)
  country          VARCHAR(60)  NOT NULL DEFAULT 'Bangladesh',  -- বিদেশি ডাক্তারের জন্য দেশ
  consultation_fee DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  is_verified      TINYINT(1)   NOT NULL DEFAULT 0,  -- অ্যাডমিন ভেরিফাই করলে 1
  created_at       TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_doctors_user (user_id),
  UNIQUE KEY uq_doctors_bmdc (bmdc_reg_no),
  KEY idx_doctors_search (specialization, country, district),
  CONSTRAINT fk_doctors_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 3. patients : রোগীর প্রোফাইল (users এর সাথে 1:1)
-- -----------------------------------------------------
CREATE TABLE patients (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id           INT UNSIGNED NOT NULL,
  date_of_birth     DATE DEFAULT NULL,
  gender            ENUM('male','female','other') DEFAULT NULL,
  blood_group       ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') DEFAULT NULL,
  address           VARCHAR(255) DEFAULT NULL,
  emergency_contact VARCHAR(20)  DEFAULT NULL,
  created_at        TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_patients_user (user_id),
  CONSTRAINT fk_patients_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 4. appointments : অ্যাপয়েন্টমেন্ট (patient N:1 doctor)
-- -----------------------------------------------------
CREATE TABLE appointments (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  patient_id       INT UNSIGNED NOT NULL,
  doctor_id        INT UNSIGNED NOT NULL,
  appointment_date DATE NOT NULL,
  appointment_time TIME NOT NULL,
  reason           VARCHAR(255) DEFAULT NULL,
  status           ENUM('pending','confirmed','completed','cancelled') NOT NULL DEFAULT 'pending',
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  -- ডাবল বুকিং আটকানো হয় appointments.php এর কোডে (বাতিল হওয়া স্লট যেন আবার বুক করা যায়)
  KEY idx_doctor_slot (doctor_id, appointment_date, appointment_time),
  KEY idx_appt_patient (patient_id),
  CONSTRAINT fk_appt_patient
    FOREIGN KEY (patient_id) REFERENCES patients(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_appt_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 5. prescriptions : প্রেসক্রিপশন (appointment এর সাথে 1:1)
-- -----------------------------------------------------
CREATE TABLE prescriptions (
  id               INT UNSIGNED NOT NULL AUTO_INCREMENT,
  appointment_id   INT UNSIGNED NOT NULL,
  doctor_id        INT UNSIGNED NOT NULL,
  patient_id       INT UNSIGNED NOT NULL,
  diagnosis        TEXT NOT NULL,
  medicines        TEXT NOT NULL,               -- ওষুধের নাম, ডোজ, সময়
  advice           TEXT DEFAULT NULL,
  follow_up_date   DATE DEFAULT NULL,
  created_at       TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  UNIQUE KEY uq_presc_appointment (appointment_id),
  KEY idx_presc_doctor (doctor_id),
  KEY idx_presc_patient (patient_id),
  CONSTRAINT fk_presc_appointment
    FOREIGN KEY (appointment_id) REFERENCES appointments(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_presc_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_presc_patient
    FOREIGN KEY (patient_id) REFERENCES patients(id)
    ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 6. blood_donors : রক্তদাতা তালিকা
--    user_id ঐচ্ছিক (লগইন ছাড়াও ডোনার যোগ করা যাবে)
-- -----------------------------------------------------
CREATE TABLE blood_donors (
  id                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
  user_id            INT UNSIGNED DEFAULT NULL,
  donor_name         VARCHAR(100) NOT NULL,
  blood_group        ENUM('A+','A-','B+','B-','AB+','AB-','O+','O-') NOT NULL,
  phone              VARCHAR(20)  NOT NULL,
  district           VARCHAR(80)  NOT NULL,
  address            VARCHAR(255) DEFAULT NULL,
  last_donation_date DATE DEFAULT NULL,
  is_available       TINYINT(1) NOT NULL DEFAULT 1,
  created_at         TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_donor_search (blood_group, district),
  CONSTRAINT fk_donor_user
    FOREIGN KEY (user_id) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- -----------------------------------------------------
-- 7. fake_doctor_reports : ভুয়া ডাক্তার রিপোর্ট
--    doctor_id ঐচ্ছিক (সিস্টেমে না থাকা ব্যক্তিকেও রিপোর্ট করা যাবে)
-- -----------------------------------------------------
CREATE TABLE fake_doctor_reports (
  id                   INT UNSIGNED NOT NULL AUTO_INCREMENT,
  reported_by          INT UNSIGNED NOT NULL,       -- রিপোর্টকারী (users.id)
  doctor_id            INT UNSIGNED DEFAULT NULL,   -- সিস্টেমে থাকা ডাক্তার হলে
  reported_doctor_name VARCHAR(100) NOT NULL,
  claimed_reg_no       VARCHAR(50)  DEFAULT NULL,
  description          TEXT NOT NULL,
  evidence_path        VARCHAR(255) DEFAULT NULL,   -- আপলোড করা প্রমাণের ফাইল পাথ
  status               ENUM('pending','under_review','confirmed','dismissed') NOT NULL DEFAULT 'pending',
  admin_note           TEXT DEFAULT NULL,
  reviewed_by          INT UNSIGNED DEFAULT NULL,   -- রিভিউ করা অ্যাডমিন (users.id)
  created_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at           TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (id),
  KEY idx_fdr_status (status),
  CONSTRAINT fk_fdr_reporter
    FOREIGN KEY (reported_by) REFERENCES users(id)
    ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT fk_fdr_doctor
    FOREIGN KEY (doctor_id) REFERENCES doctors(id)
    ON DELETE SET NULL ON UPDATE CASCADE,
  CONSTRAINT fk_fdr_reviewer
    FOREIGN KEY (reviewed_by) REFERENCES users(id)
    ON DELETE SET NULL ON UPDATE CASCADE
) ENGINE=InnoDB;

-- =====================================================
-- ঐচ্ছিক: টেস্ট অ্যাডমিন (পাসওয়ার্ড বদলে নিন; আসল হ্যাশ PHP দিয়ে বানান)
-- PHP: echo password_hash('YourStrongPassword', PASSWORD_DEFAULT);
-- =====================================================
-- INSERT INTO users (full_name, email, password_hash, role)
-- VALUES ('Admin', 'admin@careflow.test', '<এখানে password_hash() এর ফল>', 'admin');
