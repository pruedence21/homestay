<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\KamarModel;
use CodeIgniter\API\ResponseTrait;

class Booking extends BaseController
{
    use ResponseTrait;

    protected $db;
    protected $bookingModel;
    protected $kamarModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->bookingModel = new BookingModel();
        $this->kamarModel = new KamarModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Data Booking',
            'booking' => $this->bookingModel
                ->select('booking.*, kamar.nomor_kamar')
                ->join('kamar', 'kamar.id_kamar = booking.id_kamar')
                ->findAll()
        ];
        return view('admin/booking/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Booking',
            'kamar_tersedia' => $this->kamarModel
                ->where('status', 'tersedia')
                ->findAll()
        ];
        return view('admin/booking/create', $data);
    }

    public function store()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $json = $this->request->getJSON();
            
            // Prepare booking data
            $bookingData = [
                'id_kamar' => $json->id_kamar,
                'nama_tamu' => $json->nama_tamu,
                'email' => $json->email,
                'no_telp' => $json->no_telp,
                'checkin' => $json->checkin,
                'checkout' => $json->checkout,
                'jumlah_tamu' => $json->jumlah_tamu,
                'total_harga' => $json->total_harga,
                'status' => 'pending',
                'total_sebelum_diskon' => $json->total_sebelum_diskon ?? 0,
                'total_setelah_diskon' => $json->total_setelah_diskon ?? 0,
                'diskon' => $json->diskon ?? 0,
                'jenis_diskon' => $json->jenis_diskon ?? 'nominal'
            ];

            // Start transaction
            $this->db->transStart();

            // Insert booking
            $this->bookingModel->insert($bookingData);
            $bookingId = $this->bookingModel->insertID();

            // Insert layanan if exists
            if (!empty($json->layanan)) {
                $bookingLayananModel = new \App\Models\BookingLayananModel();
                foreach ($json->layanan as $layanan) {
                    $bookingLayananModel->insert([
                        'id_booking' => $bookingId,
                        'id_layanan' => $layanan->id_layanan,
                        'jumlah' => $layanan->jumlah,
                        'subtotal' => $layanan->subtotal
                    ]);
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === false) {
                return $this->fail('Gagal menyimpan booking');
            }

            return $this->respond([
                'success' => true,
                'message' => 'Booking berhasil ditambahkan',
                'id_booking' => $bookingId
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Booking::store] ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkAvailability()
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $json = $this->request->getJSON();
            
            if (empty($json->id_kamar) || empty($json->checkin) || empty($json->checkout)) {
                return $this->fail('Data tidak lengkap');
            }

            // Validate dates
            $checkin = new \DateTime($json->checkin);
            $checkout = new \DateTime($json->checkout);
            
            if ($checkin > $checkout) {
                return $this->fail('Tanggal check-in tidak boleh lebih besar dari check-out');
            }

            // Fix the query syntax
            $exists = $this->bookingModel
                ->where('id_kamar', $json->id_kamar)
                ->whereNotIn('status', ['cancelled', 'checkout'])
                ->groupStart()
                    ->where('checkin <', $json->checkout)
                    ->where('checkout >', $json->checkin)
                ->groupEnd()
                ->countAllResults() > 0;

            return $this->respond([
                'success' => true,
                'available' => !$exists,
                'message' => $exists ? 'Kamar tidak tersedia untuk tanggal yang dipilih' : 'Kamar tersedia'
            ]);

        } catch (\Exception $e) {
            log_message('error', '[Booking::checkAvailability] ' . $e->getMessage());
            return $this->fail('Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function checkin($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return $this->fail('Booking tidak ditemukan');
            }

            $this->bookingModel->update($id, ['status' => 'checkin']);
            $this->kamarModel->update($booking['id_kamar'], ['status' => 'terisi']);

            return $this->respond([
                'success' => true,
                'message' => 'Check-in berhasil'
            ]);
        } catch (\Exception $e) {
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function checkout($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $booking = $this->bookingModel->find($id);
            if (!$booking) {
                return $this->fail('Booking tidak ditemukan');
            }

            $this->bookingModel->update($id, ['status' => 'checkout']);
            $this->kamarModel->update($booking['id_kamar'], ['status' => 'tersedia']);

            return $this->respond([
                'success' => true,
                'message' => 'Check-out berhasil'
            ]);
        } catch (\Exception $e) {
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function updateStatus($id)
    {
        if (!$this->request->isAJAX()) {
            return $this->fail('Invalid Request');
        }

        try {
            $json = $this->request->getJSON();
            $booking = $this->bookingModel->find($id);
            
            if (!$booking) {
                return $this->fail('Booking tidak ditemukan');
            }

            // Update booking status
            $this->bookingModel->update($id, ['status' => $json->status]);

            // Update kamar status
            if ($json->status == 'checkin') {
                $this->kamarModel->update($booking['id_kamar'], ['status' => 'terisi']);
            } 
            elseif ($json->status == 'checkout') {
                $this->kamarModel->update($booking['id_kamar'], ['status' => 'tersedia']);
            }

            return $this->respond([
                'success' => true,
                'message' => 'Status berhasil diupdate'
            ]);

        } catch (\Exception $e) {
            return $this->fail('Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}