-- Create Database
CREATE DATABASE IF NOT EXISTS crimsondb;
USE crimsondb;

-- ==============================
-- 1. Users Table
-- ==============================
CREATE TABLE if not exists users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    email VARCHAR(150) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    phone VARCHAR(20),
    street_address VARCHAR(255),
    suburb VARCHAR(100),
    state VARCHAR(10),
    postal_code VARCHAR(10),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

-- Insert sample users (5) with hashed passwords: hashedpassword1, hashedpassword2, etc.
INSERT INTO users (first_name, last_name, email, password, phone, street_address, suburb, state, postal_code) VALUES
('John', 'Smith', 'john.smith@email.com', '$2y$10$DAO8puFltvJ6kirDdHUqEu0r4BAP8TfgzkEfQKp9Y7Ulw/WDBDmJ.', '0401234567', '12 King St', 'Adelaide', 'SA', '5000'),
('Emily', 'Brown', 'emily.brown@email.com', '$2y$10$WksTGyMsPfOx.1BJwtksweMhTHudox8HErJHQ7pD3pHXxGOA8uJMW', '0402345678', '25 Queen St', 'Melbourne', 'VIC', '3000'),
('Michael', 'Lee', 'michael.lee@email.com', '$2y$10$drl7ZqiMXKYRu1k9NedeMOF/jCNYOFIOHOiVLfUzmvoMk5PWP9KZ2', '0403456789', '78 George St', 'Sydney', 'NSW', '2000'),
('Sarah', 'Johnson', 'sarah.j@email.com', '$2y$10$drl7ZqiMXKYRu1k9NedeMOF/jCNYOFIOHOiVLfUzmvoMk5PWP9KZ2', '0404567890', '50 Main Rd', 'Perth', 'WA', '6000'),
('David', 'Wilson', 'david.w@email.com', '$2y$10$4iFTAQkHYtLaBYC3gx21Nuy/EdDj9QzHdLIIH9EvIZzj0HJwOKT7q', '0405678901', '90 High St', 'Brisbane', 'QLD', '4000');

-- ==============================
-- 2. Wine Attributes Tables
-- ==============================
CREATE TABLE if not exists colours (
    colour_id INT AUTO_INCREMENT PRIMARY KEY,
    colour_name VARCHAR(50) NOT NULL
);

CREATE TABLE if not exists styles (
    style_id INT AUTO_INCREMENT PRIMARY KEY,
    style_name VARCHAR(50) NOT NULL
);

CREATE TABLE if not exists countries (
    country_id INT AUTO_INCREMENT PRIMARY KEY,
    country_name VARCHAR(100) NOT NULL
);

-- Insert base attributes
INSERT INTO colours (colour_name) VALUES
('Red'), ('White'), ('Rosé');

INSERT INTO styles (style_name) VALUES
('Still'), ('Sparkling');

INSERT INTO countries (country_name) VALUES
('France'), ('Italy'), ('Argentina'), ('Australia'), ('New Zealand'), ('Germany'), ('USA'), ('Spain');

-- ==============================
-- 3. Products Table
-- ==============================
CREATE TABLE if not exists products (
    product_id INT AUTO_INCREMENT PRIMARY KEY,
    colour_id INT,
    style_id INT,
    country_id INT,
    name VARCHAR(200) NOT NULL,
    description TEXT,
    vintage YEAR,
    region VARCHAR(100),
    alcohol_percentage DECIMAL(4,2),
    volume_ml INT,
    closure_type VARCHAR(50),
    serving_temperature VARCHAR(50),
    tasting_notes TEXT,
    food_pairing TEXT,
    price DECIMAL(10,2) NOT NULL,
    stock INT DEFAULT 10,
    image_url VARCHAR(255),
    label_url VARCHAR(255),
    FOREIGN KEY (colour_id) REFERENCES colours(colour_id),
    FOREIGN KEY (style_id) REFERENCES styles(style_id),
    FOREIGN KEY (country_id) REFERENCES countries(country_id)
);

-- Insert sample wines (15 total)
INSERT INTO products (colour_id, style_id, country_id, name, description, vintage, region, alcohol_percentage, volume_ml, closure_type, serving_temperature, tasting_notes, food_pairing, price, stock, image_url, label_url) VALUES
(1, 1, 8, 'Castillo Vgay Gran Reserva Especial 2012', 'Historic Spanish red wine, complex and aged.', 2012, 'Rioja, Spain', 13.50, 750, 'Cork', '16-18°C', 'Rich dark fruit, leather, and tobacco notes.', 'Roast lamb, aged Manchego cheese.', 120.00, 5, 'image/wine/Castillo_Vgay_Gran_Reserva_Especial_2012/bottle.png', 'image/wine/Castillo_Vgay_Gran_Reserva_Especial_2012/label.png'),
(1, 1, 2, 'Case Basse di Gianfranco Soldera Toscana IGT 2015', 'Iconic Italian red from Tuscany.', 2015, 'Tuscany, Italy', 14.00, 750, 'Cork', '16-18°C', 'Cherry, spice, and floral notes with long finish.', 'Florentine steak, truffle pasta.', 450.00, 3, 'image/wine/Case_Basse_di_Gianfranco_Soldera_Toscana_IGT_2015/bottle.png', 'image/wine/Case_Basse_di_Gianfranco_Soldera_Toscana_IGT_2015/label.png'),
(1, 1, 3, 'Catena Zapata Adrianna Vineyard Malbec 2018', 'Top Argentinian Malbec from high altitude vineyards.', 2018, 'Mendoza, Argentina', 14.50, 750, 'Cork', '16-18°C', 'Blackberry, plum, and spice.', 'Grilled meats, empanadas.', 130.00, 8, 'image/wine/Catena_Zapata_Adrianna_Vineyard_Malbec_2018/bottle.png', 'image/wine/Catena_Zapata_Adrianna_Vineyard_Malbec_2018/label.png'),
(2, 1, 1, 'Domaine Leflaive Batard Montrachet Grand Cru 2021', 'Prestigious white Burgundy.', 2021, 'Burgundy, France', 13.50, 750, 'Cork', '10-12°C', 'Rich tropical fruit, buttery oak.', 'Lobster, scallops.', 650.00, 2, 'image/wine/Domaine_Leflaive_Batard_Montrachet_Grand_Cru_2021/bottle.png', 'image/wine/Domaine_Leflaive_Batard_Montrachet_Grand_Cru_2021/label.png'),
(2, 1, 6, 'Egon Muller Scharzhofberger Riesling Auslese 2021', 'One of the world’s most sought-after Rieslings.', 2021, 'Mosel, Germany', 8.00, 750, 'Cork', '8-10°C', 'Honey, citrus, and slate minerality.', 'Spicy Asian food, soft cheeses.', 400.00, 4, 'image/wine/Egon_Muller_Scharzhofberger_Riesling_Auslese_2021/bottle.png', 'image/wine/Egon_Muller_Scharzhofberger_Riesling_Auslese_2021/label.png'),
(1, 2, 1, 'Krug Grande Cuvée NV', 'Luxury multi-vintage Champagne.', NULL, 'Champagne, France', 12.00, 750, 'Cork', '8-10°C', 'Rich brioche, citrus, and almond.', 'Caviar, foie gras.', 350.00, 7, 'image/wine/Krug_Grande_Cuvee_NV/bottle.png', 'image/wine/Krug_Grande_Cuvee_NV/label.png'),
(1, 2, 1, 'Champagne Dom Pérignon Vintage 2012', 'Prestige Champagne.', 2012, 'Champagne, France', 12.50, 750, 'Cork', '8-10°C', 'Citrus, brioche, almond.', 'Oysters, caviar.', 280.00, 12, 'image/wine/Champagne_Dom_Perignon_Vintage_2012/bottle.png', 'image/wine/Champagne_Dom_Perignon_Vintage_2012/label.png'),
(1, 1, 1, 'Château Lafite Rothschild 2015', 'Premier Cru Bordeaux red wine.', 2015, 'Pauillac, Bordeaux', 13.50, 750, 'Cork', '16-18°C', 'Blackcurrant, cedar, and graphite.', 'Steak, lamb chops.', 900.00, 3, 'image/wine/Chateau_Lafite_Rothschild_2015/bottle.png', 'image/wine/Chateau_Lafite_Rothschild_2015/label.png'),
(1, 1, 1, 'Château Pétrus 2018', 'One of the world’s greatest Merlots.', 2018, 'Pomerol, Bordeaux', 14.50, 750, 'Cork', '16-18°C', 'Plum, truffle, and chocolate.', 'Duck, mushroom risotto.', 4000.00, 1, 'image/wine/Chateau_Petrus_2018/bottle.png', 'image/wine/Chateau_Petrus_2018/label.png'),
(2, 1, 5, 'Cloudy Bay Sauvignon Blanc 2024', 'Famous New Zealand Sauvignon Blanc.', 2024, 'Marlborough, NZ', 13.00, 750, 'Screwcap', '8-10°C', 'Passionfruit, gooseberry, citrus.', 'Goat cheese, seafood.', 40.00, 20, 'image/wine/Cloudy_Bay_Sauvignon_Blanc_2024/bottle.png', 'image/wine/Cloudy_Bay_Sauvignon_Blanc_2024/label.png'),
(2, 2, 1, 'Cristal Louis Roederer 2014', 'Prestige cuvée Champagne.', 2014, 'Champagne, France', 12.00, 750, 'Cork', '8-10°C', 'Citrus, white flowers, hazelnut.', 'Caviar, shellfish.', 350.00, 10, 'image/wine/Cristal_Louis_Roederer_2014/bottle.png', 'image/wine/Cristal_Louis_Roederer_2014/label.png'),
(2, 1, 1, 'Domaine William Chablis Grand Cru Les Clos 2019', 'Classic Chablis from Burgundy.', 2019, 'Chablis, Burgundy', 12.50, 750, 'Cork', '10-12°C', 'Crisp apple, mineral, saline.', 'Oysters, sushi.', 120.00, 6, 'image/wine/Domaine_William_Chablis_Grand_Cru_Les_Clos_2019/bottle.png', 'image/wine/Domaine_William_Chablis_Grand_Cru_Les_Clos_2019/label.png'),
(2, 2, 2, 'Franciacorta Brut NV', 'High-quality Italian sparkling.', 2022, 'Lombardy, Italy', 12.00, 750, 'Cork', '8-10°C', 'Citrus, almond, floral notes.', 'Seafood, aperitif.', 60.00, 15, 'image/wine/Franciacorta_Brut_NV/bottle.png', 'image/wine/Franciacorta_Brut_NV/label.png'),
(1, 1, 7, 'Opus One 2016', 'Prestige Napa Valley red.', 2016, 'Napa Valley, USA', 14.50, 750, 'Cork', '16-18°C', 'Blackcurrant, cassis, vanilla.', 'Beef tenderloin, lamb.', 450.00, 4, 'image/wine/Opus_One_2016/bottle.png', 'image/wine/Opus_One_2016/label.png'),
(1, 1, 4, 'Penfolds Grange 2017', 'Australia’s most iconic wine.', 2017, 'Barossa Valley, Australia', 14.50, 750, 'Cork', '16-18°C', 'Dark berries, spice, oak.', 'Kangaroo steak, beef ribs.', 850.00, 5, 'image/wine/Penfolds_Grange_2017/bottle.png', 'image/wine/Penfolds_Grange_2017/label.png'),
(2, 1, 4, 'Tower Estate Hunter Valley Semillon 2015', 'Classic aged Hunter Semillon.', 2015, 'Hunter Valley, Australia', 11.00, 750, 'Screwcap', '10-12°C', 'Citrus, honey, toast.', 'Shellfish, chicken.', 75.00, 7, 'image/wine/Tower_Estate_Hunter_Valley_Semillon_2015/bottle.png', 'image/wine/Tower_Estate_Hunter_Valley_Semillon_2015/label.png');


-- ==============================
-- 4. Orders Table
-- ==============================
CREATE TABLE if not exists orders (
    order_id VARCHAR PRIMARY KEY,
    user_id INT,
    order_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    total_amount DECIMAL(10,2),
    status VARCHAR(50) DEFAULT 'Pending',
    shipping_street_address VARCHAR(255),
    shipping_suburb VARCHAR(100),
    shipping_state VARCHAR(10),
    shipping_postal_code VARCHAR(10),
    payment_method VARCHAR(50),
    payment_status VARCHAR(50) DEFAULT 'Unpaid',
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

-- Insert sample orders (5)
INSERT INTO orders (user_id, total_amount, status, shipping_street_address, shipping_suburb, shipping_state, shipping_postal_code, payment_method, payment_status) VALUES
(1, 240.00, 'Completed', '12 King St', 'Adelaide', 'SA', '5000', 'Credit Card', 'Paid'),
(2, 900.00, 'Pending', '25 Queen St', 'Melbourne', 'VIC', '3000', 'PayPal', 'Unpaid'),
(3, 1350.00, 'Shipped', '78 George St', 'Sydney', 'NSW', '2000', 'Credit Card', 'Paid'),
(4, 75.00, 'Completed', '50 Main Rd', 'Perth', 'WA', '6000', 'Debit Card', 'Paid'),
(5, 490.00, 'Processing', '90 High St', 'Brisbane', 'QLD', '4000', 'Bank Transfer', 'Paid');


-- ==============================
-- 5. Order Items Table
-- ==============================
CREATE TABLE if not exists order_items (
    order_item_id INT AUTO_INCREMENT PRIMARY KEY,
    order_id VARCHAR,
    product_id INT,
    quantity INT,
    subtotal DECIMAL(10,2),
    FOREIGN KEY (order_id) REFERENCES orders(order_id),
    FOREIGN KEY (product_id) REFERENCES products(product_id)
);

-- Insert sample order items (linked to above orders)
INSERT INTO order_items (order_id, product_id, quantity, subtotal) VALUES
-- Order 1 (John Smith, total = 240)
(1, 1, 2, 240.00),   -- 2 × Castillo Vgay (120 each)

-- Order 2 (Emily Brown, total = 900)
(2, 7, 1, 900.00),   -- 1 × Château Lafite Rothschild (900 each)

-- Order 3 (Michael Lee, total = 1480)
(3, 2, 2, 900.00),   -- 2 × Soldera (450 each)
(3, 3, 1, 130.00),   -- 1 × Catena Malbec (130 each)
(3, 14, 1, 450.00),  -- 1 × Opus One (450 each)

-- Order 4 (Sarah Johnson, total = 75)
(4, 15, 1, 75.00),   -- 1 × Tower Estate Semillon (75 each)

-- Order 5 (David Wilson, total = 490)
(5, 9, 2, 80.00),    -- 2 × Cloudy Bay Sauvignon (40 each)
(5, 11, 1, 120.00),  -- 1 × Chablis Les Clos (120 each)
(5, 10, 1, 350.00);  -- 1 × Cristal Champagne (350 each)