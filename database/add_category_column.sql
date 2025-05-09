-- Add category column to articles table
ALTER TABLE articles ADD COLUMN category VARCHAR(50) DEFAULT 'Uncategorized' AFTER title;

-- Update existing articles to have a default category
UPDATE articles SET category = 'Uncategorized' WHERE category IS NULL; 