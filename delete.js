$('#exampleModalCenter').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    console.log(button);

    button[0].classList.add('ready-to-delete');
    button[0].setAttribute('type', 'submit');
});
function confirmDelete(){
    /*const modal = document.getElementById('exampleModalCenter');
    modal.classList.toggle('hidden');*/
    //Selection du lien ayant la class ready-to-delete
    const elementsToDelete = document.getElementsByClassName('ready-to-delete');
    console.log(elementsToDelete);
    
    for( let elementToDelete of elementsToDelete){
        if ( document.createEvent ) {
            var evt = document.createEvent('MouseEvents');
            evt.initEvent('click', true, false);
            elementToDelete.dispatchEvent(evt);

        } else if( document.createEventObject ) {
            elementToDelete.fireEvent('onclick') ; 

        } else if (typeof elementToDelete.onclick == 'function' ) {
            elementToDelete.onclick(); 
        }        
    }
}