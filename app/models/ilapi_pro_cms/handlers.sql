DELIMITER $$
DROP PROCEDURE IF EXISTS available_schedules$$
DROP PROCEDURE IF EXISTS curr_assigned_schedules$$
DROP PROCEDURE IF EXISTS get_messages_by_status$$
DROP PROCEDURE IF EXISTS get_views_peryear$$
DROP PROCEDURE IF EXISTS get_posts_peryear$$
DROP PROCEDURE IF EXISTS get_speakers_peryear$$
DROP PROCEDURE IF EXISTS get_eventpost_peryear$$
DROP PROCEDURE IF EXISTS get_event_speakers$$
DROP PROCEDURE IF EXISTS get_side_links$$
DROP PROCEDURE IF EXISTS get_users$$


CREATE PROCEDURE available_schedules(in in_speakerId VARCHAR(255))
BEGIN
    SELECT 
        ET.*, 
        eventName 
    FROM DsEventTime ET
    INNER JOIN DsEvent E ON E.eventId=ET.eventId
    WHERE CONCAT(ET.endDate,' ',ET.startTime) >= NOW() AND
    ET.timeId NOT IN(
        SELECT timeId FROM DsLiveEvent
        WHERE speakerId=in_speakerId
    );
END$$

CREATE PROCEDURE curr_assigned_schedules(in in_speakerId VARCHAR(255))
BEGIN
    SELECT 
        ET.*, 
        eventName, 
        liveEventId 
    FROM DsLiveEvent LE
    INNER JOIN DsEventTime ET ON ET.timeId=LE.timeId
    INNER JOIN DsEvent E ON E.eventId=ET.eventId
    WHERE CONCAT(ET.endDate,' ',ET.startTime) >= NOW() AND
    LE.speakerId=in_speakerId;
END$$

CREATE PROCEDURE get_messages_by_status(in in_messageType ENUM('read','unread'))
BEGIN
    SELECT * FROM DsContact WHERE status=in_messageType;
END$$

CREATE PROCEDURE get_views_peryear()
BEGIN
    SELECT 
        SUM(pageViews) AS PageViews,
        YEAR(dateAdded)  AS ViewYear
    FROM DsPage
    GROUP BY ViewYear
    ORDER BY ViewYear ASC;
END$$

CREATE PROCEDURE get_posts_peryear()
BEGIN
    SELECT 
        COUNT(*) AS PagePosts,
        YEAR(dateAdded)  AS PostYear
    FROM DsPage
    GROUP BY PostYear
    ORDER BY PostYear ASC;
END$$

CREATE PROCEDURE get_eventpost_peryear()
BEGIN
    SELECT 
        COUNT(*) AS EventPosts,
        YEAR(dateRegistered)  AS EventYear
    FROM DsEvent
    GROUP BY EventYear
    ORDER BY EventYear ASC;
END$$

CREATE PROCEDURE get_speakers_peryear()
BEGIN
    SELECT 
        COUNT(*) AS Speakers,
        YEAR(dateRegistered)  AS RegYear
    FROM DsSpeakers
    GROUP BY RegYear
    ORDER BY RegYear ASC;
END$$

CREATE PROCEDURE get_event_speakers(in in_eventId VARCHAR(255))
BEGIN
    SELECT S.* 
        FROM 
        (SELECT timeId FROM DsEventTime WHERE eventId=in_eventId) ET
    INNER JOIN DsLiveEvent LE ON LE.timeId=ET.timeId
    INNER JOIN DsSpeakers S   ON S.speakerId=LE.speakerId
    GROUP BY S.speakerId;
END$$

CREATE PROCEDURE get_side_links()
BEGIN
    SELECT  SP.pageName,SL.levelName,SL.lType,SL.item
    FROM DsSidePages SP 
    INNER JOIN DsSidePagesLevel SL ON SP.id=SL.spId
    WHERE SP.publish = 'Y';
END$$

CREATE PROCEDURE get_users()
BEGIN
    SELECT M.*,L.username,L.accessLevel 
        FROM DsMember M
    INNER JOIN DsLogin L ON M.memberId=L.memberId
    WHERE M.accountStatus = 'opened' AND L.username!='@ai_admin_lapi';
END$$
DELIMITER ;