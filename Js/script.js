//xhr error handing

 var getHttpRequest = function () {
  var httpRequest = false;

  if (window.XMLHttpRequest) {
    httpRequest = new XMLHttpRequest();
    if (httpRequest.overrideMimeType) {
      httpRequest.overrideMimeType('text/xml');
    }
  }
  else if (window.ActiveXObject) {
    try {
      httpRequest = new ActiveXObject("Msxml2.XMLHTTP");
    }
    catch (e) {
      try {
        httpRequest = new ActiveXObject("Microsoft.XMLHTTP");
      }
      catch (e) {}
    }
  }

  if (!httpRequest) {
    alert('Abort, unable to create XMLHTTP object');
    return false;
  }

  return httpRequest;
}

//Ajax form errors

var form = document.querySelectorAll('.form');
for (var i = 0; i < form.length; i++) {
    form[i].addEventListener("submit", function(e) {

        var errElem = this.querySelectorAll(".error_box");
        for (var i = 0; i < errElem.length; i++) {
          errElem[i].classList.remove("error_box");
          var span = errElem[i].querySelector('.error_txt');
          if (span) {
            span.parentNode.removeChild(span);
          }
        }

        e.preventDefault();
        var data = new FormData(this);
        var xhr = getHttpRequest();
        
        xhr.onreadystatechange = function () {
          if (xhr.readyState === 4) {
            var response = JSON.parse(xhr.responseText);
            if (xhr.status === 200) {
              window.location.replace(response["redirect"]);
            }
            else {
              var errKey = Object.keys(response);
              for (var i = 0; i < errKey.length; i++) {
                var key = errKey[i];
                if (key == "taken") {
                  var input = document.querySelector('[name=uname]');
                }
                else if (key == "mtaken") {
                  var input = document.querySelector('[name=email]');
                }
                else {
                  var input = document.querySelector('[name='+key+']');
                }
                var span = document.createElement('span');
                span.className = "error_txt";
                span.innerHTML = response[key];
                input.parentNode.classList.add('error_box');
                input.parentNode.appendChild(span);
                if (response["redirect"]) {
                  window.location.replace(response["redirect"]);
                }
              }
            }
          }
        }
        
        xhr.open('POST', this.getAttribute('action'), true);
        xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
        xhr.send(data);

    });
}

//Filter selection

function whichFilter() {
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

//Checkbox select

function radioCheck(id) {
    for (var i = 1;i <= 3; i++) {
        document.getElementById("cb" + i).checked = false;
    }
    document.getElementById(id).checked = true;
    document.getElementById("startbutton").style.visibility = "initial";
    document.getElementById("fileToUpload").style.visibility = "initial";
}

//Ajax picture deletion

function deletePic(id) {
  var pic = document.getElementById(id).id;
  if (confirm("Do you want to delete this picture?")) {
    var xhr = getHttpRequest();
    var post = new FormData();
    post.append('img', pic);
    xhr.open('POST', '../Controlers/delete_pic.php', true);
    xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
    xhr.onreadystatechange = function () {
      if (xhr.readyState === 4) {
        if (xhr.status === 200) {
          var tmp = document.getElementById(id);
          tmp.parentNode.removeChild(tmp);
        }
        else {
          window.alert("This picture does not exist");
        }
      }
    }
    xhr.send(post);
  }
}