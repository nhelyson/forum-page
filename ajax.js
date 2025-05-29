function dislike_like() {
  const bouton_like = document.getElementById('like');
  const bouton_dislike = document.getElementById('dislike');
  const reponse_like = document.getElementById('response_like');
  const reponse_dislike = document.getElementById('response_dislike');

  const id = bouton_like.dataset.id;
  const vote_like = bouton_like.dataset.value;
  const vote_dislike = bouton_dislike.dataset.value;

    fetch(`vote.php?id_post=${id}&objet_like=like`)
      .then(response => response.json())
      .then(data => {
        if (data.success) reponse_like.textContent = data.like_count;
      });

    fetch(`vote.php?id_post=${id}&objet_dislike=dislike`)
      .then(response => response.json())
      .then(data => {
        if (data.success) reponse_dislike.textContent = data.dislike_count;
      });

  bouton_like.addEventListener('click', () => {
    fetch(`vote.php?id_post=${encodeURIComponent(id)}&vote=${encodeURIComponent(vote_like)}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          reponse_like.textContent = data.like_count;
          reponse_dislike.textContent = data.dislike_count;  
        }
      });
  });

  bouton_dislike.addEventListener('click', () => {
    fetch(`vote.php?id_post=${encodeURIComponent(id)}&vote=${encodeURIComponent(vote_dislike)}`)
      .then(res => res.json())
      .then(data => {
        if (data.success) {
          reponse_like.textContent = data.like_count;       
          reponse_dislike.textContent = data.dislike_count;
        }
      });
  });
}

dislike_like();
if(document.getElementById('input_describe')) {

document.getElementById('input_describe').addEventListener('submit', function(e) {
  e.preventDefault();
   const form = e.target;
   const formData = new FormData(form)
   fetch('description.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    console.log(data);
    if(data.length > 0){
     const reponse_contains = document.querySelector('.reponse_contain');
     reponse_contains.innerHTML = '';
     const p = document.createElement('p');
     p.className = "text-dark query mt-5"
     p.id = "reponse_content";
     p.innerHTML = data.describe;
     p.setAttribute('style', "font-size: 0.8rem;width:18rem;font-family: 'Asap', sans-serif!important;")
     reponse_contains.appendChild(p);
    }else{
       const reponse_contains = document.querySelector('.reponse_contain');
       reponse_contains.innerHTML = '';
       const div = document.createElement('div');
       div.className = "position-fixed"
       div.innerHTML = `<P class="text-center">${data.describe}</P>`
    }
  }
  )
})
}

document.getElementById('uploadForm').addEventListener('submit', function(e) {
   e.preventDefault();
   const profile_picture = document.getElementById("profile_picture");
   img_file.innerHTML = '';
   const background = document.querySelector('.background');
   background.innerHTML = '';
   const form = e.target;
   const formData = new FormData(form)
   fetch('upload.php', {
    method: 'POST',
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    console.log(data);
    if(data.succes === true){
      const img_background = document.createElement('img');
      img_background.id = "back-image";
      img_background.src = `img_profile/${data.img_profile}`;
      img_background.alt = `${data.img_profile}`;

      const img = document.createElement('img');
      img.id= "imagePreview";
      img.src = `img_profile/${data.img_profile}`;
      img.alt = `${data.img_profile}`;

      background.appendChild(img_background);
       profile_picture .appendChild(img);
    }
  }
  )
})

