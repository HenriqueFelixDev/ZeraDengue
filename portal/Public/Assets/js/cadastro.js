let i = 0
var phones = document.getElementById('phones')

window.onload = function() {
    phones = document.getElementById('phones')
    const phonesCount = document.querySelectorAll('#phones [data-index]').length
    i = phonesCount
}



function addPhone() {
    let newPhone =  '<div id="phone'+i+'" data-index="'+i+'" class="row"> '+
    '<div class="col-2">' +
        '<input type="tel" name="ddd[]" placeholder="DDD" maxlength="2" required />' +
    '</div>' +
    '<div class="col"> ' +
        '<input type="tel" name="tel[]" id="tel'+i+'" placeholder="Telefone" maxlength="11" required />' +
    '</div>' +
    '<div class="col">' +
        '<button type="button" class="btn-remove-phone" onclick="removePhone('+i+')"><i class="fa fa-minus"></i></button>' +
    '</div>'
'</div>';
    phones.innerHTML = phones.innerHTML + (newPhone)
}

function removePhone(id) {
    let removedPhone = document.getElementById('phone'+id)
    
    const removedPhoneEl = document.createElement('input')
    removedPhoneEl.setAttribute('type', 'hidden')
    removedPhoneEl.setAttribute('name', 'telefones_removidos[]')
    removedPhoneEl.setAttribute('value', removedPhone.querySelector(`[name='telefone_id[${id}]']`).value)
    i--
    removedPhone.remove()
    phones.appendChild(removedPhoneEl)

}