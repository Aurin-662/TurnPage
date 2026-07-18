-- ============================================================================
-- CSE 3109/3110: Database Systems Lab Project - Phase 1
-- File: 9_triggers_advanced.sql
-- Purpose: Database Triggers (Lab 09)
-- ============================================================================

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 1: UPDATE_RATING_AFTER_REVIEW
-- Automatically updates STAR_RATING and REVIEW_COUNT when a review is added
-- (Lab 09: AFTER INSERT ROW-level TRIGGER using :NEW)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER UPDATE_RATING_AFTER_REVIEW
AFTER INSERT ON REVIEW
FOR EACH ROW
BEGIN
    -- Update the book's star rating and review count
    UPDATE BOOK
    SET STAR_RATING = (
            SELECT ROUND(AVG(RATING), 1)
            FROM REVIEW
            WHERE BOOK_ID = :NEW.BOOK_ID
        ),
        REVIEW_COUNT = (
            SELECT COUNT(REVIEW_ID)
            FROM REVIEW
            WHERE BOOK_ID = :NEW.BOOK_ID
        )
    WHERE BOOK_ID = :NEW.BOOK_ID;
    
END UPDATE_RATING_AFTER_REVIEW;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 2: VALIDATE_BOOK_PRICE_INS
-- Validates that book price is within acceptable range
-- (Lab 09: BEFORE INSERT ROW-level TRIGGER, RAISE_APPLICATION_ERROR)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_BOOK_PRICE_INS
BEFORE INSERT ON BOOK
FOR EACH ROW
BEGIN
    IF :NEW.PRICE < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Book price cannot be negative');
    END IF;
    
    IF :NEW.PRICE = 0 THEN
        RAISE_APPLICATION_ERROR(-20002, 'Book price must be greater than 0');
    END IF;
    
    IF :NEW.PRICE > 100000 THEN
        RAISE_APPLICATION_ERROR(-20003, 'Book price cannot exceed 100,000 Tk');
    END IF;
END VALIDATE_BOOK_PRICE_INS;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 3: VALIDATE_BOOK_PRICE_UPD
-- Validates book price during update
-- (Lab 09: BEFORE UPDATE ROW-level TRIGGER)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_BOOK_PRICE_UPD
BEFORE UPDATE ON BOOK
FOR EACH ROW
BEGIN
    IF :NEW.PRICE < 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Book price cannot be negative');
    END IF;
    
    IF :NEW.PRICE = 0 THEN
        RAISE_APPLICATION_ERROR(-20002, 'Book price must be greater than 0');
    END IF;
    
    IF :NEW.PRICE > 100000 THEN
        RAISE_APPLICATION_ERROR(-20003, 'Book price cannot exceed 100,000 Tk');
    END IF;
END VALIDATE_BOOK_PRICE_UPD;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 4: STOCK_ALERT_BEFORE_UPDATE
-- Sends alert when stock falls below threshold
-- (Lab 09: Using :OLD and :NEW to detect changes)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER STOCK_ALERT_BEFORE_UPDATE
BEFORE UPDATE ON BOOK
FOR EACH ROW
BEGIN
    -- Alert when stock drops below 5
    IF :OLD.STOCK_QUANTITY > 5 AND :NEW.STOCK_QUANTITY <= 5 THEN
        NULL;
    END IF;
    
    -- Alert when stock reaches 0
    IF :OLD.STOCK_QUANTITY > 0 AND :NEW.STOCK_QUANTITY = 0 THEN
        NULL;
    END IF;
    
    -- Alert when stock is replenished
    IF :OLD.STOCK_QUANTITY = 0 AND :NEW.STOCK_QUANTITY > 0 THEN
        NULL;
    END IF;
END STOCK_ALERT_BEFORE_UPDATE;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 5: VALIDATE_STOCK_QUANTITY
-- Validates that stock quantity is not negative
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_STOCK_QUANTITY
BEFORE INSERT OR UPDATE ON BOOK
FOR EACH ROW
BEGIN
    IF :NEW.STOCK_QUANTITY < 0 THEN
        RAISE_APPLICATION_ERROR(-20010, 'Stock quantity cannot be negative');
    END IF;
END VALIDATE_STOCK_QUANTITY;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 6: VALIDATE_STAR_RATING
-- Validates that star rating is between 0 and 5
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_STAR_RATING
BEFORE INSERT OR UPDATE ON BOOK
FOR EACH ROW
BEGIN
    IF :NEW.STAR_RATING < 0 OR :NEW.STAR_RATING > 5 THEN
        RAISE_APPLICATION_ERROR(-20011, 'Star rating must be between 0 and 5');
    END IF;
END VALIDATE_STAR_RATING;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 7: VALIDATE_REVIEW_RATING
-- Validates that review rating is between 1 and 5
-- (Lab 09: Validating inserted data)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_REVIEW_RATING
BEFORE INSERT ON REVIEW
FOR EACH ROW
BEGIN
    IF :NEW.RATING < 1 OR :NEW.RATING > 5 THEN
        RAISE_APPLICATION_ERROR(-20012, 'Review rating must be between 1 and 5');
    END IF;
    
    -- Ensure review date is not in the future
    IF :NEW.REVIEW_DATE > SYSDATE THEN
        RAISE_APPLICATION_ERROR(-20013, 'Review date cannot be in the future');
    END IF;
END VALIDATE_REVIEW_RATING;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 8: VALIDATE_VOUCHER_DISCOUNT
-- Validates voucher discount percentage
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_VOUCHER_DISCOUNT
BEFORE INSERT OR UPDATE ON VOUCHER
FOR EACH ROW
BEGIN
    IF :NEW.DISCOUNT_PERCENT < 0 OR :NEW.DISCOUNT_PERCENT > 100 THEN
        RAISE_APPLICATION_ERROR(-20020, 'Discount percentage must be between 0 and 100');
    END IF;
END VALIDATE_VOUCHER_DISCOUNT;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 9: PREVENT_BOOK_DEL_WITH_REVIEWS
-- Prevents deletion of a book that has reviews
-- (Lab 09: BEFORE DELETE with custom logic)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER PREVENT_BOOK_DEL_WITH_REVIEWS
BEFORE DELETE ON BOOK
FOR EACH ROW
DECLARE
    v_review_count NUMBER;
BEGIN
    SELECT COUNT(REVIEW_ID)
    INTO v_review_count
    FROM REVIEW
    WHERE BOOK_ID = :OLD.BOOK_ID;
    
    IF v_review_count > 0 THEN
        RAISE_APPLICATION_ERROR(-20030, 
            'Cannot delete book with ' || v_review_count || ' reviews. ' ||
            'Delete reviews first or set book as inactive.');
    END IF;
END PREVENT_BOOK_DEL_WITH_REVIEWS;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 10: AUTO_UPDATE_CART_TOTAL
-- Updates cart total when cart items are modified
-- (Lab 09: Maintaining referential integrity via triggers)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER AUTO_UPDATE_CART_TOTAL
AFTER INSERT OR UPDATE OR DELETE ON CART_ITEM
FOR EACH ROW
BEGIN
    -- This trigger could be extended to update a TOTAL column in CART table
    -- For now, it serves as demonstration of multi-event triggers
    NULL;
END AUTO_UPDATE_CART_TOTAL;
/


