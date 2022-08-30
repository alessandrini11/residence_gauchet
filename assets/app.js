const $ = require('jquery')
const selectMultiple = require('select2')
/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

const menuBtnOpen = document.getElementById("menu-button")
const mobileMenu = document.getElementById("mobile-menu")
const menuBtnClose = document.getElementById("mobile-menu-close")
const toTopButton = document.getElementById("to-top-button")
const imageUploader = document.getElementById("post_image")
const imagesUploader = document.getElementById("room_images")
const imageUploadDisplayer = document.getElementById("image-displayer")
const imagesUploadDisplayer = document.getElementById("images-displayer")
const imageUploadPreview = document.getElementById("image-preview")
const closeImagePreview = document.getElementById("close-image-preview")
const imagesDisplayer = $("#images-displayer")

const menu = document.getElementById("menu")

if(closeImagePreview){
    closeImagePreview.addEventListener('click', () => {
        imageUploadPreview.src = ""
        imageUploader.value = ""
        imageUploadDisplayer.classList.add("hidden")
    })
}

if (imagesUploader) {
    imagesUploader.addEventListener('change', (e) => {
        imagesUploadDisplayer.classList.remove("hidden")
        let fileObj = [];
        let images = []
        fileObj.push(e.target.files);
        for (let i = 0; i < fileObj[0].length; i++) {
            let imagesUploadPreview = $("<div></div>").addClass('relative')
            let image = $("<img />").addClass("w-full aspect-square rounded-md")
            
            
            image.attr("src", URL.createObjectURL(fileObj[0][i]))
            console.log(image)
            imagesUploadPreview.append(image)
            images.push(imagesUploadPreview)
        }
        images.map(image => {
            imagesDisplayer.append(image)
        })
    })
}

if (imageUploader) {
    imageUploader.addEventListener('change', (e) => {
        imageUploadDisplayer.classList.remove("hidden")
        imageUploadPreview.src = URL.createObjectURL(e.target.files[0])
    })
}
menuBtnOpen.addEventListener('click', () => {
    mobileMenu.classList.toggle("-left-full")
})

menuBtnClose.addEventListener('click', () => {
    mobileMenu.classList.toggle("-left-full")
})

toTopButton.addEventListener('click', () => {
    window.scrollTo({ top: 0, behavior: 'smooth' })
})

window.addEventListener('scroll', (e) => {
    if(menu){
        if(window.scrollY > 100){
            toTopButton.classList.remove('hidden')
            menu.classList.add("fixed")
        } else {
            toTopButton.classList.add('hidden')
            menu.classList.remove("fixed")
        }
    }
})

$(document).ready(() => {
    $('#post_categories').select2()
    $('#room_type').select2()
    $('#room_facilities').select2()
    $('#user_roles').select2()
})


// any CSS you import will output into a single css file (app.css in this case)
import './styles/app.css';

// start the Stimulus application
import './bootstrap';
