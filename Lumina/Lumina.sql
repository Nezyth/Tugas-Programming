CREATE TABLE users (
    user_id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(100) NOT NULL, 
    role ENUM('admin','user') DEFAULT 'user',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE books (
    book_id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(200) NOT NULL,
    author VARCHAR(150) NOT NULL,
    category VARCHAR(100),
    description TEXT,
    stock INT DEFAULT 0,

    cover_image VARCHAR(255), 
    file_path VARCHAR(255),   

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE borrowings (
    borrow_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    book_id INT NOT NULL,
    borrow_date DATE NOT NULL,
    return_date DATE NOT NULL,
    status ENUM('borrowed','returned') DEFAULT 'borrowed',

    CONSTRAINT fk_borrow_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_borrow_book
        FOREIGN KEY (book_id)
        REFERENCES books(book_id)
        ON DELETE CASCADE
);

CREATE TABLE forums (
    forum_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(200) NOT NULL,
    content TEXT NOT NULL,
    category ENUM('ask','recommend','discussion') NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_forum_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE forum_comments (
    comment_id INT AUTO_INCREMENT PRIMARY KEY,
    forum_id INT NOT NULL,
    user_id INT NOT NULL,
    comment TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,

    CONSTRAINT fk_comment_forum
        FOREIGN KEY (forum_id)
        REFERENCES forums(forum_id)
        ON DELETE CASCADE,

    CONSTRAINT fk_comment_user
        FOREIGN KEY (user_id)
        REFERENCES users(user_id)
        ON DELETE CASCADE
);

CREATE TABLE forum_posts (
    forum_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id)
);

CREATE INDEX idx_books_title ON books(title);
CREATE INDEX idx_books_author ON books(author);
CREATE INDEX idx_books_category ON books(category);

INSERT INTO users (name, email, password, role)
VALUES ('Admin', 'admin@library.com', 'admin123', 'admin');

INSERT INTO users (name, email, password)
VALUES ('Test User', 'user@library.com', 'user123');

INSERT INTO books 
(title, author, category, description, stock, cover_image, file_path)
VALUES
('Clean Code', 'Robert C. Martin', 'Programming', 'A handbook of agile software craftsmanship.', 5, 'clean_code.jpg', 'clean_code.pdf');

-- To fix the problem with not being able to comment on your own forum post--

ALTER TABLE forum_comments
DROP FOREIGN KEY fk_comment_forum;

ALTER TABLE forum_comments
ADD CONSTRAINT fk_comment_forum
FOREIGN KEY (forum_id)
REFERENCES forum_posts(forum_id)
ON DELETE CASCADE;
