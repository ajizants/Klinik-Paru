<?php

namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;

class ROTransaksiHasilModel extends Model
{

    protected $connection = 'rontgen';

    protected $table = 'foto_thorax';
    public $timestamps = false;

    public function simpanFoto($param)
    {
        $client = new Client();
        // $url = 'http://172.16.10.88/upload.php';
        $url = 'http://127.0.0.1:8006/ro/upload.php';

        $response = $client->request('POST', $url, [
            'multipart' => $param,
        ]);
    }

}
