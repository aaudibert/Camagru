(function() {

  var streaming = false,
      video        = document.querySelector('#video'),
      cover        = document.querySelector('#cover'),
      canvas       = document.querySelector('#canvas'),
      photo        = document.querySelector('#photo'),
      startbutton  = document.querySelector('#startbutton'),
      width = 640,
      height = 0;

  navigator.getMedia = ( navigator.getUserMedia ||
                         navigator.webkitGetUserMedia ||
                         navigator.mozGetUserMedia ||
                         navigator.msGetUserMedia);

  navigator.getMedia(
    {
      video: true,
      audio: false
    },
    function(stream) {
      if (navigator.mozGetUserMedia) {
        video.mozSrcObject = stream;
      } else {
        var vendorURL = window.URL || window.webkitURL;
        video.src = vendorURL.createObjectURL(stream);
      }
      video.play();
    },
    function(err) {
      console.log("An error occured! " + err);
    }
  );

  video.addEventListener('canplay', function(ev){
    if (!streaming) {
      height = video.videoHeight / (video.videoWidth/width);
      video.setAttribute('width', width);
      video.setAttribute('height', height);
      canvas.setAttribute('width', width);
      canvas.setAttribute('height', height);
      streaming = true;
    }
  }, false);

function whichFilter()
{
  var cb1 = document.getElementById('cb1');
  var cb2 = document.getElementById('cb2');
  var cb3 = document.getElementById('cb3');

  if (cb1.checked) {
    var src = '../Filters/fokof.png';
    document.getElementById('cb1').checked = false;
  }
  if (cb2.checked) {
    var src = '../Filters/frog.png';
    document.getElementById('cb2').checked = false;
  }
  if (cb3.checked) {
    var src = '../Filters/penguin.png';
    document.getElementById('cb3').checked = false;
  }
  document.getElementById("startbutton").style.visibility = "hidden";
  document.getElementById("fileToUpload").style.visibility = "hidden";
  return src;
}

  function takepicture() {
    var img = whichFilter();
    if (img) {
      var ctx = canvas.getContext('2d');
      ctx.drawImage(video, 0, 0);
      var pic = canvas.toDataURL('image/png');
      ctx.globalAlpha = 1;
      var flt = new Image();
      flt.src = img;
      flt.onload = function(){
        ctx.drawImage(flt, 0, 50);
        var xhr = getHttpRequest();
        var post = new FormData();
        post.append('img', pic);
        post.append('filter', img);
        xhr.open('POST', '../Controlers/picToDb.php', true);
        xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
               canvas.style.display="initial";
              var prev = document.getElementById('preview');
              var cell = document.createElement("img");
              cell.setAttribute("id", response['id']);
              cell.setAttribute("class", 'prevs');
              cell.src = response['link'];
              cell.style.width = '300px';
              prev.insertBefore(cell, prev.firstChild);
              cell.setAttribute('onclick', 'deletePic('+response['id']+')');
            }
            else {
              location.reload();
            }
          }
        }
        xhr.send(post);
      };
    }
    else {
      alert('You must choose a filter');
    }
  }

  startbutton.addEventListener('click', function(ev){
      takepicture();
    ev.preventDefault();
  }, false);

})();

//File upload handling

function filterUpload(file, name, data) {
  var img = whichFilter();
  
  var flt = new Image();
  flt.src = img;
  flt.onload = function(){
    var xhr = getHttpRequest();
    var post = new FormData();
    post.append('data', file, name);
    post.append('img', data);
    post.append('filter', img);
    xhr.open('POST', '../Controlers/picToDb.php', true);
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        var response = JSON.parse(xhr.responseText);
        canvas.style.display="none";
        if (xhr.status === 200) {
          var prev = document.getElementById('preview');
          var cell = document.createElement("img");
          cell.setAttribute("id", response['id']);
          cell.setAttribute("class", 'prevs');
          cell.src = response['link'];
          cell.style.width = '300px';
          prev.insertBefore(cell, prev.firstChild);
          cell.setAttribute('onclick', 'deletePic('+response['id']+')');
        }
        else {
          location.reload();
        }
      }
    }
    xhr.send(post);
  }
}

document.getElementById('file').addEventListener('change', function(ev){
  var file = this.files;
  var reader = new FileReader();
  
  reader.addEventListener('load', function() {
    if (!file[1]) {
      if (file[0].size > 2097152) {
        console.log("The file you're trying to upload is to big");
        window.alert("The file you're trying to upload is to big");
      }
      else {
        filterUpload(file[0], file[0].name, reader.result);
      }
    }
    else {
      window.alert('You can only upload one file at a time');
      console.log('You can only upload one file at a time');
    }
  });
  reader.readAsDataURL(file[0]);
  ev.preventDefault();
}, false);