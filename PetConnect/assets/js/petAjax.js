document.addEventListener("DOMContentLoaded", () => {
    // ---------------------------
    // SIGNUP
    // ---------------------------
    const signupBtn = document.getElementById("signup-submit");
    if (signupBtn) {
        signupBtn.addEventListener("click", () => {
            const box = document.getElementById("signup-form");
            let data = new FormData();

            data.append("name", box.querySelector("[name=name]").value);
            data.append("email", box.querySelector("[name=email]").value);
            data.append("password", box.querySelector("[name=password]").value);
            data.append("password_confirm", box.querySelector("[name=password_confirm]").value);

            let recaptchaResponse = '';
            if (typeof grecaptcha !== "undefined") {
                recaptchaResponse = grecaptcha.getResponse();
            }
            data.append("g-recaptcha-response", recaptchaResponse);

            fetch("api/auth/registration.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(d => {
                    // alert(d.message);
                    
                    if (d.success) {
                        // Swal.fire("", d.message, "success");
                         window.location.href = "pet_post.php";
                    }
                    else
                    {
                        Swal.fire("Error", d.message, "error");
                        grecaptcha.reset();
                    }
                });
        });
    }

    // ---------------------------
    // LOGIN
    // ---------------------------
    const loginBtn = document.getElementById("login-submit");
    if (loginBtn) {
        loginBtn.addEventListener("click", () => {
            const box = document.getElementById("login-form");
            let data = new FormData();

            data.append("email", box.querySelector("[name=email]").value);
            data.append("password", box.querySelector("[name=password]").value);

            let recaptchaResponse = '';
            if (typeof grecaptcha !== "undefined") {
                recaptchaResponse = grecaptcha.getResponse();
            }
            data.append("g-recaptcha-response", recaptchaResponse);

            fetch("api/auth/login.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(d => {
                    // alert(d.message);
                    if (d.success) 
                    {
                        // Swal.fire("", d.message, "success");
                        window.location.href = "index.php";
                    }
                    else
                    {   
                        Swal.fire("Error", d.message, "error");
                        grecaptcha.reset();
                    }
                })
                .catch(err => {
                    // alert('Error during login.');
                    Swal.fire("Error", 'Error during login.', "error");
                });
        });
    }

    // =============================
    // LOAD PET LIST
    // =============================
    const petListContainer = document.getElementById("pet-list-container");

    if (petListContainer) {
        fetch("api/pet/get_pet_list.php")
            .then(res => res.json())
            .then(data => {
                if (!data.success) {
                    petListContainer.innerHTML = "<p>Error loading pets.</p>";
                    return;
                }

                petListContainer.innerHTML = ""; 

                data.pets.forEach(pet => {
                    const image = pet.image_path || "https://via.placeholder.com/300x200?text=No+Image";
                    const html = `
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100">
                                <img src="${image}" class="card-img-top" alt="${pet.pet_name}">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        ${pet.type_name ?? ""} ${pet.pet_name}
                                        ${pet.age ? ", Age: " + pet.age : ""}
                                    </h5>
                                    <p class="card-text">${pet.description ?? ""}</p>
                                </div>
                                <div class="card-footer d-flex justify-content-between align-items-center">
                                    <div class="btn-group">
                                        <a href="pet_detail.php?pet_id=${pet.id}" class="btn btn-primary">See details</a>
                                        ${pet.user_id === loggedUserId
                                        ? `<a href="post_create.php?pet_id=${pet.id}" class="btn btn-success">Create post</a>`
                                        : ``}
                                    </div>
                                    <small class="text-muted">by ${pet.owner_name ?? "Unknown"}</small>
                                </div>
                            </div>
                        </div>
                    `;
                    petListContainer.insertAdjacentHTML("beforeend", html);
                });
            })
            .catch(err => {
                console.error(err);
                petListContainer.innerHTML = "<p>Error loading pets.</p>";
            });
    }

    // ---------------------------
    // CREATE PET
    // ---------------------------
    const createBtn = document.getElementById("pet-create-submit");
    if (createBtn) {
        createBtn.addEventListener("click", (e) => {
            e.preventDefault();
            const box = document.getElementById("pet-create-form");

            const name = box.querySelector("[name=name]").value.trim();
            const type = box.querySelector("[name=type]").value;
            const age = box.querySelector("[name=age]").value.trim();
            const description = box.querySelector("[name=description]").value.trim();
            const imageFile = box.querySelector("[name=image]").files[0];

            if (!name || !type) {
                Swal.fire("Error", "Please provide both pet name and type.", "error");
                // alert("Please provide both pet name and type.");
                return;
            }

            if (!age || !/^\d+$/.test(age) || parseInt(age) <= 0) {
                Swal.fire("Error", "Age must be a positive number.", "error");
                return;
            }

            if (!imageFile) {
                Swal.fire("Error", "Please select an image file for the pet.", "error");
                return;
            }

            let data = new FormData();
            data.append("name", name);
            data.append("type", type);
            data.append("age", age);
            data.append("description", description);

            if (imageFile) data.append("image", imageFile);

            fetch("api/pet/add_pet.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(d => {
                    // alert(d.message);
                    if (d.success) {
                        //Swal.fire("", d.message, "success");
                        window.location.href = "pet_list.php";
                    }
                    else {
                        Swal.fire("Error", d.message, "error");
                    }
                })
                .catch(err => {
                    console.error(err);
                    // alert("Error while creating pet.");
                    Swal.fire("Error", "Error while creating pet.", "error");
                });
        });
    }

    // =============================
    // PET DETAILS
    // =============================
    function loadPosts(petId, isOwner) {
        const postsContainer = document.getElementById("pet-posts-container");
        postsContainer.innerHTML = ''; 

        fetch("api/pet/get_pet_details.php?pet_id=" + encodeURIComponent(petId))
            .then(r => r.json())
            .then(d => {
                if (!d.success) {
                    postsContainer.innerHTML = "<p>Error loading posts</p>";
                    return;
                }

                let postsHtml = `<h3 class="mb-3">Posts</h3>`;

                if (d.posts.length === 0) {
                    postsHtml += `<p>No posts yet.</p>`;
                } else {
                    d.posts.forEach(post => {
                        let likesHtml = `
        <div class="d-flex align-items-center mb-2">
            <button class="btn btn-sm btn-primary" data-post-id="${post.id}">
                ❤️ ${post.like_count}
            </button>
        </div>
    `;

                        let commentsHtml = '<div class="comments-section">';
                        post.comments.forEach(c => {
                            commentsHtml += `<p><strong>${c.user_name}:</strong> ${c.comment} <small class="text-muted">${c.created_at}</small></p>`;
                        });
                        commentsHtml += '</div>';

                        postsHtml += `
        <div class="card mb-3">
            ${post.image_path ? `<img src="${post.image_path}" class="card-img-top">` : ""}
            <div class="card-body">
                <h3>${post.title}</h3>
                <p>${post.content}</p>
                <small class="text-muted d-block mb-2">${post.created_at}</small>
                ${likesHtml}
                ${commentsHtml}
                ${isOwner ? `
                    <button class="btn btn-danger btn-sm delete-post-btn" data-post-id="${post.id}">Delete post</button>
                ` : ""}
            </div>
        </div>
    `;
                    });
                }
                postsContainer.innerHTML = postsHtml;

                postsContainer.querySelectorAll(".like-btn").forEach(btn => {
                    btn.addEventListener("click", function () {
                        const postId = this.dataset.postId;
                        fetch("handlers/like_post_handler.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ post_id: postId })
                        })
                            .then(r => r.json())
                            .then(resp => {
                                if (resp.success) {
                                    loadPosts(petId, isOwner);
                                }
                            });
                    });
                });

                postsContainer.querySelectorAll(".delete-post-btn").forEach(btn => {
                    btn.addEventListener("click", function () {
                        const postId = this.dataset.postId;
                        if (!confirm("Do you really want to delete this post?")) return;

                        fetch("api/post/delete_post.php", {
                            method: "POST",
                            headers: { "Content-Type": "application/json" },
                            body: JSON.stringify({ post_id: postId })
                        })
                            .then(r => r.json())
                            .then(resp => {
                                if (resp.success) {
                                    Swal.fire("", "Post deleted!", "success");
                                    loadPosts(petId, isOwner);
                                } else {
                                    Swal.fire("Error", resp.message, "error");
                                }
                            })
                            .catch(() => Swal.fire("Error", "Request failed.", "error"));
                    });
                });
            });
    }

    // ======================
    // Initial load
    // ======================
    const petDetailBox = document.getElementById("pet-detail-container");
    if (petDetailBox) {
        const petId = petDetailBox.dataset.petId;

        fetch("api/pet/get_pet_details.php?pet_id=" + encodeURIComponent(petId))
            .then(r => r.json())
            .then(d => {
                if (!d.success) {
                    petDetailBox.innerHTML = "<h3>Pet not found</h3>";
                    return;
                }

                const p = d.pet;
                let html = `
                <div class="card mb-4">
                    <img src="${p.image_path || 'assets/no-image.png'}" class="card-img-top" alt="${p.name}">
                    <div class="card-body">
                        <h2 class="card-title">${p.name}</h2>
                        <p><strong>Type:</strong> ${p.type_name}</p>
                        <p><strong>Age:</strong> ${p.age}</p>
                        <p><strong>Description:</strong> ${p.description}</p>
                        <p><strong>Owner:</strong> ${p.owner_name} (${p.owner_email})</p>
                    </div>
                    <div class="card-footer">
                        ${d.isOwner ? `
                            <a class="btn btn-warning mr-2" href="pet_edit.php?pet_id=${p.id}">Edit</a>
                            <button class="btn btn-danger" data-page="pet_delete" data-pet-id="${p.id}">Delete</button>
                            <a class="btn btn-success ml-2" href="post_create.php?pet_id=${p.id}">Create post</a>
                        ` : ""}
                    </div>
                </div>
            `;
                petDetailBox.innerHTML = html;

                loadPosts(petId, d.isOwner);
            });
    }


    // ---------------------------
    // EDIT PET
    // ---------------------------
    const inEditForm = document.getElementById("pet-edit-form");
    if (inEditForm) {
        const petId = inEditForm.querySelector("[name=pet_id]").value;

        fetch("api/pet/get_pet_details.php?pet_id=" + encodeURIComponent(petId))
            .then(r => r.json())
            .then(d => {
                if (!d.success) return Swal.fire("Error", "Pet not found", "error");

                const pet = d.pet;
                inEditForm.querySelector("[name=name]").value = pet.name;
                inEditForm.querySelector("[name=type_id]").value = pet.type_id;
                inEditForm.querySelector("[name=age]").value = pet.age;
                inEditForm.querySelector("[name=description]").value = pet.description;

                if (pet.image_path) {
                    const img = document.getElementById("pet-current-image");
                    img.src = pet.image_path;
                    img.style.display = "block";
                }
            });
    }


    const outEditForm = document.getElementById("pet-edit-form");
    if (outEditForm) {
        outEditForm.addEventListener("submit", (ev) => {
            ev.preventDefault();

            const name = outEditForm.querySelector("[name=name]").value.trim();
            const type = outEditForm.querySelector("[name=type_id]").value;
            const age = outEditForm.querySelector("[name=age]").value.trim();
            const description = outEditForm.querySelector("[name=description]").value.trim();
            const imageFile = outEditForm.querySelector("[name=image]").files[0];

            if (!name || !type) {
                Swal.fire("Error", "Please provide both pet name and type.", "error");
                return;
            }

            if (!age || !/^\d+$/.test(age) || parseInt(age) <= 0) {
                Swal.fire("Error", "Age must be a positive number.", "error");
                return;
            }

            if (imageFile) {
                const allowed = ["image/jpeg", "image/png", "image/webp", "image/gif"];
                if (!allowed.includes(imageFile.type)) {
                    Swal.fire("Error", "Please upload a valid image file.", "error");
                    return;
                }
            }

            let formData = new FormData(outEditForm);
            fetch("api/pet/edit_pet.php", {
                method: "POST",
                body: formData
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        window.location.href = "pet_detail.php?pet_id=" + formData.get("pet_id");
                    } else {
                        Swal.fire("Error", d.message, "error");
                    }
                })
                .catch(err => {
                    Swal.fire("Error", "Server error.", "error");
                });
        });

    }
    // ---------------------------
    // DELETE PET
    // ---------------------------
    document.body.addEventListener("click", (e) => {
        const btn = e.target.closest("[data-page='pet_delete']");
        if (!btn) return;

        e.preventDefault();

        if (!confirm("Are you sure you want to delete this pet?")) return;

        const petId = btn.dataset.petId;
        let data = new FormData();
        data.append("pet_id", petId);

        fetch("api/pet/delete_pet.php", {
            method: "POST",
            body: data
        })
            .then(r => r.json())
            .then(d => {
                // alert(d.message);
                if (d.success) {
                    Swal.fire("", d.message, "success");
                    window.location.href = "pet_list.php";
                }
            })
            .catch(err => {
                console.error(err);
                Swal.fire("Error", "Error deleting pet.", "error");
                // alert("Error deleting pet.");
            });
    });


    // ---------------------------
    // COMMENT PET
    // ---------------------------
    const commentBtn = document.getElementById("comment-submit");
    if (commentBtn) {
        commentBtn.addEventListener("click", () => {
            const box = document.getElementById("comment-box");
            let data = new FormData();
            data.append("pet_id", commentBtn.dataset.petId);
            data.append("comment", box.querySelector("[name=comment]").value);

            fetch("handlers/comment_pet_handler.php", {
                method: "POST",
                body: data
            })
                .then(r => r.json())
                .then(d => {
                    // alert(d.message);
                    if (d.success) {
                        Swal.fire("", d.message, "success");
                        window.location.reload();
                    }
                });
        });
    }

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

});
