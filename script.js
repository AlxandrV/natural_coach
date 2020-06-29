const form = document.querySelector("#reqAjaxSubmit");
const reponse_ajax = document.getElementById("req_ajax");
const clone = document.getElementById("clone");

document.getElementById("reqAjaxSubmit").addEventListener('submit', function(e){
    e.preventDefault();

    let formData = new FormData(form);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", "ajout.php", true);
    xhr.send(formData);
    xhr.addEventListener("readystatechange", () => {
        if (xhr.readyState === 3) {
            console.log("ça charge");
        }
        if (xhr.readyState === 4 && xhr.status === 200) {
            let datas = JSON.parse(xhr.responseText);
            req_ajax.innerHTML = "";
            req_ajax.appendChild(datas);
        }
    });
});
