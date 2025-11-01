<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mengambil data . . .</title>
</head>
<body>
    Mengambil data . . .

    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js" integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        $(document).ready(function(){
            let url = "<?= $url;?>";
            let token = getParameterFromUrl('access_token');
            
            if(token){
                $.ajax({
                    url: url,
                    type: 'GET',
                    headers: {
                        'Authorization': 'Bearer ' + token
                    },
                    success: function(data) {
                        sendToServer(data);
                    },
                    error: function(error) {
                        console.error(error);
                    }
                });
            }else{
                alert('Ada kesalahan');
                window.location.href = "<?= site_url('user/login'); ?>";
            }

            function sendToServer(data) {
                $.ajax({
                    url: '<?= site_url('api/user/oauth_check'); ?>',
                    type: 'POST',
                    data: JSON.stringify(data),
                    contentType: 'application/json',
                    success: function(data) {
                        window.location.href = data.url_redirect;
                    },
                    error: function(error) {
                        console.error(error);
                    }
                })
            }

            function getParameterFromUrl(name) {
                var fragment = window.location.hash.substring(1);

                // Memecah nilai-nilai di dalam fragment
                var params = fragment.split('&');
                var token;

                // Loop melalui parameter untuk menemukan access_token
                for (var i = 0; i < params.length; i++) {
                    var pair = params[i].split('=');
                    if (pair[0] === name) {
                        token = pair[1];
                        break;
                    }
                }

                return token;
            }
        });
    </script>
</body>
</html>