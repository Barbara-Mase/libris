export function showResponsiveMenu() {
    const menu = document.getElementById("topnav-menu-burger");
    const icon = document.getElementById("topnav-hamburger-icon");
    const root = document.getElementById("root");
    if(menu.className === "") {
        menu.className = "open";
        icon.className = "open";
        root.style.overflowY = "hidden";
    } else {
        menu.className = "";
        icon.className = "";
        root.style.overflowY = "";
    }
}