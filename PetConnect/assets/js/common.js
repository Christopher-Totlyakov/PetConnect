document.addEventListener("DOMContentLoaded", () => {
    $("#logoutBtn").on("click", function (e) {
        e.preventDefault();
        $.post("logout.php",
            function (response) {
                let data = JSON.parse(response);
                if (data.status == "success")
                    location.href = "index.php";
            }
        )
    })
});