ALTER TABLE members
ADD COLUMN mother_name VARCHAR(255)  NULL,
ADD COLUMN mother_dob DATE  NULL,
ADD COLUMN mother_official_paper_type VARCHAR(255) NULL,
ADD COLUMN mother_id_number VARCHAR(255)  NULL,
ADD COLUMN mother_phone_number VARCHAR(20)  NULL,
ADD COLUMN mother_job_type VARCHAR(255) NULL,
ADD COLUMN mother_income_per_month DECIMAL(10, 2) NULL,
ADD COLUMN mother_house ENUM('yes', 'no')  NULL,
ADD COLUMN mother_education_level VARCHAR(255) NULL,
ADD COLUMN mother_disability_type VARCHAR(255) NULL,
ADD COLUMN mother_head_of_family ENUM('yes', 'no')  NULL;
