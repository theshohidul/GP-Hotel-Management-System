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