<?php
// app/Controllers/Rom.php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\RomBookingModel;
use App\Models\RomSlotModel;
use App\Models\RomVenueModel;
use App\Models\RomCoupleModel;
use App\Models\RomDocumentModel;
use App\Models\RomPaymentModel;
use App\Models\RomCalendarModel;
use App\Models\PaymentModeModel;
use App\Models\PermissionModel;

class Rom extends BaseController
{
    protected $bookingModel;
    protected $slotModel;
    protected $venueModel;
    protected $coupleModel;
    protected $documentModel;
    protected $paymentModel;
    protected $calendarModel;
    protected $paymentModeModel;

    public function __construct()
    {
        $this->bookingModel = new RomBookingModel();
        $this->slotModel = new RomSlotModel();
        $this->venueModel = new RomVenueModel();
        $this->coupleModel = new RomCoupleModel();
        $this->documentModel = new RomDocumentModel();
        $this->paymentModel = new RomPaymentModel();
        $this->calendarModel = new RomCalendarModel();
        $this->paymentModeModel = new PaymentModeModel();
        $this->model = new PermissionModel();
    }

    public function index()
    {
        $data['permission'] = $this->model->get_permission('rom_settings');
        $data['bookings'] = $this->bookingModel
            ->select('rom_bookings.*, rs.slot_name, rs.start_time, rs.end_time, rv.venue_name')
            ->join('rom_slot_settings rs', 'rs.id = rom_bookings.slot_id')
            ->join('rom_venues rv', 'rv.id = rom_bookings.venue_id')
            ->orderBy('rom_bookings.created_at', 'DESC')
            ->findAll();

        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/index', $data);
		echo view('template/footer');
    }

    public function create()
    {
        $data['permission'] = $this->model->get_permission('rom_settings');
        $data['slots'] = $this->slotModel->getActiveSlots();
        $data['venues'] = $this->venueModel->getActiveVenues();
        $data['payment_modes'] = $this->paymentModeModel
            ->where('status', 1)
            ->where('rom', 1)
            ->findAll();

        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/create', $data);
		echo view('template/footer');
    }

    public function checkAvailability()
    {
        $date = $this->request->getPost('date');
        $slotId = $this->request->getPost('slot_id');

        // Check calendar availability
        $dateAvailability = $this->calendarModel->checkDateAvailability($date);
        if (!$dateAvailability['available']) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Date not available: ' . $dateAvailability['reason']
            ]);
        }

        // Check slot availability
        $slotAvailability = $this->slotModel->checkSlotAvailability($date, $slotId);
        
        return $this->response->setJSON([
            'success' => true,
            'available' => $slotAvailability['available'],
            'remaining' => $slotAvailability['remaining']
        ]);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'booking_date' => 'required|valid_date',
            'slot_id' => 'required|numeric',
            'venue_id' => 'required|numeric',
            'bride_name' => 'required',
            'bride_dob' => 'required|valid_date',
            'bride_nationality' => 'required',
            'bride_ic' => 'required',
            'groom_name' => 'required',
            'groom_dob' => 'required|valid_date',
            'groom_nationality' => 'required',
            'groom_ic' => 'required',
            'payment_mode_id' => 'required|numeric',
            'payment_amount' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Create booking
            $venue = $this->venueModel->find($this->request->getPost('venue_id'));
            $bookingData = [
                'booking_no' => $this->bookingModel->generateBookingNo(),
                'booking_date' => $this->request->getPost('booking_date'),
                'slot_id' => $this->request->getPost('slot_id'),
                'venue_id' => $this->request->getPost('venue_id'),
                'user_id' => session()->get('user_id'),
                'total_amount' => $venue['price'] + ($this->request->getPost('extra_charges') ?? 0),
                'security_deposit' => $this->request->getPost('security_deposit') ?? 0,
                'discount_amount' => $this->request->getPost('discount_amount') ?? 0,
                'extra_charges' => $this->request->getPost('extra_charges') ?? 0,
                'tax_amount' => $this->request->getPost('tax_amount') ?? 0,
                'remarks' => $this->request->getPost('remarks')
            ];

            $bookingId = $this->bookingModel->insert($bookingData);

            // Save bride details
            $brideAge = date_diff(date_create($this->request->getPost('bride_dob')), date_create('today'))->y;
            $brideData = [
                'booking_id' => $bookingId,
                'person_type' => 'bride',
                'name' => $this->request->getPost('bride_name'),
                'dob' => $this->request->getPost('bride_dob'),
                'age' => $brideAge,
                'nationality' => $this->request->getPost('bride_nationality'),
                'ic_passport_no' => $this->request->getPost('bride_ic'),
                'phone' => $this->request->getPost('bride_phone'),
                'email' => $this->request->getPost('bride_email'),
                'address' => $this->request->getPost('bride_address'),
                'occupation' => $this->request->getPost('bride_occupation'),
                'father_name' => $this->request->getPost('bride_father'),
                'mother_name' => $this->request->getPost('bride_mother')
            ];

            // Handle bride photo upload
            $bridePhoto = $this->request->getFile('bride_photo');
            if ($bridePhoto && $bridePhoto->isValid() && !$bridePhoto->hasMoved()) {
                $newName = $bridePhoto->getRandomName();
                $bridePhoto->move(WRITEPATH . 'uploads/rom/photos', $newName);
                $brideData['photo'] = $newName;
            }

            $this->coupleModel->insert($brideData);

            // Save groom details
            $groomAge = date_diff(date_create($this->request->getPost('groom_dob')), date_create('today'))->y;
            $groomData = [
                'booking_id' => $bookingId,
                'person_type' => 'groom',
                'name' => $this->request->getPost('groom_name'),
                'dob' => $this->request->getPost('groom_dob'),
                'age' => $groomAge,
                'nationality' => $this->request->getPost('groom_nationality'),
                'ic_passport_no' => $this->request->getPost('groom_ic'),
                'phone' => $this->request->getPost('groom_phone'),
                'email' => $this->request->getPost('groom_email'),
                'address' => $this->request->getPost('groom_address'),
                'occupation' => $this->request->getPost('groom_occupation'),
                'father_name' => $this->request->getPost('groom_father'),
                'mother_name' => $this->request->getPost('groom_mother')
            ];

            // Handle groom photo upload
            $groomPhoto = $this->request->getFile('groom_photo');
            if ($groomPhoto && $groomPhoto->isValid() && !$groomPhoto->hasMoved()) {
                $newName = $groomPhoto->getRandomName();
                $groomPhoto->move(WRITEPATH . 'uploads/rom/photos', $newName);
                $groomData['photo'] = $newName;
            }

            $this->coupleModel->insert($groomData);

            // Handle document uploads
            $documents = $this->request->getFiles();
            if (isset($documents['documents'])) {
                foreach ($documents['documents'] as $docType => $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $newName = $file->getRandomName();
                        $file->move(WRITEPATH . 'uploads/rom/documents', $newName);
                        
                        $this->documentModel->insert([
                            'booking_id' => $bookingId,
                            'document_type' => $docType,
                            'document_name' => $file->getClientName(),
                            'file_path' => $newName,
                            'uploaded_by' => session()->get('user_id')
                        ]);
                    }
                }
            }

            // Process payment
            $paymentData = [
                'booking_id' => $bookingId,
                'payment_date' => date('Y-m-d H:i:s'),
                'payment_mode_id' => $this->request->getPost('payment_mode_id'),
                'payment_type' => $this->request->getPost('payment_type'),
                'amount' => $this->request->getPost('payment_amount'),
                'transaction_id' => $this->request->getPost('transaction_id'),
                'remarks' => $this->request->getPost('payment_remarks'),
                'created_by' => session()->get('user_id')
            ];

            // Check if payment gateway
            $paymentMode = $this->paymentModeModel->find($paymentData['payment_mode_id']);
            if ($paymentMode['is_payment_gateway'] == 1) {
                // TODO: Implement payment gateway integration
                // For now, we'll complete the payment like COD
                $paymentData['payment_status'] = 'success';
                $paymentData['payment_gateway_response'] = 'Payment gateway integration pending';
            } else {
                $paymentData['payment_status'] = 'success';
            }

            $this->paymentModel->insert($paymentData);

            // Update booking payment status
            $this->updateBookingPaymentStatus($bookingId);

            // Update slot booking count
            $this->slotModel->updateSlotBookingCount(
                $this->request->getPost('booking_date'),
                $this->request->getPost('slot_id')
            );

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/rom')->with('success', 'Marriage registration booking created successfully!');

        } catch (\Exception $e) {
            $db->transRollback();
            return redirect()->back()->withInput()->with('error', 'Failed to create booking: ' . $e->getMessage());
        }
    }

    public function view($id)
    {
        $data['booking'] = $this->bookingModel->getBookingWithDetails($id);
        $data['couple'] = $this->coupleModel->getCoupleByBooking($id);
        $data['documents'] = $this->documentModel->getDocumentsByBooking($id);
        $data['payments'] = $this->paymentModel->getPaymentsByBooking($id);
        $data['payment_summary'] = $this->paymentModel->getPaymentSummary($id);

        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/view', $data);
		echo view('template/footer');
    }

    public function addPayment($bookingId)
    {
        $validation = \Config\Services::validation();
        $validation->setRules([
            'payment_mode_id' => 'required|numeric',
            'payment_type' => 'required',
            'amount' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->with('errors', $validation->getErrors());
        }

        $paymentData = [
            'booking_id' => $bookingId,
            'payment_date' => date('Y-m-d H:i:s'),
            'payment_mode_id' => $this->request->getPost('payment_mode_id'),
            'payment_type' => $this->request->getPost('payment_type'),
            'amount' => $this->request->getPost('amount'),
            'transaction_id' => $this->request->getPost('transaction_id'),
            'remarks' => $this->request->getPost('remarks'),
            'created_by' => session()->get('user_id'),
            'payment_status' => 'success'
        ];

        $this->paymentModel->insert($paymentData);
        $this->updateBookingPaymentStatus($bookingId);

        return redirect()->back()->with('success', 'Payment added successfully!');
    }

    private function updateBookingPaymentStatus($bookingId)
    {
        $booking = $this->bookingModel->find($bookingId);
        $paymentSummary = $this->paymentModel->getPaymentSummary($bookingId);
        $totalPaid = $paymentSummary['total_paid'] ?? 0;

        $updateData = ['paid_amount' => $totalPaid];

        if ($totalPaid >= $booking['total_amount']) {
            $updateData['payment_status'] = 'paid';
        } elseif ($totalPaid > 0) {
            $updateData['payment_status'] = 'partial';
        }

        $this->bookingModel->update($bookingId, $updateData);
    }

    // Settings Methods
    public function slots()
    {
        $data['slots'] = $this->slotModel->findAll();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/settings/slots', $data);
		echo view('template/footer');
    }

    public function saveSlot()
    {
        $id = $this->request->getPost('id');
        $data = [
            'slot_name' => $this->request->getPost('slot_name'),
            'start_time' => $this->request->getPost('start_time'),
            'end_time' => $this->request->getPost('end_time'),
            'max_bookings' => $this->request->getPost('max_bookings'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        if ($id) {
            $this->slotModel->update($id, $data);
        } else {
            $this->slotModel->insert($data);
        }

        return redirect()->back()->with('success', 'Slot saved successfully!');
    }

    public function venues()
    {
        $data['venues'] = $this->venueModel->findAll();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/settings/venues', $data);
		echo view('template/footer');
    }

    public function saveVenue()
    {
        $id = $this->request->getPost('id');
        $data = [
            'venue_name' => $this->request->getPost('venue_name'),
            'venue_type' => $this->request->getPost('venue_type'),
            'capacity' => $this->request->getPost('capacity'),
            'description' => $this->request->getPost('description'),
            'price' => $this->request->getPost('price'),
            'is_active' => $this->request->getPost('is_active') ?? 1
        ];

        if ($id) {
            $this->venueModel->update($id, $data);
        } else {
            $this->venueModel->insert($data);
        }

        return redirect()->back()->with('success', 'Venue saved successfully!');
    }

    public function calendar()
    {
        $data['unavailable_dates'] = $this->calendarModel->findAll();
        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/settings/calendar', $data);
		echo view('template/footer');
    }

    public function saveCalendarAvailability()
    {
        $date = $this->request->getPost('date');
        $isAvailable = $this->request->getPost('is_available');
        $reason = $this->request->getPost('reason');

        $existing = $this->calendarModel->where('date', $date)->first();

        if ($existing) {
            $this->calendarModel->update($existing['id'], [
                'is_available' => $isAvailable,
                'reason' => $reason
            ]);
        } else {
            $this->calendarModel->insert([
                'date' => $date,
                'is_available' => $isAvailable,
                'reason' => $reason
            ]);
        }

        return redirect()->back()->with('success', 'Calendar availability updated!');
    }

    // Reports
    public function reports()
    {
        $filters = [
            'date_from' => $this->request->getGet('date_from'),
            'date_to' => $this->request->getGet('date_to'),
            'payment_status' => $this->request->getGet('payment_status'),
            'payment_mode' => $this->request->getGet('payment_mode')
        ];

        $query = $this->bookingModel
            ->select('rom_bookings.*, rs.slot_name, rv.venue_name')
            ->join('rom_slot_settings rs', 'rs.id = rom_bookings.slot_id')
            ->join('rom_venues rv', 'rv.id = rom_bookings.venue_id');

        if ($filters['date_from']) {
            $query->where('booking_date >=', $filters['date_from']);
        }
        if ($filters['date_to']) {
            $query->where('booking_date <=', $filters['date_to']);
        }
        if ($filters['payment_status']) {
            $query->where('payment_status', $filters['payment_status']);
        }

        $data['bookings'] = $query->findAll();
        $data['filters'] = $filters;
        $data['payment_modes'] = $this->paymentModeModel->where('rom', 1)->findAll();

        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/reports', $data);
		echo view('template/footer');
    }

    public function dueReport()
    {
        $data['bookings'] = $this->bookingModel
            ->select('rom_bookings.*, rs.slot_name, rv.venue_name, 
                     (rom_bookings.total_amount - rom_bookings.paid_amount) as due_amount')
            ->join('rom_slot_settings rs', 'rs.id = rom_bookings.slot_id')
            ->join('rom_venues rv', 'rv.id = rom_bookings.venue_id')
            ->where('payment_status !=', 'paid')
            ->where('booking_status !=', 'cancelled')
            ->findAll();

        echo view('template/header');
		echo view('template/sidebar');
		echo view('rom/due_report', $data);
		echo view('template/footer');
    }

    public function print($id)
    {
        $data['booking'] = $this->bookingModel->getBookingWithDetails($id);
        $data['couple'] = $this->coupleModel->getCoupleByBooking($id);
        $data['documents'] = $this->documentModel->getDocumentsByBooking($id);
        $data['payments'] = $this->paymentModel->getPaymentsByBooking($id);

        return view('rom/print', $data);
    }

    public function deleteSlot($id)
    {
        $this->slotModel->delete($id);
        return redirect()->to('/rom/slots')->with('success', 'Slot deleted successfully!');
    }

    public function deleteVenue($id)
    {
        // Check if venue has bookings
        $bookingCount = $this->bookingModel->where('venue_id', $id)->countAllResults();
        if ($bookingCount > 0) {
            return redirect()->back()->with('error', 'Cannot delete venue with existing bookings!');
        }
        
        $this->venueModel->delete($id);
        return redirect()->to('/rom/venues')->with('success', 'Venue deleted successfully!');
    }

    public function blockDateRange()
    {
        $startDate = $this->request->getPost('start_date');
        $endDate = $this->request->getPost('end_date');
        $reason = $this->request->getPost('reason');

        $currentDate = $startDate;
        while ($currentDate <= $endDate) {
            $existing = $this->calendarModel->where('date', $currentDate)->first();
            
            if (!$existing) {
                $this->calendarModel->insert([
                    'date' => $currentDate,
                    'is_available' => 0,
                    'reason' => $reason
                ]);
            }
            
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }

        return redirect()->back()->with('success', 'Date range blocked successfully!');
    }

    public function monthlyReport()
    {
        // TODO: Implement monthly report
        return view('rom/monthly_report');
    }

    public function venueReport()
    {
        // TODO: Implement venue-wise report
        return view('rom/venue_report');
    }

    public function paymentModeReport()
    {
        // TODO: Implement payment mode report
        return view('rom/payment_mode_report');
    }

    public function exportReport()
    {
        // TODO: Implement export functionality
        return $this->response->download('report.csv');
    }
}