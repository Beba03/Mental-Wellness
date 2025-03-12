<?php

use PHPUnit\Framework\TestCase;

require_once 'database.php';

class BookTherapyTest extends TestCase
{
    protected $conn;

    protected function setUp(): void
    {
        $this->conn = new mysqli('sql8.freesqldatabase.com', 'sql8767118', 'ACVyISAule', 'sql8767118');
    }

    public function testBookingSuccess()
    {
        $user_id = 1;
        $date = date('Y-m-d', strtotime('+1 day'));
        $time = '10:00';

        // Ensure the slot is available
        $checkQuery = $this->conn->prepare("SELECT COUNT(*) FROM bookings WHERE date = ? AND time = ?");
        $checkQuery->bind_param("ss", $date, $time);
        $checkQuery->execute();
        $checkQuery->bind_result($existingCount);
        $checkQuery->fetch();
        $checkQuery->close();

        if ($existingCount === 0) {
            // Try to book the slot
            $stmt = $this->conn->prepare("INSERT INTO bookings (user_id, date, time) VALUES (?, ?, ?)");
            $stmt->bind_param("iss", $user_id, $date, $time);
            $this->assertTrue($stmt->execute());
            $stmt->close();
        } else {
            $this->markTestSkipped("Slot already booked.");
        }
    }

    public function testBookingConflict()
    {
        $user_id = 1;
        $date = date('Y-m-d', strtotime('+1 day'));
        $time = '10:00';

        // Insert an existing booking
        $this->conn->query("INSERT INTO bookings (user_id, date, time) VALUES ($user_id, '$date', '$time')");

        // Attempt to book the same slot again
        $checkQuery = $this->conn->prepare("SELECT COUNT(*) FROM bookings WHERE date = ? AND time = ?");
        $checkQuery->bind_param("ss", $date, $time);
        $checkQuery->execute();
        $checkQuery->bind_result($existingCount);
        $checkQuery->fetch();
        $checkQuery->close();

        $this->assertGreaterThan(0, $existingCount, "Slot should be booked already.");
    }

    public function testBookingCancellation()
    {
        $user_id = 1;
        $date = date('Y-m-d', strtotime('+1 day'));
        $time = '10:00';

        // Insert a new booking
        $this->conn->query("INSERT INTO bookings (user_id, date, time) VALUES ($user_id, '$date', '$time')");

        // Get booking ID
        $result = $this->conn->query("SELECT id FROM bookings WHERE user_id = $user_id AND date = '$date' AND time = '$time' LIMIT 1");
        $row = $result->fetch_assoc();
        $booking_id = $row['id'];

        // Try to cancel the booking
        $deleteQuery = $this->conn->prepare("DELETE FROM bookings WHERE id = ? AND user_id = ?");
        $deleteQuery->bind_param("ii", $booking_id, $user_id);
        $this->assertTrue($deleteQuery->execute());
        $deleteQuery->close();
    }
}
?>
