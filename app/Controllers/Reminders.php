<?php
namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PermissionModel;

class Reminders extends BaseController
{
	function __construct()
	{
		parent::__construct();
		helper('url');
		$this->model = new PermissionModel();
		if (($this->session->get('login')) == false && $this->session->get('role') != 1) {
			$data['dn_msg'] = 'Please Login';
			return redirect()->to('/login');
		}
	}
	public function index() {
        $data['modules'] = $this->db->query('SELECT id, name FROM module ORDER BY id')->getResultArray();
        
        echo view('template/header');
        echo view('template/sidebar');
        
        // Main container view that holds the tabs
        echo view('reminders/index', $data);
        
        // Load the individual tab contents
        echo view('reminders/custom_messages', $data);
        echo view('reminders/auto_messages', $data);
        // Add more tabs as needed
        
        echo view('template/footer');
    }

    public function customMessages()
    {
        $modules = $this->db->query('SELECT id, name FROM module ORDER BY id')->getResultArray();

        echo view('template/header');
		echo view('template/sidebar');
		echo view('reminders/custom_messages', ['modules' => $modules]);
        echo view('template/footer');
    }

    public function autoMessages()
    {
        echo view('template/header');
		echo view('template/sidebar');
		echo view('reminders/auto_messages');
        echo view('template/footer');
    }

    public function reminderLogs()
    {
        echo view('template/header');
		echo view('template/sidebar');
		echo view('reminders/reminder_logs');
        echo view('template/footer');
    }

    public function getDevotees() {
		$is_member = $this->request->getPost('is_member');
        $user_module_tag = $this->request->getPost('user_module_tag');
        $package_id = $this->request->getPost('package_id');

        $sql = 'SELECT dm.id, dm.name, dm.dob, dm.phone_code, dm.phone_number, dm.email, dm.is_member 
                FROM devotee_management dm 
                WHERE 1=1 ';

        $params = [];
        if ($is_member !== null && $is_member !== '') {
            $sql .= ' AND dm.is_member = ?';
            $params[] = $is_member;
        }

        if ($user_module_tag !== null && $user_module_tag !== '' && $package_id == null) {
            $sql .= ' AND dm.user_module_tag = ?';
            $params[] = $user_module_tag;
        }

        if ($package_id !== null && $package_id !== '') {
            $sql .= ' AND EXISTS (
                        SELECT 1
                        FROM devotee_activity da
                        WHERE da.devotee_id = dm.id 
                        AND da.activity_type = 4
                        AND da.module_type = 6
                        AND JSON_EXTRACT(da.details, "$.booking_id") IN (
                            SELECT bp.booking_id
                            FROM booked_packages bp
                            WHERE bp.package_id = ' . (int)$package_id . '  -- Directly inserting package_id
                            GROUP BY bp.booking_id
                            HAVING COUNT(bp.booking_id) >= 1
                        )
                    )';
        }

        $query = $this->db->query($sql, $params);
        $devotees = $query->getResultArray();

		return $this->response->setJSON($devotees);
	}

    public function getPackages() {
        $module_id = $this->request->getPost('module_id');
        $package_type = $module_id == 6 ? 2 : 1;
        
        $query = $this->db->query('SELECT id, name FROM temple_packages WHERE package_type = ? AND status = 1', [$package_type]);
        $packages = $query->getResultArray();

        echo json_encode($packages);
		exit();
    }

    public function send_Ultra_Message() {
        $messageType = $this->request->getPost('message_type');
        $devotee_ids = json_decode($this->request->getPost('devotee_ids'), true);

        $success_count = 0;
        $instance_id = '124172';
        $token = 'hasuoghdg3osunch';

        if (empty($messageType) || empty($devotee_ids)) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid input provided.']);
        }

        $paramsTemplate = [];
        if ($messageType === 'text') {
            $raw_message = $this->request->getPost('message');
            $message = $this->convertHtmlToWhatsappFormat($raw_message);
            if (empty($message)) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Message cannot be empty']);
            }
            $paramsTemplate['body'] = $message;
            $endpoint = 'chat';

        } elseif ($messageType === 'image') {
            $raw_caption = $this->request->getPost('caption');
            $message = $this->convertHtmlToWhatsappFormat($raw_caption);
            $image = $this->request->getFile('image');

            if (!$image->isValid() || $image->getSize() > 2 * 1024 * 1024) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid or oversized image.']);
            }

            $newName = $image->getRandomName();
            $image->move(FCPATH . 'uploads/messages', $newName);
            $image_url = base_url('uploads/messages/' . $newName);

            $paramsTemplate['image'] = $image_url;
            $paramsTemplate['caption'] = $message;
            $endpoint = 'image';

        } elseif ($messageType === 'pdf') {
            $raw_caption = $this->request->getPost('caption');
            $message = $this->convertHtmlToWhatsappFormat($raw_caption);
            $document = $this->request->getFile('document');

            if (!$document->isValid() || $document->getSize() > 5 * 1024 * 1024) {
                return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid or oversized PDF.']);
            }

            $newName = $document->getRandomName();
            $document->move(FCPATH . 'uploads/messages', $newName);
            $document_url = base_url('uploads/messages/' . $newName);

            $paramsTemplate['document'] = $document_url;
            $paramsTemplate['filename'] = $newName;
            $paramsTemplate['caption'] = $message;
            $endpoint = 'document';

        } else {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Invalid message type']);
        }

        foreach ($devotee_ids as $devotee_id) {
            $devotee = $this->db->table('devotee_management')
                ->select('name, phone_code, phone_number')
                ->where('id', $devotee_id)
                ->get()->getRowArray();

            if (!$devotee || empty($devotee['phone_number'])) {
                continue;
            }

            $phone = $devotee['phone_code'] . $devotee['phone_number'];
            $params = array_merge($paramsTemplate, [
                'token' => $token,
                'to' => $phone,
            ]);

            $curl = curl_init();
            curl_setopt_array($curl, [
                CURLOPT_URL => "https://api.ultramsg.com/instance{$instance_id}/messages/{$endpoint}",
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_CUSTOMREQUEST => "POST",
                CURLOPT_POSTFIELDS => http_build_query($params),
                CURLOPT_HTTPHEADER => ["content-type: application/x-www-form-urlencoded"],
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_SSL_VERIFYPEER => 0,
            ]);

            $response = curl_exec($curl);
            curl_close($curl);

            $response_data = json_decode($response, true);
            if (isset($response_data['sent']) && $response_data['sent'] === 'true') {
                $success_count++;
            }
        }


        $reminder_logs = array();
        $reminder_logs['reminder_type'] = 1;
        $reminder_logs['message_type'] = $messageType === 'text' ? 1 : $messageType === 'image' ? 2 : 3;
        $reminder_logs['date'] = date('Y-m-d');
        $reminder_logs['message'] = $message;
        $reminder_logs['url'] = $newName;
        $reminder_logs['message_count'] = $success_count;
        $reminder_logs['created_at'] = date('Y-m-d H:i:s');
        $reminder_logs['created_by'] = $this->session->get('log_id');
        if ($success_count > 0) {
            $reminder_logs['status'] = 0;
            $this->db->table('reminder_logs')->insert($reminder_logs);
            return $this->response->setJSON(['success' => true, 'sent_count' => $success_count]);
        } else {
            $reminder_logs['status'] = 1;
            $this->db->table('reminder_logs')->insert($reminder_logs);
            return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to send messages']);
        }
    }

    public function convertHtmlToWhatsappFormat($html) {

        $html = str_replace(['<br>', '<br/>', '</p>', '</li>'], "\n", $html);
        $html = preg_replace('/<strong>(.*?)<\/strong>/', '*$1*', $html);
        $html = preg_replace('/<b>(.*?)<\/b>/', '*$1*', $html);
        $html = preg_replace('/<em>(.*?)<\/em>/', '_$1_', $html);
        $html = preg_replace('/<i>(.*?)<\/i>/', '_$1_', $html);
        $html = preg_replace('/<u>(.*?)<\/u>/', '$1', $html);
        $html = preg_replace('/<li>(.*?)<\/li>/', "• $1\n", $html);

        $text = strip_tags($html);

        $text = preg_replace('/\*+/', '*', $text);  // collapse multiple asterisks
        $text = preg_replace('/_+/', '_', $text);   // collapse multiple underscores

        return trim($text);
    }





	// public function sendCustomMessage() {

    //     $data = $this->request->getPost();
    //     $message = $data['message'] ?? '';
    //     $devotee_ids = $data['devotee_ids'] ?? [];

    //     if (empty($message) || empty($devotee_ids)) {
    //         return $this->response->setStatusCode(400)->setJSON(['error' => 'Message and devotee IDs are required']);
    //     }

    //     $instance_id = '124028';  
    //     $token = 'bptzmuob147s7z88';  

    //     $url = "https://api.ultramsg.com/instance{$instance_id}/messages/chat";

    //     $success_count = 0;

    //     foreach ($devotee_ids as $devotee_id) {
    //         $devotee = $this->db->table('devotee_management')->select('name, phone_code, phone_number')->where('id', $devotee_id)->get()->getRowArray();
    //         if ($devotee && !empty($devotee['phone_number'])) {
    //             $phone_number = $devotee['phone_code'] . $devotee['phone_number'];

    //             $params=array(
    //                     'token' => $token,
    //                     'to' => $phone_number,
    //                     'body' => $message
    //                     );
    //             $curl = curl_init();
    //             curl_setopt_array($curl, array(
    //                 CURLOPT_URL => "https://api.ultramsg.com/instance{$instance_id}/messages/chat",
    //                 CURLOPT_RETURNTRANSFER => true,
    //                 CURLOPT_ENCODING => "",
    //                 CURLOPT_MAXREDIRS => 10,
    //                 CURLOPT_TIMEOUT => 30,
    //                 CURLOPT_SSL_VERIFYHOST => 0,
    //                 CURLOPT_SSL_VERIFYPEER => 0,
    //                 CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    //                 CURLOPT_CUSTOMREQUEST => "POST",
    //                 CURLOPT_POSTFIELDS => http_build_query($params),
    //                 CURLOPT_HTTPHEADER => array("content-type: application/x-www-form-urlencoded"),
    //             ));

    //             $response = curl_exec($curl);
    //             $err = curl_error($curl);
    //             curl_close($curl);

    //             $response_data = json_decode($response, true);
                
    //             if (isset($response_data['sent']) && $response_data['sent'] == 'false') {
    //                 $success_count++;
    //             }
    //         }
    //     }

    //     if ($success_count > 0) {
    //         return $this->response->setJSON(['success' => true, 'sent_count' => $success_count]);
    //     } else {
    //         return $this->response->setStatusCode(500)->setJSON(['error' => 'Failed to send messages']);
    //     }
    // }

}