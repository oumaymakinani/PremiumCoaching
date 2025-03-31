document.addEventListener("DOMContentLoaded", () => {
    loadUserProfile();
    setupCloseModalButton();

    document.getElementById("manage-bookings").addEventListener("click", () => {
        fetch("backend/get_coaches.php")
            .then(res => res.json())
            .then(coaches => {
                const coachSelect = document.getElementById("coach-select");
                coachSelect.innerHTML = "";

                coaches.forEach(coach => {
                    const opt = document.createElement("option");
                    opt.value = coach.coach_id;
                    opt.innerText = coach.name;
                    coachSelect.appendChild(opt);
                });

                const coachId = coachSelect.value;
                loadAvailableSlots(coachId);
                loadSessions(coachId);

                document.getElementById("bookingModal").style.display = "block";
            });
    });

    document.getElementById("coach-select").addEventListener("change", function () {
        loadAvailableSlots(this.value);
        loadSessions(this.value);
    });

    document.getElementById("session-select").addEventListener("change", updateSessionPreview);

    document.getElementById("available-slot-select").addEventListener("change", checkBookingFormValidity);
    document.getElementById("session-select").addEventListener("change", checkBookingFormValidity);
    document.getElementById("coach-select").addEventListener("change", checkBookingFormValidity);

    document.getElementById("bookingForm").addEventListener("submit", function (e) {
        e.preventDefault();

        const formData = new FormData(this);
        formData.append("user_id", localStorage.getItem("user_id"));

        fetch("backend/book_session.php", {
            method: "POST",
            body: formData
        })
            .then(res => res.json())
            .then(data => {
                alert(data.success || "❌ " + data.error);
                document.getElementById("bookingModal").style.display = "none";
                location.reload();
            });
    });
});

function loadUserProfile() {
    fetch("backend/get_profile.php")
        .then(res => res.json())
        .then(data => {
            if (data.error) {
                alert(data.error);
                window.location.href = "LoginPage.html";
                return;
            }

            const { full_name, role, email, phone } = data.user;

            document.getElementById("profile-name").innerText = full_name;
            document.getElementById("profile-role").innerText = role;
            document.getElementById("user-email").innerText = email;
            document.getElementById("user-phone").innerText = phone;

            const coachDiv = document.getElementById("coach-booking-container");
            if (data.booked_coach) {
                coachDiv.innerHTML = `
                    <div class="coach-card">
                        <img src="coach.jpg" alt="Coach Profile" class="coach-img">
                        <div class="coach-details">
                            <p><strong>Coach:</strong> ${data.booked_coach.name}</p>
                            <p><strong>Specialty:</strong> ${data.booked_coach.specialization}</p>
                            <p><strong>Next Session:</strong> ${data.booked_coach.session_date}</p>
                        </div>
                    </div>`;
            } else {
                coachDiv.innerHTML = "<p>No coach booked yet.</p>";
            }

            const list = document.getElementById("training-sessions");
            list.innerHTML = "";
            data.upcoming_sessions.forEach(session => {
                const li = document.createElement("li");
                li.innerText = `${session.session_date} - ${session.title}`;
                list.appendChild(li);
            });
        });
}

function loadAvailableSlots(coachId) {
    fetch(`backend/get_availabilities.php?coach_id=${coachId}`)
        .then(res => res.json())
        .then(slots => {
            const slotSelect = document.getElementById("available-slot-select");
            slotSelect.innerHTML = "";

            slots.forEach(slot => {
                const opt = document.createElement("option");
                opt.value = slot.availability_id;
                opt.innerText = `${slot.available_date} - ${slot.start_time} to ${slot.end_time}`;
                slotSelect.appendChild(opt);
            });

            checkBookingFormValidity();
        });
}

function loadSessions(coachId) {
    fetch(`backend/get_sessions.php?coach_id=${coachId}`)
        .then(res => res.json())
        .then(sessions => {
            const sessionSelect = document.getElementById("session-select");
            sessionSelect.innerHTML = "";

            if (!Array.isArray(sessions)) {
                console.error("Invalid sessions response", sessions);
                return;
            }

            sessions.forEach(session => {
                const opt = document.createElement("option");
                opt.value = session.session_id;
                opt.setAttribute("data-duration", session.duration);
                opt.setAttribute("data-description", session.description);
                opt.innerText = session.title;
                sessionSelect.appendChild(opt);
            });

            updateSessionPreview();
            checkBookingFormValidity();
        });
}

function updateSessionPreview() {
    const select = document.getElementById("session-select");
    const selected = select.options[select.selectedIndex];
    const priceDisplay = document.getElementById("price-estimate");

    let priceBox = priceDisplay;
    if (!priceBox) {
        priceBox = document.createElement("p");
        priceBox.id = "price-estimate";
        priceBox.style.marginTop = "10px";
        priceBox.style.fontWeight = "bold";
        select.parentNode.appendChild(priceBox);
    }

    const duration = parseInt(selected.getAttribute("data-duration"));
    if (isNaN(duration)) {
        priceBox.innerText = `Estimated Duration: N/A | Estimated Price: N/A`;
    } else {
        const price = (duration * 0.5).toFixed(2);
        priceBox.innerText = `Duration: ${duration} min | Estimated Price: $${price}`;
    }
}

function checkBookingFormValidity() {
    const coach = document.getElementById("coach-select").value;
    const slot = document.getElementById("available-slot-select").value;
    const session = document.getElementById("session-select").value;
    const submitBtn = document.querySelector("#bookingForm button[type='submit']");

    submitBtn.disabled = !(coach && slot && session);
}

function setupCloseModalButton() {
    const closeBtn = document.getElementById("closeBookingModal");
    closeBtn.addEventListener("click", () => {
        document.getElementById("bookingModal").style.display = "none";
    });
}
