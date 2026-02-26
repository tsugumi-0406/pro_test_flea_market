const likeBtns = document.querySelectorAll(".like-btn");

likeBtns.forEach((likeBtn) => {
    likeBtn.addEventListener("click", async (e) => {
        const icon = e.currentTarget; 

        const item_id = icon.dataset.itemId;

        await fetch(`/items/${item_id}/like`, {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document
                    .querySelector('meta[name="csrf-token"]')
                    .getAttribute("content"),
            },
        })
            .then((res) => res.json())
            .then((data) => {
                // いいね数更新
                icon.nextElementSibling.innerHTML = data.likes_count;

                // アイコン即反映
                if (icon.classList.contains("text-red-500")) {
                    icon.classList.remove("text-red-500");
                    icon.setAttribute("name", "heart-outline");
                } else {
                    icon.classList.add("text-red-500");
                    icon.setAttribute("name", "heart");
                }
            })
            .catch(() => alert("いいね処理が失敗しました"));
    });
});
