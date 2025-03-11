function openCity(evt, cityName) {
    var i, tabcontent, tablinks;

    // Get all elements with class="tabcontent" and hide them
    tabcontent = document.getElementsByClassName("tabcontent");
    for (i = 0; i < tabcontent.length; i++) {
        tabcontent[i].style.display = "none";
    }

    // Remove "active" class from all tab buttons
    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].classList.remove("active");
        tablinks[i].setAttribute("aria-selected", "false");
    }

    // Show the selected tab and set the active button
    document.getElementById(cityName).style.display = "block";
    evt.currentTarget.classList.add("active");
    evt.currentTarget.setAttribute("aria-selected", "true");
}

// Ensure the "Items" tab is shown by default when the page loads
window.onload = function () {
    document.getElementById("Items").style.display = "block";
    var defaultTab = document.getElementsByClassName("tablinks")[0]; // First tab (Items)
    defaultTab.classList.add("active");
    defaultTab.setAttribute("aria-selected", "true");
};

function handleSelection() {
    var selection = document.getElementById("footerDropdown").value;

    if (selection === "addItem") {
        // Redirect to the page to add an item (or trigger a modal, etc.)
        window.location.href = "add-item.html";  // Change this to your actual URL for adding an item
    } else if (selection === "createOutfit") {
        // Redirect to the page to create an outfit
        window.location.href = "create-outfit.html";  // Change this to your actual URL for creating an outfit
    } else if (selection === "createCollection") {
        // Redirect to the page to create a collection
        window.location.href = "create-collection.html";  // Change this to your actual URL for creating a collection
    }
}