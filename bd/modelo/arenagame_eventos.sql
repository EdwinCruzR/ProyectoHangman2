DELIMITER //
CREATE EVENT update_room_status
ON SCHEDULE EVERY 30 SECOND
DO
BEGIN
    UPDATE room
    SET isopen =
        CASE
            WHEN hasenddatetime = 1 AND NOW() >= enddatetime THEN 0
            WHEN hasstardatetime = 1 AND NOW() <= stardatetime THEN 0
            ELSE 1
        END;
END //
DELIMITER ;