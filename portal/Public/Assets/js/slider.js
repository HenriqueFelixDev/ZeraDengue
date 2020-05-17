document.addEventListener('DOMContentLoaded', () => {
    const previousHandler = document.querySelector('[data-handler=previous]')
    const nextHandler = document.querySelector('[data-handler=next]')
    const slider = document.querySelector('[data-slider=slider]')
    const sliderItems = document.querySelectorAll('[data-slider-item]')
    
    let index = 0

    const removeActiveDot = () => {
        const activeDot = document.querySelector('.slider-dot.active')
        if(!activeDot) return
        activeDot.classList.remove('active')
    }

    const addActiveToDot = () => {
        if (index == null || index == undefined || index < 0) return
        const dot = document.querySelector(`div[data-slider-item-index="${index}"]`)
        
        if(!dot) return
        dot.classList.add('active')
    }

    addActiveToDot()

    previousHandler.addEventListener('click', () => {
        if (--index < 0) index = sliderItems.length
        removeActiveDot()
        addActiveToDot()
        slider.style.transform = `translate(-${index * 100}%)`
        
    })

    nextHandler.addEventListener('click', () => {
        if (++index >= sliderItems.length) index = 0
        removeActiveDot()
        addActiveToDot()
        slider.style.transform = `translate(-${index * 100}%)`
    })
    
})