let allPrograms = [];

const likedPrograms = new Set(JSON.parse(localStorage.getItem("likedPrograms") || "[]"));

document.addEventListener("DOMContentLoaded", function () {
  loadFilters();
  loadPrograms();

  document.getElementById("search-bar").addEventListener("input", handleSearch);
  document.getElementById("difficulty-filter").addEventListener("change", filterPrograms);
  document.getElementById("type-filter").addEventListener("change", filterPrograms);
  document.getElementById("reset-filters").addEventListener("click", resetFilters);
});

function loadFilters() {
  fetch("backend/get_filters.php")
    .then((response) => response.json())
    .then((data) => {
      const difficultyFilter = document.getElementById("difficulty-filter");
      const typeFilter = document.getElementById("type-filter");

      data.difficulties.forEach((difficulty) => {
        const option = document.createElement("option");
        option.value = difficulty;
        option.innerText = difficulty;
        difficultyFilter.appendChild(option);
      });

      data.session_types.forEach((type) => {
        const option = document.createElement("option");
        option.value = type;
        option.innerText = type;
        typeFilter.appendChild(option);
      });
    })
    .catch((error) => console.error("Error fetching filters:", error));
}

function loadPrograms() {
  fetch(`backend/get_programs.php?difficulty=all&session_type=all`)
    .then((response) => response.json())
    .then((programs) => {
      allPrograms = programs;
      filterPrograms();
    })
    .catch((error) => console.error("Error fetching programs:", error));
}

function filterPrograms() {
  const difficulty = document.getElementById("difficulty-filter").value;
  const sessionType = document.getElementById("type-filter").value;
  const searchTerm = document.getElementById("search-bar").value.toLowerCase();

  const filtered = allPrograms.filter((program) => {
    const matchDifficulty = difficulty === "all" || program.difficulty === difficulty;
    const matchType = sessionType === "all" || program.session_type === sessionType;
    const matchTitle = program.title.toLowerCase().includes(searchTerm);
    return matchDifficulty && matchType && matchTitle;
  });

  renderPrograms(filtered, searchTerm);
}

function renderPrograms(programs, highlight = "") {
  const container = document.getElementById("programs-list");
  container.innerHTML = "";

  if (programs.length === 0) {
    container.innerHTML = "<p>No programs match your criteria.</p>";
    return;
  }

  programs.forEach((program) => {
    let highlightedTitle = program.title;
    if (highlight) {
      const regex = new RegExp(`(${highlight})`, "gi");
      highlightedTitle = program.title.replace(regex, "<strong>$1</strong>");
    }

    const card = document.createElement("div");
    card.classList.add("program-card");

    const isLiked = likedPrograms.has(program.session_id);

    card.innerHTML = `
      <h3>${highlightedTitle}</h3>
      <p><strong>Description:</strong> ${program.description}</p>
      <p><strong>Difficulty:</strong> ${program.difficulty}</p>
      <p><strong>Type:</strong> ${program.session_type}</p>
      <p><strong>Duration:</strong> ${program.duration} mins</p>
      <button class="like-btn" data-session-id="${program.session_id}">
        ${isLiked ? "💔" : "❤️"} <span class="likes-count">${program.likes || 0}</span>
      </button>
    `;

    const btn = card.querySelector(".like-btn");
    btn.addEventListener("click", function () {
      const sessionId = this.getAttribute("data-session-id");
      const countSpan = this.querySelector(".likes-count");

      const action = likedPrograms.has(sessionId) ? "unlike" : "like";

      fetch("backend/like_program.php", {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
        },
        body: JSON.stringify({ session_id: sessionId, action }),
      })
        .then((res) => res.json())
        .then((data) => {
          if (data.success) {
            let count = parseInt(countSpan.innerText);
            if (action === "like") {
              likedPrograms.add(sessionId);
              countSpan.innerText = count + 1;
              this.innerHTML = `💔 <span class="likes-count">${count + 1}</span>`;
            } else {
              likedPrograms.delete(sessionId);
              countSpan.innerText = count - 1;
              this.innerHTML = `❤️ <span class="likes-count">${count - 1}</span>`;
            }
            localStorage.setItem("likedPrograms", JSON.stringify(Array.from(likedPrograms)));
          } else {
            alert("❌ Failed to update like status.");
          }
        })
        .catch((err) => console.error("Error updating like status:", err));
    });

    container.appendChild(card);
  });
}

function handleSearch() {
  const searchInput = document.getElementById("search-bar");
  const charCount = document.getElementById("char-count");
  const remaining = 50 - searchInput.value.length;
  charCount.innerText = `${remaining} characters remaining`;

  filterPrograms();
}

function resetFilters() {
  document.getElementById("search-bar").value = "";
  document.getElementById("difficulty-filter").value = "all";
  document.getElementById("type-filter").value = "all";
  document.getElementById("char-count").innerText = "50 characters remaining";
  filterPrograms();
}