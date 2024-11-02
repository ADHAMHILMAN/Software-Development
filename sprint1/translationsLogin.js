// translations.js

// Translation dictionary for each text in different languages
const translations = {
    en: {
        loginTitle: "Login",
        welcomeText: "Welcome to Flat Jubli Perak Community!",
        loginButton: "Login",
        registerTitle: "Register",
        registerButton: "Register",
        registerLink: "Don't have an account? <a href='register.php'>Register now!</a>"
    },
    my: {
        loginTitle: "Log Masuk",
        welcomeText: "Selamat datang ke Komuniti Flat Jubli Perak!",
        loginButton: "Log Masuk",
        registerTitle: "Daftar",
        registerButton: "Daftar",
        registerLink: "Belum ada akaun? <a href='register.php'>Daftar sekarang!</a>"

    }
};

// Function to update text based on selected language
function updateLanguage(selectedLang) {
    console.log("Updating language to:", selectedLang); // Debug log

    document.getElementById("loginTitle").textContent = translations[selectedLang].loginTitle;
    document.getElementById("welcomeText").textContent = translations[selectedLang].welcomeText;
    document.getElementById("loginButton").textContent = translations[selectedLang].loginButton;
    document.getElementById("registerLink").innerHTML = translations[selectedLang].registerLink;
    document.getElementById("registerTitle").textContent = translations[selectedLang].registerTitle;
    document.getElementById("registerButton").textContent = translations[selectedLang].registerButton;
    
    /* const registerLink = document.getElementById("registerLink");
    console.log("registerLink element found:", registerLink);

    if (registerLink) {
        registerLink.innerHTML = translations[selectedLang].registerLink;
        console.log("registerLink updated to:", registerLink.innerHTML);
    } else {
        console.log("registerLink element not found");
    }*/

}

// Event listener for language selector dropdown
document.getElementById("languageSelect").addEventListener("change", function() {
    const selectedLanguage = this.value;
    updateLanguage(selectedLanguage);
});

// Set default language on page load
document.addEventListener("DOMContentLoaded", () => {
    const defaultLanguage = "en"; // Default to English
    document.getElementById("languageSelect").value = defaultLanguage;
    updateLanguage(defaultLanguage);
});