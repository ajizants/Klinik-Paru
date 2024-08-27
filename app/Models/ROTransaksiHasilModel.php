<?php
namespace App\Models;

use GuzzleHttp\Client;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ROTransaksiHasilModel extends Model
{
    protected $connection = 'mysql';
    protected $table = 't_rontgen_hasil_foto';
    public $timestamps = false;

    public function deleteGambar($id)
    {
        $client = new Client();
        $url = env('APP_URL_DELETE');

        // Mulai transaksi
        DB::beginTransaction();
        try {
            // Hapus gambar dari database lokal
            $gambar = $this->find($id);
            if (!$gambar) {
                throw new \Exception('Data tidak ditemukan di database lokal.');
            }

            // Hapus dari database
            $gambar->delete();

            // Kirim permintaan hapus ke server eksternal
            $response = $client->request('POST', $url, [
                'json' => ['id' => $id],
            ]);

            // Periksa status respons
            if ($response->getStatusCode() !== 200) {
                throw new \Exception('Gagal menghapus gambar di server eksternal.');
            }

            // Commit transaksi jika semua berhasil
            DB::commit();

            return [
                'status' => 'success',
                'message' => 'Gambar berhasil dihapus dari database lokal dan server eksternal.',
            ];
        } catch (\Exception $e) {
            // Rollback jika terjadi kesalahan
            DB::rollback();
            Log::error('Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage(),
            ];
        }
    }

    public function deleteFoto($id)
    {
        $client = new Client();
        $url = env('APP_URL_DELETE'); // Ensure this points to the correct URL of your PHP script on Server B
        $urlDelete = $url . '?id=' . $id;
        // dd($urlDelete);
        try {
            // Send a GET request to Server B to delete the photo
            $response = $client->request('GET', $urlDelete);
            // dd($response);

            // Decode the JSON response from Server B
            $result = json_decode($response->getBody()->getContents(), true);

            // Check the result and return appropriate response
            if (isset($result['status']) && $result['status'] === 'success') {
                return [
                    'status' => 'success',
                    'message' => 'Foto berhasil dihapus.',
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menghapus foto.',
                ];
            }
        } catch (\Exception $e) {
            Log::error('Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage());
            return [
                'status' => 'error',
                'message' => 'Terjadi kesalahan saat menghapus gambar: ' . $e->getMessage(),
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
