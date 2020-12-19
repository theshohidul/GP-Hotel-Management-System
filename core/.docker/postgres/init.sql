CREATE TABLE IF NOT EXISTS customers (
	id serial PRIMARY KEY,
	first_name character varying(100) NOT NULL,
	last_name character varying(100) NOT NULL,
	email character varying(255) NOT NULL UNIQUE ,
	phone character varying(255) NOT NULL UNIQUE ,
	password character varying(255) NOT NULL,
	registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO customers ("first_name", "last_name", "email", "phone", "password") VALUES ('Shohidul', 'Islam', 'shohidul.islam@portonics.com', '01313983737', '123456');

CREATE TABLE IF NOT EXISTS admins (
	id serial PRIMARY KEY,
	first_name character varying(100) NOT NULL,
	last_name character varying(100) NOT NULL,
	email character varying(255) NOT NULL UNIQUE ,
	phone character varying(255) NOT NULL UNIQUE ,
	password character varying(255) NOT NULL,
	registered_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO admins ("first_name", "last_name", "email", "phone", "password") VALUES ('Shohidul', 'Islam', 'shohidul.islam1@portonics.com', '01313983737', '123456');



CREATE TABLE IF NOT EXISTS rooms (
	id serial PRIMARY KEY,
	room_number character varying(100) NOT NULL UNIQUE,
	price decimal NOT NULL,
	locked character varying(255) NOT NULL,
	max_persons integer NOT NULL,
	room_type character varying(255) NOT NULL
);


CREATE TABLE IF NOT EXISTS payments (
	id serial PRIMARY KEY,
	booking_id integer NOT NULL,
	customer_id integer NOT NULL,
	amount decimal NOT NULL,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);


CREATE TABLE IF NOT EXISTS bookings (
	id serial PRIMARY KEY,
	room_id integer NOT NULL,
	room_number character varying(100) NOT NULL,
	arrival TIMESTAMP NOT NULL,
	checkout TIMESTAMP NOT NULL,
	customer_id integer  NOT NULL,
	book_type character varying(100) NOT NULL,
	book_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
