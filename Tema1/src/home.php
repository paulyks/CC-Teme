<?php
class Home
{
    public function __construct()
    {
        $this->run();
    }
    protected function run()
    {
        echo '<html lang="en-US">
        <head>
        <title>Tema 1 - CC</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        </head>
        <body class="home">
            <div class="divHomeTable">
                <div class="divHomeTableRow">
                    <a href="/random" class="divHomeTableCell randomness">Joy of randomness</a>
                    <a href="/photos" class="divHomeTableCell fullOfPhotos">Golden hour</a>
                    <a href="/telegram" class="divHomeTableCell chatWithTelegram">Telegram talks</a>
                    <a href="/metrics" class="divHomeTableCell seeTheResults">Kowalski analysis</a>
                </div>
            </div>
        </body>
        </html>';
    }
}
?>