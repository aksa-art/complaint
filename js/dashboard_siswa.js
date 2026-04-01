// Modal logic
const modal = document.getElementById('complaint-modal');
const btnTambah = document.getElementById('btn-new-complaint');
const btnCancel = document.getElementById('cancel-complaint');
if (btnTambah && modal && btnCancel) {
    btnTambah.onclick = function() {
        modal.style.display = 'flex';
    };
    btnCancel.onclick = function() {
        modal.style.display = 'none';
    };
    // Optional: close modal on outside click
    modal.onclick = function(e) {
        if (e.target === modal) modal.style.display = 'none';
    };
}
// Tab switching logic
function showTab(tab) {
    var myTab = document.getElementById('tab-my');
    var otherTab = document.getElementById('tab-other');
    var myContent = document.getElementById('complaints-my');
    var otherContent = document.getElementById('complaints-other');
    var underline = document.getElementById('tab-underline');
    if(tab === 'my') {
        myTab.classList.add('active');
        otherTab.classList.remove('active');
        myContent.style.display = '';
        otherContent.style.display = 'none';
        underline.style.left = '0';
    } else {
        myTab.classList.remove('active');
        otherTab.classList.add('active');
        myContent.style.display = 'none';
        otherContent.style.display = '';
        underline.style.left = '50%';
    }
}
// Set underline bar width and position
window.onload = function() {
    var underline = document.getElementById('tab-underline');
    var myTab = document.getElementById('tab-my');
    if (underline && myTab) {
        underline.style.width = myTab.offsetWidth + 'px';
        underline.style.left = '0';
    }
};
window.onresize = function() {
    var underline = document.getElementById('tab-underline');
    var myTab = document.getElementById('tab-my');
    if (underline && myTab) {
        underline.style.width = myTab.offsetWidth + 'px';
    }
};
// AJAX Like

document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.love-icon').forEach(function(icon) {
        icon.addEventListener('click', function() {
            var id = this.getAttribute('data-id');
            var countSpan = document.getElementById('like-count-' + id);
            var self = this;
            var xhr = new XMLHttpRequest();
            xhr.open('POST', 'like_aspirasi.php', true);
            xhr.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    countSpan.textContent = xhr.responseText;
                    self.classList.toggle('liked');
                }
            };
            xhr.send('id_aspirasi=' + encodeURIComponent(id));
        });
    });
});
