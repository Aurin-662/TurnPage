

-- ───────────── AUTHOR (5 rows) ─────────────
INSERT INTO AUTHOR VALUES (1, 'Humayun Ahmed', 'Legendary Bangladeshi novelist and dramatist', DATE '1948-11-13', 'Bangladesh', NULL);
INSERT INTO AUTHOR VALUES (2, 'Humayun Azad', 'Poet, novelist, and linguist', DATE '1947-04-28', 'Bangladesh', NULL);
INSERT INTO AUTHOR VALUES (3, 'J.K. Rowling', 'British author, best known for Harry Potter series', DATE '1965-07-31', 'United Kingdom', NULL);
INSERT INTO AUTHOR VALUES (4, 'Paulo Coelho', 'Brazilian novelist, author of The Alchemist', DATE '1947-08-24', 'Brazil', NULL);
INSERT INTO AUTHOR VALUES (5, 'Muhammed Zafar Iqbal', 'Bangladeshi science fiction writer and academic', DATE '1952-12-23', 'Bangladesh', NULL);

-- ───────────── PUBLISHER (5 rows) ─────────────
INSERT INTO PUBLISHER VALUES (1, 'Anyaprokash', 'Banglabazar, Dhaka', '02-7117234', 'contact@anyaprokash.com', NULL, DATE '1995-01-01');
INSERT INTO PUBLISHER VALUES (2, 'Sheba Prokashani', 'Sahitya Bhaban, Dhaka', '02-7113456', 'info@shebaprokash.com', NULL, DATE '1972-03-15');
INSERT INTO PUBLISHER VALUES (3, 'Bloomsbury', '50 Bedford Square, London', '44-2074475000', 'info@bloomsbury.com', NULL, DATE '1986-01-01');
INSERT INTO PUBLISHER VALUES (4, 'HarperCollins', 'New York, USA', '1-2125072000', 'contact@harpercollins.com', NULL, DATE '1989-01-01');
INSERT INTO PUBLISHER VALUES (5, 'Kakali Prokashani', 'Banglabazar, Dhaka', '02-7119988', 'kakali@prokashani.com', NULL, DATE '1980-06-10');

-- ───────────── BOOK (10 rows) ─────────────
INSERT INTO BOOK VALUES (1, 1, 1, 'Himu', '978-984-401-001-1', 250.00, 40, 180, 'Bangla', NULL, 4.5, 0);
INSERT INTO BOOK VALUES (2, 1, 1, 'Misir Ali Omnibus', '978-984-401-002-8', 450.00, 25, 320, 'Bangla', NULL, 4.7, 0);
INSERT INTO BOOK VALUES (3, 2, 2, 'Pak Sar Jamin Sad Bad', '978-984-401-003-5', 300.00, 30, 210, 'Bangla', NULL, 4.3, 0);
INSERT INTO BOOK VALUES (4, 3, 3, 'Harry Potter and the Philosopher''s Stone', '978-0-7475-3269-9', 850.00, 20, 223, 'English', NULL, 4.9, 0);
INSERT INTO BOOK VALUES (5, 3, 3, 'Harry Potter and the Chamber of Secrets', '978-0-7475-3849-3', 850.00, 18, 251, 'English', NULL, 4.8, 0);
INSERT INTO BOOK VALUES (6, 4, 4, 'The Alchemist', '978-0-06-231500-7', 550.00, 35, 197, 'English', NULL, 4.6, 0);
INSERT INTO BOOK VALUES (7, 5, 5, 'Copotronic Sukh Dukho', '978-984-401-004-2', 200.00, 50, 150, 'Bangla', NULL, 4.2, 0);
INSERT INTO BOOK VALUES (8, 5, 5, 'Amar Bondhu Rashed', '978-984-401-005-9', 180.00, 45, 130, 'Bangla', NULL, 4.4, 0);
INSERT INTO BOOK VALUES (9, 2, 2, 'Nirbachita Kobita', '978-984-401-006-6', 220.00, 28, 160, 'Bangla', NULL, 4.1, 0);
INSERT INTO BOOK VALUES (10, 4, 4, 'Brida', '978-0-06-208980-2', 480.00, 22, 256, 'English', NULL, 4.3, 0);

-- ───────────── APP_USER (5 rows: 1 admin + 4 customers) ─────────────
INSERT INTO APP_USER VALUES (1, 'Admin User', 'admin@turnpage.com', 'admin123', '01700000000', NULL, 'admin', SYSDATE);
INSERT INTO APP_USER VALUES (2, 'Aurin Rahman', 'aurin@example.com', 'pass1234', '01711111111', NULL, 'customer', SYSDATE);
INSERT INTO APP_USER VALUES (3, 'Fatima Khan', 'fatima@example.com', 'pass1234', '01722222222', NULL, 'customer', SYSDATE);
INSERT INTO APP_USER VALUES (4, 'Rakib Hasan', 'rakib@example.com', 'pass1234', '01733333333', NULL, 'customer', SYSDATE);
INSERT INTO APP_USER VALUES (5, 'Nusrat Jahan', 'nusrat@example.com', 'pass1234', '01744444444', NULL, 'customer', SYSDATE);

-- ───────────── VOUCHER (3 rows) ─────────────
INSERT INTO VOUCHER VALUES (1, 'WELCOME10', 10.00, DATE '2026-01-01', DATE '2026-12-31', 300.00, 100, 1);
INSERT INTO VOUCHER VALUES (2, 'BOOKLOVER20', 20.00, DATE '2026-01-01', DATE '2026-12-31', 500.00, 50, 1);
INSERT INTO VOUCHER VALUES (3, 'EIDOFFER', 15.00, DATE '2026-03-01', DATE '2026-04-30', 400.00, 200, 1);

-- ───────────── CART (2 rows, for customers 2 and 3) ─────────────
INSERT INTO CART VALUES (1, 2, SYSDATE);
INSERT INTO CART VALUES (2, 3, SYSDATE);

-- ───────────── CART_ITEM (4 rows) ─────────────
INSERT INTO CART_ITEM VALUES (1, 1, 1, 2, 250.00);
INSERT INTO CART_ITEM VALUES (2, 1, 4, 1, 850.00);
INSERT INTO CART_ITEM VALUES (3, 2, 6, 1, 550.00);
INSERT INTO CART_ITEM VALUES (4, 2, 7, 3, 200.00);

-- ───────────── ORDERS (3 rows, completed orders) ─────────────
INSERT INTO ORDERS VALUES (1, 2, 1, DATE '2026-05-10', 1100.00, 'Delivered');
INSERT INTO ORDERS VALUES (2, 4, NULL, DATE '2026-05-15', 850.00, 'Shipped');
INSERT INTO ORDERS VALUES (3, 5, 2, DATE '2026-06-01', 480.00, 'Pending');

-- ───────────── ORDER_ITEM (4 rows) ─────────────
INSERT INTO ORDER_ITEM VALUES (1, 1, 1, 1, 250.00);
INSERT INTO ORDER_ITEM VALUES (2, 1, 4, 1, 850.00);
INSERT INTO ORDER_ITEM VALUES (3, 2, 5, 1, 850.00);
INSERT INTO ORDER_ITEM VALUES (4, 3, 10, 1, 480.00);

-- ───────────── PAYMENT (3 rows) ─────────────
INSERT INTO PAYMENT VALUES (1, 1, 'Credit Card', 1100.00, 'Completed', DATE '2026-05-10', 'TXN1001');
INSERT INTO PAYMENT VALUES (2, 2, 'Cash on Delivery', 850.00, 'Pending', NULL, NULL);
INSERT INTO PAYMENT VALUES (3, 3, 'bKash', 480.00, 'Completed', DATE '2026-06-01', 'TXN1003');

-- ───────────── REVIEW (5 rows) ─────────────
INSERT INTO REVIEW VALUES (1, 2, 1, 5, 'One of the best Himu books! Loved it.', DATE '2026-05-12');
INSERT INTO REVIEW VALUES (2, 2, 4, 5, 'A magical start to an amazing series.', DATE '2026-05-13');
INSERT INTO REVIEW VALUES (3, 4, 5, 4, 'Great sequel, kept me hooked.', DATE '2026-05-18');
INSERT INTO REVIEW VALUES (4, 5, 10, 4, 'Beautifully written, very thoughtful.', DATE '2026-06-03');
INSERT INTO REVIEW VALUES (5, 3, 6, 5, 'Life changing book. Highly recommend.', DATE '2026-05-20');

COMMIT;

