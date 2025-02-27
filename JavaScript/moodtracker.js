document.addEventListener('DOMContentLoaded', function () {
    const moodSelect = document.querySelector("#emotionSelect");
    const commentInput = document.querySelector("#commentInput");
    const logButton = document.querySelector("#logMood");

    // Button click event
    logButton.addEventListener("click", logMood);

    function logMood() {
        const mood = moodSelect.value; // Get the selected mood
        const comment = commentInput.value.trim(); // Get the comment
        const date = new Date().toLocaleDateString('en-GB'); // Format the date as DD/MM/YYYY

        if (!mood) {
            alert("Please select a mood before logging.");
            return;
        }

        const moodEntry = { mood, comment, date };

        // Send data to PHP via fetch
        fetch("log_mood.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json"
            },
            body: JSON.stringify(moodEntry),
        })
        .then((response) => response.json())
        .then((data) => {
            if (data.success) {
                alert("Mood logged successfully!");
                commentInput.value = ""; // Clear the comment input field
                moodSelect.value = "1"; // Reset the emotion selection
            } else {
                alert("Error logging mood: " + data.message);
            }
        })
        .catch((error) => {
            console.error("Error:", error);
            alert("An error occurred.");
        });
    }
});
