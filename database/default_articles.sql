-- Insert default articles
INSERT INTO articles (title, content, author_id, category, status, image_path, created_at) VALUES
('Getting Started with Web Development', 
'Web development is an exciting field that combines creativity with technical skills. In this article, we''ll explore the basics of HTML, CSS, and JavaScript - the three core technologies of the web.',
1, 'Web Development', 'published', 'assets/images/web-dev.jpg', NOW()),

('The Future of Artificial Intelligence', 
'Artificial Intelligence is transforming industries across the globe. From healthcare to finance, AI is making significant impacts. This article explores current trends and future possibilities in AI development.',
1, 'Technology', 'published', 'assets/images/ai.jpg', NOW()),

('Sustainable Living: A Guide', 
'Learn how to reduce your environmental impact through sustainable living practices. This comprehensive guide covers everything from energy conservation to waste reduction.',
1, 'Lifestyle', 'published', 'assets/images/sustainability.jpg', NOW()),

('Digital Marketing Strategies for 2024', 
'Stay ahead of the competition with these cutting-edge digital marketing strategies. Learn about SEO, content marketing, social media, and more in this detailed guide.',
1, 'Marketing', 'published', 'assets/images/marketing.jpg', NOW()),

('Healthy Eating Habits', 
'Discover the secrets to maintaining a healthy diet and lifestyle. This article provides practical tips and scientific insights into nutrition and wellness.',
1, 'Health', 'published', 'assets/images/health.jpg', NOW()); 