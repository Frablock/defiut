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
