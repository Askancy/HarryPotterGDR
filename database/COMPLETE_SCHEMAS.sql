-- =====================================================
-- HOGWARTS GDR - COMPLETE DATABASE SCHEMAS
-- 4 New Advanced Systems
-- =====================================================

-- =====================================================
-- 1. CREATURE BREEDING SYSTEM
-- =====================================================

-- Already created via Laravel migrations:
-- - creature_species
-- - user_creatures
-- - creature_interactions

-- =====================================================
-- 2. POTION CRAFTING SYSTEM
-- =====================================================

CREATE TABLE IF NOT EXISTS `potions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) UNIQUE NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `description` TEXT NOT NULL,
    `image` VARCHAR(255),
    `type` ENUM('healing', 'buff', 'debuff', 'utility', 'transformation') DEFAULT 'utility',
    `rarity` ENUM('common', 'uncommon', 'rare', 'epic', 'legendary') DEFAULT 'common',
    `effects` JSON COMMENT 'Effects: {health: +50, mana: +30, strength: +10, duration: 60}',
    `difficulty` TINYINT UNSIGNED DEFAULT 1 CHECK (`difficulty` BETWEEN 1 AND 10),
    `brewing_time_minutes` INT UNSIGNED DEFAULT 10,
    `success_rate_base` TINYINT UNSIGNED DEFAULT 70 CHECK (`success_rate_base` BETWEEN 0 AND 100),
    `required_level` INT UNSIGNED DEFAULT 1,
    `market_value` INT UNSIGNED DEFAULT 50,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_rarity` (`rarity`),
    INDEX `idx_type` (`type`),
    INDEX `idx_active` (`is_active`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `ingredients` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(255) UNIQUE NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `description` TEXT,
    `image` VARCHAR(255),
    `rarity` ENUM('common', 'uncommon', 'rare', 'epic', 'legendary') DEFAULT 'common',
    `type` ENUM('plant', 'mineral', 'creature_part', 'magical_essence') DEFAULT 'plant',
    `source` TEXT COMMENT 'Where it can be found',
    `gathering_difficulty` TINYINT UNSIGNED DEFAULT 1,
    `market_value` INT UNSIGNED DEFAULT 10,
    `is_active` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    INDEX `idx_rarity` (`rarity`),
    INDEX `idx_type` (`type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_ingredients` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `ingredient_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED DEFAULT 1,
    `quality` ENUM('poor', 'normal', 'good', 'excellent', 'perfect') DEFAULT 'normal',
    `acquired_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_ingredient` (`user_id`, `ingredient_id`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `potion_recipes` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `potion_id` BIGINT UNSIGNED NOT NULL,
    `ingredient_id` BIGINT UNSIGNED NOT NULL,
    `quantity_required` TINYINT UNSIGNED DEFAULT 1,
    `is_catalyst` BOOLEAN DEFAULT FALSE COMMENT 'Catalyst ingredient',
    `order` TINYINT UNSIGNED DEFAULT 1 COMMENT 'Order of addition',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`potion_id`) REFERENCES `potions`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`ingredient_id`) REFERENCES `ingredients`(`id`) ON DELETE CASCADE,
    UNIQUE KEY `unique_recipe_ingredient` (`potion_id`, `ingredient_id`),
    INDEX `idx_potion` (`potion_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_potions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `potion_id` BIGINT UNSIGNED NOT NULL,
    `quantity` INT UNSIGNED DEFAULT 1,
    `quality` ENUM('flawed', 'standard', 'superior', 'masterwork') DEFAULT 'standard',
    `potency` TINYINT UNSIGNED DEFAULT 100 CHECK (`potency` BETWEEN 50 AND 150),
    `brewed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`potion_id`) REFERENCES `potions`(`id`) ON DELETE CASCADE,
    INDEX `idx_user_potion` (`user_id`, `potion_id`),
    INDEX `idx_expires` (`expires_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 3. PLAYER MARKETPLACE & TRADING SYSTEM
-- =====================================================

CREATE TABLE IF NOT EXISTS `market_listings` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `seller_id` BIGINT UNSIGNED NOT NULL,
    `item_type` ENUM('potion', 'ingredient', 'clothing', 'creature', 'spell_book', 'misc') NOT NULL,
    `item_id` BIGINT UNSIGNED NOT NULL COMMENT 'ID of the specific item',
    `item_data` JSON COMMENT 'Additional item data',
    `quantity` INT UNSIGNED DEFAULT 1,
    `price_per_unit` INT UNSIGNED NOT NULL,
    `currency` ENUM('galleons', 'house_points', 'trade_only') DEFAULT 'galleons',
    `listing_type` ENUM('fixed_price', 'auction', 'trade') DEFAULT 'fixed_price',
    `status` ENUM('active', 'sold', 'cancelled', 'expired') DEFAULT 'active',
    `auction_ends_at` TIMESTAMP NULL,
    `highest_bid` INT UNSIGNED NULL,
    `highest_bidder_id` BIGINT UNSIGNED NULL,
    `views_count` INT UNSIGNED DEFAULT 0,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `expires_at` TIMESTAMP DEFAULT (CURRENT_TIMESTAMP + INTERVAL 7 DAY),
    FOREIGN KEY (`seller_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`highest_bidder_id`) REFERENCES `users`(`id`) ON DELETE SET NULL,
    INDEX `idx_seller` (`seller_id`),
    INDEX `idx_item_type` (`item_type`, `item_id`),
    INDEX `idx_status` (`status`),
    INDEX `idx_auction_ends` (`auction_ends_at`),
    INDEX `idx_created` (`created_at` DESC)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `player_trades` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `initiator_id` BIGINT UNSIGNED NOT NULL,
    `receiver_id` BIGINT UNSIGNED NOT NULL,
    `status` ENUM('pending', 'accepted', 'rejected', 'cancelled', 'completed') DEFAULT 'pending',
    `initiator_offer` JSON COMMENT '{potions: [], ingredients: [], money: 100}',
    `receiver_offer` JSON,
    `message` TEXT,
    `accepted_at` TIMESTAMP NULL,
    `completed_at` TIMESTAMP NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`initiator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`receiver_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_initiator` (`initiator_id`),
    INDEX `idx_receiver` (`receiver_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `trade_offers` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `listing_id` BIGINT UNSIGNED NOT NULL,
    `buyer_id` BIGINT UNSIGNED NOT NULL,
    `offer_amount` INT UNSIGNED NOT NULL,
    `offer_items` JSON COMMENT 'For trade listings',
    `status` ENUM('pending', 'accepted', 'rejected', 'counter_offered') DEFAULT 'pending',
    `message` TEXT,
    `counter_offer_amount` INT UNSIGNED NULL,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`listing_id`) REFERENCES `market_listings`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`buyer_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_listing` (`listing_id`),
    INDEX `idx_buyer` (`buyer_id`),
    INDEX `idx_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- 4. INTERACTIVE STORY SYSTEM (House-Specific)
-- =====================================================

CREATE TABLE IF NOT EXISTS `story_chapters` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `title` VARCHAR(255) NOT NULL,
    `slug` VARCHAR(255) UNIQUE NOT NULL,
    `content` TEXT NOT NULL COMMENT 'Markdown supported',
    `chapter_number` INT UNSIGNED NOT NULL,
    `house_specific` ENUM('all', 'gryffindor', 'slytherin', 'ravenclaw', 'hufflepuff') DEFAULT 'all',
    `required_level` INT UNSIGNED DEFAULT 1,
    `required_previous_chapter_id` BIGINT UNSIGNED NULL,
    `image` VARCHAR(255),
    `background_music` VARCHAR(255),
    `is_published` BOOLEAN DEFAULT FALSE,
    `created_by_user_id` BIGINT UNSIGNED NOT NULL COMMENT 'Admin who created',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`required_previous_chapter_id`) REFERENCES `story_chapters`(`id`) ON DELETE SET NULL,
    FOREIGN KEY (`created_by_user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    INDEX `idx_house` (`house_specific`),
    INDEX `idx_chapter_number` (`chapter_number`),
    INDEX `idx_published` (`is_published`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `story_choices` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `chapter_id` BIGINT UNSIGNED NOT NULL,
    `choice_text` TEXT NOT NULL,
    `choice_number` TINYINT UNSIGNED NOT NULL,
    `leads_to_chapter_id` BIGINT UNSIGNED NULL,
    `requirements` JSON COMMENT '{min_level: 5, has_item: "Elder Wand", relationship: "dumbledore:50"}',
    `stat_requirements` JSON COMMENT '{intelligence: 50, courage: 30}',
    `consequences` JSON COMMENT 'What happens if chosen',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`chapter_id`) REFERENCES `story_chapters`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`leads_to_chapter_id`) REFERENCES `story_chapters`(`id`) ON DELETE SET NULL,
    INDEX `idx_chapter` (`chapter_id`),
    INDEX `idx_choice_number` (`choice_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `user_story_progress` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `chapter_id` BIGINT UNSIGNED NOT NULL,
    `choice_id` BIGINT UNSIGNED NULL COMMENT 'Choice made',
    `completed_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `story_state` JSON COMMENT 'Story variables state',
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`chapter_id`) REFERENCES `story_chapters`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`choice_id`) REFERENCES `story_choices`(`id`) ON DELETE SET NULL,
    UNIQUE KEY `unique_user_chapter` (`user_id`, `chapter_id`),
    INDEX `idx_user` (`user_id`),
    INDEX `idx_completed` (`completed_at`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `story_consequences` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `choice_id` BIGINT UNSIGNED NOT NULL,
    `consequence_type` ENUM('stat_change', 'item_gain', 'item_loss', 'unlock_chapter', 'relationship_change', 'reputation_change') NOT NULL,
    `consequence_data` JSON COMMENT '{stat: "courage", change: +10} or {item: "Elder Wand", quantity: 1}',
    `description` TEXT,
    `is_permanent` BOOLEAN DEFAULT TRUE,
    `created_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (`choice_id`) REFERENCES `story_choices`(`id`) ON DELETE CASCADE,
    INDEX `idx_choice` (`choice_id`),
    INDEX `idx_type` (`consequence_type`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================
-- SAMPLE DATA INSERTS
-- =====================================================

-- Sample Creatures
INSERT INTO `creature_species` (`name`, `slug`, `description`, `rarity`, `danger_level`, `base_health`, `max_level`, `purchase_price`) VALUES
('Ippogrifo', 'hippogriff', 'Maestosa creatura con corpo di cavallo e testa di aquila', 'rare', 'moderate', 150, 15, 500),
('Niffler', 'niffler', 'Piccola creatura affascinata dagli oggetti luccicanti', 'common', 'harmless', 80, 10, 100),
('Fenice', 'phoenix', 'Uccello leggendario capace di rinascere dalle proprie ceneri', 'legendary', 'harmless', 200, 20, 0),
('Bowtruckle', 'bowtruckle', 'Custode degli alberi, piccolo e timido', 'uncommon', 'harmless', 60, 8, 75),
('Drago Ungaro', 'hungarian-horntail', 'Il drago più pericoloso e aggressivo', 'epic', 'extreme', 300, 25, 0);

-- Sample Ingredients
INSERT INTO `ingredients` (`name`, `slug`, `description`, `rarity`, `type`, `market_value`) VALUES
('Mandragola', 'mandrake', 'Radice dalle proprietà curative straordinarie', 'uncommon', 'plant', 50),
('Lacrime di Fenice', 'phoenix-tears', 'Potente ingrediente curativo', 'legendary', 'creature_part', 500),
('Corno di Bicorno', 'bicorn-horn', 'Ingrediente per pozioni di trasformazione', 'rare', 'creature_part', 200),
('Radice di Zenzero', 'ginger-root', 'Ingrediente comune per pozioni base', 'common', 'plant', 5),
('Polvere di Luna', 'moondust', 'Essenza magica raccolta al chiaro di luna', 'epic', 'magical_essence', 300);

-- Sample Potions
INSERT INTO `potions` (`name`, `slug`, `description`, `type`, `rarity`, `difficulty`, `brewing_time_minutes`, `market_value`, `effects`) VALUES
('Pozione Curativa', 'healing-potion', 'Ripristina la salute', 'healing', 'common', 2, 10, 50, '{"health_restore": 50, "instant": true}'),
('Felix Felicis', 'felix-felicis', 'La pozione della fortuna', 'buff', 'legendary', 10, 360, 5000, '{"luck": 50, "critical_chance": 25, "duration_minutes": 60}'),
('Polisucco', 'polyjuice-potion', 'Permette di trasformarsi in qualcun altro', 'transformation', 'epic', 8, 180, 1000, '{"transform": true, "duration_minutes": 60}'),
('Veritaserum', 'veritaserum', 'Costringe a dire la verità', 'utility', 'rare', 6, 120, 500, '{"truth_serum": true, "duration_minutes": 10}');

-- =====================================================
-- USEFUL VIEWS
-- =====================================================

-- Active marketplace listings view
CREATE OR REPLACE VIEW `active_marketplace_listings` AS
SELECT
    ml.*,
    u.name as seller_name,
    u.team as seller_house
FROM market_listings ml
JOIN users u ON ml.seller_id = u.id
WHERE ml.status = 'active'
    AND (ml.expires_at IS NULL OR ml.expires_at > NOW())
ORDER BY ml.created_at DESC;

-- User creature summary
CREATE OR REPLACE VIEW `user_creature_summary` AS
SELECT
    uc.user_id,
    COUNT(*) as total_creatures,
    SUM(CASE WHEN uc.life_stage = 'adult' THEN 1 ELSE 0 END) as adult_creatures,
    AVG(uc.bond_level) as avg_bond_level,
    MAX(uc.level) as highest_level_creature
FROM user_creatures uc
WHERE uc.is_active = 1
GROUP BY uc.user_id;

-- Story progress per house
CREATE OR REPLACE VIEW `story_progress_by_house` AS
SELECT
    u.team as house,
    sc.title as chapter_title,
    COUNT(DISTINCT usp.user_id) as users_completed
FROM user_story_progress usp
JOIN users u ON usp.user_id = u.id
JOIN story_chapters sc ON usp.chapter_id = sc.id
GROUP BY u.team, sc.id, sc.title
ORDER BY u.team, sc.chapter_number;

-- =====================================================
-- TRIGGERS & STORED PROCEDURES
-- =====================================================

-- Auto-update creature age daily
DELIMITER $$
CREATE EVENT IF NOT EXISTS update_creature_ages
ON SCHEDULE EVERY 1 DAY
DO
BEGIN
    UPDATE user_creatures
    SET age_days = DATEDIFF(NOW(), hatched_at)
    WHERE hatched_at IS NOT NULL;
END$$
DELIMITER ;

-- Auto-expire old market listings
DELIMITER $$
CREATE EVENT IF NOT EXISTS expire_old_listings
ON SCHEDULE EVERY 1 HOUR
DO
BEGIN
    UPDATE market_listings
    SET status = 'expired'
    WHERE status = 'active'
        AND expires_at < NOW();
END$$
DELIMITER ;

-- Auto-apply passive creature decay
DELIMITER $$
CREATE PROCEDURE apply_creature_decay()
BEGIN
    DECLARE done INT DEFAULT FALSE;
    DECLARE creature_id BIGINT;
    DECLARE cur CURSOR FOR SELECT id FROM user_creatures WHERE is_active = 1;
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

    OPEN cur;

    read_loop: LOOP
        FETCH cur INTO creature_id;
        IF done THEN
            LEAVE read_loop;
        END IF;

        -- Logic would call Laravel service
        -- This is a placeholder
    END LOOP;

    CLOSE cur;
END$$
DELIMITER ;

-- =====================================================
-- INDEXES FOR PERFORMANCE
-- =====================================================

-- Additional composite indexes
CREATE INDEX idx_user_potions_expiry ON user_potions(user_id, expires_at);
CREATE INDEX idx_user_ingredients_quality ON user_ingredients(user_id, quality, ingredient_id);
CREATE INDEX idx_story_progress_completion ON user_story_progress(user_id, completed_at);
CREATE INDEX idx_market_active_type ON market_listings(status, item_type, created_at);

-- =====================================================
-- DONE!
-- All schemas created for 4 advanced systems
-- =====================================================
