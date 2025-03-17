document.addEventListener("DOMContentLoaded", loadTrips);

function loadTrips() {
    fetch('read.php')
        .then(response => response.json())
        .then(data => {
            let container = document.getElementById("trips-container");
            if (!data || data.length === 0) {
                container.innerHTML = `<p>You don’t have any trips yet</p>
                                       <button class="btn btn-primary" onclick="openCreateTripModal()">Create a Trip</button>`;
            } else {
                let html = '<div class="trip-list">';
                data.forEach(trip => {
                    html += `<div class="trip">
                                <h4>${trip.title}</h4>
                                <p>${trip.description}</p>
                                <p>${trip.start_date} to ${trip.end_date}</p>
                                <button class="btn btn-warning" onclick="editTrip(${trip.id})">Edit</button>
                                <button class="btn btn-danger" onclick="deleteTrip(${trip.id})">Delete</button>
                            </div>`;
                });
                html += '</div>';
                container.innerHTML = html;
            }
        })
        .catch(error => console.error("Error loading trips:", error));
}

function openCreateTripModal() {
    document.getElementById("createTripModal").style.display = "block";
}

function closeCreateTripModal() {
    document.getElementById("createTripModal").style.display = "none";
}

function createTrip() {
    const title = document.getElementById("tripTitle").value;
    const description = document.getElementById("tripDescription").value;
    const start_date = document.getElementById("tripStartDate").value;
    const end_date = document.getElementById("tripEndDate").value;

    fetch('create.php', {
        method: 'POST',
        body: JSON.stringify({ title, description, start_date, end_date }),
        headers: { 'Content-Type': 'application/json' }
    }).then(() => {
        closeCreateTripModal();
        loadTrips();
    }).catch(error => console.error("Error creating trip:", error));
}

function deleteTrip(id) {
    if (confirm("Are you sure you want to delete this trip?")) {
        fetch('delete.php', {
            method: 'POST',
            body: JSON.stringify({ id }),
            headers: { 'Content-Type': 'application/json' }
        }).then(() => loadTrips())
          .catch(error => console.error("Error deleting trip:", error));
    }
}
