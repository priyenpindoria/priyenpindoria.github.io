<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Inactivity Test</title>
<link  type="text/javascript" />
</head>

<body>
<button id="btn" value="click for ajax" onclick="document.getElementById('01').style.display='block'" />Hello There</button>


<script
  src="https://code.jquery.com/jquery-1.7.2.min.js"
  integrity="sha256-R7aNzoy2gFrVs+pNJ6+SokH04ppcEqJ0yFLkNGoFALQ="
  crossorigin="anonymous"></script>
  
<script>
$('#btn').click(function(){
    var btn = $(this);
    $.post('http://jsfiddle.net/echo/jsonp/',{delay: 10}).complete(function(){
        btn.prop('disabled', false);
    });
    btn.prop('disabled', true);

});
</script>

</body>
</html>