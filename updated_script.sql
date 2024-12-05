-- Add `status` column in winning_settings. 2 DEC 2024
ALTER TABLE `winning_settings` 
ADD COLUMN `status` TINYINT DEFAULT 1 AFTER `prize_money`;
