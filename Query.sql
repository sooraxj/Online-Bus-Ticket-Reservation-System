ALTER TABLE tbl_ AUTO_INCREMENT = 1;



-- Login table

CREATE TABLE tbl_Login (
    Username varchar(30) UNIQUE, 
    login_Password varchar(100) NOT NULL,
    login_type ENUM('user', 'admin') DEFAULT 'user',
    login_status BOOLEAN DEFAULT 0,
    primary key(Username)
);

ALTER TABLE `tbl_login` ADD PRIMARY KEY(`Username`);
INSERT INTO tbl_Login (Username, login_Password, login_type) VALUES ('Admin@gmail.com', '$2a$12$q4QGj3o77zOANAo03Y19PuINIEcaBAc0nnXRdEk2.ltNo4gJyQw5u', 'admin');

--  ............................................... 

-- Driver

CREATE TABLE tbl_Driver (
     Driver_id INT(5) AUTO_INCREMENT,
     D_fname VARCHAR(10) NOT NULL ,
     D_mname VARCHAR(10) NOT NULL , 
     D_lname VARCHAR(10) NOT NULL , 
     D_licence_no VARCHAR(16) NOT NULL , 
     D_licence_expiry DATE NOT NULL , 
     D_badge_no VARCHAR(15) NOT NULL , 
     D_city VARCHAR(20) NOT NULL , 
     D_dist VARCHAR(20) NOT NULL , 
     D_state VARCHAR(20) NOT NULL ,     
     D_pin NUMERIC(6) NOT NULL , 
     D_street VARCHAR(20) NOT NULL , 
     D_phone NUMERIC(10) NOT NULL UNIQUE, 
     D_email VARCHAR(30) NOT NULL UNIQUE , 
     D_gender VARCHAR(1) NOT NULL , 
     D_dob DATE NOT NULL , 
     D_join DATE NOT NULL , 
     D_experience VARCHAR(2) NOT NULL ,
     primary key(Driver_id)
       );
ALTER TABLE tbl_driver ADD D_state varchar(20) NOT NULL;
-- ..................................................

-- Customer

CREATE TABLE tbl_Customer (
     Customer_id INT(5) AUTO_INCREMENT,
     Username VARCHAR(30) UNIQUE, 
     C_fname VARCHAR(10) NOT NULL ,
     C_mname VARCHAR(10) NOT NULL , 
     C_lname VARCHAR(10) NOT NULL ,  
     C_city VARCHAR(20) NOT NULL , 
     C_dist VARCHAR(20) NOT NULL , 
     C_state VARCHAR(20) NOT NULL , 
     C_pin NUMERIC(6) NOT NULL , 
     C_street VARCHAR(20) NOT NULL , 
     C_phone NUMERIC(10) NOT NULL UNIQUE,  
     C_gender VARCHAR(1) NOT NULL , 
     C_dob DATE NOT NULL , 
     C_reg DATE NOT NULL DEFAULT now(), 
     C_Status BOOLEAN NOT NULL , 
     PRIMARY KEY(Customer_id),
     FOREIGN KEY (Username) REFERENCES tbl_Login (Username)
       );
-- ....................................................

-- Bus

CREATE TABLE tbl_Bus (
     Bus_id INT(5) AUTO_INCREMENT,
     Bus_Reg_no VARCHAR(15) NOT NULL UNIQUE,
     Bus_Comp VARCHAR(10) NOT NULL,
     Seating_capacity Numeric(2) NOT NULL ,
     Yearofmanufacture NUMERIC(4) ,
     PRIMARY KEY (Bus_id)
);
-- ......................................................

-- Route
CREATE TABLE tbl_Route (
     Route_id INT(5) AUTO_INCREMENT,
     Route_no NUMERIC(4) NOT NULL UNIQUE,
     Route_name VARCHAR(20) NOT NULL,
     Starting_point VARCHAR(20) NOT NULL ,
     Destination VARCHAR(20) NOT NULL ,
     Distance NUMERIC(4) NOT NULL,
     Route_status BOOLEAN NOT NULL,
     PRIMARY KEY (Route_id)
);


-- .....................................................

-- Stop

CREATE TABLE tbl_Stop (
     Stop_id INT(5) AUTO_INCREMENT,
     Route_id INT(5),
     Stop_no NUMERIC(5),
     Stop_name VARCHAR(20) NOT NULL,
     Arrival_time TIME NOT NULL UNIQUE,
     Distance NUMERIC(4) NOT NULL,
     Stop_status BOOLEAN NOT NULL,
     Main BOOLEAN NOT NULL,
     PRIMARY KEY (Stop_id),
    FOREIGN KEY (Route_id) REFERENCES tbl_Route (Route_id)
);


-- table stop issue


-- CREATE TABLE `tbl_stop` (
--   `Stop_id` int(5) NOT NULL,
--   `Route_id` int(5) NOT NULL,
--   `Stop_no` varchar(5) NOT NULL,
--   `Stop_name` varchar(20) NOT NULL,
--   `Arrival_time` time NOT NULL,
--   `Distance` decimal(4,0) NOT NULL,
--   `Stop_status` tinyint(1) NOT NULL
-- ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;


-- ALTER TABLE `tbl_stop`
--   ADD PRIMARY KEY (`Stop_id`),
--   ADD KEY `Route_id` (`Route_id`);

--

--   ALTER TABLE `tbl_stop`
--   ADD CONSTRAINT `tbl_stop_ibfk_1` FOREIGN KEY (`Route_id`) REFERENCES `tbl_route` (`Route_id`);
-- COMMIT;




-- .........................................................
-- Farestage

CREATE TABLE tbl_Farestage (
    Farestage_id INT PRIMARY KEY AUTO_INCREMENT,
    Base_fare Numeric(5) NOT NULL,
    Additional_fare Numeric(5) NOT NULL,
    F_status BOOLEAN NOT NULL
);

-- ..........................................................
-- Driver bus

CREATE TABLE tbl_Driver_bus (
     Driver_bus_id INT(5) AUTO_INCREMENT,
     Bus_id INT(5),
     Driver_id INT(5),
     Allocation_date DATE NOT NULL,
     Start_time TIME NOT NULL,
     End_time TIME NOT NULL,
     Db_status BOOLEAN NOT NULL,
     PRIMARY KEY (Driver_bus_id),
    FOREIGN KEY (Bus_id) REFERENCES tbl_Bus (Bus_id),
    FOREIGN KEY (Driver_id) REFERENCES tbl_Driver (Driver_id)
);

-- ...........................................................
-- Bus route


CREATE TABLE tbl_Bus_route (
     Bus_route_id INT(5) AUTO_INCREMENT,
     Driver_bus_id INT(5),
     Route_id INT(5),
     Allocation_date DATE NOT NULL,
     Start_time TIME NOT NULL,
     End_time TIME NOT NULL ,
     Br_status BOOLEAN NOT NULL,
     PRIMARY KEY (Bus_route_id),
    FOREIGN KEY (Driver_bus_id) REFERENCES tbl_Driver_bus (Driver_bus_id),
    FOREIGN KEY (Route_id) REFERENCES tbl_Route (Route_id)
);



-- ...........................................................
-- booking master


CREATE TABLE tbl_Booking_master (
     Booking_master_id INT(5) AUTO_INCREMENT,
     Customer_id INT(5),
     Bus_route_id INT(5),
     Farestage_id INT(5),
     Booking_date DATE NOT NULL,
     Total_fare Numeric(7) NOT NULL,
     Distance NUMERIC(5) NOT NULL,
     Total_seats NUMERIC(2) NOT NULL,
     PRIMARY KEY (Booking_master_id),
    FOREIGN KEY (Customer_id) REFERENCES tbl_Customer (Customer_id),
    FOREIGN KEY (Bus_route_id) REFERENCES tbl_Bus_route (Bus_route_id),
    FOREIGN KEY (Farestage_id) REFERENCES tbl_Farestage (Farestage_id)
);

-- ...........................................................
-- booking child


CREATE TABLE tbl_Booking_child (
     Booking_child_id INT(5) AUTO_INCREMENT,
     Booking_master_id INT(5),
     Passenger_name VARCHAR(20) NOT NULL,
     Age NUMERIC (2) NOT NULL,
     Gender VARCHAR(1) NOT NULL,
     Seat_number NUMERIC(2) NOT NULL,
     Sstop_no NUMERIC(2) NOT NULL,
     Dstop_no NUMERIC(2) NOT NULL,
     Source VARCHAR(20) NOT NULL,
     Destination VARCHAR(20) NOT NULL,
     Booking_status Boolean NOT NULL,
     PRIMARY KEY (Booking_child_id),
    FOREIGN KEY (Booking_master_id) REFERENCES tbl_Booking_master (Booking_master_id)
    
);

-- ...........................................................
-- card


CREATE TABLE tbl_Card (
     Card_id INT(5) AUTO_INCREMENT,
     Customer_id INT(5),
    Card_no NUMERIC(20) NOT NULL,
    Card_holder VARCHAR (20) NOT NULL,
    Cvv NUMERIC(3) NOT NULL,
    Exp_date varchar(7) NOT NULL,
     PRIMARY KEY (Card_id),
    FOREIGN KEY (Customer_id) REFERENCES tbl_Customer (Customer_id)
    
);

-- ...........................................................
-- payment


CREATE TABLE tbl_Payment (
     Payment_id INT(5) AUTO_INCREMENT,
     Booking_master_id INT(5),
     Card_id INT(5),
     Payment_date Date NOT NULL,
     Refund_amount NUMERIC(5) NOT NULL,
     Payment_status Boolean NOT NULL,
     PRIMARY KEY (Payment_id),
     FOREIGN KEY (Booking_master_id) REFERENCES tbl_Booking_master (Booking_master_id),
     FOREIGN KEY (Card_id) REFERENCES tbl_Card (Card_id)
);

-- ...........................................................
-- ticket


CREATE TABLE tbl_Ticket (
     Ticket_id INT(5) AUTO_INCREMENT,
     Customer_id INT(5),
     Booking_master_id INT(5),
     Payment_id INT(5),
     Ticket_number VARCHAR(10) NOT NULL,
     Journey_date Date NOT NULL,
     Journey_stime Time NOT NULL,
     Journey_etime Time NOT NULL,
     Ticket_status Boolean NOT NULL,
     PRIMARY KEY (Ticket_id),
     FOREIGN KEY (Customer_id) REFERENCES tbl_Customer (Customer_id),
     FOREIGN KEY (Booking_master_id) REFERENCES tbl_Booking_master (Booking_master_id),
     FOREIGN KEY (Payment_id) REFERENCES tbl_Payment (Payment_id)
);

-- ...........................................................
-- Feedback


CREATE TABLE tbl_Feedback (
     Feedback_id INT(5) AUTO_INCREMENT,
     Customer_id INT(5),
     Ticket_id INT(5),
     Feedback_date Date NOT NULL,
     Bus_rating NUMERIC(1) NOT NULL,
     Driver_rating NUMERIC(1) NOT NULL,
     Comment VARCHAR(50) NOT NULL,
     Complaint Boolean NOT NULL,
     PRIMARY KEY (Feedback_id),
     FOREIGN KEY (Customer_id) REFERENCES tbl_Customer (Customer_id),
     FOREIGN KEY (Ticket_id) REFERENCES tbl_Ticket (Ticket_id)
);


current_timestamp()




