CREATE TABLE IF NOT EXISTS reservation_cancellations (
    id INT(11) PRIMARY KEY AUTO_INCREMENT,
    reservation_id INT(11) NOT NULL,
    cancellation_reason TEXT NOT NULL,
    cancelled_by VARCHAR(50) NOT NULL,
    cancelled_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (reservation_id) REFERENCES reservasi(id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
