// Var qui contiendra la valeur submit du button
let submit_button;

// Récupération du button cliquer
$('#exampleModalCenter').on('shown.bs.modal', function (event) {
    var button = $(event.relatedTarget);
    console.log(button);
    submit_button = button[0];
    button[0].classList.add('ready-to-delete');
});

// Fonction de suppression
function confirmDelete(){
    // Selection du lien ayant la class ready-to-delete
    const elementsToDelete = document.getElementsByClassName('ready-to-delete');
    console.log(elementsToDelete);
    
    for( let elementToDelete of elementsToDelete){
        if ( document.createEvent ) {
            submit_button.setAttribute('type', 'submit');
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