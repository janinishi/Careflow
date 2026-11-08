-- =====================================================
-- CareFlow Hospital Management System - PostgreSQL Schema
-- =====================================================

-- পুরানো টেবিলগুলো ড্রপ করা (যদি থাকে)
DROP TABLE IF EXISTS fake_doctor_reports CASCADE;
DROP TABLE IF EXISTS prescriptions CASCADE;
DROP TABLE IF EXISTS appointments CASCADE;
DROP TABLE IF EXISTS blood_donors CASCADE;
DROP TABLE IF EXISTS patients CASCADE;
DROP TABLE IF EXISTS doctors CASCADE;
DROP TABLE IF EXISTS users CASCADE;

-- -----------------------------------------------------
-- 1. users : সবার লগইন তথ্য (admin / doctor / patient)
-- -----------------------------------------------------
CREATE TABLE users (
    id SERIAL PRIMARY KEY,
    full_name VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    phone VARCHAR(20) DEFAULT NULL,
    role VARCHAR(20) CHECK (role IN ('admin','doctor','patient')) NOT NULL DEFAULT 'patient',
    is_active SMALLINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 2. doctors : ডাক্তারের প্রোফাইল
-- -----------------------------------------------------
CREATE TABLE doctors (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    specialization VARCHAR(100) NOT NULL,
    bmdc_reg_no VARCHAR(50) NOT NULL UNIQUE,
    qualification VARCHAR(255) DEFAULT NULL,
    experience_years INT NOT NULL DEFAULT 0,
    chamber_address VARCHAR(255) DEFAULT NULL,
    hospital_name VARCHAR(150) DEFAULT NULL,
    district VARCHAR(80) DEFAULT NULL,
    country VARCHAR(60) NOT NULL DEFAULT 'Bangladesh',
    consultation_fee NUMERIC(10,2) NOT NULL DEFAULT 0.00,
    is_verified SMALLINT NOT NULL DEFAULT 0,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_doctors_search ON doctors (specialization, country, district);

-- -----------------------------------------------------
-- 3. patients : রোগীর প্রোফাইল
-- -----------------------------------------------------
CREATE TABLE patients (
    id SERIAL PRIMARY KEY,
    user_id INT NOT NULL UNIQUE REFERENCES users(id) ON DELETE CASCADE,
    date_of_birth DATE DEFAULT NULL,
    gender VARCHAR(10) CHECK (gender IN ('male','female','other')),
    blood_group VARCHAR(5) CHECK (blood_group IN ('A+','A-','B+','B-','AB+','AB-','O+','O-')),
    address VARCHAR(255) DEFAULT NULL,
    emergency_contact VARCHAR(20) DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

-- -----------------------------------------------------
-- 4. appointments : অ্যাপয়েন্টমেন্ট
-- -----------------------------------------------------
CREATE TABLE appointments (
    id SERIAL PRIMARY KEY,
    patient_id INT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    doctor_id INT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    appointment_date DATE NOT NULL,
    appointment_time TIME NOT NULL,
    reason VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) CHECK (status IN ('pending','confirmed','completed','cancelled')) NOT NULL DEFAULT 'pending',
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_doctor_slot ON appointments (doctor_id, appointment_date, appointment_time);
CREATE INDEX idx_appt_patient ON appointments (patient_id);

-- -----------------------------------------------------
-- 5. prescriptions : প্রেসক্রিপশন
-- -----------------------------------------------------
CREATE TABLE prescriptions (
    id SERIAL PRIMARY KEY,
    appointment_id INT NOT NULL UNIQUE REFERENCES appointments(id) ON DELETE CASCADE,
    doctor_id INT NOT NULL REFERENCES doctors(id) ON DELETE CASCADE,
    patient_id INT NOT NULL REFERENCES patients(id) ON DELETE CASCADE,
    diagnosis TEXT NOT NULL,
    medicines TEXT NOT NULL,
    advice TEXT DEFAULT NULL,
    follow_up_date DATE DEFAULT NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_presc_doctor ON prescriptions (doctor_id);
CREATE INDEX idx_presc_patient ON prescriptions (patient_id);

-- -----------------------------------------------------
-- 6. blood_donors : রক্তদাতা তালিকা
-- -----------------------------------------------------
CREATE TABLE blood_donors (
    id SERIAL PRIMARY KEY,
    user_id INT DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL,
    donor_name VARCHAR(100) NOT NULL,
    blood_group VARCHAR(5) CHECK (blood_group IN ('A+','A-','B+','B-','AB+','AB-','O+','O-')) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    district VARCHAR(80) NOT NULL,
    address VARCHAR(255) DEFAULT NULL,
    last_donation_date DATE DEFAULT NULL,
    is_available SMALLINT NOT NULL DEFAULT 1,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_donor_search ON blood_donors (blood_group, district);

-- -----------------------------------------------------
-- 7. fake_doctor_reports : ভুয়া ডাক্তার রিপোর্ট
-- -----------------------------------------------------
CREATE TABLE fake_doctor_reports (
    id SERIAL PRIMARY KEY,
    reported_by INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    doctor_id INT DEFAULT NULL REFERENCES doctors(id) ON DELETE SET NULL,
    reported_doctor_name VARCHAR(100) NOT NULL,
    claimed_reg_no VARCHAR(50) DEFAULT NULL,
    description TEXT NOT NULL,
    evidence_path VARCHAR(255) DEFAULT NULL,
    status VARCHAR(20) CHECK (status IN ('pending','under_review','confirmed','dismissed')) NOT NULL DEFAULT 'pending',
    admin_note TEXT DEFAULT NULL,
    reviewed_by INT DEFAULT NULL REFERENCES users(id) ON DELETE SET NULL,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
);

CREATE INDEX idx_fdr_status ON fake_doctor_reports (status);