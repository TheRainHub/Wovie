document.addEventListener("DOMContentLoaded", () => {
    const header = document.getElementById("main-header");
    const blurOverlay = document.getElementById("blur-overlay");
    const searchInput = document.getElementById("search-input");
    const searchBar = document.getElementById("search-bar");
    let lastScrollY = window.scrollY;

    // Handle header hide/show on scroll
    window.addEventListener("scroll", () => {
        if (window.scrollY > lastScrollY) {
            header.classList.add("hidden");
        } else {
            header.classList.remove("hidden");
        }
        lastScrollY = window.scrollY;
    });

    // Handle search bar focus/blur
    searchInput.addEventListener("focus", () => {
        blurOverlay.style.display = "block";
        searchBar.classList.add("focused"); 
    });

    searchInput.addEventListener("blur", () => {
        blurOverlay.style.display = "none";
        searchBar.classList.remove("focused"); 
    });
});

