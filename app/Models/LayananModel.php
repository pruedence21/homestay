<?php

namespace App\Models;

use CodeIgniter\Model;

class LayananModel extends Model
{
    protected $table = 'layanan';
    protected $primaryKey = 'id_layanan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'nama_layanan',
        'harga',
        'kategori',
        'deskripsi'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'nama_layanan' => 'required|min_length[3]',
        'kategori' => 'required|in_list[makanan,extrabed,snack]',
        'harga' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'nama_layanan' => [
            'required' => 'Nama layanan harus diisi',
            'min_length' => 'Nama layanan minimal 3 karakter'
        ],
        'kategori' => [
            'required' => 'Kategori harus dipilih',
            'in_list' => 'Kategori tidak valid'
        ],
        'harga' => [
            'required' => 'Harga harus diisi',
            'numeric' => 'Harga harus berupa angka',
            'greater_than' => 'Harga harus lebih dari 0'
        ]
    ];
}
