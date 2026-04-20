</div> <!-- content -->

<script>
function toggleSidebar() {
    document.getElementById("sidebar").classList.toggle("collapsed");
}

function toggleSub(id, el) {
    let menu = document.getElementById(id);

    if (menu.style.display === "block") {
        menu.style.display = "none";
        el.querySelector(".bi-chevron-right").classList.remove("rotate");
    } else {
        menu.style.display = "block";
        el.querySelector(".bi-chevron-right").classList.add("rotate");
    }
}
function toggleSub(id, el) {

    let menu = document.getElementById(id);

    if (menu.style.display === "block") {
        menu.style.display = "none";
        el.querySelector(".bi-chevron-right").classList.remove("rotate");
    } else {
        menu.style.display = "block";
        el.querySelector(".bi-chevron-right").classList.add("rotate");
    }
}
</script>

</body>
</html>