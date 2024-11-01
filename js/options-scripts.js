setTimeout(() => {
    const acc = document.getElementsByClassName("sp_accordion");
    let i;

    for (i = 0; i < acc.length; i++) {
        acc[i].addEventListener("click", function() {
            /* Toggle between adding and removing the "active" class,
            to highlight the button that controls the panel */
            this.classList.toggle("sp_active");

            /* Toggle between hiding and showing the active panel */
            let panel = this.nextElementSibling;

            if (panel.style.maxHeight) {
                panel.style.maxHeight = null;
            } else {
                panel.style.maxHeight = panel.scrollHeight + "px";
            }
        });

        if (acc[i].classList.contains('sp_active')) {
            acc[i].nextElementSibling.style.maxHeight = "620px";
        } else {
            acc[i].nextElementSibling.style.maxHeight = null;
        }
    }
}, 0);