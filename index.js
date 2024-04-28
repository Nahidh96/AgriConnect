document.addEventListener("DOMContentLoaded", function() {
    const sidebarOpenBtn = document.querySelector(".sidebar-open-btn");
    const sidebarCloseBtn = document.querySelector(".sidebar-close-btn");
    const sidebar = document.querySelector(".sidebar");

    sidebarOpenBtn.addEventListener("click", function() {
        sidebar.style.left = "0"; // Open the sidebar
    });

    sidebarCloseBtn.addEventListener("click", function() {
        sidebar.style.left = "-300px"; // Close the sidebar
    });
});
