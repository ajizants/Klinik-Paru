<?php
namespace App\Models;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class ROTransaksiHasilModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 't_rontgen_hasil_foto';
    public $timestamps = false;

    public function simpanFoto1($param)
    {
        $client = new Client();
        // $url = 'http://172.16.10.88/ro/upload.php';
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

    public function simpanFoto($param)
    {
        $client = new Client();
        $url = env('APP_URL_UPLOAD');
        try {
            $response = $client->request('POST', $url, [
                'multipart' => $param,
            ]);

            // Mengembalikan isi respons dari server
            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat mengupload foto: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat mengupload foto: ' . $e->getMessage(),
            ];
        }
    }

}
