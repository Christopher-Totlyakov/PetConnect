// =============================
// LOAD USER INFO & PETS
// =============================
document.addEventListener("DOMContentLoaded", () => {
    const userInfoBox = document.getElementById("user-info");
    const userPetsBox = document.getElementById("user-pets-container");

    if (userInfoBox && userPetsBox) {
        fetch("api/user/get_user_info.php")
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    userInfoBox.innerHTML = "<p>Error loading user info.</p>";
                    userPetsBox.innerHTML = "<p>Unable to load pets.</p>";
                    return;
                }

                const user = data.user;
                userInfoBox.innerHTML = `
                    <p><strong>Name:</strong> ${user.name}</p>
                    <p><strong>Email:</strong> ${user.email}</p>
                    <p><strong>Member since:</strong> ${user.created_at}</p>
                `;

                userPetsBox.innerHTML = ""; 
                if (data.pets.length === 0) {
                    userPetsBox.innerHTML = "<p>You have no pets yet.</p>";
                } else {
                    data.pets.forEach(pet => {
                        const image = pet.image_path || "https://via.placeholder.com/300x200?text=No+Image";
                        const html = `
                            <div class="col-lg-4 col-md-6 mb-4">
                                <div class="card h-100">
                                    <img src="${image}" class="card-img-top" alt="${pet.name}">
                                    <div class="card-body">
                                        <h5 class="card-title">
                                            ${pet.type_name ?? ""} ${pet.name}
                                            ${pet.age ? ", Age: " + pet.age : ""}
                                        </h5>
                                        <p class="card-text">${pet.description ?? ""}</p>
                                    </div>
                                    <div class="card-footer">
                                        <a href="pet_detail.php?pet_id=${pet.id}" class="btn btn-primary">See details</a>
                                    </div>
                                </div>
                            </div>
                        `;
                        userPetsBox.insertAdjacentHTML("beforeend", html);
                    });
                }
            })
            .catch(err => {
                console.error(err);
                userInfoBox.innerHTML = "<p>Error loading user info.</p>";
                userPetsBox.innerHTML = "<p>Error loading pets.</p>";
            });
    }
});
