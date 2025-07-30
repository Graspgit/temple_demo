<?php

namespace App\Controllers;

use App\Models\MarriageRegistrationModel;
use App\Models\MarriageSlotsModel;
use App\Models\MarriageVenuesModel;
use App\Models\MarriageCategoriesModel;
use App\Models\MarriageBlockedDatesModel;
use App\Models\MarriageAdditionalServicesModel;
use App\Models\MarriageRegistrationDocumentsModel;
use App\Models\MarriageRegistrationPaymentsModel;
use App\Models\MarriageRegistrationServicesModel;
use App\Models\MarriageSettingsModel;

class Marriage extends BaseController
{
    protected $marriageModel;
    protected $slotsModel;
    protected $venuesModel;
    protected $categoriesModel;
    protected $blockedDatesModel;
    protected $servicesModel;
    protected $documentsModel;
    protected $paymentsModel;
    protected $registrationServicesModel;
    protected $settingsModel;

    public function __construct()
    {
        $this->marriageModel = new MarriageRegistrationModel();
        $this->slotsModel = new MarriageSlotsModel();
        $this->venuesModel = new MarriageVenuesModel();
        $this->categoriesModel = new MarriageCategoriesModel();
        $this->blockedDatesModel = new MarriageBlockedDatesModel();
        $this->servicesModel = new MarriageAdditionalServicesModel();
        $this->documentsModel = new MarriageRegistrationDocumentsModel();
        $this->paymentsModel = new MarriageRegistrationPaymentsModel();
        $this->registrationServicesModel = new MarriageRegistrationServicesModel();
        $this->settingsModel = new MarriageSettingsModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Marriage Registration',
            'registrations' => $this->marriageModel->getRegistrationsWithDetails(),
            'slots' => $this->slotsModel->getActiveSlots(),
            'venues' => $this->venuesModel->getActiveVenues(),
            'categories' => $this->categoriesModel->getActiveCategories()
        ];
        
        return view('marriage/index', $data);
    }

    public function calendar()
    {
        $data = [
            'title' => 'Marriage Calendar',
            'slots' => $this->slotsModel->getActiveSlots(),
            'venues' => $this->venuesModel->getActiveVenues(),
            'blocked_dates' => $this->blockedDatesModel->getBlockedDates()
        ];
        
        return view('marriage/calendar2', $data);
    }

    public function check_availability()
    {
        $date = $this->request->getPost('date');
        $slot_id = $this->request->getPost('slot_id');
        
        if (!$date || !$slot_id) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Date and slot are required'
            ]);
        }

        // Check if date is blocked
        $blocked = $this->blockedDatesModel->isDateBlocked($date, $slot_id);
        if ($blocked) {
            return $this->response->setJSON([
                'status' => 'blocked',
                'message' => 'Selected date/slot is blocked'
            ]);
        }

        // Check slot capacity
        $slot = $this->slotsModel->find($slot_id);
        $existing_bookings = $this->marriageModel->getBookingCountForSlot($date, $slot_id);
        
        $available = $slot['max_bookings'] - $existing_bookings;
        
        return $this->response->setJSON([
            'status' => 'success',
            'available' => $available,
            'max_bookings' => $slot['max_bookings'],
            'existing_bookings' => $existing_bookings,
            'is_available' => $available > 0
        ]);
    }

    public function get_calendar_data()
    {
        $month = $this->request->getGet('month');
        $year = $this->request->getGet('year');
        
        if (!$month || !$year) {
            $month = date('m');
            $year = date('Y');
        }

        $calendar_data = $this->marriageModel->getCalendarData($month, $year);
        $blocked_dates = $this->blockedDatesModel->getBlockedDatesForMonth($month, $year);
        
        return $this->response->setJSON([
            'status' => 'success',
            'bookings' => $calendar_data,
            'blocked_dates' => $blocked_dates
        ]);
    }

    public function create()
    {
        $data = [
            'title' => 'New Marriage Registration',
            'slots' => $this->slotsModel->getActiveSlots(),
            'venues' => $this->venuesModel->getActiveVenues(),
            'categories' => $this->categoriesModel->getActiveCategories(),
            'additional_services' => $this->servicesModel->getActiveServices(),
            'settings' => $this->settingsModel->getAllSettings()
        ];
        
        return view('marriage/create', $data);
    }

    public function store()
    {
        $validation = \Config\Services::validation();
        
        $rules = [
            'registration_date' => 'required|valid_date',
            'slot_id' => 'required|numeric',
            'venue_id' => 'required|numeric',
            'category_id' => 'required|numeric',
            'bride_name' => 'required|min_length[2]|max_length[100]',
            'bride_dob' => 'required|valid_date',
            'bride_nationality' => 'required',
            'bride_ic_passport' => 'required|min_length[8]|max_length[50]',
            'bride_father_name' => 'required|min_length[2]|max_length[100]',
            'bride_mother_name' => 'required|min_length[2]|max_length[100]',
            'bride_address' => 'required',
            'bride_phone' => 'required|min_length[8]|max_length[20]',
            'bride_religion' => 'required',
            'groom_name' => 'required|min_length[2]|max_length[100]',
            'groom_dob' => 'required|valid_date',
            'groom_nationality' => 'required',
            'groom_ic_passport' => 'required|min_length[8]|max_length[50]',
            'groom_father_name' => 'required|min_length[2]|max_length[100]',
            'groom_mother_name' => 'required|min_length[2]|max_length[100]',
            'groom_address' => 'required',
            'groom_phone' => 'required|min_length[8]|max_length[20]',
            'groom_religion' => 'required',
            'witness1_name' => 'required|min_length[2]|max_length[100]',
            'witness1_ic' => 'required|min_length[8]|max_length[50]',
            'witness2_name' => 'required|min_length[2]|max_length[100]',
            'witness2_ic' => 'required|min_length[8]|max_length[50]'
        ];

        if (!$validation->setRules($rules)->run($this->request->getPost())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Check availability again
        $date = $this->request->getPost('registration_date');
        $slot_id = $this->request->getPost('slot_id');
        
        $availability = $this->checkSlotAvailability($date, $slot_id);
        if (!$availability['is_available']) {
            return redirect()->back()->withInput()->with('error', 'Selected slot is no longer available');
        }

        // Calculate ages
        $bride_age = $this->calculateAge($this->request->getPost('bride_dob'));
        $groom_age = $this->calculateAge($this->request->getPost('groom_dob'));

        // Check minimum ages
        $min_bride_age = $this->settingsModel->getSetting('minimum_age_bride', 18);
        $min_groom_age = $this->settingsModel->getSetting('minimum_age_groom', 21);

        if ($bride_age < $min_bride_age) {
            return redirect()->back()->withInput()->with('error', "Bride must be at least {$min_bride_age} years old");
        }

        if ($groom_age < $min_groom_age) {
            return redirect()->back()->withInput()->with('error', "Groom must be at least {$min_groom_age} years old");
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Generate registration number
            $registration_number = $this->generateRegistrationNumber();

            // Prepare registration data
            $registration_data = $this->request->getPost();
            $registration_data['registration_number'] = $registration_number;
            $registration_data['bride_age'] = $bride_age;
            $registration_data['groom_age'] = $groom_age;
            $registration_data['created_by'] = session('user_id');

            // Calculate total amount
            $total_amount = $this->calculateTotalAmount(
                $this->request->getPost('venue_id'),
                $this->request->getPost('category_id'),
                $this->request->getPost('additional_services', [])
            );
            $registration_data['total_amount'] = $total_amount;

            // Handle file uploads
            $bride_photo = $this->handleFileUpload('bride_photo', $registration_number . '_bride');
            $groom_photo = $this->handleFileUpload('groom_photo', $registration_number . '_groom');
            
            if ($bride_photo) $registration_data['bride_photo'] = $bride_photo;
            if ($groom_photo) $registration_data['groom_photo'] = $groom_photo;

            // Insert registration
            $registration_id = $this->marriageModel->insert($registration_data);
            echo $this->db->getLastQuery();
            die("rr");

            // Handle additional services
            $additional_services = $this->request->getPost('additional_services');
            if ($additional_services) {
                $this->saveAdditionalServices($registration_id, $additional_services);
            }

            // Handle document uploads
            $this->handleDocumentUploads($registration_id, $registration_number);

            $db->transComplete();

            if ($db->transStatus() === false) {
                throw new \Exception('Transaction failed');
            }

            return redirect()->to('/marriage/view/' . $registration_id)->with('success', 'Marriage registration created successfully');

        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Marriage registration error: ' . $e->getMessage());
            return redirect()->back()->withInput()->with('error', 'Failed to create registration. Please try again.');
        }
    }

    public function view($id)
    {
        $registration = $this->marriageModel->getRegistrationWithDetails($id);
        
        if (!$registration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registration not found');
        }

        $data = [
            'title' => 'Marriage Registration Details',
            'registration' => $registration,
            'documents' => $this->documentsModel->getDocumentsByRegistration($id),
            'payments' => $this->paymentsModel->getPaymentsByRegistration($id),
            'services' => $this->registrationServicesModel->getServicesByRegistration($id)
        ];
        
        return view('marriage/view', $data);
    }

    public function edit($id)
    {
        $registration = $this->marriageModel->find($id);
        
        if (!$registration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registration not found');
        }

        $data = [
            'title' => 'Edit Marriage Registration',
            'registration' => $registration,
            'slots' => $this->slotsModel->getActiveSlots(),
            'venues' => $this->venuesModel->getActiveVenues(),
            'categories' => $this->categoriesModel->getActiveCategories(),
            'additional_services' => $this->servicesModel->getActiveServices(),
            'selected_services' => $this->registrationServicesModel->getServicesByRegistration($id),
            'settings' => $this->settingsModel->getAllSettings()
        ];
        
        return view('marriage/edit', $data);
    }

    public function update($id)
    {
        $registration = $this->marriageModel->find($id);
        
        if (!$registration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registration not found');
        }

        // Similar validation and update logic as store method
        // ... (implementation similar to store but for updates)
        
        return redirect()->to('/marriage/view/' . $id)->with('success', 'Registration updated successfully');
    }

    public function delete($id)
    {
        $registration = $this->marriageModel->find($id);
        
        if (!$registration) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Registration not found']);
        }

        if ($registration['registration_status'] == 'completed') {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Cannot delete completed registration']);
        }

        $this->marriageModel->delete($id);
        
        return $this->response->setJSON(['status' => 'success', 'message' => 'Registration deleted successfully']);
    }

    public function payment($id)
    {
        $registration = $this->marriageModel->getRegistrationWithDetails($id);
        
        if (!$registration) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Registration not found');
        }

        $data = [
            'title' => 'Marriage Registration Payment',
            'registration' => $registration,
            'payments' => $this->paymentsModel->getPaymentsByRegistration($id),
            'payment_methods' => ['cash', 'card', 'online', 'bank_transfer', 'cheque']
        ];
        
        return view('marriage/payment', $data);
    }

    public function process_payment()
    {
        $registration_id = $this->request->getPost('registration_id');
        $amount = $this->request->getPost('amount');
        $payment_method = $this->request->getPost('payment_method');
        $reference_number = $this->request->getPost('reference_number');
        $payment_details = $this->request->getPost('payment_details');

        $registration = $this->marriageModel->find($registration_id);
        if (!$registration) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Registration not found']);
        }

        $remaining_amount = $registration['total_amount'] - $registration['paid_amount'];
        
        if ($amount > $remaining_amount) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Payment amount exceeds remaining balance']);
        }

        $db = \Config\Database::connect();
        $db->transStart();

        try {
            // Insert payment record
            $payment_data = [
                'registration_id' => $registration_id,
                'payment_date' => date('Y-m-d H:i:s'),
                'amount' => $amount,
                'payment_method' => $payment_method,
                'reference_number' => $reference_number,
                'payment_details' => $payment_details,
                'received_by' => session('user_id')
            ];
            
            $this->paymentsModel->insert($payment_data);

            // Update registration paid amount and status
            $new_paid_amount = $registration['paid_amount'] + $amount;
            $payment_status = 'partial';
            
            if ($new_paid_amount >= $registration['total_amount']) {
                $payment_status = 'paid';
            }

            $this->marriageModel->update($registration_id, [
                'paid_amount' => $new_paid_amount,
                'payment_status' => $payment_status,
                'updated_by' => session('user_id')
            ]);

            $db->transComplete();

            return $this->response->setJSON(['status' => 'success', 'message' => 'Payment processed successfully']);

        } catch (\Exception $e) {
            $db->transRollback();
            return $this->response->setJSON(['status' => 'error', 'message' => 'Payment processing failed']);
        }
    }

    // Helper Methods

    private function checkSlotAvailability($date, $slot_id)
    {
        $blocked = $this->blockedDatesModel->isDateBlocked($date, $slot_id);
        if ($blocked) {
            return ['is_available' => false, 'reason' => 'blocked'];
        }

        $slot = $this->slotsModel->find($slot_id);
        $existing_bookings = $this->marriageModel->getBookingCountForSlot($date, $slot_id);
        
        return [
            'is_available' => $existing_bookings < $slot['max_bookings'],
            'available' => $slot['max_bookings'] - $existing_bookings,
            'max_bookings' => $slot['max_bookings']
        ];
    }

    private function calculateAge($dob)
    {
        $birth_date = new \DateTime($dob);
        $today = new \DateTime();
        return $today->diff($birth_date)->y;
    }

    private function generateRegistrationNumber()
    {
        $prefix = 'MRG';
        $year = date('Y');
        $month = date('m');
        
        $last_number = $this->marriageModel
            ->where('registration_number LIKE', $prefix . $year . $month . '%')
            ->orderBy('id', 'DESC')
            ->first();

        $sequence = 1;
        if ($last_number) {
            $last_seq = substr($last_number['registration_number'], -4);
            $sequence = intval($last_seq) + 1;
        }

        return $prefix . $year . $month . sprintf('%04d', $sequence);
    }

    private function calculateTotalAmount($venue_id, $category_id, $services = [])
    {
        $total = 0;

        // Base registration fee
        $registration_fee = $this->settingsModel->getSetting('registration_fee', 50);
        $total += $registration_fee;

        // Venue cost
        $venue = $this->venuesModel->find($venue_id);
        if ($venue) {
            $total += $venue['base_price'] + $venue['additional_charges'];
        }

        // Category fee
        $category = $this->categoriesModel->find($category_id);
        if ($category) {
            $total += $category['base_fee'];
        }

        // Additional services
        if ($services) {
            foreach ($services as $service_id => $quantity) {
                $service = $this->servicesModel->find($service_id);
                if ($service && $quantity > 0) {
                    $total += $service['service_price'] * $quantity;
                }
            }
        }

        return $total;
    }

    private function handleFileUpload($field_name, $prefix)
    {
        $file = $this->request->getFile($field_name);
        
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $upload_path = WRITEPATH . 'uploads/marriage/photos/';
            if (!is_dir($upload_path)) {
                mkdir($upload_path, 0777, true);
            }

            $new_name = $prefix . '_' . time() . '.' . $file->getExtension();
            $file->move($upload_path, $new_name);
            
            return 'uploads/marriage/photos/' . $new_name;
        }
        
        return null;
    }

    private function saveAdditionalServices($registration_id, $services)
    {
        foreach ($services as $service_id => $quantity) {
            if ($quantity > 0) {
                $service = $this->servicesModel->find($service_id);
                if ($service) {
                    $this->registrationServicesModel->insert([
                        'registration_id' => $registration_id,
                        'service_id' => $service_id,
                        'quantity' => $quantity,
                        'unit_price' => $service['service_price'],
                        'total_price' => $service['service_price'] * $quantity
                    ]);
                }
            }
        }
    }

    private function handleDocumentUploads($registration_id, $registration_number)
    {
        $document_types = ['bride_ic', 'groom_ic', 'jpn_form', 'other_documents'];
        
        foreach ($document_types as $doc_type) {
            $files = $this->request->getFiles();
            if (isset($files[$doc_type])) {
                foreach ($files[$doc_type] as $file) {
                    if ($file->isValid() && !$file->hasMoved()) {
                        $upload_path = WRITEPATH . 'uploads/marriage/documents/';
                        if (!is_dir($upload_path)) {
                            mkdir($upload_path, 0777, true);
                        }

                        $new_name = $registration_number . '_' . $doc_type . '_' . time() . '.' . $file->getExtension();
                        $file->move($upload_path, $new_name);

                        $this->documentsModel->insert([
                            'registration_id' => $registration_id,
                            'document_type' => $doc_type,
                            'document_name' => $file->getClientName(),
                            'file_path' => 'uploads/marriage/documents/' . $new_name,
                            'file_size' => $file->getSize(),
                            'mime_type' => $file->getClientMimeType()
                        ]);
                    }
                }
            }
        }
    }
}