<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\LayananModel;
use CodeIgniter\API\ResponseTrait;

class Layanan extends BaseController
{
    use ResponseTrait;

    protected $layananModel;

    public function __construct()
    {
        $this->layananModel = new LayananModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Layanan',
            'layanan' => $this->layananModel->findAll()
        ];
        return view('admin/layanan/index', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $json = $this->request->getJSON();
            
            // Validate data
            if (!$this->validate([
                'nama_layanan' => 'required|min_length[3]',
                'kategori' => 'required|in_list[makanan,extrabed,snack]',
                'harga' => 'required|numeric|greater_than[0]'
            ])) {
                return $this->fail($this->validator->getErrors());
            }

            // Save data
            $data = [
                'nama_layanan' => $json->nama_layanan,
                'kategori' => $json->kategori,
                'harga' => $json->harga,
                'deskripsi' => $json->deskripsi ?? ''
            ];

            $this->layananModel->insert($data);

            return $this->respond([
                'success' => true,
                'message' => 'Layanan berhasil ditambahkan'
            ]);
            
        } catch (\Exception $e) {
            log_message('error', '[Layanan::store] ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function update($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        $json = $this->request->getJSON();
        $json->id_layanan = $id;

        if (!$this->layananModel->save($json)) {
            return $this->fail($this->layananModel->errors());
        }

        return $this->respond([
            'success' => true,
            'message' => 'Layanan berhasil diupdate'
        ]);
    }

    public function delete($id)
    {
        $this->layananModel->delete($id);
        return $this->respond([
            'success' => true,
            'message' => 'Layanan berhasil dihapus'
        ]);
    }

    public function list()
    {
        return $this->respond($this->layananModel->findAll());
    }
}
