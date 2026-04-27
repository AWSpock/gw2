var menu = document.querySelector("nav");
var menu_link = menu.children[0];
var menu_list = menu.children[1];

menu_link.addEventListener("click", toggleMenu);

function toggleMenu(e) {
  var startHeight = 0;
  var endHeight = menu_list.scrollHeight;
  if (menu_list.offsetHeight != "0") {
    startHeight = menu_list.offsetHeight;
    endHeight = 0;
  }

  menu_list.animate(
    [{ height: startHeight + "px" }, { height: endHeight + "px" }],
    {
      duration: 500,
      easing: "ease-in-out",
      fill: "forwards",
    },
  );
}

//

document.querySelectorAll("nav li a").forEach((link) => {
  var curPage = window.location.pathname;
  var compare = new URL(link.href).pathname;
  if (compare === curPage) link.classList.add("active");
});
