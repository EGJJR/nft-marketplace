-- Modify transactions table to allow NULL nft_id
ALTER TABLE transactions MODIFY COLUMN nft_id INT NULL; 