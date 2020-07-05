// const form = document.querySelector("#reqAjaxSubmit");

// document.getElementById("reqAjaxSubmit").addEventListener('submit', function(e){
//     e.preventDefault();
    
//     let formData = new FormData(form);
    
//     const xhr = new XMLHttpRequest();
//     xhr.open("POST", "ajout.php", true);
//     xhr.send(formData);
//     xhr.addEventListener("readystatechange", () => {
//         if (xhr.readyState === 3) {
//             console.log("ça charge");
//         }
//         if (xhr.readyState === 4 && xhr.status === 200) {
//             let datas = JSON.parse(xhr.responseText);
//             console.log(datas);
//             // Balise template et récupération de son contenu vide
//             const template = document.querySelector("#reponse_ajax");
//             const clone = document.importNode(template.content, true);
//             // Récupération des balise 'span' à l'intérieur et injection avec les valeurs de datas
//             const spans = clone.querySelectorAll('span');
//             spans[0].innerText  = datas[1];
//             spans[1].innerText  = datas[2];
//             spans[2].innerText  = datas[3];
//             spans[3].innerText  = datas[4];
//             spans[4].innerText  = datas[6];
//             spans[5].innerText  = datas[8];
//             // Récupération du boutton supprimer et injection de value datas
//             const button = clone.querySelector('button');
//             button.value = datas[0];
//             // Récupération de link et injection de value datas
//             const link = clone.querySelector('a');
//             link.setAttribute('href', `excursion.php?n=${datas[0]}`);
//             // Récupération de 'ul' et injection de 'clone' dedans
//             const liste = document.getElementById("list");
//             liste.appendChild(clone);
//         }
//     });
// });
