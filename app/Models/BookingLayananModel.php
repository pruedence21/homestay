<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingLayananModel extends Model
{
    protected $table = 'booking_layanan';
    protected $primaryKey = 'id_booking_layanan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = [
        'id_booking',
        'id_layanan',
        'jumlah',
        'subtotal'
    ];

    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $validationRules = [
        'id_booking' => 'required|numeric',
        'id_layanan' => 'required|numeric',
        'jumlah' => 'required|numeric|greater_than[0]',
        'subtotal' => 'required|numeric|greater_than[0]'
    ];

    protected $validationMessages = [
        'id_booking' => [
            'required' => 'ID Booking harus diisi',
            'numeric' => 'ID Booking harus berupa angka'
        ],
        'id_layanan' => [
            'required' => 'ID Layanan harus diisi',
            'numeric' => 'ID Layanan harus berupa angka'
        ],
        'jumlah' => [
            'required' => 'Jumlah harus diisi',
            'numeric' => 'Jumlah harus berupa angka',
            'greater_than' => 'Jumlah harus lebih dari 0'
        ],
        'subtotal' => [
            'required' => 'Subtotal harus diisi',
            'numeric' => 'Subtotal harus berupa angka',
            'greater_than' => 'Subtotal harus lebih dari 0'
        ]
    ];

    public function getBookingLayanan($id_booking)
    {
        return $this->select('booking_layanan.*, layanan.nama_layanan, layanan.harga')
            ->join('layanan', 'layanan.id_layanan = booking_layanan.id_layanan')
            ->where('booking_layanan.id_booking', $id_booking)
            ->findAll();
    }
}