document.querySelector("#loginForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let response = await fetch("/api/login.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ email, password })
    });

    let result = await response.json();
    if (result.user) {
        localStorage.setItem("user", JSON.stringify(result.user));
        window.location.href = "profile.html"; 
    } else {
        alert(result.error);
    }
});
