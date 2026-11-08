/* CareFlow - Data & Symptom Search Script */

const DIVISIONS = {
  "Dhaka": ["Dhaka", "Gazipur", "Narayanganj", "Narsingdi", "Manikganj", "Munshiganj", "Tangail", "Kishoreganj", "Faridpur", "Gopalganj", "Madaripur", "Rajbari", "Shariatpur"],
  "Chattogram": ["Chattogram", "Cox's Bazar", "Cumilla", "Feni", "Brahmanbaria", "Chandpur", "Lakshmipur", "Noakhali", "Khagrachhari", "Rangamati", "Bandarban"],
  "Rajshahi": ["Rajshahi", "Bogura", "Pabna", "Sirajganj", "Natore", "Naogaon", "Chapainawabganj", "Joypurhat"],
  "Khulna": ["Khulna", "Jashore", "Satkhira", "Bagerhat", "Kushtia", "Chuadanga", "Meherpur", "Magura", "Narail", "Jhenaidah"],
  "Barishal": ["Barishal", "Bhola", "Patuakhali", "Pirojpur", "Jhalokathi", "Barguna"],
  "Sylhet": ["Sylhet", "Moulvibazar", "Habiganj", "Sunamganj"],
  "Rangpur": ["Rangpur", "Dinajpur", "Kurigram", "Gaibandha", "Lalmonirhat", "Nilphamari", "Panchagarh", "Thakurgaon"],
  "Mymensingh": ["Mymensingh", "Jamalpur", "Netrokona", "Sherpur"]
};

const DIVISION_BN = { "Dhaka": "ঢাকা", "Chattogram": "চট্টগ্রাম", "Rajshahi": "রাজশাহী", "Khulna": "খুলনা", "Barishal": "বরিশাল", "Sylhet": "সিলেট", "Rangpur": "রংপুর", "Mymensingh": "ময়মনসিংহ" };
const DISTRICT_BN = { 
  "Dhaka": "ঢাকা", "Dinajpur": "দিনাজপুর", "Gazipur": "গাজীপুর", "Narayanganj": "নারায়ণগঞ্জ", "Chattogram": "চট্টগ্রাম", 
  "Rajshahi": "রাজশাহী", "Khulna": "খুলনা", "Sylhet": "সিলেট", "Rangpur": "রংপুর", "Barishal": "বরিশাল", "Mymensingh": "ময়মনসিংহ",
  "Bogura": "বগুড়া", "Cumilla": "কুমিল্লা", "Cox's Bazar": "কক্সবাজার", "Jashore": "যশোর"
};

const ABROAD_COUNTRIES = ["India", "Singapore", "Thailand", "Malaysia"];
const COUNTRY_BN = { "Bangladesh": "বাংলাদেশ", "India": "ভারত", "Singapore": "সিঙ্গাপুর", "Thailand": "থাইল্যান্ড", "Malaysia": "মালয়েশিয়া" };

function districtName(d) { return typeof isBn === 'function' && isBn() && DISTRICT_BN[d] ? DISTRICT_BN[d] : d; }
function divisionName(v) { return typeof isBn === 'function' && isBn() && DIVISION_BN[v] ? DIVISION_BN[v] : v; }
function countryName(c) { return typeof isBn === 'function' && isBn() && COUNTRY_BN[c] ? COUNTRY_BN[c] : c; }

function fillLocationSelect(select) {
  if (!select) return;
  const keep = select.value;
  select.innerHTML = "";
  select.add(new Option(t("loc.all"), ""));

  for (const [division, districts] of Object.entries(DIVISIONS)) {
    const group = document.createElement("optgroup");
    group.label = t("loc.division", { d: divisionName(division) });
    districts.forEach(function (d) { group.appendChild(new Option(districtName(d), d)); });
    select.appendChild(group);
  }

  const abroad = document.createElement("optgroup");
  abroad.label = t("loc.abroad");
  abroad.appendChild(new Option(t("loc.anyAbroad"), "ABROAD"));
  ABROAD_COUNTRIES.forEach(function (c) { abroad.appendChild(new Option(countryName(c), c)); });
  select.appendChild(abroad);

  select.value = keep;
}

// Complete Symptom Map for both English and Bangla matching
const SYMPTOM_MAP = [
  { specialty: "ENT", keywords: ["gola", "chulkai", "shordi", "kashi", "kan", "nak", "throat", "ear", "nose", "cough", "cold", "গলা", "সর্দি", "কাশি", "কান", "নাক", "চুলকায়", "গলা ব্যথা", "সর্দি কাশি"] },
  { specialty: "Cardiology", keywords: ["chest pain", "heart", "bu ke betha", "বুকে ব্যথা", "হার্ট"] },
  { specialty: "Medicine", keywords: ["fever", "jor", "weakness", "জ্বর", "দুর্বলতা", "শরীর ব্যথা"] },
  { specialty: "Dermatology", keywords: ["skin", "rash", "chulkani", "acne", "চর্ম", "ব্রণ", "এলার্জি", "চুলকায়"] },
  { specialty: "Orthopedics", keywords: ["bone", "joint", "back pain", "komor betha", "হাড়", "কোমর ব্যথা", "বাত", "ব্যথা"] },
  { specialty: "Gynecology", keywords: ["pregnant", "period", "masik", "গর্ভ", "মাসিক"] },
  { specialty: "Pediatrics", keywords: ["child", "baby", "baccha", "শিশু", "বাচ্চা"] },
  { specialty: "Ophthalmology", keywords: ["chokh", "eye", "vision", "চোখ", "চক্ষু", "ঝাপসা", "চোখে ব্যথা", "চোখ চুলকায়"] }
];

function guessSpecialties(text) {
  const clean = text.toLowerCase();
  let matchedSpecs = [];

  for (const item of SYMPTOM_MAP) {
    for (const kw of item.keywords) {
      if (clean.includes(kw)) {
        if (!matchedSpecs.includes(item.specialty)) {
          matchedSpecs.push(item.specialty);
        }
        break;
      }
    }
  }

  if (matchedSpecs.length > 0) {
    return { list: matchedSpecs, matched: true };
  }
  return { list: ["Medicine"], matched: false };
}