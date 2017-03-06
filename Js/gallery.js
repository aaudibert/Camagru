//Ajax comments

function comment(id, page){
    var key = window.event.keyCode;
    var image_id = 'c' + id;
    var comment = document.getElementById(image_id) ;
    
    if (key == 13 && comment.value) {
      var xhr = getHttpRequest();
      var post = new FormData();
      
      post.append('comment', comment.value);
      post.append('pic_id', id);
      post.append('page', page);
      xhr.open('POST', '../Controlers/comment.php', true);
      xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
      xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
          var errElem = document.querySelectorAll(".error_box");
          for (var i = 0; i < errElem.length; i++) {
            errElem[i].classList.remove("error_box");
            var span = errElem[i].querySelector('.error_txt');
            if (span) {
              span.parentNode.removeChild(span);
            }
          }
          var response = JSON.parse(xhr.responseText);
          if (xhr.status === 200) {
            comment.value = '';
            
            var val = document.createElement("p");
            val.innerHTML = response['username']+": "+response['com'];
            var c_area = document.getElementById(response['pic_id']);
            c_area.insertBefore(val, c_area.firstChild);
          }
          else {
            comment.value = '';
            if (response['missing']) {
              location.reload();
            }
            else if (response['error']) {
                var span = document.createElement('span');
                
                span.className = "error_txt";
                span.innerHTML = response['error'];
                comment.parentNode.classList.add('error_box');
                comment.parentNode.appendChild(span);
            }
          }
        }
      }
      xhr.send(post);
    }
  }

  //Ajax Like

function like_count(id, response) {
  var like = document.getElementById('c'+id);
  
  console.log(id);
  if (response['like']) {
    var count = like.innerHTML.replace(" likes", "")
    
    if (like.innerHTML == '0 likes') {
      like.innerHTML = '1 like';
    }
    else if (like.innerHTML == '1 like') {
      like.innerHTML = '2 likes';
    }
    else if (count) { 
      like.innerHTML = (parseInt(count) + 1) + ' likes';
    } 
  }
  else if (response['unlike']) {
    var count = like.innerHTML.replace(" likes", "")
    
    if (like.innerHTML == '1 like') {
      like.innerHTML = '0 likes';
    }
    else if (like.innerHTML == '2 likes') {
      like.innerHTML = '1 like';
    }
    else if (count != 0) { 
      like.innerHTML = (parseInt(count) - 1) + ' likes';
    } 
  }
}

function likeImg(id){
  var heart = document.getElementById(id);

  var xhr = getHttpRequest();
  var post = new FormData();
  
  post.append('pic_id', id);
  xhr.open('POST', 'http://localhost:8080/camagru/Controlers/like.php', true);
  xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      var response = JSON.parse(xhr.responseText);
      if (xhr.status === 200) {
        if (response['like']) {
          heart.classList.remove('fa-heart-o');
          heart.classList.add('fa-heart');
          heart.style.color='red';
          like_count(id, response);
        }
        else if (response['unlike']) {
          heart.classList.add('fa-heart-o');
          heart.style.color='#9b9b9b';
          like_count(id, response);
        }
      }
      else {
        location.reload();
      }
    }
  }
  xhr.send(post);
}