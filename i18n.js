/* CareFlow - Complete & Unified i18n Translation Script for All Pages */

const TRANSLATIONS = {
  en: {
    "nav.home": "Home", "nav.book": "Book Appointment", "nav.blood": "Blood Donors", "nav.report": "Report Fake Doctor", "nav.login": "Login", "nav.register": "Register", "nav.dashboard": "Dashboard",
    
    /* Home Page English */
    "idx.title": "CareFlow - Hospital Management System",
    "idx.h1": "Tell us your problem. We will find the right doctor.",
    "idx.sub": "Describe how you feel, choose any district in Bangladesh, and see suitable doctors, including specialists abroad for serious cases.",
    "idx.ph": "Describe symptoms (e.g. fever, headache, chest pain)...",
    "idx.find": "Find Doctors",
    "idx.know": "Already know your doctor?",
    "idx.knowLink": "Book directly →",
    
    "em.badge": "EMERGENCY",
    "em.h": "Need Blood Urgently?",
    "em.p": "Find available donors near you by blood group.",
    "em.btn": "Find a Donor",

    "two.h": "Two Ways to Book a Doctor",
    "two.1.h": "1. Tell us your problem",
    "two.1.p": "Not sure which doctor you need? Describe your symptoms and CareFlow suggests suitable doctors for you.",
    "two.1.a": "Describe symptoms →",
    "two.2.h": "2. I know what I need",
    "two.2.p": "Already know your problem or your doctor? Browse all doctors and book an appointment directly.",
    "two.2.a": "Browse doctors →",

    "svc.h": "What You Can Do on CareFlow",
    "svc.1.h": "Book Appointments",
    "svc.1.p": "Find doctors across all 64 districts, and specialists abroad when you need more.",
    "svc.1.a": "Book now →",
    "svc.2.h": "Digital Prescriptions",
    "svc.2.p": "Access your prescriptions and medical history anytime, safely.",
    "svc.2.a": "Open dashboard →",
    "svc.3.h": "Report Fake Doctors",
    "svc.3.p": "Help keep patients safe by reporting suspicious practitioners.",
    "svc.3.a": "Report →",

    "foot.copy": "© 2026 CareFlow Hospital Management System",
    "foot.built": "Built as a diploma project",

    /* Booking Page English */
    "bk.title": "Book Appointment - CareFlow",
    "bk.h1": "Book an Appointment",
    "bk.sub": "Choose how you would like to find your doctor.",
    "opt1.h": "Option 1: Tell us your problem",
    "opt1.p": "Not sure which doctor to see? Describe your symptoms and we will suggest suitable doctors.",
    "opt2.h": "Option 2: I know what I need",
    "opt2.p": "Already know your problem or doctor? Browse doctors and book directly.",
    "s.label": "What is your problem?",
    "s.ph": "Describe symptoms (e.g. fever, headache)...",
    "s.chips": "Common problems:",
    "s.loc": "Your location",
    "s.abroad": "Also show specialists abroad",
    "s.find": "Find Doctors",
    "s.disclaimer": "This is only a guide to help you choose a doctor. It is not a medical diagnosis.",

    /* Booking Chips & Footer Info English */
    "chip.stomach": "stomach pain", "chip.fever": "fever", "chip.headache": "headache", "chip.cough": "cough", "chip.rash": "skin rash", "chip.back": "back pain", "chip.chest": "chest pain", "chip.tooth": "toothache", "chip.eye": "eye problem", "chip.child": "child sickness",
    "a.b.h": "Before you book",
    "a.b1": "Arrive 10 minutes early",
    "a.b2": "Bring previous reports",
    "a.b3": "You will get a confirmation after approval",
    "a.e.h": "Emergency?",
    "a.e.p": "Call 999 or visit the nearest hospital immediately.",
    "a.e.a": "Find blood donors →",

    /* Location Dropdown English */
    "loc.all": "All Districts (Bangladesh)",
    "loc.division": "{d} Division",
    "loc.abroad": "Specialists Abroad",
    "loc.anyAbroad": "Any Country Abroad",

    /* Appointments Page English */
    "appts.title": "Appointments - CareFlow",
    "appts.h1": "Appointments",
    "appts.back": "Back to Dashboard",
    "appts.history": "Appointment History",
    "appts.reason": "REASON / CAUSE",
    "appts.bookNew": "📅 Book New Appointment",
    "appts.bookSub": "Select doctor and schedule your visit.",
    "appts.selDoc": "Select Doctor",
    "appts.date": "Appointment Date",
    "appts.cause": "Reason / Cause",
    "appts.confirm": "Confirm Booking",

    /* Prescriptions Page English */
    "presc.title": "Digital Prescriptions - CareFlow",
    "presc.headerTitle": "Digital Prescriptions",
    "presc.h1": "Digital Prescriptions & Medical Records",
    "presc.sub": "View and download all your digital prescriptions issued by verified doctors.",
    "presc.back": "Back to Dashboard",
    "presc.listTitle": "Your Prescriptions History",
    "presc.recordsTitle": "Prescription Records",
    "presc.cloudSecured": "Cloud Secured",
    "presc.diagnosisLabel": "Diagnosis:",
    "presc.sampleTableTitle": "Prescription Sample Table:",
    "th.medName": "Medicine Name",
    "th.dosage": "Dosage",
    "th.duration": "Duration",
    "presc.formHeader": "Upload / Add Note",
    "presc.formSub": "Quickly attach an external prescription or write custom notes.",
    "presc.lblDocName": "Doctor / Clinic Name",
    "presc.phDoc": "e.g. Dr. Zaman",
    "presc.lblDiagnosis": "Diagnosis / Reason",
    "presc.phDiag": "e.g. Fever checkup",
    "presc.lblMedName": "Medicine Name",
    "presc.phMed": "e.g. Napa Extra",
    "presc.lblDosage": "Dosage",
    "presc.phDosage": "1 + 0 + 1",
    "presc.lblDuration": "Duration",
    "presc.phDuration": "5 Days",
    "presc.lblFile": "Upload Image / Note File",
    "presc.noFile": "No file selected.",
    "presc.btnSave": "Save Prescription",
    "btn.delete": "Delete",
    "btn.viewPhoto": "View Photo",
    "presc.attachment": "Attachment / Scan:",
    "presc.btnPhoto": "Photo",
    "th.dx": "DX / DIAGNOSIS",
    "btn.view": "View",
    "btn.download": "Download",

    /* Report Fake Doctor Page English */
    "report.title": "Fake Doctor Reporting System - CareFlow",
    "rep.h1": "🛡️ Fake Doctor Reporting System",
    "rep.sub": "Help protect patients by reporting fraudulent or unverified medical practitioners.",
    "rep.formTitle": "Submit a Report",
    "rep.docName": "Doctor's Name / Chamber Name",
    "rep.docPh": "Enter doctor or clinic name",
    "rep.bmdc": "Claimed BMDC Reg. No (if any)",
    "rep.bmdcPh": "e.g. A-12345 or Invalid",
    "rep.desc": "Description / Reason for Suspicion",
    "rep.descPh": "Describe why you suspect this doctor...",
    "rep.submitBtn": "Submit Report",
    "rep.tableTitle": "Verification & Report Status",

    /* Report Table Headers English */
    "th.doctor": "Doctor Name",
    "th.bmdc": "BMDC Reg. No.",
    "th.spec": "Specialization",
    "th.status": "Status",
    "th.action": "Action",

    /* Common Table Headers & Diagnosis */
    "th.date": "DATE",
    "th.meds": "MEDS",
    "diag.routine": "Routine Checkup & Followup",
    "diag.hypertension": "Hypertension",
    "diag.flu": "Seasonal Flu",
    "diag.viralFever": "Viral Fever & Cold",
    "diag.cardiology": "Cardiology Checkup",
    "med.amlodipine": "Amlodipine 5mg",
    "med.paracetamol": "Paracetamol 500mg",
    "status.pendingApproval": "Pending Approval",

    /* Appointments & Dashboard Data English */
    "doc.rahman": "Dr. Rahman",
    "doc.karim": "Dr. Karim",
    "doc.wahab": "Dr. M. A. Wahab",
    "doc.anika": "Dr. Anika Rahman",
    "doc.karimH": "Dr. Karim Hossain",
    "reason.chest": "Chest Pain & Followup",
    "reason.migraine": "Severe Migraine",
    "reason.routine": "Routine Checkup",
    "status.approved": "Approved",
    "status.pending": "Pending",
    "status.completed": "Completed",

    /* Dashboard English */
    "dash.title": "Patient Dashboard - CareFlow",
    "dash.h1": "Patient Dashboard",
    "dash.welcomePrefix": "Welcome back",
    "hello.txt": "Hello",
    "dash.sub": "Here is a quick look at your health activity.",
    "c1.l": "Upcoming", "c1.v": "Appointments",
    "c2.l": "Completed", "c2.v": "Visits",
    "c3.l": "Prescriptions", "c3.v": "Total",
    "c4.l": "Blood Group", "c4.v": "Registered",
    "dash.next": "Next Appointment",
    "dash.nextDoc": "Dr. Anika Rahman (Cardiology)",
    "dash.nextDate": "25 Oct 2026, 10:30 AM",
    "dash.bookAnother": "Book Another",
    "mh.h": "Medical History",
    "mh.viewAll": "View all",

    /* Dashboard & Side Navigation */
    "side.dash": "🏠 Dashboard",
    "side.book": "📅 Book Appointment",
    "side.appts": "🗓️ My Appointments",
    "side.presc": "💊 Prescriptions",
    "side.blood": "🩸 Blood Donors",
    "side.report": "🛡️ Report Doctor",
    "side.logout": "🚪 Logout"
  },
  bn: {
    "nav.home": "হোম", "nav.book": "অ্যাপয়েন্টমেন্ট বুকিং", "nav.blood": "রক্তদাতা", "nav.report": "ভুয়া ডাক্তার রিপোর্ট", "nav.login": "লগইন", "nav.register": "রেজিস্ট্রেশন", "nav.dashboard": "ড্যাশবোর্ড",
    
    /* Home Page Bengali */
    "idx.title": "কেয়ারফ্লো - হসপিটাল ম্যানেজমেন্ট সিস্টেম",
    "idx.h1": "আপনার সমস্যা বলুন। আমরা সঠিক ডাক্তার খুঁজে দেব।",
    "idx.sub": "আপনার অনুভূতি কেমন তা লিখুন, বাংলাদেশের যেকোনো জেলা বেছে নিন এবং উপযুক্ত ডাক্তারদের দেখুন—গুরুতর মামলার জন্য বিদেশের বিশেষজ্ঞসহ।",
    "idx.ph": "লক্ষণ লিখুন (যেমন: জ্বর, মাথা ব্যথা, বুক ব্যথা)...",
    "idx.find": "ডাক্তার খুঁজুন",
    "idx.know": "আপনার ডাক্তার কি আগেই চেনা আছে?",
    "idx.knowLink": "সরাসরি বুক করুন →",
    
    "em.badge": "জরুরি",
    "em.h": "জরুরি রক্ত প্রয়োজন?",
    "em.p": "ব্লাড গ্রুপ অনুযায়ী আপনার কাছাকাছি উপলব্ধ রক্তদাতা খুঁজুন।",
    "em.btn": "রক্তদাতা খুঁজুন",

    "two.h": "ডাক্তার বুক করার দুটি উপায়",
    "two.1.h": "১. আপনার সমস্যা বলুন",
    "two.1.p": "কোন ডাক্তার দরকার নিশ্চিত নন? আপনার লক্ষণগুলো জানান এবং কেয়ারফ্লো আপনার জন্য উপযুক্ত ডাক্তার সাজেস্ট করবে।",
    "two.1.a": "লক্ষণ বর্ণনা করুন →",
    "two.2.h": "২. আমার কী লাগবে আমি জানি",
    "two.2.p": "ইতিমধ্যেই আপনার সমস্যা বা ডাক্তার চেনা আছে? সমস্ত ডাক্তার ব্রাউজ করুন এবং সরাসরি অ্যাপয়েন্টমেন্ট বুক করুন।",
    "two.2.a": "ডাক্তার ব্রাউজ করুন →",

    "svc.h": "কেয়ারফ্লোতে আপনি যা করতে পারেন",
    "svc.1.h": "অ্যাপয়েন্টমেন্ট বুকিং",
    "svc.1.p": "দেশের ৬৪ জেলার ডাক্তার খুঁজুন এবং প্রয়োজনে বিদেশের বিশেষজ্ঞ ডাক্তার পান।",
    "svc.1.a": "বুক করুন →",
    "svc.2.h": "ডিজিটাল প্রেসক্রিপশন",
    "svc.2.p": "যেকোনো সময় নিরাপদে আপনার প্রেসক্রিপশন এবং চিকিৎসা ইতিহাস অ্যাক্সেস করুন।",
    "svc.2.a": "ড্যাশবোর্ড খুলুন →",
    "svc.3.h": "ভুয়া ডাক্তার রিপোর্ট",
    "svc.3.p": "সন্দেহজনক চিকিৎসকদের রিপোর্ট করে রোগীদের নিরাপদ রাখতে সাহায্য করুন।",
    "svc.3.a": "রিপোর্ট করুন →",

    "foot.copy": "© ২০২৬ কেয়ারফ্লো হসপিটাল ম্যানেজমেন্ট সিস্টেম",
    "foot.built": "ডিপ্লোমা প্রজেক্ট হিসেবে তৈরি",

    /* Booking Page Bengali */
    "bk.title": "অ্যাপয়েন্টমেন্ট বুকিং - কেয়ারফ্লো",
    "bk.h1": "একটি অ্যাপয়েন্টমেন্ট বুক করুন",
    "bk.sub": "আপনার ডাক্তার কীভাবে খুঁজতে চান তা বেছে নিন।",
    "opt1.h": "অপশন ১: আপনার সমস্যা বলুন",
    "opt1.p": "কোন ডাক্তার দেখাবেন নিশ্চিত নন? আপনার লক্ষণগুলো জানান এবং আমরা উপযুক্ত ডাক্তার সাজেস্ট করব।",
    "opt2.h": "অপশন ২: আমি জানি আমার কী লাগবে",
    "opt2.p": "ইতিমধ্যেই আপনার সমস্যা বা ডাক্তার চেনেন? ডাক্তার ব্রাউজ করুন এবং সরাসরি বুক করুন।",
    "s.label": "আপনার সমস্যা কী?",
    "s.ph": "লক্ষণ লিখুন (যেমন: জ্বর, মাথা ব্যথা)...",
    "s.chips": "সাধারণ সমস্যাসমূহ:",
    "s.loc": "আপনার লোকেশন",
    "s.abroad": "বিদেশের বিশেষজ্ঞ ডাক্তারও দেখান",
    "s.find": "ডাক্তার খুঁজুন",
    "s.disclaimer": "এটি শুধুমাত্র ডাক্তার নির্বাচনে সহায়তার জন্য। এটি কোনো চিকিৎসাভিত্তিক সিদ্ধান্ত নয়।",

    /* Booking Chips & Footer Info Bengali */
    "chip.stomach": "পেট ব্যথা", "chip.fever": "জ্বর", "chip.headache": "মাথা ব্যথা", "chip.cough": "কাশি", "chip.rash": "ত্বকের ফুসকুড়ি", "chip.back": "কোমর ব্যথা", "chip.chest": "বুক ব্যথা", "chip.tooth": "দাঁতে ব্যথা", "chip.eye": "চোখের সমস্যা", "chip.child": "শিশুর অসুস্থতা",
    "a.b.h": "বুক করার পূর্বে",
    "a.b1": "১০ মিনিট আগে উপস্থিত হোন",
    "a.b2": "পূর্ববর্তী রিপোর্টগুলো সাথে রাখুন",
    "a.b3": "অনুমোদনের পর আপনি কনফার্মেশন পাবেন",
    "a.e.h": "জরুরি অবস্থা?",
    "a.e.p": "৯৯৯ এ কল করুন অথবা তাৎক্ষণিকভাবে নিকটস্থ হাসপাতালে যান।",
    "a.e.a": "রক্তদাতা খুঁজুন →",

    /* Location Dropdown Bengali */
    "loc.all": "সব জেলা (বাংলাদেশ)",
    "loc.division": "{d} বিভাগ",
    "loc.abroad": "বিদেশের বিশেষজ্ঞ",
    "loc.anyAbroad": "যেকোনো বিদেশি দেশ",

    /* Appointments Page Bengali */
    "appts.title": "অ্যাপয়েন্টমেন্ট - কেয়ারফ্লো",
    "appts.h1": "অ্যাপয়েন্টমেন্টসমূহ",
    "appts.back": "ড্যাশবোর্ডে ফিরে যান",
    "appts.history": "অ্যাপয়েন্টমেন্টের ইতিহাস",
    "appts.reason": "কারণ / সমস্যা",
    "appts.bookNew": "📅 নতুন অ্যাপয়েন্টমেন্ট বুক করুন",
    "appts.bookSub": "ডাক্তার নির্বাচন করুন এবং আপনার সাক্ষাতের সময় নির্ধারণ করুন।",
    "appts.selDoc": "ডাক্তার নির্বাচন করুন",
    "appts.date": "অ্যাপয়েন্টমেন্টের তারিখ",
    "appts.cause": "কারণ / সমস্যা",
    "appts.confirm": "বুকিং নিশ্চিত করুন",

    /* Prescriptions Page Bengali */
    "presc.title": "ডিজিটাল প্রেসক্রিপশন - কেয়ারফ্লো",
    "presc.headerTitle": "ডিজিটাল প্রেসক্রিপশন",
    "presc.h1": "ডিজিটাল প্রেসক্রিপশন ও চিকিৎসা রেকর্ড",
    "presc.sub": "যাচাইকৃত ডাক্তারদের দ্বারা প্রদত্ত আপনার সমস্ত ডিজিটাল প্রেসক্রিপশন দেখুন এবং ডাউনলোড করুন।",
    "presc.back": "ড্যাশবোর্ডে ফিরে যান",
    "presc.listTitle": "আপনার প্রেসক্রিপশনের ইতিহাস",
    "presc.recordsTitle": "প্রেসক্রিপশন রেকর্ডসমূহ",
    "presc.cloudSecured": "ক্লাউড সিকিউরড",
    "presc.diagnosisLabel": "রোগ নির্ণয়:",
    "presc.sampleTableTitle": "প্রেসক্রিপশন নমুনা টেবিল:",
    "th.medName": "ঔষধের নাম",
    "th.dosage": "ডোজ",
    "th.duration": "সময়কাল",
    "presc.formHeader": "আপলোড / নোট যোগ করুন",
    "presc.formSub": "দ্রুত একটি বাইরের প্রেসক্রিপশন যুক্ত করুন অথবা কাস্টম নোট লিখুন।",
    "presc.lblDocName": "ডাক্তার / ক্লিনিকের নাম",
    "presc.phDoc": "যেমন: ডা. জামান",
    "presc.lblDiagnosis": "রোগ নির্ণয় / কারণ",
    "presc.phDiag": "যেমন: জ্বর চেকআপ",
    "presc.lblMedName": "ঔষধের নাম",
    "presc.phMed": "যেমন: নাপা এক্সট্রা",
    "presc.lblDosage": "ডোজ",
    "presc.phDosage": "১ + ০ + ১",
    "presc.lblDuration": "সময়কাল",
    "presc.phDuration": "৫ দিন",
    "presc.lblFile": "ছবি / নোট ফাইল আপলোড করুন",
    "presc.noFile": "কোনো ফাইল সিলেক্ট করা হয়নি।",
    "presc.btnSave": "প্রেসক্রিপশন সংরক্ষণ করুন",
    "btn.delete": "ডিলিট",
    "btn.viewPhoto": "ফটো দেখুন",
    "presc.attachment": "অ্যাটাচমেন্ট / স্ক্যান:",
    "presc.btnPhoto": "ফটো",
    "th.dx": "রোগ নির্ণয় (ডায়াগনোসিস)",
    "btn.view": "দেখুন",
    "btn.download": "ডাউনলোড",

    /* Report Fake Doctor Page Bengali */
    "report.title": "ভুয়া ডাক্তার রিপোর্টিং সিস্টেম - কেয়ারফ্লো",
    "rep.h1": "🛡️ ভুয়া ডাক্তার রিপোর্টিং সিস্টেম",
    "rep.sub": "প্রতারক বা যাচাইহীন চিকিৎসকদের রিপোর্ট করে রোগীদের নিরাপদ রাখতে সাহায্য করুন।",
    "rep.formTitle": "রিপোর্ট জমা দিন",
    "rep.docName": "ডাক্তারের নাম / চেম্বারের নাম",
    "rep.docPh": "ডাক্তার বা ক্লিনিকের নাম লিখুন",
    "rep.bmdc": "বিএমডিসি (BMDC) রেজিস্ট্রেশন নম্বর (যদি থাকে)",
    "rep.bmdcPh": "যেমন: A-12345 অথবা অবৈধ",
    "rep.desc": "বিবরণ / সন্দেহের কারণ",
    "rep.descPh": "কেন আপনার এই ডাক্তারকে সন্দেহ হয় তা বর্ণনা করুন...",
    "rep.submitBtn": "রিপোর্ট জমা দিন",
    "rep.tableTitle": "যাচাইকরণ এবং রিপোর্টের অবস্থা",

    /* Report Table Headers Bengali */
    "th.doctor": "ডাক্তারের নাম",
    "th.bmdc": "বিএমডিসি রেজি. নং",
    "th.spec": "বিশেষজ্ঞতা",
    "th.status": "অবস্থা",
    "th.action": "পদক্ষেপ",

    /* Common Table Headers & Diagnosis */
    "th.date": "তারিখ",
    "th.diagnosis": "রোগ নির্ণয়",
    "th.meds": "ঔষধ",
    "diag.routine": "রুটিন চেকআপ ও ফলোআপ",
    "diag.hypertension": "উচ্চ রক্তচাপ",
    "diag.flu": "মৌসুমি সর্দি-জ্বর",
    "diag.viralFever": "মৌসুমি সর্দি ও জ্বর",
    "diag.cardiology": "হৃদরোগ পরীক্ষা (কার্ডিওলজি চেকআপ)",
    "med.amlodipine": "অ্যামলোডিপিন ৫ মিগ্রা",
    "med.paracetamol": "প্যারাসিটামল ৫০০ মিগ্রা",
    "status.pendingApproval": "অনুমোদনের অপেক্ষায়",

    /* Appointments & Dashboard Data Bengali */
    "doc.rahman": "ডা. রহমান",
    "doc.karim": "ডা. করিম",
    "doc.wahab": "ডা. এম. এ. ওয়াহাব",
    "doc.anika": "ডা. আনিকা রহমান",
    "doc.karimH": "ডা. করিম হোসেন",
    "reason.chest": "বুকে ব্যথা ও ফলোআপ",
    "reason.migraine": "তীব্র মাইগ্রেন ও মাথা ব্যথা",
    "reason.routine": "রুটিন চেকআপ",
    "status.approved": "অনুমোদিত",
    "status.pending": "পেন্ডিং",
    "status.completed": "সম্পন্ন",

    /* Dashboard Bengali */
    "dash.title": "পেশেন্ট ড্যাশবোর্ড - কেয়ারফ্লো",
    "dash.h1": "রোগীর ড্যাশবোর্ড",
    "dash.welcome": "স্বাগতম, মিজান 👋",
    "dash.welcomePrefix": "স্বাগতম",
    "hello.txt": "হ্যালো",
    "dash.sub": "আপনার স্বাস্থ্য কর্মকাণ্ডের একটি সংক্ষিপ্ত বিবরণ।",
    "c1.l": "আসন্ন", "c1.v": "অ্যাপয়েন্টমেন্ট",
    "c2.l": "সম্পন্ন", "c2.v": "সাক্ষাৎ",
    "c3.l": "প্রেসক্রিপশন", "c3.v": "মোট",
    "c4.l": "রক্তের গ্রুপ", "c4.v": "রেজিস্টার্ড",
    "dash.next": "পরবর্তী অ্যাপয়েন্টমেন্ট",
    "dash.nextDoc": "ডা. আনিকা রহমান (হৃদরোগ বিশেষজ্ঞ)",
    "dash.nextDate": "২৫ অক্টোবর ২০২৬, সকাল ১০:৩০",
    "dash.bookAnother": "আরেকটি বুক করুন",
    "mh.h": "মেডিকেল হিস্ট্রি",
    "mh.viewAll": "সব দেখুন",

    /* Dashboard & Side Navigation */
    "side.dash": "🏠 ড্যাশবোর্ড",
    "side.book": "📅 অ্যাপয়েন্টমেন্ট বুকিং",
    "side.appts": "🗓️ আমার অ্যাপয়েন্টমেন্ট",
    "side.presc": "💊 প্রেসক্রিপশন",
    "side.blood": "🩸 রক্তদাতা",
    "side.report": "🛡️ রিপোর্ট ডাক্তার",
    "side.logout": "🚪 লগআউট"
  }
};

let currentLang = localStorage.getItem("careflow_lang") || "en";

function isBn() {
  return currentLang === "bn";
}

function t(key, vars) {
  let text = (TRANSLATIONS[currentLang] && TRANSLATIONS[currentLang][key]) || 
             (TRANSLATIONS["en"] && TRANSLATIONS["en"][key]) || key;
  if (vars) {
    for (const [k, v] of Object.entries(vars)) {
      text = text.replace(new RegExp("\\{" + k + "\\}", "g"), v);
    }
  }
  return text;
}

function applyTranslations() {
  document.querySelectorAll("[data-i18n]").forEach(el => {
    const key = el.getAttribute("data-i18n");
    el.textContent = t(key);
  });
  document.querySelectorAll("[data-i18n-placeholder]").forEach(el => {
    const key = el.getAttribute("data-i18n-placeholder");
    el.placeholder = t(key);
  });
  
  const btn = document.getElementById("lang-btn");
  if (btn) {
    btn.textContent = isBn() ? "English" : "বাংলা";
  }

  document.documentElement.lang = currentLang;
  document.dispatchEvent(new CustomEvent("langchange", { detail: { lang: currentLang } }));
}

function toggleLang() {
  currentLang = currentLang === "en" ? "bn" : "en";
  localStorage.setItem("careflow_lang", currentLang);
  applyTranslations();
  
  const locSelects = document.querySelectorAll("#location, #s-location, #d-location");
  locSelects.forEach(sel => {
    if (typeof fillLocationSelect === 'function') {
      fillLocationSelect(sel);
    }
  });
}

document.addEventListener('DOMContentLoaded', () => {
  applyTranslations();
  const locSelects = document.querySelectorAll("#location, #s-location, #d-location");
  locSelects.forEach(sel => {
    if (typeof fillLocationSelect === 'function') {
      fillLocationSelect(sel);
    }
  });
});