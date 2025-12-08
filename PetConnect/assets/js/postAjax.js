document.addEventListener("DOMContentLoaded", () => {

    // =============================
    // CREATE POST HANDLER
    // =============================
    const postCreateForm = document.getElementById("postCreateForm");

    if (postCreateForm) {
        postCreateForm.addEventListener("submit", function (e) {
            e.preventDefault();

            const formData = new FormData(postCreateForm);

            fetch("api/post/create_post.php", {
                method: "POST",
                body: formData
            })
                .then(res => res.json())
                .then(data => {

                    const msgBox = document.getElementById("postCreateMessage");

                    if (!data.success) {
                        Swal.fire("Error", data.message, "error");
                        // msgBox.innerHTML = `<div class="alert alert-danger">${data.message}</div>`;
                        return;
                    }
                    Swal.fire("", data.message, "success");
                    // msgBox.innerHTML = `<div class="alert alert-success">${data.message}</div>`;

                    setTimeout(() => {
                        window.location.href = "pet_detail.php?pet_id=" + formData.get("pet_id");
                    }, 1200);
                })
                .catch(err => {
                    console.error(err);
                });
        });
    }

    // =====================================================
    // LOAD ALL POSTS
    // =====================================================
    function loadAllPetPosts() {
        const container = document.getElementById("all-posts-container");
        if (!container) return;
        container.innerHTML = "<p>Loading posts...</p>";

        fetch("api/post/get_pet_post.php")
            .then(r => r.json())
            .then(data => {
                if (!data.success) {
                    container.innerHTML = "<p>Error loading posts.</p>";
                    return;
                }

                container.innerHTML = "<h2>All Pet Posts</h2>";

                if (data.posts.length === 0) {
                    container.innerHTML += "<p>No posts yet.</p>";
                    return;
                }

                data.posts.forEach(post => {
                    const html = `
                    <div class="card mb-3" data-post-id="${post.id}">
                        
                        ${post.image_path ? `<img src="${post.image_path}" class="card-img-top">` : ""}

                        <div class="card-body">
                            <h3>${post.title}</h3>
                            <p>${post.content}</p>

                            <small class="text-muted d-block mb-2">
                                Pet: ${post.pet_name} | Owner: ${post.owner_name} | ${post.created_at}
                            </small>

                            ${isLogged ? `
                                <button class="btn btn-primary btn-sm" data-action="like" data-post-id="${post.id}">
                                    Like
                                </button>

                                <span id="like-count-${post.id}">${post.likes_count}</span> Likes

                                <button class="btn btn-warning btn-sm" data-action="toggle-comments" data-post-id="${post.id}">
                                    Show comments
                                </button>

                                <button class="btn btn-secondary btn-sm" data-action="add-comment" data-post-id="${post.id}">
                                    Comment
                                </button>
                            ` : ""}

                            <div class="mt-3 comments-box" id="comments-${post.id}" style="display:none;"></div>
                        </div>
                    </div>
                    `;

                    container.insertAdjacentHTML("beforeend", html);
                });

            })
            .catch(err => {
                console.error(err);
                container.innerHTML = "<p>Error loading posts.</p>";
            });
    }

    // =====================================================
    // EVENT DELEGATION (ВСИЧКИ БУТОНИ ТУК)
    // =====================================================
    const allPostsContainer = document.getElementById("all-posts-container");

    if (allPostsContainer) {
    document.getElementById("all-posts-container").addEventListener("click", function (event) {

        const btn = event.target;

        // ----- LIKE BUTTON -----
        if (btn.dataset.action === "like") {
            const postId = btn.dataset.postId;

            fetch("api/post/like_post.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ post_id: postId })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        document.getElementById("like-count-" + postId).textContent = d.likes_count;
                    } else {
                        Swal.fire("Error", data.message, "error");
                        // alert("Error: " + d.message);
                    }
                });

            return;
        }

        // ----- ADD COMMENT -----
        if (btn.dataset.action === "add-comment") {
            const postId = btn.dataset.postId;

            const text = prompt("Enter your comment:");
            if (!text) return;

            fetch("api/post/comment_post.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({
                    post_id: postId,
                    comment: text
                })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        // alert("Comment added!");
                        Swal.fire("", "Comment added!", "success");

                        const box = document.getElementById("comments-" + postId);
                        if (box.style.display === "block") {
                            loadComments(postId);
                        }

                    } else {
                        // alert("Error: " + d.message);
                        Swal.fire("Error", d.message, "error");
                    }
                });

            return;
        }

        // ----- SHOW / HIDE COMMENTS -----
        if (btn.dataset.action === "toggle-comments") {
            const postId = btn.dataset.postId;
            const box = document.getElementById("comments-" + postId);

            if (box.style.display === "none") {
                loadComments(postId); // зарежда и показва
            } else {
                box.style.display = "none";
            }

            return;
        }


        // ----- DELETE COMMENT -----
        if (btn.dataset.action === "delete-comment") {
            const commentId = btn.dataset.commentId;

            if (!confirm("Delete this comment?")) return;

            fetch("api/post/delete_comment.php", {
                method: "POST",
                headers: { "Content-Type": "application/json" },
                body: JSON.stringify({ comment_id: commentId })
            })
                .then(r => r.json())
                .then(d => {
                    if (d.success) {
                        Swal.fire("", d.message, "success");
                        document.getElementById("comment-" + commentId).remove();
                    } else {
                        // alert("Error: " + d.message);
                        Swal.fire("Error", d.message, "error");
                    }
                });

            return;
        }

    });
    }

    // =====================================================
    // LOAD COMMENTS FUNCTION
    // =====================================================
    function loadComments(postId) {
        const box = document.getElementById("comments-" + postId);
        box.style.display = "block";
        box.innerHTML = "<p><em>Loading comments...</em></p>";

        fetch("api/post/get_comments.php?post_id=" + postId)
            .then(r => r.json())
            .then(data => {

                if (!data.success) {
                    box.innerHTML = "<p>Error loading comments.</p>";
                    return;
                }

                if (data.comments.length === 0) {
                    box.innerHTML = "<p>No comments yet.</p>";
                    return;
                }

                let html = "";
                data.comments.forEach(c => {
                    html += `
                    <div class="border p-2 mb-2 d-flex justify-content-between" id="comment-${c.id}">
                        <div>
                            <strong>${c.user_name}</strong>: ${c.comment}<br>
                            <small class="text-muted">${c.created_at}</small>
                        </div>

                        ${c.user_id == data.current_user ? `
                            <button class="btn btn-danger btn-sm" 
                                data-action="delete-comment"
                                data-comment-id="${c.id}">
                                Delete
                            </button>
                        ` : ""}
                    </div>`;
                });

                box.innerHTML = html;

            });
    }

    // =====================================================
    // INITIAL LOAD
    // =====================================================
    loadAllPetPosts();

});
