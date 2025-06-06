 
const tabs = document.querySelectorAll(".tab");
const formContainers = document.querySelectorAll(".form-container");

tabs.forEach((tab) => {
  tab.addEventListener("click", () => {
    tabs.forEach((t) => t.classList.remove("active"));
    tab.classList.add("active");
    const tabId = tab.dataset.tab;
    formContainers.forEach((container) => {
      container.style.display = container.id === tabId ? "block" : "none";
    });
  });
});

const navLinks = document.querySelectorAll(".nav-links");
const pages = document.querySelectorAll(".page");
const sidebar = document.getElementById("sidebar");

navLinks.forEach((link) => {
  link.addEventListener("click", (event) => {
    event.preventDefault();
    const pageId = link.dataset.page;

    navLinks.forEach((navLink) => navLink.classList.remove("active"));
    pages.forEach((page) => (page.style.display = "none"));

    document.getElementById(pageId).style.display = "block";
    link.classList.add("active");
  });
});
 

// dark mode
function dark_mode() {
  let bouton_mode = document.getElementById("toggle-dark");
  let body = document.body;
  let moon = document.querySelector(".moon")
  let sun = document.querySelector(".sun")
  if(bouton_mode){
  bouton_mode.addEventListener("click", function () {
    body.classList.toggle("dark-mode");
    if (body.classList.contains("dark-mode")) {
        moon.style.display = 'block'; 
      sun.style.display = 'none';
      bouton.style.backgroundColor="transparent"
      bouton.style.border="none"

      }
      else {
        moon.style.display = 'none';  
        sun.style.display = 'block';
    }
    
  })

}
}

dark_mode();
// faire appraitre le menu sur des taille inferieur

let boutondark = document.getElementById('navbar-toggler');
let menu = document.getElementById('menu-right');
let content = document.querySelector('.content');
let body = document.body;
let background = document.getElementById('background-menu');
let bouton_input = document.getElementById('bouton-input');

if (boutondark) {
  boutondark.addEventListener("click", function () {
    menu.classList.toggle('active');
    background.classList.toggle('blur-background');
    body.classList.toggle('menu-open');
    bouton_input.classList.toggle('btn-none');
  });
}

// desactivation du menu verticale gauche si l'utilisateur clique sur un onglet sur mobile
if(document.getElementById('menu-right')){
let nav_links = document.querySelectorAll('.nav-links');
let menu_remove = document.getElementById('menu-right');
let background_remove = document.getElementById('background-menu')

 nav_links.forEach(function(link){
  link.addEventListener("click",function(){
    menu_remove.classList.remove('active')
    background_remove.classList.remove('blur-background')
  })
 })
 if(menu_remove.classList.contains('active')){
 window.addEventListener("click" , function(){
  menu.classList.remove('active');
 })
}
}

if(document.querySelector('.input-search')){
let tabs_seconde = document.getElementById('tab-2')
let input_see = document.querySelector('.input-search')

tabs_seconde.addEventListener('click' , function(){
  input_see.disabled = true;
  input_see.style.cursor = "not-allowed";
  title.textContent = "manga page"
})
}

if(document.querySelector('.input-search')){
let tabs_first = document.getElementById('tab-1')
let input_see_remove = document.querySelector('.input-search')
let title = document.getElementById('color')
tabs_first.addEventListener("click" , function(){
  input_see_remove.disabled = false
  input_see.style.cursor = "pointer"
  title.textContent = "I see you"
})
}

if( document.getElementById('welcome-popup')){
  function modifierURLSucces() {
    let nouvelleUrl = window.location.origin + window.location.pathname + "?succes=ok";
    window.history.replaceState({}, '', nouvelleUrl);
    document.getElementById('welcome-popup').style.display = 'none';
    body.style.overflowY = 'scroll';
    body.style.overflowX = 'hidden';

  }
function closePopup() {
  modifierURLSucces()
}

window.onload = function() {
  const urlParams = new URLSearchParams(window.location.search);
  const success = urlParams.get('success');
  const type = urlParams.get('type');
  const body = document.body;

  if (success == '1' && type === 'inscription') {
    // Affiche la popup si success=1
    document.getElementById('welcome-popup').style.display = 'flex';
    body.style.overflow = 'hidden';

  } else if (success == '1' && type === 'connexion') {
    document.getElementById('welcome-popup').style.display = 'flex';
    const text_change = document.querySelector('.change-text');
    const type_auth = document.querySelector('.type_auth');
    const  bouton_pop = document.createElement('button')
    let bouton_pop_2 = document.createElement('button')
    bouton_pop_2.textContent = "Fermer";
    bouton_pop_2.addEventListener('click',   closePopup)
    let reserve = document.querySelector('.reserve')
    bouton_pop_2.textContent = "Fermer";
    text_change.textContent = "Tu t'es souvenu de moi ? c'est sympa,";
    body.style.overflow = 'hidden';
  }
}

}

if(document.getElementById('upload')){
  let previousImageSrc = null;
  let previewback = null;
  const image = document.getElementById("imagePreview")
  function previewImage(event) {
      const imagePreview = document.getElementById("imagePreview");
      const backImage = document.getElementById("back-image");
        previousImageSrc = image.src;
        previewback = image.src
         if(event.target.files[0].length !== null || event.target.files[0].length !== undefined ){
         let src = URL.createObjectURL(event.target.files[0]);
         imagePreview.src = src;
         backImage.src = src;
         }else{
          document.getElementById("imagePreview").src = previousImageSrc;
          document.getElementById("back-image").src = previewback;
         }
    }

  function closeupload(){
          pop_profile.style.display = 'none'
}

const pop_upload = document.getElementById('pop-upload')
const pop_profile  = document.getElementById('upload')
pop_upload.addEventListener('click', function(){
   pop_profile.style.display = 'flex'
})
}

if(document.getElementById('pop-upload-describe')){
  function closedescribe(){
    document.getElementById('pop-upload-describe').style.display ="none"
  }
const pop_describe = document.getElementById('describe-pop-visible')
const describe  = document.getElementById('pop-upload-describe')
pop_describe.addEventListener('click', function(){
  describe.style.display = 'flex'
  describe.style.justifyContent = 'center'
  describe.style.alignItems = 'center'
})
}

if(document.getElementById('error_username')){
const input_text  = document.getElementById('username')
const  error_username = document.getElementById('error_username')

input_text.addEventListener('focus', function() {
    error_username.textContent = ""
});

input_text.addEventListener('blur', function() {
  input_text.style.borderColor = "#eeeeee"
  input_text.style.boxShadow = "none";
})

}

if(document.getElementById('error_email')){
  const input_text_2  = document.getElementById('email')
  const  error_email = document.getElementById('error_email')
  
  input_text_2.addEventListener('focus', function() {
      error_email.textContent = ""
  });
  
  input_text.addEventListener('blur', function() {
    input_text_2.style.borderColor = "#eeeeee"
    input_text_2.style.boxShadow = "none";
  })
  
  }
  let paragraph = document.querySelectorAll(".paragraph"); 

  function reduct(paragraph, caractere_extracte, number_value, color , mention , remention) {
    paragraph.forEach(function(paragraphs) {
      let bouton_retry = document.createElement('button');
      bouton_retry.textContent = '.Lire la suite';
      bouton_retry.style.color = color;
      bouton_retry.style.border = "none";
      bouton_retry.style.fontWeight = "900"
      bouton_retry.style.backgroundColor = "transparent";
      bouton_retry.style.fontSize = "16px";
      let sauvegarde_bouton_2 = bouton_retry
      let sauvergarde_bouton = bouton_retry
      let texte = paragraphs.textContent;
      let sauvegarde = texte ;
      let extrait = texte.substring(0, caractere_extracte);
  
      if (texte.length >= number_value) {
        let span = document.createElement('span');
        span.textContent = extrait;
        paragraphs.textContent = '';
        paragraphs.appendChild(span);
        paragraphs.appendChild(bouton_retry);
      } else {
        paragraphs.textContent = sauvegarde;
      }
      let change = false;
      bouton_retry.addEventListener("click", function() {
        if (change === false) {
          change = true;
          paragraphs.textContent = sauvegarde ;
          paragraphs.appendChild(sauvergarde_bouton);
          bouton_retry.textContent = mention ;
          bouton_retry.style.color = color;
          bouton_retry.style.border = "none";
          bouton_retry.style.backgroundColor = "transparent";
          bouton_retry.style.fontSize = "16px";
        } else if (change === true) {
          change = false;
          paragraphs.textContent = extrait;
          paragraphs.appendChild(sauvegarde_bouton_2);
          bouton_retry.textContent =  remention;
          bouton_retry.style.color = color;
          bouton_retry.style.border = "none";
          bouton_retry.style.backgroundColor = "transparent";
          bouton_retry.style.fontSize = "16px";
        }
      });
    });
  }

  reduct(paragraph, 50, 50, 'white' , '.voir moins' , '.voir plus');
  
  let paragraph_content = document.querySelectorAll(".paragraphs_content");
  reduct(paragraph_content, 906, 906, '#606060' ,'.Lire moins' , '.lire la suite');
  
  let commentaire = document.querySelectorAll('.content_commentaire ')
  reduct(commentaire, 350, 350, 'black' ,  '.Lire moins' , '.lire la suite');

  if(document.getElementById('form-comment')){
    const commentaire = document.getElementById('comment-input')
    const form_comment = document.getElementById('form-comment')
    commentaire.addEventListener('keydown', function(e) {
      if(e.key === 'Enter'){
       e.preventDefault();
       form_comment.submit() 
      }
  })
}

//javascript page.php
if (document.getElementById("navbar_page")) {
  const navbar_toggle = document.getElementById("navbar_page");
  const div = document.getElementById("menu-page")
  const bouton_close =  document.createElement('button')
  bouton_close.className = "btn btn-transparent  border-0 position-relative"
  bouton_close.innerHTML= `<span>
  <svg xmlns="http://www.w3.org/2000/svg" width="35" height="35" fill="currentColor" viewBox="0 0 16 16">
    <path d="M4.646 4.646a.5.5 0 011 0L8 6.293l2.354-2.647a.5.5 0 11.707.707L8.707 7l2.647 2.354a.5.5 0 01-.707.707L8 7.707l-2.354 2.647a.5.5 0 11-.707-.707L7.293 7 4.646 4.646a.5.5 0 010-.707z"/>
  </svg></span>`
  const element = document.createElement('ul')
  element.style.height = "100%"
  element.className = "nav d-flex flex-column mt-5 gap-5"
   function li_mode(text, href) {
    const li = document.createElement("li");
    li.className = "nav-item";

    const a = document.createElement("a");
    a.className = "nav-link";
    a.href = href;
    a.style.fontSize = "1.2rem"
    a.style.textAlign = "center"
    a.textContent = text;

    li.appendChild(a);
    element.appendChild(li);
  }
  
  li_mode('Acceuil' , "#" ,)
  li_mode('Equipe' , "#" ,)
  li_mode('A propos de nous' , "#")
  li_mode('contactez nous' , "#")
  div.appendChild(bouton_close)
  div.appendChild(element)
  div.className = "menu_page border";
  div.style.transition = 'all 0.4s ease-in'
  navbar_toggle.addEventListener("click", function () {
    div.classList.toggle("active_page");
  });
   bouton_close.addEventListener('click', function() {
    div.classList.remove("active_page");
  })

  window.addEventListener("click", function(event) {
  if (
    div.classList.contains("active_page") &&
    !div.contains(event.target) &&
    !navbar_toggle.contains(event.target)
  ) {
    div.classList.remove("active_page");
  }
});

  
}
