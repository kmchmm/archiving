/*Dashboard Navigation Burger Button*/
const menuBtn = document.querySelector('.burgerBtn');
let menuOpen = false;
menuBtn.addEventListener('click', () =>{
	if(!menuOpen) {
		menuBtn.classList.add('open');
		menuOpen = true;
	} else {
		menuBtn.classList.remove('open');
		menuOpen = false;
	} 
});


/*Sidebar menu active*/
const sidebarMenu = document.querySelector('.sidebar');
const profileright = document.querySelector('.profile-right');
menuBtn.onclick = function() {
	sidebarMenu.classList.toggle('active');
	profileright.classList.toggle('active');
}



/* THEME SELECTION */

const themeSelect = document.getElementById("themeSelect");
const themeStylesheet = document.getElementById("themeStylesheet");

themeSelect.addEventListener("change", function (){
  themeStylesheet.setAttribute("href", "cssfolders/" +this.value + ".css");
}); 