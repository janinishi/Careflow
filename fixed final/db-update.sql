-- =====================================================
-- CareFlow - doctors টেবিলে লোকেশন কলাম যোগ (বাংলাদেশের জেলা + বিদেশি ডাক্তার)
--
-- যারা আগেই db.sql ইম্পোর্ট করেছেন তাদের জন্য: phpMyAdmin > careflow > SQL ট্যাবে
-- এটা রান করুন। এতে আগের কোনো ডাটা মুছবে না।
-- নতুন করে db.sql ইম্পোর্ট করলে এটা আর লাগবে না (db.sql এ কলামগুলো আছে)।
-- =====================================================

USE careflow;

ALTER TABLE doctors
  ADD COLUMN hospital_name VARCHAR(150) DEFAULT NULL AFTER chamber_address,
  ADD COLUMN district      VARCHAR(80)  DEFAULT NULL AFTER hospital_name,   -- জেলা (বিদেশি হলে শহর)
  ADD COLUMN country       VARCHAR(60)  NOT NULL DEFAULT 'Bangladesh' AFTER district,
  ADD KEY idx_doctors_search (specialization, country, district);
