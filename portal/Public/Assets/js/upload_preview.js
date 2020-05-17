document.addEventListener('DOMContentLoaded', () => {
    const fotosList = document.querySelector('#fotos-list')
    const fotoEl = document.querySelector('#fotos')
    
    fotoEl.addEventListener('change', () => {
        const addPhotoButton = document.querySelector('#fotos-list label.photo')
        fotosList.innerHTML = ''

        for(let i = 0; i < fotoEl.files.length; i++) {
            addPhoto(fotoEl.files[i], fotosList)
            fotosList.appendChild(addPhotoButton)
        }
        
    })
})


const addPhoto = (photo, element) => {
    const reader = new FileReader()
    const foto = document.createElement('div')
    foto.classList.add('photo')

    reader.onloadstart = () => {
        const progress = document.createElement('div')
        progress.classList.add('circular-progress-indicator')
        foto.appendChild(progress)
        element.prepend(foto)
    }

    reader.onloadend = () => {
        foto.innerHTML = ''
        foto.style.background = `url(${reader.result}) center center/contain no-repeat`
    }

    reader.readAsDataURL(photo)
}