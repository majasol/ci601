document.addEventListener("DOMContentLoaded", function () {
    const profileImg = document.getElementById("profile-img");
    const dropdownMenu = document.getElementById("dropdown-menu");

    profileImg.addEventListener("click", function () {
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", function (event) {
        if (!profileImg.contains(event.target) && !dropdownMenu.contains(event.target)) {
            dropdownMenu.style.display = "none";
        }
    });
});

document.addEventListener('DOMContentLoaded', () => {
    // Fetch the authentication status from the server
    fetch('/auth-status')
        .then(response => response.json())
        .then(data => {
            const authAction = document.getElementById("auth-action");

            if (data.isAuthenticated) {
                authAction.textContent = 'Logout';
                authAction.setAttribute("href", "/logout");
            } else {
                authAction.textContent = 'Login';
                authAction.setAttribute("href", "/login");
            }
        })
        .catch(error => console.error('Error fetching authentication status:', error));
});

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

// Fetch data from the server
fetch('/get_items.php')  // Change from /items to /get_items.php
    .then(response => response.json())
    .then(data => {
        const container = document.getElementById('items-container');
        container.innerHTML = ''; // Clear existing items

        let leftColumn = document.createElement('div');
        let rightColumn = document.createElement('div');
        leftColumn.classList.add('item-column');
        rightColumn.classList.add('item-column');

        data.forEach((item, index) => {
            let itemHTML = `
            <div class="item">
                <img src="${item.image_url}" alt="${item.name}" />
                <p>${item.name}</p>
            </div>
        `;

            if (index % 2 === 0) {
                leftColumn.innerHTML += itemHTML;
            } else {
                rightColumn.innerHTML += itemHTML;
            }
        });

        container.appendChild(leftColumn);
        container.appendChild(rightColumn);
    })
    .catch(error => {
        console.error('Error fetching items:', error);
    });

fetch('/add_item.php', {
    method: 'POST',
    body: formData
})




document.addEventListener("DOMContentLoaded", () => {
    const addItemBtn = document.getElementById("add-item-btn");
    const modal = document.getElementById("upload-form");
    const closeModal = modal.querySelector(".close");

    // Show modal when "Add Item" is clicked
    addItemBtn.addEventListener("click", (event) => {
        event.preventDefault();
        modal.style.display = "flex"; // Change from "block" to "flex" for centering
    });

    // Hide modal when close button is clicked
    closeModal.addEventListener("click", () => {
        modal.style.display = "none";
    });

    // Close modal when clicking outside of the form
    window.addEventListener("click", (event) => {
        if (event.target === modal) {
            modal.style.display = "none";
        }
    });
});



// Get the modal and the button to open it
const modal = document.getElementById("upload-form");
const btn = document.getElementById("add-item-btn");
const span = document.getElementsByClassName("close")[0];

// When the user clicks the "Add item" button, open the modal
btn.onclick = function () {
    modal.style.display = "block";
};

// When the user clicks on the close button, close the modal
span.onclick = function () {
    modal.style.display = "none";
};

// When the user clicks anywhere outside the modal, close it
window.onclick = function (event) {
    if (event.target == modal) {
        modal.style.display = "none";
    }
};

// Handle form submission
document.getElementById("item-form").onsubmit = function (event) {
    event.preventDefault();

    const formData = new FormData();
    formData.append("name", document.getElementById("item-name").value);
    formData.append("image", document.getElementById("item-image").files[0]);
    formData.append("category", document.getElementById("item-category").value);
    formData.append("sub_category", document.getElementById("item-sub-category").value);
    formData.append("color", document.getElementById("item-color").value);
    formData.append("times_used", document.getElementById("item-times-used").value);
    formData.append("cost", document.getElementById("item-cost").value);

    fetch('/add-item', {
        method: 'POST',
        body: formData
    })
        .then(response => {
            if (response.ok) {
                alert("Item added successfully!");
                modal.style.display = "none"; // Close modal after submission
                // You can also refresh the items list or perform other UI updates here
            } else {
                alert("Error adding item");
            }
        })
        .catch(error => {
            console.error("Error:", error);
            alert("Error adding item");
        });
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