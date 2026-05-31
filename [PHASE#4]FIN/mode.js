document.addEventListener("DOMContentLoaded", () => {
    const chgmode = document.getElementById("chgmode");
    const btnchgmode = document.getElementById("btnchgmode");

    const cookieTheme = document.cookie
        .split('; ')
        .find(row => row.startsWith('theme='))
        ?.split('=')[1];

    if (chgmode) {
        chgmode.setAttribute("href", cookieTheme === "sombre" ? "stylessombre.css" : "styles.css");
        btnchgmode.textContent = cookieTheme === "sombre" ? "☀️" : "🌙";
    }

    if (btnchgmode && chgmode) {
        btnchgmode.addEventListener("click", () => {
            const estSombre = chgmode.getAttribute("href").includes("stylessombre");
            if (estSombre) {
                chgmode.setAttribute("href", "styles.css");
                document.cookie = "theme=clair; max-age=31536000; path=/";
                btnchgmode.textContent = "🌙";
            } else {
                chgmode.setAttribute("href", "stylessombre.css");
                document.cookie = "theme=sombre; max-age=31536000; path=/";
                btnchgmode.textContent = "☀️";
            }
        });
    }
});
