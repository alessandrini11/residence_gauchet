AOS.init();
const $ = require('jquery')

const menu = document.querySelector('#menu')
const menuBtnOpen = document.querySelector("#menu-button-open")
const mobileMenuBack = document.querySelector("#mobile-menu-backend")
const menuBtnClose = document.querySelector("#mobile-menu-close")
const toTopButton = document.querySelector("#to-top-button")
const imageUploader = document.getElementById("post_file")
const imagesUploader = document.getElementById("room_files")
const imageUploadDisplayer = document.getElementById("image-displayer")
const imagesUploadDisplayer = document.getElementById("images-displayer")
const imageUploadPreview = document.getElementById("image-preview")
const closeImagePreview = document.getElementById("close-image-preview")
// const menu = document.getElementById("menu")
const imagesDisplayer = $("#images-displayer")
const togglePostButton = $('#toggle-post')
const toogleUserButton = $('#toggle-user')
const fixedScroll = document.getElementById('fixed-scroll')
const fixedBar = document.getElementById('fixed-bar')

if(fixedScroll){
    window.addEventListener('scroll', (e) => {
        console.log(fixedScroll.scrollTo)
        if(fixedScroll.scrollTop == fixedScroll.scrollHeight){
            fixedBar.classList.remove('fixed')
        }
    })
    
}
const toogleEntity = (id, entity) => {
    fetch(
        `/admin/${entity}/approved/${id}`, {
            method: 'PATCH',
            headers: {
                'Content-Type': 'application/json'
            }
        }
    )
    .then(response => {
        location.reload(true)
    })
    .catch(error => console.log(error))
}

if(togglePostButton){
    const id = togglePostButton.val()
    togglePostButton.on('change',() => {
        toogleEntity(id, 'post')
    })
}

if(toogleUserButton){
    const id = toogleUserButton.val()
    toogleUserButton.on('change',() => {
        toogleEntity(id, 'user')
    })
}



if(closeImagePreview){
    closeImagePreview.addEventListener('click', () => {
        imageUploadPreview.src = ""
        imageUploader.value = ""
        imageUploadDisplayer.addClass("hidden")
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
    mobileMenuBack.classList.toggle("-left-full")
})

menuBtnClose.addEventListener('click', () => {
    mobileMenuBack.classList.toggle("-left-full")
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

$(document).ready(() => {
    $('#post_categories').select2()
    $('#room_type').select2()
    $('#room_facilities').select2()
    $('#user_roles').select2()
})
