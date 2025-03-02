<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
/* width */
::-webkit-scrollbar {
    width: 20px;
}

/* Track */
::-webkit-scrollbar-track {
    box-shadow: inset 0 0 5px grey;
    border-radius: 10px;
}

/* Handle */
::-webkit-scrollbar-thumb {
    background: red;
    border-radius: 10px;
}

/* Handle on hover */
::-webkit-scrollbar-thumb:hover {
    background: #b30000;
}
</style>
</head>
<body>
Cette page simule un scroll infinie<br>
La page classique ne contient que des photos de chats<br>

l'api de test ne renvoit que des photos aléatoires

<div id="post-data">
<?php

for ($i=0; $i < 4; $i++) {
    echo "<div><img src='https://cataas.com/cat?width=500&height=500&a=" . rand() . "'></div>";
}


?>
</div>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
var itemHeight = 200;

$(window).scroll(function() {
    if ($(this).scrollTop()  >= $(document).height() - 500*3) {
        loadMore(1);
    }
});

function loadMore(last_id){
    $.ajax({
        url: 'api/load_more.php?last_id=' + last_id,
        type: "get",
        beforeSend: function(){
            $('.ajax-load').show();
        }
    }).done(function(data){
        $('.ajax-load').hide();
        $("#post-data").append(data);
    }).fail(function(jqXHR, ajaxOptions, thrownError){
        alert('server not responding...');
    });
}
</script>
</body>
</html>
