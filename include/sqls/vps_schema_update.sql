-- ==============================================================
-- VPS Digital Services Company Database Schema Migration
-- Database: vps
-- ==============================================================

USE `vps`;

-- Ensure users table has userlevel 9 for admin users
UPDATE `users` SET `userlevel` = 9 WHERE `username` IN ('Admin', 'admin', 'khizar');

-- Create services table
CREATE TABLE IF NOT EXISTS `services` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `full_desc` MEDIUMTEXT DEFAULT NULL,
  `icon` VARCHAR(100) DEFAULT 'code',
  `image` VARCHAR(255) DEFAULT NULL,
  `category` VARCHAR(100) DEFAULT 'Development',
  `features` TEXT DEFAULT NULL,
  `display_order` INT(11) DEFAULT 0,
  `is_featured` TINYINT(1) DEFAULT 1,
  `is_active` TINYINT(1) DEFAULT 1,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create projects table
CREATE TABLE IF NOT EXISTS `projects` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `reference_no` VARCHAR(50) NOT NULL,
  `client_name` VARCHAR(150) NOT NULL,
  `client_email` VARCHAR(150) NOT NULL,
  `client_phone` VARCHAR(50) DEFAULT NULL,
  `client_whatsapp` VARCHAR(50) DEFAULT NULL,
  `company_name` VARCHAR(150) DEFAULT NULL,
  `country` VARCHAR(100) DEFAULT NULL,
  `preferred_contact` VARCHAR(50) DEFAULT 'email',
  `title` VARCHAR(255) NOT NULL,
  `service_id` INT(11) DEFAULT NULL,
  `service_name` VARCHAR(150) DEFAULT NULL,
  `description` MEDIUMTEXT NOT NULL,
  `objectives` TEXT DEFAULT NULL,
  `scope_features` TEXT DEFAULT NULL,
  `timeline` VARCHAR(100) DEFAULT NULL,
  `budget_type` VARCHAR(50) DEFAULT 'fixed',
  `budget_amount` VARCHAR(100) DEFAULT NULL,
  `currency` VARCHAR(20) DEFAULT 'USD',
  `budget_flexible` TINYINT(1) DEFAULT 0,
  `additional_notes` TEXT DEFAULT NULL,
  `status` VARCHAR(50) DEFAULT 'Pending Review',
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `ref_unique` (`reference_no`),
  KEY `idx_status` (`status`),
  KEY `idx_service` (`service_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create project attachments table
CREATE TABLE IF NOT EXISTS `project_attachments` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `original_name` VARCHAR(255) NOT NULL,
  `file_path` VARCHAR(255) NOT NULL,
  `file_size` INT(11) NOT NULL,
  `mime_type` VARCHAR(100) NOT NULL,
  `uploaded_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_att` (`project_id`),
  CONSTRAINT `fk_project_att` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create project status history table
CREATE TABLE IF NOT EXISTS `project_status_history` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `previous_status` VARCHAR(50) DEFAULT NULL,
  `new_status` VARCHAR(50) NOT NULL,
  `changed_by` VARCHAR(50) NOT NULL,
  `comment` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_hist` (`project_id`),
  CONSTRAINT `fk_project_hist` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create project internal notes table
CREATE TABLE IF NOT EXISTS `project_notes` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `project_id` INT(11) NOT NULL,
  `admin_username` VARCHAR(50) NOT NULL,
  `note` TEXT NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_project_note` (`project_id`),
  CONSTRAINT `fk_project_note` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create team members table
CREATE TABLE IF NOT EXISTS `team` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `slug` VARCHAR(150) NOT NULL,
  `title` VARCHAR(150) NOT NULL,
  `image` VARCHAR(255) DEFAULT NULL,
  `initials` VARCHAR(10) DEFAULT 'VP',
  `short_intro` VARCHAR(255) DEFAULT NULL,
  `bio` TEXT DEFAULT NULL,
  `specialties` TEXT DEFAULT NULL,
  `skills` TEXT DEFAULT NULL,
  `experience` VARCHAR(100) DEFAULT NULL,
  `social_linkedin` VARCHAR(255) DEFAULT NULL,
  `social_github` VARCHAR(255) DEFAULT NULL,
  `social_twitter` VARCHAR(255) DEFAULT NULL,
  `display_order` INT(11) DEFAULT 0,
  `is_active` TINYINT(1) DEFAULT 1,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `team_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create portfolio / case studies table
CREATE TABLE IF NOT EXISTS `portfolio` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(200) NOT NULL,
  `slug` VARCHAR(200) NOT NULL,
  `category` VARCHAR(50) NOT NULL,
  `service_id` INT(11) DEFAULT NULL,
  `short_desc` TEXT DEFAULT NULL,
  `full_desc` MEDIUMTEXT DEFAULT NULL,
  `client_name` VARCHAR(150) DEFAULT NULL,
  `industry` VARCHAR(100) DEFAULT NULL,
  `technologies` VARCHAR(255) DEFAULT NULL,
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `gallery_images` TEXT DEFAULT NULL,
  `challenge` TEXT DEFAULT NULL,
  `solution` TEXT DEFAULT NULL,
  `results` TEXT DEFAULT NULL,
  `completion_date` VARCHAR(50) DEFAULT NULL,
  `project_url` VARCHAR(255) DEFAULT NULL,
  `is_featured` TINYINT(1) DEFAULT 0,
  `is_published` TINYINT(1) DEFAULT 1,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `portfolio_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create blog categories table
CREATE TABLE IF NOT EXISTS `blog_categories` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL,
  `slug` VARCHAR(100) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `bcat_slug_unique` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create blog posts table
CREATE TABLE IF NOT EXISTS `blog_posts` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  `category_id` INT(11) DEFAULT NULL,
  `category_name` VARCHAR(100) DEFAULT 'General',
  `featured_image` VARCHAR(255) DEFAULT NULL,
  `icon` VARCHAR(50) DEFAULT 'article',
  `excerpt` TEXT DEFAULT NULL,
  `content` MEDIUMTEXT NOT NULL,
  `author` VARCHAR(100) DEFAULT 'VPS Team',
  `reading_time` VARCHAR(20) DEFAULT '5 min read',
  `tags` VARCHAR(255) DEFAULT NULL,
  `is_published` TINYINT(1) DEFAULT 1,
  `published_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `meta_title` VARCHAR(255) DEFAULT NULL,
  `meta_desc` TEXT DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `blog_slug_unique` (`slug`),
  KEY `idx_blog_cat` (`category_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create contact messages table
CREATE TABLE IF NOT EXISTS `contact_messages` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(150) NOT NULL,
  `email` VARCHAR(150) NOT NULL,
  `phone` VARCHAR(50) DEFAULT NULL,
  `subject` VARCHAR(255) NOT NULL,
  `message` TEXT NOT NULL,
  `is_read` TINYINT(1) DEFAULT 0,
  `status` VARCHAR(50) DEFAULT 'New',
  `ip_address` VARCHAR(45) DEFAULT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  KEY `idx_msg_status` (`status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create website settings table
CREATE TABLE IF NOT EXISTS `settings` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `setting_key` VARCHAR(100) NOT NULL,
  `setting_value` TEXT DEFAULT NULL,
  `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `setting_key_unique` (`setting_key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Create newsletter subscribers table
CREATE TABLE IF NOT EXISTS `newsletter_subscribers` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `email` VARCHAR(150) NOT NULL,
  `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `news_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Seed default settings if not exists
INSERT IGNORE INTO `settings` (`setting_key`, `setting_value`) VALUES
('company_name', 'V Provide Services (VPS)'),
('company_tagline', 'Everything Digital. One Trusted Provider.'),
('contact_email', 'info@vprovideservices.com'),
('contact_phone', '03328912706'),
('contact_whatsapp', '+92 332 8912706'),
('address', 'Islamabad / Rawalpindi, Pakistan • Serving Clients Worldwide'),
('meta_title', 'VPS | V Provide Services: Web, App, Design, Data & Architecture Solutions Worldwide'),
('meta_description', 'V Provide Services (VPS) is a global IT and creative services partner: web and app development, graphic and logo design, ecommerce management, data science, architecture and interior design.'),
('social_facebook', 'https://facebook.com/vprovideservices'),
('social_linkedin', 'https://linkedin.com/company/vprovideservices'),
('social_instagram', 'https://instagram.com/vprovideservices'),
('maintenance_mode', '0');

-- Seed 10 core services
INSERT IGNORE INTO `services` (`id`, `title`, `slug`, `short_desc`, `full_desc`, `icon`, `category`, `features`, `display_order`, `is_featured`, `is_active`) VALUES
(1, 'Website Development', 'website-development', 'Fast, secure websites, corporate portals and custom CMS platforms engineered for conversion.', '<p>At VPS, our website development team designs and builds fast, modern, and accessible websites that turn visitors into long-term clients. Whether you need a corporate business presentation, a dynamic product catalog, or a specialized web portal, we combine robust architectures with seamless user experiences.</p><h3>What We Provide:</h3><ul><li>Custom responsive web designs tailored to your brand</li><li>High performance and fast page load times</li><li>Search engine optimized code architecture</li><li>Secure admin dashboards for effortless content updates</li><li>Ongoing maintenance and technical support</li></ul>', 'code', 'Development', 'Custom Web Design, Responsive Layouts, SEO Optimization, CMS Integration, Speed Optimization, Cross-Browser Compatibility', 1, 1, 1),

(2, 'Web Application Development', 'web-application-development', 'Scalable, feature-rich web applications built with robust backend logic and intuitive UI.', '<p>We engineer end-to-end custom web applications tailored to solve complex operational challenges. From client portals and management dashboards to multi-tenant SaaS platforms, our team creates reliable, scalable systems that power modern businesses.</p><h3>Capabilities:</h3><ul><li>Custom business process automation</li><li>Database architecture and API development</li><li>Role-based access control and security protocols</li><li>Real-time data synchronization and reporting</li></ul>', 'terminal', 'Development', 'Custom SaaS Systems, Role-Based Access, API Integrations, Real-time Reporting, Scalable Databases, Cloud Deployment', 2, 1, 1),

(3, 'Mobile Application Development', 'mobile-application-development', 'Native-quality Android and iOS apps with clean UX, reliable performance, and secure backends.', '<p>Reach your users on any device with performant, beautifully crafted mobile applications. We build native and cross-platform apps using Flutter and modern frameworks, ensuring fluid animations, offline functionality, and seamless API integrations.</p><h3>Mobile Capabilities:</h3><ul><li>iOS & Android cross-platform mobile apps</li><li>User-centric UI/UX prototyping</li><li>Offline caching & push notification engines</li><li>App Store and Google Play deployment management</li></ul>', 'phone_iphone', 'Development', 'Flutter Apps, iOS & Android, Push Notifications, Offline Support, App Store Submission, Secure API Integration', 3, 1, 1),

(4, 'Graphic Design', 'graphic-design', 'Campaign creatives, brochures, banners, and social assets with a distinct visual voice.', '<p>Elevate your brand presence across all marketing touchpoints. Our graphic designers craft high-impact marketing visuals, print materials, social media kits, and corporate decks that captivate audiences and communicate your core value proposition clearly.</p>', 'palette', 'Creative', 'Social Media Graphics, Marketing Collateral, Pitch Decks & Presentations, Print Ready Brochures, Ad Campaigns, Brand Illustrations', 4, 1, 1),

(5, 'Logo & Brand Identity Design', 'logo-and-brand-identity-design', 'Distinctive logos and complete corporate brand identity systems ready to deploy anywhere.', '<p>Your brand is more than just a logo; it is the visual signature of your organization. We develop cohesive brand identity systems including primary and secondary logo marks, typography scales, color harmony palettes, iconography, and comprehensive style guides.</p>', 'draw', 'Creative', 'Primary & Secondary Logos, Brand Style Guides, Color Palettes, Typography Systems, Stationery Design, Vector Master Files', 5, 1, 1),

(6, 'E-commerce Development', 'ecommerce-development', 'High-converting online stores built on WooCommerce, Shopify, or custom shopping carts.', '<p>We build secure, high-conversion online stores that simplify purchasing for customers and streamline order handling for your operations team. From single-product funnels to extensive multi-category stores, we guarantee speed, security, and conversion-focused checkout flows.</p>', 'shopping_cart', 'E-commerce', 'Custom Store Themes, Secure Payment Gateways, Inventory Management, Automated Invoicing, Cart Abandonment Recovery, Conversion Optimization', 6, 1, 1),

(7, 'E-commerce Account Management', 'ecommerce-account-management', 'End-to-end store and marketplace management that keeps sales and ratings growing.', '<p>Scale your online selling without operational headaches. Our account management specialists handle store setup, product listing optimization, inventory sync, promotional campaigns, customer query responses, and marketplace compliance across Amazon, Shopify, Daraz, and eBay.</p>', 'shopping_bag', 'E-commerce', 'Catalog & Listing Management, Marketplace SEO, Inventory Tracking, Review Management, Promotions & Discounts, Sales Analytics', 7, 1, 1),

(8, 'Data Science & Analytics', 'data-science-and-analytics', 'Interactive dashboards, predictive models, and deep analytics turning data into decisions.', '<p>Unlock actionable business intelligence from your raw operational data. We build dynamic executive dashboards in Power BI and Python, forecast customer demand, automate periodic reporting, and identify revenue opportunities.</p>', 'hub', 'Analytics', 'Power BI Dashboards, Python Data Pipelines, Predictive Forecasting, SQL Data Warehousing, KPI Tracking, Automated Reporting', 8, 1, 1),

(9, 'Architecture Design', 'architecture-design', 'Concept-to-construction drawings, 3D exteriors, and municipal approval-ready plan sets.', '<p>Transform ideas into structural masterpieces. Our architectural design team delivers comprehensive floor plans, elevations, sections, structural drawings, and photorealistic 3D exterior renderings adhering strictly to modern engineering standards.</p>', 'architecture', 'Engineering', '2D Floor Plans, 3D Exterior Renderings, BIM Modeling, Elevations & Sections, Permit Ready Drawings, Landscape Integration', 9, 1, 1),

(10, 'Interior Design', 'interior-design', 'Space planning, material selections, and photorealistic 3D interior visualizations.', '<p>We bring interior spaces to life with ergonomic space planning, curated color schemes, custom lighting plans, furniture layouts, and 8K ultra-realistic interior 3D renders that allow you to walk through your space before construction begins.</p>', 'chair', 'Engineering', 'Space Planning, Photorealistic 3D Renders, Material & Finish Schedules, Lighting Design, Custom Furniture Layouts, Residential & Commercial', 10, 1, 1);

-- Seed team members
INSERT IGNORE INTO `team` (`id`, `name`, `slug`, `title`, `initials`, `short_intro`, `bio`, `specialties`, `skills`, `display_order`, `is_active`) VALUES
(1, 'Farhan Malik', 'farhan-malik', 'Chief Technology Officer', 'FM', 'Architecting scalable cloud applications, distributed systems, and technical strategy.', 'Farhan leads the technology team at VPS, overseeing web, mobile, and backend development architectures. With over 9 years of experience delivering international enterprise software, he ensures every project meets elite standards of performance, security, and maintainability.', 'System Architecture, Full-Stack PHP, Cloud Platforms', 'PHP, MySQL, Flutter, Docker, Linux, System Architecture', 1, 1),

(2, 'Ayesha Siddiqui', 'ayesha-siddiqui', 'Head of Product Design', 'AS', 'Crafting user-centric UI/UX and distinctive brand identity systems.', 'Ayesha drives visual design and product user experience across VPS engagements. She brings a deep understanding of human factors, typography, and interactive aesthetics to craft memorable, high-converting digital products.', 'UI/UX Design, Brand Identity, Design Systems', 'Figma, Adobe Creative Suite, Design Systems, Prototyping, Wireframing', 2, 1),

(3, 'Hamza Tariq', 'hamza-tariq', 'Lead Data Scientist', 'HT', 'Specializing in business intelligence, machine learning, and automated pipelines.', 'Hamza transforms complex business datasets into intuitive dashboards and predictive intelligence. His expertise spans Power BI, Python pipelines, and statistical modeling for ecommerce and operations clients.', 'Data Engineering, Machine Learning, Power BI', 'Python, SQL, Power BI, Pandas, Scikit-learn, Tableau', 3, 1),

(4, 'Zainab Raza', 'zainab-raza', 'Senior Architecture Lead', 'ZR', 'Delivering award-winning residential and commercial architectural renderings.', 'Zainab brings over 8 years of architectural and interior design mastery. She leads 2D/3D BIM projects, municipal drawing sets, and hyper-realistic V-Ray renders for global real estate and construction clients.', 'Architectural 3D, Interior Planning, BIM', 'AutoCAD, Revit, 3ds Max, V-Ray, Lumion, SketchUp', 4, 1);

-- Seed portfolio case studies
INSERT IGNORE INTO `portfolio` (`id`, `title`, `slug`, `category`, `service_id`, `short_desc`, `full_desc`, `client_name`, `industry`, `technologies`, `challenge`, `solution`, `results`, `is_featured`, `is_published`) VALUES
(1, 'Corporate Business Platform', 'corporate-business-platform', 'web', 1, 'Dynamic corporate website with custom project management dashboard.', 'Designed and built a high-speed corporate business portal featuring dynamic service catalogs, project onboarding workflows, and secure administrative controls.', 'Apex Global Corp', 'Enterprise Services', 'PHP, MySQL, Tailwind CSS, JavaScript, jQuery', 'The client had an outdated static site with slow load times and zero dynamic lead management capability.', 'We engineered a bespoke, responsive web platform with instant quote calculators and automated project intake.', 'Page load time dropped below 0.8 seconds; inbound qualified inquiries increased by 140% in the first quarter.', 1, 1),

(2, 'Service Booking & Dispatch App', 'service-booking-dispatch-app', 'app', 3, 'Cross-platform mobile application with live order tracking.', 'Built cross-platform iOS and Android apps allowing customers to book on-demand professional technicians with real-time location dispatching.', 'FixIt Solutions', 'Home & Commercial Services', 'Flutter, Firebase, Node.js, REST API', 'Scheduling conflicts, slow manual phone booking, and lack of customer status visibility.', 'Delivered a clean, intuitive Flutter mobile application with live map tracking, push notifications, and card payment.', 'Over 25,000 bookings completed in the first 6 months with a 4.9-star average app store rating.', 1, 1),

(3, 'Modern FinTech Brand Identity', 'modern-fintech-brand-identity', 'brand', 5, 'Comprehensive brand system, logo marks, and corporate guidelines.', 'Crafted an authoritative, modern brand identity for a digital payments venture including color palettes, typography scales, iconography, and investor pitch collateral.', 'NovaPay Financial', 'FinTech & Banking', 'Figma, Adobe Illustrator, Photoshop', 'Establishing trust and standing out in a crowded market filled with legacy institutions.', 'Created an energetic yet dependable visual system anchored by vibrant cyan and navy tones with precise geometry.', 'Successfully raised Series-A funding and rolled out brand assets across web, cards, and outdoor billboards.', 1, 1),

(4, 'Multi-Channel Store Management', 'multi-channel-store-management', 'shop', 7, 'Catalog, listings, inventory synchronization, and order handling at scale.', 'Managed end-to-end ecommerce operations for a multi-category lifestyle brand across Shopify, Amazon, and regional marketplaces.', 'Aura Home Living', 'Retail & Home Goods', 'Shopify, Amazon Seller Central, WooCommerce, Klaviyo', 'Inventory stockouts, inconsistent product images, and high return rates due to inaccurate listings.', 'Standardized SKU taxonomy, optimized listing keywords and imagery, and automated warehouse inventory sync.', 'Sales grew 220% year-over-year while customer return rates dropped by 38%.', 1, 1),

(5, 'Executive Sales Insights Dashboard', 'executive-sales-insights-dashboard', 'data', 8, 'Automated data warehouse and interactive Power BI decision dashboard.', 'Connected disparate ERP, CRM, and ecommerce databases into a unified data model providing real-time executive decision support.', 'MetroLogistics Ltd', 'Logistics & Distribution', 'Power BI, Python, MySQL, SQL Server', 'Leadership relied on slow weekly manual spreadsheets that arrived too late for agile decisions.', 'Built automated nightly ETL scripts feeding into an intuitive Power BI dashboard with forecasting models.', 'Reduced executive reporting turnaround from 5 days to real-time, saving 20+ hours of management overhead weekly.', 1, 1),

(6, 'Residential Luxury 3D Visualization', 'residential-luxury-3d-visualization', 'arch', 9, 'Floor plans, elevations, and photorealistic 3D interior and exterior renders.', 'Produced approval-ready architectural drawings and 8K photorealistic architectural renders for a multi-unit luxury villa development.', 'Horizon Crest Developments', 'Real Estate & Construction', 'AutoCAD, 3ds Max, V-Ray, Revit', 'The developer needed pre-construction marketing collateral to secure off-plan buyers before breaking ground.', 'Delivered hyper-detailed exterior day/night renders and 3D interior walkthrough visuals showcasing materials.', '80% of villa units were reserved off-plan prior to construction commencement.', 1, 1);

-- Seed blog categories
INSERT IGNORE INTO `blog_categories` (`id`, `name`, `slug`) VALUES
(1, 'Web & App Development', 'web-app-development'),
(2, 'Design & Branding', 'design-and-branding'),
(3, 'Data & Business Intelligence', 'data-and-bi'),
(4, 'Architecture & 3D', 'architecture-and-3d');

-- Seed blog posts
INSERT IGNORE INTO `blog_posts` (`id`, `title`, `slug`, `category_id`, `category_name`, `featured_image`, `icon`, `excerpt`, `content`, `author`, `reading_time`, `is_published`) VALUES
(1, 'Why Your Business Needs a Dynamic Website in 2026', 'why-your-business-needs-a-dynamic-website-in-2026', 1, 'Web & App Development', NULL, 'code', 'How an admin-managed website saves time, drives organic traffic, and converts visitors into paying customers.', '<p>In today\'s hyper-competitive digital landscape, having a static brochure website that requires developer intervention for every simple text update is a severe disadvantage. Businesses need agility, speed, and real-time responsiveness to capitalize on market opportunities.</p><p>A dynamic, database-driven web platform allows your team to publish fresh case studies, announce service updates, capture customer requirements, and track project inquiries effortlessly through a centralized dashboard.</p><h3>Key Advantages:</h3><ul><li><strong>Zero Dependency on Code for Daily Updates:</strong> Empower your marketing team to publish content instantly.</li><li><strong>Seamless Lead Capture:</strong> Directly funnel client briefs into structured database records with automated notifications.</li><li><strong>Superior Search Engine Indexing:</strong> Search engines reward sites that publish consistent, structured, and relevant content.</li></ul>', 'Farhan Malik', '4 min read', 1),

(2, 'The 5 Most Common Branding Mistakes That Cost Companies Leads', '5-common-branding-mistakes-that-cost-companies-leads', 2, 'Design & Branding', NULL, 'draw', 'Discover the frequent identity pitfalls that dilute credibility and learn how to position your company for international trust.', '<p>Your visual identity is often the first and only impression an international prospect evaluates before deciding whether to engage or bounce. Yet many growing businesses fall into predictable branding traps.</p><p>From inconsistent typography scales to confusing color choices and generic stock logos, small flaws signal inexperience. A professional design system establishes immediate authority and builds trust before you even exchange your first email.</p><h3>What to Avoid:</h3><ol><li>Using unvetted clip-art or AI generated logos without human art direction</li><li>Failing to define primary, secondary, and accent color contrasts</li><li>Inconsistent imagery styles across website, social channels, and proposals</li></ol>', 'Ayesha Siddiqui', '5 min read', 1),

(3, 'Getting Started with Automated Dashboards and BI for Small Businesses', 'getting-started-with-automated-dashboards-and-bi', 3, 'Data & Business Intelligence', NULL, 'hub', 'Practical first steps to liberate your team from manual spreadsheets and unlock actionable revenue insights.', '<p>Small and mid-sized enterprises often collect vast amounts of data across point-of-sale systems, accounting software, and digital ad channels—yet make vital business decisions based on gut feel because the numbers are fragmented across disparate files.</p><p>By unifying your core metrics into an automated business intelligence dashboard, leadership gains immediate visibility into customer acquisition costs, gross margin by product category, and customer lifetime value.</p>', 'Hamza Tariq', '6 min read', 1);
