CREATE TABLE books(
  id BYTEA PRIMARY KEY,
  title VARCHAR NOT NULL,
  isbn VARCHAR(13) NOT NULL,
  authors TEXT[] NOT NULL
);

CREATE TABLE readers(
  id BYTEA PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  phone VARCHAR(255) NOT NULL
);

CREATE TABLE book_copies(
  id BYTEA PRIMARY KEY,
  book_id BYTEA REFERENCES books(id) NOT NULL,
  remarks VARCHAR(1024)
);

CREATE TABLE book_loans(
  id BYTEA PRIMARY KEY,
  book_copy_id BYTEA REFERENCES book_copies(id) NOT NULL,
  reader_id BYTEA REFERENCES readers(id) NOT NULL,
  due_date TIMESTAMP NOT NULL,
  has_ended BOOLEAN NOT NULL,
  end_date TIMESTAMP NOT NULL,
  is_prolonged BOOLEAN NOT NULL,
  remarks VARCHAR(1024)
);
