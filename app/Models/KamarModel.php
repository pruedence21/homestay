<?php

namespace App\Models;

use CodeIgniter\Model;

class KamarModel extends Model
{
    protected $table            = 'kamar';
    protected $primaryKey       = 'id_kamar';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'nomor_kamar', 
        'tipe_kamar', 
        'harga', 
        'status', 
        'deskripsi'
    ];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'nomor_kamar' => 'required|is_unique[kamar.nomor_kamar,id_kamar,{id_kamar}]',
        'tipe_kamar'  => 'required',
        'harga'       => 'required|numeric',
        'status'      => 'required|in_list[tersedia,terisi,maintenance]'
    ];
    protected $validationMessages   = [
        'nomor_kamar' => [
            'required' => 'Nomor kamar harus diisi',
            'is_unique' => 'Nomor kamar sudah digunakan'
        ]
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
