<?php

namespace App\Services;

use Exception;
use Illuminate\Support\Facades\Log;

class WhatsappTemplateService
{
    protected $wa;

    public function __construct(SidobeWhatsappService $wa)
    {
        $this->wa = $wa;
    }

    /**
     * Template pesan ketika booking dibuat
     */
    public function sendBookingCreated($booking)
    {
        try {
            $message = $this->generateBookingMessage($booking);

            $response = $this->wa->sendMessage($booking->customer_phone, $message);

            Log::info('Booking WhatsApp message sent', ['response' => $response]);

            return $response;
        } catch (Exception $e) {
            Log::error('Failed to send booking message: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Format pesan booking
     */
    protected function generateBookingMessage($booking)
    {
        return <<<EOT
Halo {$booking->customer_name}! 👋

Terima kasih telah melakukan booking di *Bengkel AM7* 🚗✨

Berikut detail booking Anda:
- Tanggal: {$booking->booking_date->format('d M Y')}
- Waktu: {$booking->booking_time}
- Kendaraan: {$booking->vehicle_type} ({$booking->vehicle_number})
- Keluhan: {$booking->problem_description}

📌 *Status Booking Anda:* Pending
Saat ini booking Anda sedang menunggu persetujuan dari tim Bengkel AM7.
Kami akan menghubungi Anda kembali setelah jadwal dikonfirmasi.

_Salam hangat,_
*Tim Bengkel AM7*
EOT;
    }

    public function sendBookingStatusUpdated($booking)
    {
        try {
            $message = $this->generateStatusMessage($booking);

            $response = $this->wa->sendMessage($booking->customer_phone, $message);

            return $response; // akan berisi message_id dan info sukses/error
        } catch (\Exception $e) {
            Log::error('Failed to send booking status message: ' . $e->getMessage());
            return ['error' => true, 'message' => $e->getMessage()];
        }
    }

    /**
     * Generate message template berdasarkan status booking
     */
    protected function generateStatusMessage($booking)
    {
        $status = $booking->status;
        $customerName = $booking->customer_name;
        $date = $booking->booking_date->format('d M Y');
        $time = $booking->booking_time;
        $vehicle = "{$booking->vehicle_type} ({$booking->vehicle_number})";
        $problem = $booking->problem_description;

        switch ($status) {
            case 'pending':
                $textStatus = "Pending";
                $note = "Booking Anda sedang menunggu persetujuan dari tim Bengkel AM7.";
                break;

            case 'approved':
                $textStatus = "Approved";
                $note = "Booking Anda telah disetujui. Mohon hadir tepat waktu sesuai jadwal.";
                break;

            case 'rejected':
                $textStatus = "Rejected";
                $note = "Mohon maaf, booking Anda ditolak oleh tim Bengkel AM7.";
                break;

            case 'in_progress':
                $textStatus = "In Progress";
                $mechanicName = $booking->mechanic->name ?? 'Mekanik belum ditentukan';
                $note = "Booking Anda sedang dikerjakan oleh mekanik: *{$mechanicName}*.";
                break;

            case 'completed':
                $textStatus = "Completed";
                $note = "Booking Anda telah selesai dikerjakan. Silakan datang ke kasir untuk melakukan pembayaran. Terima kasih telah mempercayakan kendaraan Anda kepada Bengkel AM7.";
                break;

            case 'cancelled':
                $textStatus = "Cancelled";
                $note = "Booking Anda telah dibatalkan.";
                break;

            default:
                $textStatus = ucfirst($status);
                $note = "";
                break;
        }

        return <<<EOT
Halo {$customerName}! 👋

Status booking Anda saat ini: *{$textStatus}*

Detail booking:
- Tanggal: {$date}
- Waktu: {$time}
- Kendaraan: {$vehicle}
- Keluhan: {$problem}

{$note}

_Salam hangat,_
*Tim Bengkel AM7*
EOT;
    }
}
