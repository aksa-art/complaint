// Tampilkan kotak login sesuai hash
function showLoginBox() {
  var adminBox = document.getElementById('admin-login');
  var studentBox = document.getElementById('student-login');
  if(window.location.hash === '#admin') {
    adminBox.style.display = 'block';
    studentBox.style.display = 'none';
    adminBox.scrollIntoView({behavior:'smooth'});
  } else if(window.location.hash === '#student') {
    adminBox.style.display = 'none';
    studentBox.style.display = 'block';
    studentBox.scrollIntoView({behavior:'smooth'});
  } else {
    adminBox.style.display = 'none';
    studentBox.style.display = 'none';
  }
}
window.onload = showLoginBox;
window.onhashchange = showLoginBox;
