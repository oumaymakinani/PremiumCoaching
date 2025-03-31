let feedbackIndex = 0;
const itemsPerPage = 4;
let allFeedback = [];

document.addEventListener("DOMContentLoaded", function () {
    fetchFeedback();
    loadCoaches();

    document.getElementById("apply-filters").addEventListener("click", fetchFeedback);
    document.getElementById("feedbackForm").addEventListener("submit", submitFeedback);
    document.querySelector(".prev-btn").addEventListener("click", () => navigateFeedback(-1));
    document.querySelector(".next-btn").addEventListener("click", () => navigateFeedback(1));
    document.getElementById("feedback-text").addEventListener("input", updateWordCount);
    document.getElementById("search-bar").addEventListener("input", filterSearch);
});

function fetchFeedback() {
    const coachId = document.getElementById("coach-filter").value;
    const rating = document.getElementById("rating-filter").value;
    const date = document.getElementById("date-filter").value;

    let url = "backend/feedback.php";
    let params = [];

    if (coachId) params.push(`coach_id=${coachId}`);
    if (rating) params.push(`rating=${rating}`);
    if (date) params.push(`date=${date}`);

    if (params.length) url += "?" + params.join("&");

    fetch(url)
        .then(response => response.json())
        .then(data => {
            allFeedback = data;
            feedbackIndex = 0;
            displayFeedback();
        })
        .catch(error => console.error("Error fetching feedback:", error));
}

function displayFeedback(filteredData = null) {
    const data = filteredData || allFeedback;
    const container = document.getElementById("feedback-container");
    container.innerHTML = "";

    if (!data.length) {
        container.innerHTML = "<p>No feedback found.</p>";
        return;
    }

    const start = feedbackIndex * itemsPerPage;
    const end = start + itemsPerPage;
    const current = data.slice(start, end);

    current.forEach(feedback => {
        const card = document.createElement("div");
        card.classList.add("feedback-card");
        card.innerHTML = `
            <h3>${feedback.coach_name}</h3>
            <p><strong>Rating:</strong> ${"⭐️".repeat(feedback.rating)}</p>
            <p><strong>Comments:</strong> ${feedback.comments}</p>
            <p><strong>Date:</strong> ${feedback.created_at}</p>
        `;
        container.appendChild(card);
    });
}

function navigateFeedback(direction) {
    const totalPages = Math.ceil(allFeedback.length / itemsPerPage);
    feedbackIndex = (feedbackIndex + direction + totalPages) % totalPages;
    displayFeedback();
}

function loadCoaches() {
    fetch("backend/get_coaches.php")
        .then(response => response.json())
        .then(coaches => {
            const coachFilter = document.getElementById("coach-filter");
            const feedbackCoach = document.getElementById("feedback-coach");
            coachFilter.innerHTML = '<option value="">All Coaches</option>';
            feedbackCoach.innerHTML = '<option value="">Select Coach</option>';

            coaches.forEach(coach => {
                const opt1 = new Option(coach.name, coach.coach_id);
                const opt2 = new Option(coach.name, coach.coach_id);
                coachFilter.appendChild(opt1);
                feedbackCoach.appendChild(opt2);
            });
        });
}

function submitFeedback(event) {
    event.preventDefault();

    const feedbackText = document.getElementById("feedback-text").value.trim();
    if (feedbackText.split(/\s+/).length > 300) {
        alert("❌ Feedback must be 300 words max.");
        return;
    }

    const formData = new FormData(document.getElementById("feedbackForm"));

    fetch("backend/feedback.php", {
        method: "POST",
        body: formData
    })
        .then(response => response.json())
        .then(data => {
            alert(data.success || data.error);
            fetchFeedback();
            document.getElementById("feedbackForm").reset();
            document.getElementById("word-count").innerText = "0 / 300 words";
        });
}

function updateWordCount() {
    const textarea = document.getElementById("feedback-text");
    const words = textarea.value.trim().split(/\s+/).filter(Boolean);
    document.getElementById("word-count").innerText = `${words.length} / 300 words`;

    const submitBtn = document.querySelector("#feedbackForm button");
    submitBtn.disabled = words.length === 0 || words.length > 300;
}

function filterSearch() {
    const keyword = this.value.toLowerCase();
    const filtered = allFeedback.filter(feedback =>
        feedback.comments.toLowerCase().includes(keyword) ||
        feedback.coach_name.toLowerCase().includes(keyword)
    );

    feedbackIndex = 0;
    displayFeedback(filtered);
}
