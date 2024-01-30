DELIMITER //
CREATE EVENT update_room_status
ON SCHEDULE EVERY 1 MINUTE
DO
BEGIN
    IF (hasstartdatetime = 1 AND NOW() < stardatetime) THEN
        UPDATE room SET isopen = 0;
    ELSEIF (hasstartdatetime = 1 AND NOW() > stardatetime) THEN
        UPDATE room SET isopen = 1;
    END IF;

    IF (hasenddatetime = 1 AND NOW() < enddatetime) THEN
        UPDATE room SET isopen = 1;
    ELSEIF (hasenddatetime = 1 AND NOW() > enddatetime) THEN
        UPDATE room SET isopen = 0;
    END IF;   
END //
DELIMITER ;