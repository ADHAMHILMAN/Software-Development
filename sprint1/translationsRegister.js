// translationsRegister.js

// Translation dictionary for each text in different languages for the register page
const registerTranslations = {
    en: {
        registerTitle: "Register",
        welcomeText: "Join the Flat Jubli Perak Community!",
        registerButton: "Register",
        loginLink: "Already have an account? <a href='login.php'>Login here</a>"
    },
    my: {
        registerTitle: "Daftar",
        welcomeText: "Sertai Komuniti Flat Jubli Perak!",
        registerButton: "Daftar",
        loginLink: "Sudah ada akaun? <a href='login.php'>Log masuk di sini</a>"
    }
};

// Function to update text on the register page based on selected language
function updateRegisterLanguage(selectedLang) {
    document.getElementById("registerTitle").textContent = registerTranslations[selectedLang].registerTitle;
    document.getElementById("welcomeText").textContent = registerTranslations[selectedLang].welcomeText;
    document.getElementById("registerButton").textContent = registerTranslations[selectedLang].registerButton;
    document.getElementById("loginLink").innerHTML = registerTranslations[selectedLang].loginLink;
}

// Event listener for language selector dropdown on the registration page
document.getElementById("languageSelect").addEventListener("change", function() {
    const selectedLanguage = this.value;
    updateRegisterLanguage(selectedLanguage);
});

// Set default language on page load
document.addEventListener("DOMContentLoaded", () => {
    const defaultLanguage = "en"; // Default to English
    document.getElementById("languageSelect").value = defaultLanguage;
    updateRegisterLanguage(defaultLanguage);
});
