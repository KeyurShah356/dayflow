

USE dayflow_hrms;


ALTER TABLE users 
ADD COLUMN email_verified TINYINT(1) DEFAULT 0 AFTER email,
ADD COLUMN verification_token VARCHAR(100) NULL AFTER email_verified;


CREATE INDEX idx_verification_token ON users(verification_token);


UPDATE users SET email_verified = 1 WHERE email = 'admin@dayflow.com';

