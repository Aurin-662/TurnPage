-- ============================================================================
-- CSE 3109/3110: Database Systems Lab Project - Phase 1
-- File: 8_functions_and_procedures.sql
-- Purpose: Lab 08-aligned functions and procedures
-- Notes: This file keeps only the core Lab 08 concepts:
--         scalar-returning functions and simple procedures that can be
--         demonstrated directly in SQL/PLSQL.
-- ============================================================================

-- ─────────────────────────────────────────────────────────────────────────
-- FUNCTION 1: GET_BOOK_RATING
-- Returns the average rating of a book based on reviews
-- (Lab 08: FUNCTION with RETURN)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE FUNCTION GET_BOOK_RATING(p_book_id IN NUMBER)
RETURN NUMBER
IS
    v_avg_rating NUMBER(3,2);
BEGIN
    SELECT ROUND(AVG(RATING), 2)
    INTO v_avg_rating
    FROM REVIEW
    WHERE BOOK_ID = p_book_id;
    
    -- Return 0 if no reviews found
    IF v_avg_rating IS NULL THEN
        RETURN 0;
    END IF;
    
    RETURN v_avg_rating;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END GET_BOOK_RATING;
/

-- ─────────────────────────────────────────────────────────────────────────
-- FUNCTION 2: GET_REVIEW_COUNT
-- Returns the count of reviews for a book
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE FUNCTION GET_REVIEW_COUNT(p_book_id IN NUMBER)
RETURN NUMBER
IS
    v_count NUMBER;
BEGIN
    SELECT COUNT(REVIEW_ID)
    INTO v_count
    FROM REVIEW
    WHERE BOOK_ID = p_book_id;
    
    RETURN NVL(v_count, 0);
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END GET_REVIEW_COUNT;
/

-- ─────────────────────────────────────────────────────────────────────────
-- FUNCTION 3: GET_SALES_COUNT
-- Returns total quantity sold for a book
-- (Lab 08: Using aggregate function in procedure)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE FUNCTION GET_SALES_COUNT(p_book_id IN NUMBER)
RETURN NUMBER
IS
    v_sales NUMBER;
BEGIN
    SELECT NVL(SUM(QUANTITY), 0)
    INTO v_sales
    FROM ORDER_ITEM
    WHERE BOOK_ID = p_book_id;
    
    RETURN v_sales;
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END GET_SALES_COUNT;
/

-- ─────────────────────────────────────────────────────────────────────────
-- FUNCTION 4: GET_DISCOUNT_PRICE
-- Calculates price after discount (used for vouchers)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE FUNCTION GET_DISCOUNT_PRICE(
    p_original_price IN NUMBER,
    p_discount_percent IN NUMBER
)
RETURN NUMBER
IS
    v_discount_amount NUMBER;
    v_final_price NUMBER;
BEGIN
    v_discount_amount := (p_original_price * p_discount_percent) / 100;
    v_final_price := p_original_price - v_discount_amount;
    RETURN ROUND(v_final_price, 2);
END GET_DISCOUNT_PRICE;
/

-- ─────────────────────────────────────────────────────────────────────────
-- FUNCTION 5: GET_CATEGORY_BOOK_COUNT
-- Returns number of books in a category
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE FUNCTION GET_CATEGORY_BOOK_COUNT(p_category_id IN NUMBER)
RETURN NUMBER
IS
    v_count NUMBER;
BEGIN
    SELECT COUNT(DISTINCT bc.BOOK_ID)
    INTO v_count
    FROM BOOK_CATEGORY bc
    WHERE bc.CATEGORY_ID = p_category_id;
    
    RETURN NVL(v_count, 0);
EXCEPTION
    WHEN OTHERS THEN
        RETURN 0;
END GET_CATEGORY_BOOK_COUNT;
/

-- ─────────────────────────────────────────────────────────────────────────
-- PROCEDURE 1: UPDATE_BOOK_STATISTICS
-- Updates STAR_RATING and REVIEW_COUNT in BOOK table
-- (Lab 08: PROCEDURE with multiple operations)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE PROCEDURE UPDATE_BOOK_STATISTICS(
    p_book_id IN NUMBER
)
IS
    v_avg_rating NUMBER(3,2);
    v_review_count NUMBER;
BEGIN
    -- Get average rating
    SELECT ROUND(AVG(r.RATING), 1), COUNT(r.REVIEW_ID)
    INTO v_avg_rating, v_review_count
    FROM REVIEW r
    WHERE r.BOOK_ID = p_book_id;
    
    -- Update the book record
    UPDATE BOOK
    SET STAR_RATING = NVL(v_avg_rating, 0),
        REVIEW_COUNT = NVL(v_review_count, 0)
    WHERE BOOK_ID = p_book_id;
    
    COMMIT;
EXCEPTION
    WHEN NO_DATA_FOUND THEN
        NULL;
    WHEN OTHERS THEN
        NULL;
END UPDATE_BOOK_STATISTICS;
/

-- ─────────────────────────────────────────────────────────────────────────
-- PROCEDURE 2: ADD_CATEGORY
-- Adds a new book category
-- (Lab 08: PROCEDURE with INSERT)
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE PROCEDURE ADD_CATEGORY(
    p_category_name IN VARCHAR2,
    p_description IN VARCHAR2,
    p_icon IN VARCHAR2
)
IS
    v_next_id NUMBER;
BEGIN
    -- Get next ID
    SELECT NVL(MAX(CATEGORY_ID), 0) + 1
    INTO v_next_id
    FROM CATEGORY;
    
    -- Insert new category
    INSERT INTO CATEGORY (CATEGORY_ID, CATEGORY_NAME, DESCRIPTION, ICON, DISPLAY_ORDER)
    VALUES (v_next_id, p_category_name, p_description, p_icon, v_next_id);
    
    COMMIT;
EXCEPTION
    WHEN DUP_VAL_ON_INDEX THEN
        NULL;
    WHEN OTHERS THEN
        NULL;
END ADD_CATEGORY;
/

-- ─────────────────────────────────────────────────────────────────────────
-- PROCEDURE 3: ASSIGN_BOOK_TO_CATEGORY
-- Associates a book with a category
-- ─────────────────────────────────────────────────────────────────────────

CREATE OR REPLACE PROCEDURE ASSIGN_BOOK_TO_CATEGORY(
    p_book_id IN NUMBER,
    p_category_id IN NUMBER
)
IS
    v_next_id NUMBER;
BEGIN
    -- Get next ID
    SELECT NVL(MAX(BOOK_CATEGORY_ID), 0) + 1
    INTO v_next_id
    FROM BOOK_CATEGORY;
    
    -- Insert association
    INSERT INTO BOOK_CATEGORY (BOOK_CATEGORY_ID, BOOK_ID, CATEGORY_ID)
    VALUES (v_next_id, p_book_id, p_category_id);
    
    COMMIT;
EXCEPTION
    WHEN DUP_VAL_ON_INDEX THEN
        NULL;
    WHEN OTHERS THEN
        NULL;
END ASSIGN_BOOK_TO_CATEGORY;
/

-- ─────────────────────────────────────────────────────────────────────────
-- LAB 08 DEMONSTRATION NOTES
-- The three earlier procedures are enough for a clean Lab 08 submission:
--   1) UPDATE_BOOK_STATISTICS
--   2) ADD_CATEGORY
--   3) ASSIGN_BOOK_TO_CATEGORY
--
-- The SYS_REFCURSOR-based procedures were removed because they are more
-- advanced than the syllabus expects for this lab and are not used by the
-- application. Keeping them here would make the file look unnecessary and
-- harder to explain during viva.
-- ─────────────────────────────────────────────────────────────────────────

-- Example usage (uncomment when needed for demonstration)
-- SELECT GET_BOOK_RATING(1) FROM DUAL;
-- SELECT GET_REVIEW_COUNT(1) FROM DUAL;
-- SELECT GET_CATEGORY_BOOK_COUNT(1) FROM DUAL;
--
-- BEGIN
--     UPDATE_BOOK_STATISTICS(1);
--     ADD_CATEGORY('Lab Demo', 'Demo category', '🧪');
--     ASSIGN_BOOK_TO_CATEGORY(1, 1);
-- END;
-- /

