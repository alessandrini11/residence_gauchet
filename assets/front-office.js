AOS.init();
const mobileNav = document.querySelector('#mobile-menu-front')
const openMobileMenu = document.querySelector('#menu-button-front')
const closeMobileMenu = document.querySelector('#mobile-menu-close')
const toTopButton = document.querySelector('#to-top-button')
if(mobileNav){
    openMobileMenu.addEventListener('click', () => {
        mobileNav.classList.toggle('-right-full')
    })
    closeMobileMenu.addEventListener('click', () => {
        mobileNav.classList.toggle('-right-full')
    })
}
if(toTopButton){
    toTopButton.addEventListener('click', () => {
        window.scrollTo({ top: 0, behavior: 'smooth' })
    })
}
window.addEventListener('scroll', (e) => {
    if(window.scrollY > 100){
        toTopButton.classList.remove('hidden')
        menu.classList.add("fixed")
    } else {
        toTopButton.classList.add('hidden')
        menu.classList.remove("fixed")
    }
})