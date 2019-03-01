<?php
class Random
{
    public function __construct()
    {
        $this->run();
    }
    protected function run()
    {
        $array = randomAPI();
        $data = $array[0];
        $info = $array[1];
        echo '<html lang=\'en-US\'>
            <head>
            <title>Numbers</title>
            <link rel="stylesheet" type="text/css" href="style.css">
            </head>
            <body class="random">
            <a href="/random" class="titleBox2">
            Generate random number with https://api.random.org/json-rpc/2/invoke
            </a>';
        echo '<div class="number">' . $data . '</div><br>';
        echo '</body>
            </html>';
        echo '<center>' . $info . '</center>';
        $info = 'Random: ' . $info;
    }
}
?>