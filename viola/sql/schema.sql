CREATE DATABASE IF NOT EXISTS viola_realestate
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE viola_realestate;

SET FOREIGN_KEY_CHECKS = 0;
DROP TABLE IF EXISTS inquiries;
DROP TABLE IF EXISTS posts;
DROP TABLE IF EXISTS listings;
DROP TABLE IF EXISTS agents;
DROP TABLE IF EXISTS users;
SET FOREIGN_KEY_CHECKS = 1;

CREATE TABLE users (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL UNIQUE,
  password_hash VARCHAR(255) NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE agents (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  role VARCHAR(80) NOT NULL,
  phone VARCHAR(40) NOT NULL,
  email VARCHAR(190) NOT NULL,
  location VARCHAR(120) NOT NULL,
  bio TEXT NOT NULL,
  photo_url VARCHAR(500) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE listings (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  price DECIMAL(12, 2) NOT NULL,
  location VARCHAR(120) NOT NULL,
  beds TINYINT UNSIGNED NOT NULL,
  baths TINYINT UNSIGNED NOT NULL,
  status ENUM('sale', 'rent') NOT NULL,
  type ENUM('house', 'apartment', 'villa', 'condo') NOT NULL,
  image_url VARCHAR(500) NOT NULL,
  visibility ENUM('public', 'pending') NOT NULL DEFAULT 'public',
  user_id INT UNSIGNED NULL,
  agent_id INT UNSIGNED NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  INDEX idx_listings_search (visibility, status, type, beds, baths, location),
  CONSTRAINT fk_listings_user FOREIGN KEY (user_id) REFERENCES users (id) ON DELETE SET NULL,
  CONSTRAINT fk_listings_agent FOREIGN KEY (agent_id) REFERENCES agents (id) ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE posts (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  title VARCHAR(180) NOT NULL,
  slug VARCHAR(180) NOT NULL UNIQUE,
  excerpt VARCHAR(280) NOT NULL,
  body TEXT NOT NULL,
  image_url VARCHAR(500) NOT NULL,
  published_at DATE NOT NULL
) ENGINE=InnoDB;

CREATE TABLE inquiries (
  id INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  name VARCHAR(120) NOT NULL,
  email VARCHAR(190) NOT NULL,
  topic VARCHAR(80) NOT NULL,
  message TEXT NOT NULL,
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

INSERT INTO users (name, email, password_hash) VALUES
(
  'Avery Cole',
  'demo@viola.test',
  '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi'
);

INSERT INTO agents (name, role, phone, email, location, bio, photo_url) VALUES
(
  'Elena Voss',
  'Principal Broker',
  '(310) 555-0148',
  'elena@viola.test',
  'Los Angeles, CA',
  'Elena leads VIOLA West Coast with a focus on architectural estates, off-market villa sales, and discreet family relocations.',
  'https://images.unsplash.com/photo-1544005313-94ddf0286df2?auto=format&fit=crop&w=800&q=80'
),
(
  'Marcus Hale',
  'Luxury Rentals',
  '(305) 555-0192',
  'marcus@viola.test',
  'Miami, FL',
  'Marcus places waterfront penthouses and seasonal rentals for clients who want resort living with city access.',
  'https://images.unsplash.com/photo-1500648767791-00dcc994a43e?auto=format&fit=crop&w=800&q=80'
),
(
  'Priya Raman',
  'Buyer Specialist',
  '(512) 555-0174',
  'priya@viola.test',
  'Austin, TX',
  'Priya helps first-move and relocating buyers read neighborhoods, schools, and long-term value without the noise.',
  'https://images.unsplash.com/photo-1524504388940-b1c1722653e1?auto=format&fit=crop&w=800&q=80'
),
(
  'Jonah Reed',
  'Condo Advisor',
  '(312) 555-0116',
  'jonah@viola.test',
  'Chicago, IL',
  'Jonah specializes in high-rise inventory, HOA due diligence, and skyline homes that still feel livable.',
  'https://images.unsplash.com/photo-1472099645785-5658abf4ff4e?auto=format&fit=crop&w=800&q=80'
),
(
  'Sofia Marin',
  'Coastal Estates',
  '(858) 555-0188',
  'sofia@viola.test',
  'San Diego, CA',
  'Sofia represents indoor-outdoor homes, canyon lots, and coastal properties from La Jolla through Malibu.',
  'https://images.unsplash.com/photo-1487412720507-e7ab37603c6f?auto=format&fit=crop&w=800&q=80'
),
(
  'Theo Park',
  'Pacific Northwest',
  '(206) 555-0133',
  'theo@viola.test',
  'Seattle, WA',
  'Theo works lake, forest, and downtown listings with an eye for craft, light, and year-round livability.',
  'https://images.unsplash.com/photo-1507003211169-0a1dd7228f2d?auto=format&fit=crop&w=800&q=80'
);

INSERT INTO listings (title, price, location, beds, baths, status, type, image_url, visibility, agent_id) VALUES
('Sunset Ridge Villa', 1850000.00, 'Los Angeles, CA', 5, 4, 'sale', 'villa', 'https://images.unsplash.com/photo-1613490493576-7fde63acd811?auto=format&fit=crop&w=1200&q=80', 'public', 1),
('Harborview Penthouse', 7200.00, 'Miami, FL', 3, 3, 'rent', 'apartment', 'https://images.unsplash.com/photo-1502672260266-1c1ef2d93688?auto=format&fit=crop&w=1200&q=80', 'public', 2),
('Maple Grove Family Home', 645000.00, 'Austin, TX', 4, 3, 'sale', 'house', 'https://images.unsplash.com/photo-1600596542815-ffad4c1539a9?auto=format&fit=crop&w=1200&q=80', 'public', 3),
('Downtown Skyline Condo', 389000.00, 'Chicago, IL', 2, 2, 'sale', 'condo', 'https://images.unsplash.com/photo-1560448204-e02f11c3d0e2?auto=format&fit=crop&w=1200&q=80', 'public', 4),
('Palm Court Townhouse', 4100.00, 'San Diego, CA', 3, 2, 'rent', 'house', 'https://images.unsplash.com/photo-1600585154340-be2719e2d5ce?auto=format&fit=crop&w=1200&q=80', 'public', 5),
('Lakehouse Estate', 2450000.00, 'Seattle, WA', 6, 5, 'sale', 'villa', 'https://images.unsplash.com/photo-1600607687939-ce8a6c25118c?auto=format&fit=crop&w=1200&q=80', 'public', 6),
('Brooklyn Loft Studio', 2800.00, 'New York, NY', 1, 1, 'rent', 'apartment', 'https://images.unsplash.com/photo-1522708323590-d24dbb6b0267?auto=format&fit=crop&w=1200&q=80', 'public', 4),
('Cedar Park Bungalow', 425000.00, 'Portland, OR', 3, 2, 'sale', 'house', 'https://images.unsplash.com/photo-1570129477492-45c003edd2be?auto=format&fit=crop&w=1200&q=80', 'public', 6),
('Oceanfront Glass Villa', 9800.00, 'Malibu, CA', 4, 4, 'rent', 'villa', 'https://images.unsplash.com/photo-1512917774080-9991f1c4c750?auto=format&fit=crop&w=1200&q=80', 'public', 5),
('Midtown Executive Condo', 512000.00, 'Atlanta, GA', 2, 2, 'sale', 'condo', 'https://images.unsplash.com/photo-1493809842364-78817add7ffb?auto=format&fit=crop&w=1200&q=80', 'public', 3);

INSERT INTO posts (title, slug, excerpt, body, image_url, published_at) VALUES
(
  'How to Read a Luxury Listing Like an Agent',
  'read-a-luxury-listing',
  'Price, light, and floor plan tell a clearer story than staging photos. Here is the checklist VIOLA advisors use on the first walkthrough.',
  'A polished listing can hide a weak floor plan. Start with orientation: morning light in kitchens, evening light in living rooms, and whether primary suites feel private from the entry.\n\nThen look at true usable square footage. Double-height foyers photograph well and still need to justify their volume against storage, parking, and outdoor rooms.\n\nFinally, compare the ask to recent closed sales on the same street, not the wider ZIP code. Micro-location is where luxury pricing is won or lost.',
  'https://images.unsplash.com/photo-1600585154526-990dced4db0d?auto=format&fit=crop&w=1400&q=80',
  '2026-06-12'
),
(
  'Renting a Villa for the Season Without Surprises',
  'seasonal-villa-rentals',
  'Seasonal coastal rentals move fast. Confirm utilities, HOA rules, and turnover windows before you transfer a deposit.',
  'Ask for a written inventory of furnishings and a clear utility cap. Oceanfront homes often carry higher insurance and HOA fees that landlords pass through in July and December.\n\nConfirm parking, elevator reservations, and quiet hours if you are hosting family. The best villas still operate inside a building or community rulebook.\n\nVIOLA rental specialists negotiate turnover days so you are not paying for an empty house while cleaners and inspectors finish their work.',
  'https://images.unsplash.com/photo-1613977257363-707ba9348227?auto=format&fit=crop&w=1400&q=80',
  '2026-07-03'
),
(
  'Neighborhood Notes: Austin Family Streets vs Downtown Lofts',
  'austin-family-streets',
  'Austin buyers are splitting between walkable lofts and tree-lined family blocks. Both can work if you match commute and outdoor space honestly.',
  'Downtown lofts win on restaurants, events, and lock-and-leave living. They lose on private outdoor space and school runs.\n\nFamily streets in Maple Grove and similar pockets trade nightlife for yards, quieter nights, and easier parking. The premium is less about finishes and more about lot usability.\n\nIf you work hybrid, drive the commute at the hour you will actually travel. A beautiful kitchen does not offset a 50-minute school loop.',
  'https://images.unsplash.com/photo-1600047509807-ba8f99d2cdbc?auto=format&fit=crop&w=1400&q=80',
  '2026-07-28'
),
(
  'Five Questions to Ask Before You Submit a Property',
  'questions-before-you-submit',
  'Owners who list with VIOLA get a faster review when photos, pricing logic, and occupancy status are complete on day one.',
  'Have you lived in the home during the last 12 months, or is it an investment hold? Occupancy changes staging, showing windows, and buyer confidence.\n\nAre you pricing to sell this quarter or testing the market? Those are different listing strategies.\n\nCan you share a recent inspection or HOA packet? Incomplete documents slow every luxury transaction.\n\nWhen you submit through the VIOLA form, include a well-lit exterior photo URL and an honest bed and bath count. Our team reviews pending listings before they appear in public search.',
  'https://images.unsplash.com/photo-1560518883-ce09059eeffa?auto=format&fit=crop&w=1400&q=80',
  '2026-08-10'
);
