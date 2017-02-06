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

var form = document.querySelector('.form');
form.addEventListener('submit', function(e) {
  
  e.preventDefault();
  var data = new new FormData(form);
  var xhr = getHttpRequest();
  
  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      // if (xhr.status === 200) { }
    }
  }
  
  xhr.open('POST', form.getAttribute('action'), true);
  xhr.setRequestHeader('X-Requested-With', 'xmlhttprequest');
  xhr.send(data);

});