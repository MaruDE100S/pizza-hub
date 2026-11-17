CREATE DATABASE IF NOT EXISTS pizzahub;
USE pizzahub;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    email VARCHAR(255) UNIQUE,
    password_hash VARCHAR(255),
    full_name VARCHAR(100),
    phone VARCHAR(20),
    address TEXT,
    role ENUM('client', 'employee', 'admin') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE pizzas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(100),
    description TEXT,
    price DECIMAL(10, 2),
    category VARCHAR(50),
    image_url VARCHAR(255),
    is_available BOOLEAN DEFAULT TRUE
);

CREATE TABLE orders (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    status ENUM('new', 'cooking', 'ready', 'delivered', 'cancelled') DEFAULT 'new',
    delivery_address TEXT,
    comment TEXT,
    total_price DECIMAL(10, 2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id)
);

CREATE TABLE order_items (
    id INT PRIMARY KEY AUTO_INCREMENT,
    order_id INT,
    pizza_id INT,
    quantity INT,
    price_at_time DECIMAL(10, 2),
    FOREIGN KEY (order_id) REFERENCES orders(id),
    FOREIGN KEY (pizza_id) REFERENCES pizzas(id)
);

INSERT INTO `pizzas` (`id`, `name`, `description`, `price`, `category`, `image_url`, `is_available`) VALUES
(1, 'Margherita', 'Tomato sauce, fresh mozzarella, basil', 35.00, 'Classic', '../assets/images/margherita.jpg', 1),
(2, 'Capricciosa', 'Tomato sauce, fresh mozzarella, ham, mushrooms', 42.00, 'Classic', '../assets/images/capricciosa.jpg', 1),
(3, 'Pepperoni', 'Tomato sauce, fresh mozzarella, pepperoni', 38.00, 'Classic', '../assets/images/pepperoni.jpg', 1),
(4, 'Four Chesse', 'Tomato sauce, fresh mozzarella, gorgonzola, provolone, parmesan', 45.00, 'Classic', '../assets/images/fourchesse.jpg', 1),
(5, 'Veggie', 'Tomato sauce, fresh mozzarella, mushrooms, onions, peppers, olives', 40.00, 'Classic', '../assets/images/veggie.jpg', 1);
