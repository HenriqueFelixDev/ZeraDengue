const container = document.querySelector('[data-modal=container]')
const modal = document.querySelector('[data-modal=modal]')
const closeButtons = document.querySelectorAll('[data-modal-action=close]')
const title = document.querySelector('#modal-header #title')
const content = document.querySelector('#modal-content')
const modalFooter = document.querySelector('#modal-footer')
const containerBtnOK = document.querySelector('#modal-footer #container-btn-ok')
const containerBtnYesCancel = document.querySelector('#modal-footer #container-btn-yes-cancel')
const btnYes = document.querySelector('[data-modal-action=yes]')
const btnCancel = document.querySelector('[data-modal-action=cancel]')

let mouseInModal = false

const modObj = {
    title: 'Testando o Modal',
    content: '<b>Mensagem</b>',
    buttonType: 'yes_cancel',
    onConfirm: () => {console.log('Deu')},
    onCancel: () => {}
}

const showModal = (modalObj) => {
    container.classList.remove("modal-hide")
    title.innerHTML = modalObj.title
    content.innerHTML = modalObj.content
    
    switch (modalObj.buttonType) {
        case 'yes_cancel':
            containerBtnOK.style.display = 'none'
            containerBtnYesCancel.style.display = 'block'
            modalFooter.style.display = 'block';
            btnYes.onclick = modalObj.onConfirm
            btnCancel.onclick = modalObj.onCancel
            break;
        case 'none':
            modalFooter.style.display = 'none';
            break;
        default:
            containerBtnYesCancel.style.display = 'none'
            containerBtnOK.style.display = 'block'
            modalFooter.style.display = 'block';
    }
    

}

const closeModal = () => {
    container.classList.add("modal-hide")
}

container.onclick = () => {
    if (!mouseInModal)
        closeModal()
}

modal.onmouseenter = () => {
    mouseInModal = true
}

modal.onmouseleave = () => {
    mouseInModal = false
}

closeButtons.forEach((closeButton) => {
    closeButton.onclick = closeModal
})