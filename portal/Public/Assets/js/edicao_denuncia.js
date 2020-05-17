document.addEventListener('DOMContentLoaded', () => {
    const apiKey = 'tIMJ9_pz8Vv5Pv-kna6_HJgBFdQESEoQLssdF0PWZCA'
    const latEl = document.querySelector("#lat")
    const lngEl = document.querySelector("#lng")

    const platform = new H.service.Platform({
        'apikey': apiKey
    })

    let defaultLayers = platform.createDefaultLayers()

    const map = new H.Map(
        document.querySelector('#map'),
        defaultLayers.vector.normal.map,
        {
            zoom: 14,
            center: {lat: latEl.value ? latEl.value : -20.8662453, lng: lngEl.value ? lngEl.value : -43.4920001}
        }
    )

    let ui = H.ui.UI.createDefault(map, defaultLayers)


    const local = document.querySelector('#localizacao')
    let currentSuggestion

    const removeSuggestionsElement = () => {
        const suggestionsEl = document.querySelector('#suggestions')
        if (suggestionsEl) {
            suggestionsEl.remove()
        }
    }

    const getSuggestions = (suggestions) => {
        if (suggestions) {
            removeSuggestionsElement()

            const suggestionsEl = document.createElement('div')
            suggestionsEl.setAttribute('id', 'suggestions')

            suggestions.forEach((suggestion) => {
                const opt = document.createElement('div')
                opt.classList.add('suggestion')
                opt.innerHTML = '<i class="fa fa-map-marker"></i> ' + suggestion.label
                opt.onclick = (evt) => {
                    currentSuggestion = suggestion
                    local.value = opt.textContent.trim()
                    searchLocationInfo(suggestion.locationId)
                    removeSuggestionsElement()
                }
                suggestionsEl.appendChild(opt)
            })

            document.querySelector('#suggestion-container').appendChild(suggestionsEl)
        } else {
            removeSuggestionsElement()
        }
    }

    const searchSuggestions = (query) => {
        const xhttp = new XMLHttpRequest()
        xhttp.open('GET', 'https://autocomplete.geocoder.ls.hereapi.com/6.2/suggest.json?apiKey=' + apiKey + '&query=' + query + '&beginHighlight=<mark>&endHighlight=</mark>', true)
        xhttp.send()

        xhttp.onreadystatechange = () => {
            if(xhttp.readyState === 4 && xhttp.status == 200) {
                getSuggestions(JSON.parse(xhttp.response).suggestions)
            }
        }
    }

    const searchLocationInfo = (locationId) => {
        const xhttp = new XMLHttpRequest()
        xhttp.open('GET', 'https://geocoder.ls.hereapi.com/6.2/geocode.json?locationid=' + locationId + '&jsonattributes=1&gen=9&apiKey=' + apiKey)
        xhttp.send()

        xhttp.onreadystatechange = () => {
            if(xhttp.readyState === 4 && xhttp.status == 200) {
                const response = JSON.parse(xhttp.response).response
                const location = response.view[0].result[0].location.displayPosition
                map.setCenter({lat: location.latitude, lng: location.longitude})
                latEl.value = location.latitude
                lngEl.value = location.longitude
            }
        }
    }

    local.addEventListener('input', () => searchSuggestions(local.value))

    
})