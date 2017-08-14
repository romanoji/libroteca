CREATE TABLE IF NOT EXISTS books(
  id UUID PRIMARY KEY,
  isbn VARCHAR(13) UNIQUE,
  title VARCHAR NOT NULL,
  authors TEXT[] NOT NULL
);

CREATE TABLE IF NOT EXISTS readers(
  id UUID PRIMARY KEY,
  name VARCHAR(255) NOT NULL,
  surname VARCHAR(255) NOT NULL,
  email VARCHAR(255) UNIQUE NOT NULL,
  phone VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE IF NOT EXISTS book_copies(
  id UUID PRIMARY KEY,
  book_id UUID REFERENCES books(id) NOT NULL,
  remarks VARCHAR(1024)
);

CREATE TABLE IF NOT EXISTS book_loans(
  id UUID PRIMARY KEY,
  book_copy_id UUID REFERENCES book_copies(id) NOT NULL,
  reader_id UUID REFERENCES readers(id) NOT NULL,
  due_date DATE NOT NULL,
  has_ended BOOLEAN NOT NULL,
  end_date TIMESTAMP(0),
  is_prolonged BOOLEAN NOT NULL,
  remarks VARCHAR(1024)
);

CREATE UNIQUE INDEX CONCURRENTLY index_unique_unfinished_book_loans_on_book_copy_id
  ON book_loans (book_copy_id)
  WHERE has_ended = FALSE;
