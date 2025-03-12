<?php

use PHPUnit\Framework\TestCase;

require_once 'database.php';

class MoodTrackerTest extends TestCase
{
    protected $conn;
    protected $user_id = 1;

    protected function setUp(): void
    {
        $this->conn = new mysqli('sql8.freesqldatabase.com', 'sql8767118', 'ACVyISAule', 'sql8767118');

        // Ensure user exists
        $this->conn->query("INSERT IGNORE INTO users (id, name) VALUES ($this->user_id, 'Test User')");
    }

    public function testLogMood()
    {
        $mood = "Great";
        $comment = "Feeling good today!";
        $date = date('Y-m-d');

        // Insert into the mood log
        $stmt = $this->conn->prepare("INSERT INTO moods (user_id, mood, comment, date) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("isss", $this->user_id, $mood, $comment, $date);
        $this->assertTrue($stmt->execute());
        $stmt->close();

        // Verify thatthe entry exists
        $query = $this->conn->prepare("SELECT * FROM moods WHERE user_id = ? AND date = ?");
        $query->bind_param("is", $this->user_id, $date);
        $query->execute();
        $result = $query->get_result();

        $this->assertEquals(1, $result->num_rows, "Mood log should be stored.");
        $query->close();
    }

    public function testFetchMoods()
    {
        $query = $this->conn->prepare("SELECT mood, comment, date FROM moods WHERE user_id = ? ORDER BY date DESC");
        $query->bind_param("i", $this->user_id);
        $query->execute();
        $result = $query->get_result();

        $moods = [];
        while ($row = $result->fetch_assoc()) {
            $moods[] = $row;
        }

        $this->assertNotEmpty($moods, "Should return at least one mood entry.");
        $query->close();
    }
}
?>
