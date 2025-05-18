let sauvegarde = null;          // sera remplie la 1re fois, puis conservée

  function nom(prenom) {
    const input = document.getElementById('color');

    // on mémorise la valeur de départ une seule fois
    if (sauvegarde === null) {
      sauvegarde = input.value;
    }

    // on alterne
    if (input.value === prenom) {
      input.value = sauvegarde;   // on remet la valeur d’origine
    } else {
      input.value = prenom;       // on met le prénom
    }
  }

  // appel toutes les 3 s
  const id = setInterval(nom, 3000, "japhet");

  // (facultatif) arrêter après 30 s
  // setTimeout(() => clearInterval(id), 30000);
  
const search = async (event) => {
  let input_search = event.target.value;
  

  if (input_search.trim() === '') {
    document.querySelector('.reponse_content').innerHTML = '';
    return;
  }

  const req = await fetch(`search.php?input-search=${encodeURIComponent(input_search)}`);
  const json = await req.json();
  console.log(json);

  const reponse = document.querySelector('.reponse_content');
  reponse.innerHTML = ''; 

  if (json.length > 0) {
    const container = document.createElement('div');
    container.style.display = "flex";
    container.style.flexDirection = "column";
    container.className = "reponse p-5 card w-50 mx-auto bg-light";
    container.style.gap = '50px';
    container.style.overflow = "hidden"
    container.style.overflowY = "scroll"
    container.style.height = "40rem"
    container.style.boxShadow = " 0 0 10px silver"
    container.style.border = "none";
    json.forEach(item => {
      const div = document.createElement('div');
      div.className = "resultat hover-search p-3 rounded-2"
      div.style.display = "flex";
      div.style.gap = "20px";
      div.style.cursor = "pointer"
      const img = document.createElement('img');
      img.src = `img_post/${item.image_forum}`;
      img.className = "search-img "
      img.style.objectFit = "cover";
      img.style.width = "12rem";
      img.style.height = "7rem";
      img.style.borderRadius = '10px';
      
      const textDiv = document.createElement('div');

      const titre = document.createElement('h4');
      titre.innerHTML = item.titre_content;
      titre.style.fontSize = "0.8rem"
      titre.style.fontWeight = "900"

      const contenu = document.createElement('p');
      let contenu_receive = item.content;
      let stock = contenu_receive.substring(0, 350);
      contenu.innerHTML = stock;
      contenu.style.fontSize = '0.7rem';
      contenu.style.width = '17rem';
      contenu.style.fontWeight = "500";

      textDiv.appendChild(titre);
      textDiv.appendChild(contenu);

      div.appendChild(img);
      div.appendChild(textDiv);

      container.appendChild(div);
     
      div.addEventListener('click', () => {
      window.location.href = `message.php?id_post_comment=${item.id_forum}`;
    });
    });
    window.addEventListener("click", function(){
     container.style.display = "none"
    })

    reponse.appendChild(container);
     if(innerWidth < 991){
          img.style.width = "5rem"; 
    }
  } else {
    const noResult = document.createElement('p');
    noResult.className = "p-4"
    noResult.textContent = "Désolé, nous n’avons trouvé aucun résultat pour votre recherche";
    noResult.style.height = '7rem'
    noResult.style.boxShadow = '0 0 10px silver'
    noResult.style.borderRadius = '10px'
    noResult.style.position = 'absolute'
    noResult.style.top = "100%"
    noResult.style.fontSize = "1rem"
    noResult.style.left= "20%"
    noResult.style.textAlign = 'center'
    noResult.style.zIndex = "1000"
    noResult.style.background = 'white'
    reponse.appendChild(noResult);
     window.addEventListener("click", function(){
      noResult.style.display = "none"
    })
  }
};
