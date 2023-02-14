SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE IF NOT EXISTS DsCategory (
    sn INT NOT NULL AUTO_INCREMENT UNIQUE,
    categoryId VARCHAR(255) NOT NULL PRIMARY KEY,
    categoryName VARCHAR(45) NOT NULL UNIQUE,
    visible ENUM('Y','N') DEFAULT 'Y',
    _status SET('0','1')  DEFAULT '1'
);

CREATE TABLE IF NOT EXISTS DsCategoryLevel (
    levelId INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    levelName VARCHAR(45) NOT NULL,
    categoryId VARCHAR(100) NOT NULL,
    UNIQUE(categoryId,levelName),
    FOREIGN KEY(categoryId) REFERENCES DsCategory(categoryId)
    ON UPDATE CASCADE
    ON DELETE NO ACTION
);

INSERT INTO DsCategory (categoryId, categoryName, visible, _status) VALUES
('C10','publication','Y','1'),
('C11','advocacy','Y','1'),
('C12','programs','Y','1'),
('C13','research','Y','1'),
('C14','centers','Y','1'),
('C15','blog','Y','1'),
('C16','events','Y','1'),
('C17','about','Y','1'),
('C18','press','N','1'),
('C19','top stories','N','1');



INSERT INTO DsCategoryLevel (levelName, categoryId) VALUES
('political party studies','C14'),
('economic freedom & fiscal policy','C14'),
('conflict & human rights','C14'),
('agro liberty','C14'),
('blockchain & mis','C14'),
('education policy','C14');

CREATE TABLE IF NOT EXISTS DsMember (
    memberId VARCHAR(255)  NOT NULL PRIMARY KEY,
    firstName VARCHAR(45)  NOT NULL,
    lastName VARCHAR(45)   NOT NULL,
    gender ENUM('M','F')   NOT NULL,
    phoneNumber CHAR(20)   NOT NULL,
    email    VARCHAR(100)  NOT NULL,
    imageName VARCHAR(255) NOT NULL
);

INSERT INTO DsMember (memberId, firstName, lastName, gender, phoneNumber, email, imageName) VALUES
('MQIOSOEI4', 'Kingsley', 'ILAPI','M', '+000000000000','ilapighana@gmail.com', 'user_default_image.png');


CREATE TABLE IF NOT EXISTS DsLogin (
    memberId VARCHAR(255) NOT NULL PRIMARY KEY,
    username VARCHAR(60)  NOT NULL UNIQUE,
    _password VARCHAR(255) NOT NULL,
    accessLevel ENUM('A','M','E','U') NOT NULL,
    securityQuestion TINYTEXT NOT NULL,
    answer TINYTEXT NOT NULL,
    lastSeen DATETIME NOT NULL DEFAULT NOW(),

    FOREIGN KEY(memberId) REFERENCES DsMember(memberId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE 
);

INSERT INTO DsLogin(memberId,username,_password,accessLevel,securityQuestion,answer) VALUES
('MQIOSOEI4','@ilapiking','$2y$10$YFjf5rmjFfyBTQ45Kqv./efLyhyDIS3atwoB/qIerZAHcUFJmGrMy','A','Which year did you complete high school?','2005');


CREATE TABLE IF NOT EXISTS DsPage (
    sn BIGINT NOT NULL AUTO_INCREMENT UNIQUE,
    pageId VARCHAR(255) NOT NULL PRIMARY KEY,
    title varchar(255) NOT NULL,
    content LONGTEXT NOT NULL,
    pageType ENUM(
        'default',
        'featured',
        'sponsored',
        'promoted',
        'breaking',
        'trending',
        'editorspick'
    ) NOT NULL DEFAULT 'default',
    published ENUM('Y','N') NOT NULL DEFAULT 'N',
    dateAdded DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    lastVisited DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    createdBy VARCHAR(255) NOT NULL,
    source VARCHAR(300) NOT NULL,
    pageViews INT NOT NULL DEFAULT '0',

    FOREIGN KEY (createdBy) REFERENCES DsMember(memberId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
 );

CREATE TABLE IF NOT EXISTS DsCategoryPage (
    sn BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    categoryId VARCHAR(255) NOT NULL,
    pageId VARCHAR(255) NOT NULL,

    FOREIGN KEY (categoryId) REFERENCES DsCategory(categoryId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
     
    FOREIGN KEY (pageId) REFERENCES DsPage(pageId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsSubCategoryPage (
    sn BIGINT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    subCategoryId INT NOT NULL,
    pageId VARCHAR(255) NOT NULL,

    FOREIGN KEY (subCategoryId) REFERENCES DsCategoryLevel(levelId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE,
    
    FOREIGN KEY (pageId) REFERENCES DsPage(pageId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsEvent (
    eventId VARCHAR(255) NOT NULL PRIMARY KEY,
    eventName VARCHAR(55) DEFAULT NULL UNIQUE,
    _location VARCHAR(45) DEFAULT NULL,
    _description TEXT NOT NULL,
    published enum('Y','N') DEFAULT 'N',
    eventPoster VARCHAR(255) DEFAULT 'event_default.jpg',
    createdBy VARCHAR(255) NOT NULL,    
    dateRegistered DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    eventType enum('default','internship','ajoet') NOT NULL DEFAULT 'default',

    FOREIGN KEY (createdBy) REFERENCES DsMember(memberId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsEventTime (
    timeId  VARCHAR(255) NOT NULL PRIMARY KEY,
    eventId VARCHAR(255) NOT NULL, 
    startDate DATE NOT NULL,
    endDate   DATE NOT NULL,
    startTime TIME NOT NULL,
    endTime   TIME NOT NULL,

    FOREIGN KEY(eventId) REFERENCES DsEvent(eventId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsSpeakers (
    speakerId VARCHAR(255) NOT NULL PRIMARY KEY,
    title     VARCHAR(50)  NOT NULL,
    firstName VARCHAR(50) NOT NULL,
    lastName VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phoneNumber CHAR(15) NOT NULL,
    imageName TINYTEXT NOT NULL,
    about TEXT NOT NULL,
    _status ENUM('suspended','live') DEFAULT 'live',
    UNIQUE(firstName,lastName,email,phoneNumber)
);

CREATE TABLE IF NOT EXISTS DsLiveEvent (
    liveEventId VARCHAR(255) NOT NULL PRIMARY KEY,
    timeId VARCHAR(255) NOT NULL,
    speakerId VARCHAR(255) NOT NULL,

    FOREIGN KEY (timeId) REFERENCES DsEventTime(timeId)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
    FOREIGN KEY (speakerId) REFERENCES DsSpeakers(speakerId)
    ON DELETE CASCADE
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsPageImages (
    imageId    VARCHAR(255) NOT NULL PRIMARY KEY,
    pageId     VARCHAR(255) NOT NULL,
    imageName  VARCHAR(255) NOT NULL,
    imageSize  VARCHAR(255) DEFAULT NULL,

    FOREIGN KEY (pageId) REFERENCES DsPage(pageId)
    ON DELETE NO ACTION
    ON UPDATE CASCADE
);

CREATE TABLE IF NOT EXISTS DsContact (
    contactId VARCHAR(255) NOT NULL PRIMARY KEY,
    fullName VARCHAR(150) NOT NULL,
    email VARCHAR(100) NOT NULL,
    subject VARCHAR(100) DEFAULT NULL,
    message TEXT NOT NULL,
    dateReceived DATETIME NOT NULL DEFAULT NOW(),
    status  ENUM('read','unread') NOT NULL DEFAULT 'unread'
);

CREATE TABLE IF NOT EXISTS DsPartners (
    partId      INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    partName    VARCHAR(150) NOT NULL,
    partLogo    VARCHAR(255) NOT NULL,
    partWebName VARCHAR(255) DEFAULT NULL
);

CREATE TABLE IF NOT EXISTS DsSidePages (
    id        INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    pageName  VARCHAR(255) NOT NULL,
    publish   ENUM('Y','N') NOT NULL
);

INSERT INTO DsSidePages (pageName,publish) VALUES
('Policy Briefs','Y'),
('Reports','Y'),
('ILAPI Library','Y'),
('Training','Y');

CREATE TABLE IF NOT EXISTS DsSidePagesLevel (
    levelId   INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    spId      INT NOT NULL,
    levelName VARCHAR(255) NOT NULL,
    lType     ENUM('linked','filed') NOT NULL,
    item      VARCHAR(255) NOT NULL,
    
    FOREIGN KEY (spId) REFERENCES DsSidePages(id)
    ON UPDATE CASCADE
    ON DELETE CASCADE
);

-- INSERT INTO DsSidePagesLevel (spId,levelName,lType,item) VALUES
-- ('1','Teacher Licensing In Ghana','filed','teacher_licensing_in_ghana_ilapi.pdf'),
-- ('1','Gift Tax Presentation','filed','ilapi_gift_tax.pdf'),
-- ('1','Ministerial Reshuffling','filed','ministerial_reshuffling.pdf'),
-- ('1','The Economic Approach to Managing Waste','filed','the_economic_approach_to_managing_waste.pdf'),
-- ('2','AJEOT Training Report 2018','filed','ajeot_training_report_2018.pdf'),
-- ('2','2018 Annual Report','filed','2018_Annual_Report.pdf'),
-- ('3','Applied Economic for Africa','filed','Applied_Economic_for_Africa.pdf'),
-- ('3','Self Control or State-control, You Decide-Tom Palmer','filed','self-control_or_state_control_you_decide_Tom_Palmer.pdf'),
-- ('3','Road to serfdom','filed','Road_to_serfdom.pdf'),
-- ('3','Why Africa is Poor -George Ayittey','filed','Why_Africa_is_Poor-George Ayittey.pdf'),
-- ('3','The Law - F.Bastiat','filed','The_Law-F.Bastiat.pdf'),
-- ('3','Ghana Agric Invetment Guide 2018-10-19','filed','Ghana_Agric_Invetment_Guide_2018-10-19.pdf'),
-- ('4','Entrepreurship Training','linked','https://sites.google.com/site/enacttest/home?fbclid=IwAR3BPW6inBlMLFd6eDwkfPa2CrWLWJcZdCLCf5nE9FspPG5fwF9OLbo8cyU');


CREATE TABLE IF NOT EXISTS DSMembershipForm (
    id       TINYINT      NOT NULL PRIMARY KEY,
    formName VARCHAR(255) NOT NULL,
    CHECK(id=1)
);

-- INSERT INTO DSMembershipForm(id,formName) VALUES
-- ('1','ilapi_membership_form.docx');

ALTER TABLE DsEvent
MODIFY COLUMN eventType ENUM('default','ajoet','internship') NOT NULL DEFAULT 'default';

ALTER TABLE DsMember 
ADD accountStatus ENUM('opened','quarantined') NOT NULL DEFAULT 'opened';

ALTER TABLE DsSpeakers
ADD title VARCHAR(15) NOT NULL;

ALTER TABLE DsSpeakers
ADD dateRegistered DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP;


