-- ============================================================
--  dubey Travels – MySQL Database (Final)
--  Database: dubey_travels
-- ============================================================

CREATE DATABASE IF NOT EXISTS dubey_travels CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE dubey_travels;

-- ------------------------------------------------------------
-- Table: admin_users
-- Note: password column stores bcrypt hash.
-- Demo credentials: admin / admin123
-- Generate fresh hash: php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS admin_users (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  username    VARCHAR(80)  NOT NULL UNIQUE,
  password    VARCHAR(255) NOT NULL,
  full_name   VARCHAR(120),
  email       VARCHAR(150),
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admin_users (username, password, full_name, email) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Admin User', 'admin@dubey.com')
ON DUPLICATE KEY UPDATE username=username;

-- ------------------------------------------------------------
-- Table: packages
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS packages (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  name          VARCHAR(200) NOT NULL,
  destination   VARCHAR(120) NOT NULL,
  duration      VARCHAR(80),
  price         DECIMAL(10,2) NOT NULL DEFAULT 0.00,
  category      ENUM('hill','beach','adventure','heritage','honeymoon','other') DEFAULT 'other',
  badge         VARCHAR(60),
  description   TEXT,
  image_url     VARCHAR(500),
  status        ENUM('active','inactive') DEFAULT 'active',
  created_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at    TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO packages (name, destination, duration, price, category, badge, description, image_url, status) VALUES
('Kashmir Valley - 7 Days Paradise', 'Kashmir', '7 Days / 6 Nights', 18999.00, 'hill', 'Best Seller', 'Experience the majestic beauty of Kashmir from the serene Dal Lake to the breathtaking Gulmarg meadows.', 'https://images.unsplash.com/photo-1587474260584-136574528ed5?w=600', 'active'),
('Goa Sun and Sand - 5 Days Bliss', 'Goa', '5 Days / 4 Nights', 12499.00, 'beach', 'Popular', 'Soak in the golden sun of Goas legendary beaches. Enjoy water sports, seafood, and vibrant nightlife.', 'https://images.unsplash.com/photo-1512343879784-a960bf40e7f2?w=600', 'active'),
('Himachal Hills - 8 Days Adventure', 'Himachal Pradesh', '8 Days / 7 Nights', 22499.00, 'adventure', 'New', 'From Shimla colonial charm to Manali roaring rivers and Spiti stark lunar landscape.', 'https://images.unsplash.com/photo-1626016879546-8e79e32cfaa8?w=600', 'active'),
('Rajasthan Royal Heritage - 10 Days', 'Rajasthan', '10 Days / 9 Nights', 34999.00, 'heritage', 'Premium', 'Travel through the Land of Kings — majestic forts, opulent palaces, golden deserts.', 'https://images.unsplash.com/photo-1599661046289-e31897846e41?w=600', 'active'),
('Kerala Gods Own Country - 6 Days', 'Kerala', '6 Days / 5 Nights', 16999.00, 'honeymoon', 'Honeymoon', 'Glide through emerald backwaters on a luxury houseboat, relax on pristine Kovalam beach.', 'https://images.unsplash.com/photo-1602216056096-3b40cc0c9944?w=600', 'active'),
('Leh-Ladakh Bike Expedition - 12 Days', 'Leh-Ladakh', '12 Days / 11 Nights', 45999.00, 'adventure', 'Extreme', 'The ultimate bucket-list adventure — ride through the worlds highest motorable roads.', 'https://images.unsplash.com/photo-1578070181910-f1e514afdd08?w=600', 'active'),
('Andaman Islands - 5 Days Paradise', 'Andaman', '5 Days / 4 Nights', 28499.00, 'beach', 'Island Life', 'Crystal-clear waters, powder-white beaches, and world-class snorkelling and scuba diving.', 'https://images.unsplash.com/photo-1544551763-46a013bb70d5?w=600', 'active'),
('Char Dham Yatra - 14 Days Pilgrimage', 'Uttarakhand', '14 Days / 13 Nights', 38999.00, 'heritage', 'Spiritual', 'Undertake the sacred Char Dham pilgrimage — Yamunotri, Gangotri, Kedarnath, and Badrinath.', 'https://images.unsplash.com/photo-1561361058-c12e84c47c1e?w=600', 'active');

-- ------------------------------------------------------------
-- Table: itinerary_days
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS itinerary_days (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  package_id  INT NOT NULL,
  day_number  INT NOT NULL,
  day_label   VARCHAR(10) DEFAULT 'D1',
  title       VARCHAR(200),
  description TEXT,
  FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE
);

INSERT INTO itinerary_days (package_id, day_number, day_label, title, description) VALUES
(1,1,'D1','Arrival in Srinagar','Welcome to the Venice of the East. Check into your houseboat on Dal Lake and enjoy a sunset shikara ride.'),
(1,2,'D2','Gulmarg Day Trip','Visit the Meadow of Flowers. Take the famous Gondola ride to 13,400 ft for stunning Himalayan views.'),
(1,3,'D3','Pahalgam Excursion','Drive through the lush Lidder Valley to Pahalgam, the Valley of Shepherds.'),
(1,4,'D4','Sonmarg Glacier','Visit the Golden Meadow and trek to the famous Thajiwas Glacier.'),
(1,5,'D5','Srinagar City Tour','Explore Mughal Gardens — Shalimar Bagh, Nishat Bagh, and the Hazratbal Shrine.'),
(1,6,'D6','Shopping Day','Visit old city bazaars. Shop for Kashmiri handicrafts, shawls, saffron, and dry fruits.'),
(1,7,'D7','Departure','Transfer to Srinagar Airport with memories of a lifetime.');

-- ------------------------------------------------------------
-- Table: enquiries
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS enquiries (
  id            INT AUTO_INCREMENT PRIMARY KEY,
  full_name     VARCHAR(120) NOT NULL,
  email         VARCHAR(150) NOT NULL,
  phone         VARCHAR(20)  NOT NULL,
  destination   VARCHAR(120),
  travel_date   DATE,
  num_persons   VARCHAR(20),
  message       TEXT,
  status        ENUM('pending','confirmed','cancelled') DEFAULT 'pending',
  submitted_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO enquiries (full_name, email, phone, destination, travel_date, num_persons, message, status) VALUES
('Rahul Mehta',  'rahul@example.com', '+91 98765 00001', 'Kashmir Valley', '2025-10-15', '2',   'Interested in the deluxe houseboat option.', 'confirmed'),
('Priya Sharma', 'priya@example.com', '+91 98765 00002', 'Goa Beaches',    '2025-12-20', '3-5', 'Family trip, need child-friendly activities.', 'pending');

-- ------------------------------------------------------------
-- Table: contact_messages
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS contact_messages (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  full_name   VARCHAR(120) NOT NULL,
  email       VARCHAR(150) NOT NULL,
  subject     VARCHAR(200),
  message     TEXT,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- ------------------------------------------------------------
-- Table: site_content
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS site_content (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  `key`       VARCHAR(100) NOT NULL UNIQUE,
  value       TEXT,
  updated_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

INSERT INTO site_content (`key`, value) VALUES
('hero_tagline',    'Discover the Worlds Hidden Wonders'),
('hero_subtext',    'Unforgettable journeys curated for the discerning traveller.'),
('about_text',      'dubey Travels was founded with a simple belief: every person deserves to experience the worlds beauty without stress or compromise.'),
('contact_phone',   '+91 98765 43210'),
('contact_email',   'info@dubey.com'),
('contact_address', '42 Travel House, Connaught Place, New Delhi 110001'),
('office_hours',    'Mon-Sat 9AM-7PM')
ON DUPLICATE KEY UPDATE value=VALUES(value);

-- ------------------------------------------------------------
-- Table: banners
-- ------------------------------------------------------------
CREATE TABLE IF NOT EXISTS banners (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  title       VARCHAR(150),
  image_url   VARCHAR(500),
  link_url    VARCHAR(500),
  sort_order  INT DEFAULT 0,
  is_active   TINYINT(1) DEFAULT 1,
  created_at  TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO banners (title, image_url, sort_order, is_active) VALUES
('Mountain Adventure', 'https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1800', 1, 1),
('Nature Escape',      'https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=1800', 2, 1),
('Ocean Serenity',     'https://images.unsplash.com/photo-1476514525535-07fb3b4ae5f1?w=1800', 3, 1);

-- ============================================================
-- IMPORTANT: Fix admin password before going live
-- Run: php -r "echo password_hash('admin123', PASSWORD_BCRYPT);"
-- Then: UPDATE admin_users SET password='<your_hash>' WHERE username='admin';
-- ============================================================