AOS.init();
const menuBtnOpen = document.getElementById("menu-button")
const mobileMenu = document.getElementById("mobile-menu")
const menuBtnClose = document.getElementById("mobile-menu-close")
const toTopButton = document.getElementById("to-top-button")
const menu = document.getElementById("menu")

menuBtnOpen.addEventListener('click', () => {
    mobileMenu.classList.toggle("-right-full")
})

menuBtnClose.addEventListener('click', () => {
    mobileMenu.classList.toggle("-right-full")
})

toTopButton.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

window.addEventListener('scroll', (e) => {
    if(window.scrollY > 100){
        toTopButton.classList.remove('hidden')
        menu.classList.add("fixed")
    } else {
        toTopButton.classList.add('hidden')
        menu.classList.remove("fixed")
    }
})