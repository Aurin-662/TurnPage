
DROP TRIGGER UPDATE_RATING_AFTER_REVIEW;
DROP TRIGGER VALIDATE_BOOK_PRICE_INS;
DROP TRIGGER VALIDATE_BOOK_PRICE_UPD;
DROP TRIGGER STOCK_ALERT_BEFORE_UPDATE;
DROP TRIGGER PREVENT_BOOK_DEL_WITH_REVIEWS;
DROP TRIGGER AUTO_UPDATE_CART_TOTAL;

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 1: VALIDATE_BOOK_PRICE
-- Validates book price is within acceptable range, on both INSERT and UPDATE
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_BOOK_PRICE
BEFORE INSERT OR UPDATE ON BOOK
FOR EACH ROW
BEGIN
    IF :NEW.PRICE <= 0 THEN
        RAISE_APPLICATION_ERROR(-20001, 'Book price must be greater than 0');
    END IF;

    IF :NEW.PRICE > 100000 THEN
        RAISE_APPLICATION_ERROR(-20002, 'Book price cannot exceed 100,000 Tk');
    END IF;
END VALIDATE_BOOK_PRICE;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 2: VALIDATE_STOCK_QUANTITY
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
-- TRIGGER 3: VALIDATE_STAR_RATING
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
-- TRIGGER 4: VALIDATE_REVIEW_RATING
-- Validates that review rating is between 1 and 5, and the review date is not in the future.
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE TRIGGER VALIDATE_REVIEW_RATING
BEFORE INSERT ON REVIEW
FOR EACH ROW
BEGIN
    IF :NEW.RATING < 1 OR :NEW.RATING > 5 THEN
        RAISE_APPLICATION_ERROR(-20012, 'Review rating must be between 1 and 5');
    END IF;

    IF :NEW.REVIEW_DATE > SYSDATE THEN
        RAISE_APPLICATION_ERROR(-20013, 'Review date cannot be in the future');
    END IF;
END VALIDATE_REVIEW_RATING;
/

-- ─────────────────────────────────────────────────────────────────────────
-- TRIGGER 5: VALIDATE_VOUCHER_DISCOUNT
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