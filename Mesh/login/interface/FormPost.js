

class FormPost {
  constructor(idForm, idTextarea, ListPost) {
    this.form = document.getElementById(idForm);
    this.textarea = document.getElementById(idTextarea);
    this.listPost = document.getElementById(ListPost);
    this.addSubmit();
    this.addLikeListener();
  }

  // Métodos da classe
  addSubmit() {
    const handleSubmit = (event) => {
      event.preventDefault();
      if (this.formValidate(this.textarea.value)) {
        const imgpost = sessionStorage.getItem('postimg');
        sessionStorage.removeItem('postimg');
        const newPost = document.createElement("li");
        newPost.classList.add("post");
        const time = this.getTime();
        const userName = document.getElementById('nome').innerText;
        const userImage = document.getElementById('imagem').src;
        newPost.innerHTML = `
          <div class="infoUserPost">
            <div class="imgUserPost">
              <img src="${userImage}" alt="Profile Picture">
            </div>
            <div class="nameAndHour">
              <strong>${userName}</strong>
              <p>${time}</p>
            </div>
          </div>
          <p>${this.textarea.value}</p>
          ${imgpost ? `<img src="${imgpost}" alt="Post Image" class="post-image">` : ''}
          <div class="actionBtnPost">
            <button type="button" class="filesPost like">
              <img src="./assets/heart.svg" alt="Curtir">ﾠCurtidasﾠ<span class="likeCount">0</span>
            </button>
            <button type="button" class="filesPost share">
              <img src="./assets/share.svg" alt="Compartilhar"> Compartilhar
            </button>
          </div>
        `;
        this.listPost.prepend(newPost);
        this.textarea.value = "";
      } else {
        alert("Escreva algo");
      }
    };

    
    this.onSubmit(handleSubmit);
  }

  onSubmit(func) {
    this.form.addEventListener("submit", func);
  }

  getTime() {
    const time = new Date();
    const hour = time.getHours();
    const minutes = time.getMinutes();
    return `${hour}h ${minutes}min`;
  }

  formValidate(value) {
    if (value === "" || value === undefined) {
      return false;
    }
    return true;
  }

  addLikeListener() {
    this.listPost.addEventListener('click', (event) => {
      if (event.target.classList.contains('filesPost') && event.target.classList.contains('like')) {
        const likeButton = event.target;
        const likeCount = likeButton.querySelector('.likeCount');
        if (likeButton.textContent.trim() === 'Curtir') {
          likeButton.innerHTML = `<img src="./assets/heart.svg" alt="Curtir"> Curtido <span class="likeCount">1</span>`;
        } else {
          let count = parseInt(likeCount.textContent);
          count++;
          likeCount.textContent = count;
        }
      }
    });
  }
}

// Expondo a classe FormPost para uso externo, se necessário
window.FormPost = FormPost;

// Criando uma instância de FormPost
const postForm = new FormPost("formPost", "textarea", "posts");

// Carregando foto e nome do usuário
const foto = sessionStorage.getItem('foto');
if (foto && foto.length > 1) {
  document.getElementById('imagem').src = foto;
}

const nome = localStorage.getItem('nome');
if (nome) {
  document.getElementById('nome').innerHTML = nome;
}

const fileInput = document.getElementById('file');
// Event listener para a mudança de imagem
fileInput.addEventListener('change', function() {
  const choosedFile = this.files[0];
  if (choosedFile) {
    const reader = new FileReader();
    reader.addEventListener('load', function() {
      // Armazena a imagem em base64 no sessionStorage
      sessionStorage.setItem('postimg', reader.result);
    });
    reader.readAsDataURL(choosedFile);
  }
});

let boxBuscar = document.querySelector('.buscar-box')
let lupa = document.querySelector('.lupa-buscar')
let btnFechar = document.querySelector('.btn-fechar')


lupa.addEventListener('click', ()=> {
    boxBuscar.classList.add('ativar')
})

btnFechar.addEventListener('click', ()=> {
    boxBuscar.classList.remove('ativar')
})