// Get current theme
let currentTheme = document.querySelector("html").getAttribute("data-bs-theme");
// Theme value stored in local storage
let storedTheme = localStorage.getItem("theme-value");

// Set theme from local storage
if (storedTheme == null) {
    // Set dark in theme-value as default
    localStorage.setItem("theme-value", currentTheme);
} else {
    if (storedTheme == "auto" || storedTheme == "light") {
        document.querySelector("html").setAttribute("data-bs-theme", "light");
        document.querySelector("#themeSwitcher > i").classList.remove("bi-moon-stars");
        document.querySelector("#themeSwitcher > i").classList.add("bi-brightness-high");
    } else if (storedTheme == "dark") {
        document.querySelector("html").setAttribute("data-bs-theme", "dark");
        document.querySelector("#themeSwitcher > i").classList.remove("bi-brightness-high");
        document.querySelector("#themeSwitcher > i").classList.add("bi-moon-stars");
    }
}

// Change theme on click
document.querySelector("#themeSwitcher").addEventListener("click", function () {
    let themeAttr = "data-bs-theme";
    let currentTheme = document.querySelector("html").getAttribute(themeAttr);

    if (currentTheme == "light") {
        document.querySelector("#themeSwitcher").setAttribute("data-bs-title", "Change to dark theme");
        document.querySelector("#themeSwitcher > i").classList.remove("bi-brightness-high");
        document.querySelector("#themeSwitcher > i").classList.add("bi-moon-stars");
        document.querySelector("html").setAttribute(themeAttr, "dark");
        localStorage.setItem("theme-value", "dark");
    } else if (currentTheme == "dark") {
        document.querySelector("#themeSwitcher").setAttribute("data-bs-title", "Change to light theme");
        document.querySelector("#themeSwitcher > i").classList.remove("bi-moon-stars");
        document.querySelector("#themeSwitcher > i").classList.add("bi-brightness-high");
        document.querySelector("html").setAttribute(themeAttr, "light");
        localStorage.setItem("theme-value", "light");
    }
});

// To trigger the tooltip
const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
tooltipTriggerList.forEach((tooltipTriggerEl) => new bootstrap.Tooltip(tooltipTriggerEl));