<?php
class Photos
{
    public function __construct()
    {
        $this->run();
    }
    protected function run()
    {
        $array = photosAPI();
        $data = $array[0];
        $info = $array[1];
        echo '<html lang=\'en-US\'>
            <head>
            <title>Photos</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            </head>
            <body class="photos">
                <a href=\'/photos\' class=\'titleBox\'>
                    Generate random photo with http://www.splashbase.co/api/v1/images/random
                </a>';
        echo '<img class="image" src="' . $data . '">';
        echo '<center>' . $info . '</center><br>';
        echo '</body>
            </html>';
        $info = 'Photos: ' . $info;
    }
}
?>