<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingModel extends Model
{
    protected $table = 'booking';
    protected $primaryKey = 'id_booking';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_kamar',
        'nama_tamu',
        'email',
        'no_telp',
        'checkin',
        'checkout',
        'jumlah_tamu',
        'total_harga',
        'status',
        'layanan',
        'total_sebelum_diskon',
        'total_setelah_diskon',
        'diskon',
        'jenis_diskon'
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    protected $deletedField = 'deleted_at';

    protected $validationRules = [
        'id_kamar' => 'required',
        'nama_tamu' => 'required|min_length[3]',
        'email' => 'required|valid_email',
        'no_telp' => 'required|min_length[10]',
        'checkin' => 'required|valid_date',
        'checkout' => 'required|valid_date',
        'jumlah_tamu' => 'required|numeric|greater_than[0]',
        'total_harga' => 'required|numeric|greater_than[0]'
    ];
}
