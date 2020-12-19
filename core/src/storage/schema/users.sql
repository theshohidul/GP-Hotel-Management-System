CREATE TABLE IF NOT EXISTS users (
                                id serial PRIMARY KEY,
                                name character varying(100) NOT NULL,
                                email character varying(255) NOT NULL UNIQUE ,
                                password character varying(255) NOT NULL
                            );