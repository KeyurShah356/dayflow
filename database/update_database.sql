
USE dayflow_hrms;

ALTER TABLE users 
ADD COLUMN otp_code VARCHAR(6) NULL AFTER verification_token;


ALTER TABLE users 
ADD COLUMN otp_expires_at TIMESTAMP NULL AFTER otp_code;


CREATE INDEX idx_otp_code ON users(otp_code);


SELECT COLUMN_NAME, DATA_TYPE, IS_NULLABLE 
FROM INFORMATION_SCHEMA.COLUMNS 
WHERE TABLE_SCHEMA = 'dayflow_hrms' 
AND TABLE_NAME = 'users' 
AND COLUMN_NAME IN ('otp_code', 'otp_expires_at');


