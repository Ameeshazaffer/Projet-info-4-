document.addEventListener("DOMContentLoaded", () => {
    const chgmode = document.getElementById("chgmode");
    const btnchgmode = document.getElementById("btnchgmode");

    if (chgmode) {
        if (document.cookie.includes("theme=sombre")) {
            chgmode.setAttribute("href", "sombre.css");
        } else {
            chgmode.setAttribute("href", "styles.css");
        }
    }

    if (btnchgmode && chgmode) {
        btnchgmode.addEventListener("click", fonctionchgmode);
    }

    function fonctionchgmode() {
        if (chgmode.getAttribute("href") === "styles.css") {
            chgmode.setAttribute("href", "sombre.css");
            document.cookie = "theme=sombre; max-age=31536000; path=/";
        } else {
            chgmode.setAttribute("href", "styles.css");
            document.cookie = "theme=clair; max-age=31536000; path=/";
        }
    }
});
