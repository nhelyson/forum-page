if(document.getElementById("form-signup")){

  const form = document.getElementById("form-signup");
  const word = document.getElementById("password");
  const pass = document.getElementById("signup-confirm-password");
  const error_password = document.getElementById("error-password");
  
  form.addEventListener("submit", function (e) {
    const password = word.value;
    const confirmPassword = pass.value;
  
    error_password.textContent = "";
    word.style.borderColor = "";
    pass.style.borderColor = "";
  
    if (password.length <=8) {
      e.preventDefault();
      error_password.textContent = "Le mot de passe doit contenir au moins 8 caractères.";
      word.style.borderColor = "red";
      word.style.borderWidth = "3px";
      return;
    }
  
    if (password !== confirmPassword) {
      e.preventDefault();
      error_password.textContent = "Les mots de passe ne correspondent pas.";
      error_password.style.fontSize = "12px";
      error_password.style.fontFamily = "Arial, sans-serif";
      word.style.borderColor = "orange";
      word.style.borderWidth = "3px";
      pass.style.borderColor = "red";
      pass.style.borderWidth = "3px";
    } else {
      word.style.borderColor = "green";
      pass.style.borderColor = "green";
    }
  });
}

if(document.getElementById("birthdate")){
const input = document.getElementById("birthdate");
input.type= "text"
input.addEventListener("focus", () => {
  input.type = "date";
});

input.addEventListener("blur", () => {
  if (input.value === "") {
    input.type = "text";
  }
});
}

if(document.getElementById('input_describe')){
const form_describe = document.getElementById('input_describe')
const input_textrea = document.getElementById('discribe-edit')
form_describe.addEventListener('submit', function(e){
  let content = input_textrea.value
  let error_textrea = document.querySelector('.error_texterea')
   if(content.length >500){
    e.preventDefault();
    input_textrea.style.borderColor = 'red'
    error_textrea.textContent = 'vous avez atteint la limite de mots autorise'
    error_textrea.style.fontSize = '12px'
    error_textrea.style.color = 'red'
   }else{
      input_textrea.style.borderColor = 'green'
   }
})

}
// calcule et affichage de la taille de l'image
if(document.getElementById('input_file')){
  const input_files = document.getElementById('input_file')
  const content_img = document.getElementById('content-form')
  input_files.addEventListener('change', function(event){
  const file = event.target.files[0]
  const label = document.querySelector('.label-file')
  const format = document.getElementById('format')
  const name = document.getElementById('name_file')
  const fileSize = file.size; 
  let fileunit = '';
  let result = 0;
  let reception = '';
  const fileMo = 1024 * 1024;
  const fileKO = 1024;
  const file5mo = 8 * fileMo;
  if (fileSize < fileMo) { 
    fileunit = 'ko';
    result = fileSize / fileKO; // en Ko
  } else {
    fileunit = 'Mo';
    result = fileSize / fileMo; // en Mo
  }
  const url = URL.createObjectURL(file);
  const img_prv = document.createElement('img');
  content_img.appendChild(img_prv);

if (file) {
  img_prv.src = url;
  label.style.display = 'none';
  format.style.display = 'none';

  // Comparaison correcte
  if (fileSize >= file5mo) {
    img_prv.remove();
    name.textContent = `L'image dépasse la taille autorisée de 5Mo`;
    name.style.color = 'red';
    name.style.fontSize = '12px';
    name.style.fontFamily = 'Arial, sans-serif';
    label.style.display = 'block';
    format.style.display = 'block';
  } else {

    reception = result.toFixed(2);
    name.textContent = `Nom de l'image : ${file.name}, Taille : ${reception} ${fileunit}`;
  }
}

})

}
const input_search = document.querySelector('.input-search-mobile')
const input_search_bouton = document.getElementById('bouton-input')
input_search_bouton.addEventListener('click', function(){
   input_search.style.display = 'flex'
})