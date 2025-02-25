const moodSelect = document.querySelector("#mood");
const commentInput = document.querySelector("#comment");
const logButton = document.querySelector("#logMood");
const deleteButton = document.querySelector("#deleteLogs");
const moodLog = document.querySelector("#moodLog");

logButton.addEventListener("click", logMood);
deleteButton.addEventListener("click", deleteAllLogs);

function logMood() {
    const mood = moodSelect.value;
    const comment = commentInput.value.trim(); // Get user comment
    const date = new Date().toISOString().slice(0, 19).replace("T", " "); // Format as MySQL DATETIME

    if (!mood) {
        alert("Please select a mood before logging.");
        return;
    }

    const moodEntry = { mood, comment, date };

    // Send data to PHP via fetch
    fetch("../PHP/log_mood.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(moodEntry),
    })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayMoodLog();
                commentInput.value = "";
            } else {
                alert("Error logging mood: " + data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
}

function displayMoodLog() {
    fetch("../PHP/get_moods.php")
        .then((response) => response.json())
        .then((moods) => {
            if (moods.length === 0) {
                moodLog.innerHTML = "<p>No moods logged yet.</p>";
                moodLog.style.display = "none";
                return;
            }

            moodLog.innerHTML = moods
                .map(
                    (moodObj) =>
                        `<p><strong>${moodObj.date} - ${moodObj.mood}:</strong> ${moodObj.comment || "No comment"}</p>`
                )
                .join("");

            moodLog.style.display = "block";
        })
        .catch((error) => console.error("Error:", error));
}

function deleteAllLogs() {
    fetch("../PHP/delete_moods.php", { method: "POST" })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                displayMoodLog();
            } else {
                alert("Error deleting logs: " + data.message);
            }
        })
        .catch((error) => console.error("Error:", error));
}

displayMoodLog();
