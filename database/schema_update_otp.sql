
USE dayflow_hrms;


ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token,
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;


CREATE INDEX idx_otp_code ON users(otp_code);

