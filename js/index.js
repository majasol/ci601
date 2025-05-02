document.addEventListener("DOMContentLoaded", function () {
    const tabButtons = document.querySelectorAll(".tablinks");
    const tabContents = document.querySelectorAll(".tabcontent");

    tabButtons.forEach(button => {
        button.addEventListener("click", function () {
            const targetId = button.getAttribute("data-tab-target");

            tabContents.forEach(tab => {
                tab.style.display = "none";
            });

            tabButtons.forEach(btn => {
                btn.classList.remove("active");
            });

            document.getElementById(targetId).style.display = "block";
            button.classList.add("active");

            // Load content if needed
            if (targetId === "Outfits") {
                loadOutfits();
            }
        });
    });

    const defaultTab = document.querySelector(".tablinks[data-tab-target='Items']");
    if (defaultTab) defaultTab.click();

    const profileImg = document.getElementById("profile-img");
    const dropdownMenu = document.getElementById("dropdown-menu");

    // 🔽 Profile dropdown toggle
    profileImg?.addEventListener("click", () => {
        dropdownMenu.style.display = dropdownMenu.style.display === "block" ? "none" : "block";
    });

    document.addEventListener("click", (event) => {
        if (!profileImg?.contains(event.target) && !dropdownMenu?.contains(event.target)) {
            dropdownMenu.style.display = "none";
        }
    });

    // 🔐 Auth status
    fetch('../auth/auth-status.php')
        .then(res => res.json())
        .then(data => {
            const authAction = document.getElementById("auth-action");
            if (authAction) {
                if (data.isAuthenticated) {
                    authAction.textContent = 'Logout';
                    authAction.href = "../auth/logout.php";
                } else {
                    authAction.textContent = 'Login';
                    authAction.href = "../auth/login.php";
                }
            }
        })
        .catch(err => console.error("Auth status error:", err));

    const addItemBtn = document.getElementById("add-item-btn");
    const modal = document.getElementById("upload-form");
    const closeModal = modal?.querySelector(".close");

    addItemBtn?.addEventListener("click", (e) => {
        e.preventDefault();
        modal.style.display = "flex";
    });

    closeModal?.addEventListener("click", () => {
        modal.style.display = "none";
    });

    window.addEventListener("click", (e) => {
        if (e.target === modal) {
            modal.style.display = "none";
        }
    });

    const itemForm = document.getElementById("item-form");
    if (itemForm) {
        itemForm.addEventListener("submit", (e) => {
            e.preventDefault();
            const formData = new FormData(itemForm);

            fetch('../backend/add_item.php', {
                method: 'POST',
                body: formData,
                credentials: 'include'
            })
            .then(res => res.text())
            .then(text => {
                try {
                    const data = JSON.parse(text);
                    if (data.success) {
                        alert(data.message);
                        modal.style.display = "none";
                        loadItems(); // Refresh item list
                    } else {
                        alert("Error: " + data.message);
                    }
                } catch (err) {
                    console.error("JSON parse error:", err, "\nResponse was:", text);
                    alert("Error adding item.");
                }
            })
            .catch(err => {
                console.error("Submit error:", err);
                alert("Error adding item.");
            });
        });
    }

    let outfitsLoaded = false;
    document.querySelectorAll(".tablinks").forEach(btn => {
        btn.addEventListener("click", function (e) {
            const target = this.dataset.tabTarget;

            if (target === "Outfits" && !outfitsLoaded) {
                loadOutfits();
                outfitsLoaded = true;
            }
        });
    });

    function loadItems() {
        fetch('../backend/get_items.php', { credentials: 'include' })
            .then(res => res.json())
            .then(data => {
                console.log("Items response:", data);

                const container = document.getElementById("items-container");
                if (!container) return;

                container.innerHTML = '';

                if (data.success && Array.isArray(data.items) && data.items.length > 0) {
                    const left = document.createElement("div");
                    const right = document.createElement("div");
                    left.className = right.className = "item-column";

                    data.items.forEach((item, i) => {
                        const html = `
                            <div class="item">
                                <img src="${item.image_url}" alt="${item.name}" />
                                <p>${item.name}</p>
                            </div>`;
                        (i % 2 === 0 ? left : right).innerHTML += html;
                    });

                    container.appendChild(left);
                    container.appendChild(right);
                } else {
                    container.textContent = "No items found.";
                }
            })
            .catch(err => {
                console.error("Item fetch error:", err);
            });
    }

    // 👗 Load outfits
    function loadOutfits() {
        fetch('../backend/get_outfits.php', { credentials: 'include' })
            .then(response => response.json())
            .then(data => {
                const container = document.getElementById('Outfits');
                container.innerHTML = ''; // Clear previous

                if (data.success && Array.isArray(data.outfits)) {
                    if (data.outfits.length === 0) {
                        container.innerHTML = '<p>No outfits found.</p>';
                    } else {
                        data.outfits.forEach(outfit => {
                            const outfitDiv = document.createElement('div');
                            outfitDiv.classList.add('outfit');

                            outfitDiv.innerHTML = `
                                <h4>${outfit.name}</h4>
                                <p>Created at: ${outfit.created_at}</p>
                                <div class="outfit-images">
                                    ${outfit.top_url ? `<img src="${outfit.top_url}" alt="Top">` : ''}
                                    ${outfit.bottom_url ? `<img src="${outfit.bottom_url}" alt="Bottom">` : ''}
                                    ${outfit.shoes_url ? `<img src="${outfit.shoes_url}" alt="Shoes">` : ''}
                                </div>
                            `;

                            container.appendChild(outfitDiv);
                        });
                    }
                } else {
                    container.innerHTML = '<p>Error loading outfits.</p>';
                    console.error('Failed to load outfits:', data);
                }
            })
            .catch(error => {
                console.error('Fetch error:', error);
            });
    }

    loadItems();
});
