CREATE TABLE users (
    ID int(11) NOT NULL AUTO_INCREMENT,
    USER_NAME varchar(100) NOT NULL,
    EMAIL varchar(100) NOT NULL,
    PASSWORD varchar(255) NOT NULL,
    PROFILE_IMG varchar(255) DEFAULT 'default.png',
    VERIFICATION_CODE int(11) DEFAULT NULL,
    VERIFICATION_STATUS enum('Success', 'Failed') DEFAULT 'Failed',
    PRIMARY KEY (ID)
);

