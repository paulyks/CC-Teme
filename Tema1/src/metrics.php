<?php
class Metrics
{
    public function __construct()
    {
        $this->run();
    }
    protected function run()
    {
        $requests_random = 0;
        $requests_photos = 0;
        $requests_telegram = 0;
        $failed_random = 0;
        $failed_photos = 0;
        $failed_telegram = 0;
        $latency_low_random = null;
        $latency_low_photos = null;
        $latency_low_telegram = null;
        $latency_high_random = 0;
        $latency_high_photos = 0;
        $latency_high_telegram = 0;
        $latency_overall_random = 0;
        $latency_overall_photos = 0;
        $latency_overall_telegram = 0;

        $txt_file = file_get_contents('logs.txt');
        $rows = explode("\n", $txt_file);
        echo '<html lang="en-US">
        <head>
        <title>Metrics</title>
        <link rel="stylesheet" type="text/css" href="style.css">
        </head>
        <body class="metrics">
            <div class="divTable">
                <div class="divTableRow">
                    <div class="divTableCell"></div>
                    <div class="divTableCell header">Random API</div>
                    <div class="divTableCell header">Photos API</div>
                    <div class="divTableCell header">Telegram API</div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell header">Requests</div>';
        foreach($rows as $row)
        {
            $data = explode(',', $row);
            if($data[0] == 'Random')
            {
                $requests_random += 1;
                if($data[2] == 0)
                    $failed_random += 1;
                else
                {
                    if($latency_low_random == null || $latency_low_random > $data[1])
                        $latency_low_random = $data[1];
                    if($latency_high_random < $data[1])
                        $latency_high_random = $data[1];
                    $latency_overall_random += $data[1];
                }
            }
            if($data[0] == 'Photos')
            {
                $requests_photos += 1;
                if($data[2] == 0)
                    $failed_photos += 1;
                else
                {
                    if($latency_low_photos == null || $latency_low_photos > $data[1])
                        $latency_low_photos = $data[1];
                    if($latency_high_photos < $data[1])
                        $latency_high_photos = $data[1];
                    $latency_overall_photos += $data[1];
                }
            }
            if($data[0] == 'Telegram')
            {
                $requests_telegram += 1;
                if($data[2] == 0)
                    $failed_telegram += 1;
                else
                {
                    if($latency_low_telegram == null || $latency_low_telegram > $data[1])
                        $latency_low_telegram = $data[1];
                    if($latency_high_telegram < $data[1])
                        $latency_high_telegram = $data[1];
                    $latency_overall_telegram += $data[1];
                }
            }
        }
        echo '<div class="divTableCell content">' . $requests_random . '</div>
        <div class="divTableCell content">' . $requests_photos . '</div>
        <div class="divTableCell content">' . $requests_telegram . '</div>';
        echo '</div>
                <div class="divTableRow">
                    <div class="divTableCell header">Failed requests</div>
                    <div class="divTableCell content">' . $failed_random . '</div>
                    <div class="divTableCell content">' . $failed_photos . '</div>
                    <div class="divTableCell content">' . $failed_telegram . '</div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell header">Accuracy</div>
                    <div class="divTableCell content">' . (100-($failed_random*100/$requests_random)) . '%</div>
                    <div class="divTableCell content">' . (100-($failed_photos*100/$requests_photos)) . '%</div>
                    <div class="divTableCell content">' . (100-($failed_telegram*100/$requests_telegram)) . '%</div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell header">Latency(lowest)</div>
                    <div class="divTableCell content">' . $latency_low_random . '</div>
                    <div class="divTableCell content">' . $latency_low_photos . '</div>
                    <div class="divTableCell content">' . $latency_low_telegram . '</div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell header">Latency(highest)</div>
                    <div class="divTableCell content">' . $latency_high_random . '</div>
                    <div class="divTableCell content">' . $latency_high_photos . '</div>
                    <div class="divTableCell content">' . $latency_high_telegram . '</div>
                </div>
                <div class="divTableRow">
                    <div class="divTableCell header">Latency(overall)</div>
                    <div class="divTableCell content">' . $latency_overall_random/$requests_random . '</div>
                    <div class="divTableCell content">' . $latency_overall_photos/$requests_photos . '</div>
                    <div class="divTableCell content">' . $latency_overall_telegram/$requests_telegram . '</div>
                </div>
            </div>
        </body>
        </html>';
    }
}
?>