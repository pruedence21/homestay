<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\KamarModel;
use CodeIgniter\API\ResponseTrait;

class Kamar extends BaseController
{
    use ResponseTrait;
    
    protected $kamarModel;

    public function __construct()
    {
        $this->kamarModel = new KamarModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Kamar',
            'kamar' => $this->kamarModel->findAll()
        ];
        return view('admin/kamar/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Kamar'
        ];
        return view('admin/kamar/create', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        $json = $this->request->getJSON();
        $data = [
            'nomor_kamar' => $json->nomor_kamar,
            'tipe_kamar' => $json->tipe_kamar,
            'harga' => $json->harga,
            'status' => $json->status,
            'deskripsi' => $json->deskripsi ?? ''
        ];

        if ($this->kamarModel->save($data)) {
            return $this->respond([
                'success' => true,
                'message' => 'Data kamar berhasil ditambahkan'
            ]);
        }

        return $this->fail($this->kamarModel->errors());
    }

    public function getKamarTersedia()
    {
        $kamarTersedia = $this->kamarModel
            ->where('status', 'tersedia')
            ->findAll();
            
        return $this->response->setJSON($kamarTersedia);
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $json = $this->request->getJSON();
            $data = [
                'nomor_kamar' => $json->nomor_kamar,
                'tipe_kamar' => $json->tipe_kamar,
                'harga' => $json->harga,
                'status' => $json->status,
                'deskripsi' => $json->deskripsi ?? ''
            ];

            if ($this->kamarModel->update($id, $data)) {
                return $this->respond([
                    'success' => true,
                    'message' => 'Kamar berhasil diupdate'
                ]);
            }

            return $this->fail($this->kamarModel->errors());
        } catch (\Exception $e) {
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function delete($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            if ($this->kamarModel->delete($id)) {
                return $this->respond([
                    'success' => true,
                    'message' => 'Kamar berhasil dihapus'
                ]);
            }

            return $this->fail('Gagal menghapus kamar');
        } catch (\Exception $e) {
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}
