<?php
namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;

class ROTransaksiHasilModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 't_rontgen_hasil_foto';
    public $timestamps = false;

    public function simpanFoto($param)
    {
        $client = new Client();
        // $url = 'http://172.16.10.88/upload.php';
        $url = 'http://127.0.0.1:8006/ro/upload.php';

        try {
            $response = $client->request('POST', $url, [
                'multipart' => $param,
            ]);
            return $response;
        } catch (RequestException $e) {
            // Handle the error: server might be down
            return [
                'status' => 'error',
                'message' => 'Could not connect to the upload server.',
                'details' => $e->getMessage(),
            ];
        }
    }
}
