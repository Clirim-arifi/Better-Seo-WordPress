document.addEventListener('DOMContentLoaded', function () {
  var errorMessage = document.createElement('div');
  errorMessage.id = 'title-length-error';
  errorMessage.style.display = 'none';
  errorMessage.innerHTML = 'Titulli rekomandohet te jete 55 deri ne 65 karaktere';

  var titleInput = document.getElementById('title');
  if (titleInput) {
    titleInput.parentNode.appendChild(errorMessage);
    titleInput.addEventListener('input', checkTitleLength);
  }

  function checkTitleLength() {
    var title = titleInput.value;
    var titleLength = title.length;
    if (titleLength >= 55 && titleLength <= 65) {
      titleInput.classList.add('green-border');
      titleInput.classList.remove('red-border');
      errorMessage.style.display = 'none';
      errorMessage.style.display = 'block';
    } else if (titleLength > 65) {
      titleInput.classList.add('red-border');
      titleInput.classList.remove('green-border');
      errorMessage.innerHTML = 'Eshte kaluar limiti i titullit me 65 karaktere.';
      errorMessage.style.display = 'block';
    } else {
      titleInput.classList.remove('green-border');
      titleInput.classList.remove('red-border');
      errorMessage.style.display = 'none';
    }
  }
});
