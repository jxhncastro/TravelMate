document.querySelector("#signupForm").addEventListener("submit", async (event) => {
    event.preventDefault();

    let name = document.getElementById("name").value;
    let email = document.getElementById("email").value;
    let password = document.getElementById("password").value;

    let response = await fetch("/api/auth/register.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify({ name, email, password })
    });

    let result = await response.json();
    alert(result.message || result.error);
});
