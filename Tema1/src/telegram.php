<?php
class Telegram
{
    public function __construct()
    {
        $this->run();
    }
    protected function run()
    {
        $photo = photosAPI()[0];
        $number = randomAPI()[0];
        $info = telegramAPI($photo, $number);
        echo '<html lang=\'en-US\'>
            <head>
            <title>Telegram</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            </head>
            <body class="telegram">
            <a href="/telegram" class="titleBox3">
            Send telegram message with https://api.telegram.org
            </a>';
        echo '<table><tr><td>';
        echo '<img class="leftPhoto" src="' . $photo . '">'; 
        echo '</td><td><div class="rightNumber">' . $number . '</div></td></tr></table><br>';
        echo '</body>
            </html>';
        echo '<center>' . $info . '</center>';
        $info = 'Random: ' . $info;
    }
}
?>