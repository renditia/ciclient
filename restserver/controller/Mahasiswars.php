<?php 

require APPPATH . '/libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Mahasiswars extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->database();
    }

    //insert data pasiens
    function index_post(){

        $action = $this->input->post('action', true);

        if($action == '' || $action == NULL || $action == ""){
            $this->response(['status' => false,
            'data' => 'Bad Request'
            ], REST_Controller::HTTP_BAD_REQUEST );
        }else{
            //QR : Query, IN : Insert , DL : Delete, UP : Update
            if($action == 'QR'){
                $keword = $this->input->post('keyword');
                if($keword == ''){
                    $mahasiswa = $this->db->get('mahasiswa')->result();
                }else {
                    var_dump($keword);
                    // die;
                    # code...
                    $this->db->like('upper(nama)    ', strtoupper($keword));
                    $this->db->or_like('upper(alamat)', strtoupper($keword));
                    $this->db->or_like('upper(jurusan)', strtoupper($keword));
                    $this->db->or_like('upper(npm)', strtoupper($keword));
                    $mahasiswa = $this->db->get('mahasiswa')->result();
                }

                if($mahasiswa){
                    $this->response(['status' => true,
                            'action' => $action,
                            'keyword' => $keword,
                            'data' => $mahasiswa
                    ], REST_Controller::HTTP_OK );
                }else{
                    $this->response(['status' => false,
                            'action' => $action,
                            'data' => 'data not found'
                    ], REST_Controller::HTTP_NOT_FOUND );
                }
            // action QR
            } 
            //IN : insert 
            else if($action == 'IN'){
                $arrdata = ['nama' =>  $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),    
                'jurusan' => $this->input->post('jurusan'),    
                'email' => $this->input->post('email'),
                'npm' => $this->input->post('npm')
                ];
                // $this->db->set('id_p', '(select coalesce(max(id_p),0) + 1 from pasiens)', false);
                $mahasiswa = $this->db->insert('mahasiswa',$arrdata);

                if($mahasiswa){
                    $this->response(['status' => true,
                            'action' => $action,
                            'data' => 'OK, Data Inserted.'
                    ], REST_Controller::HTTP_OK );
                }else{
                    $this->response(['status' => false,
                            'action' => $action,
                            'data' => 'Bad Request'
                    ], REST_Controller::HTTP_BAD_REQUEST );
                }
            }
            else if ($action == 'UP'){
                $arrdata = ['nama' =>  $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),    
                'jurusan' => $this->input->post('jurusan'),    
                'email' => $this->input->post('email') //,
                // 'npm' => $this->input->post('npm')
                ];
                // $this->db->set('id_p', '(select coalesce(max(id_p),0) + 1 from pasiens)', false);
                $mahasiswa = $this->db->update('mahasiswa',$arrdata, ['npm' => $this->input->post('npm') ]);

                if($mahasiswa){
                    $this->response(['status' => true,
                            'action' => $action,
                            'data' => 'OK, Data Updated.'
                    ], REST_Controller::HTTP_OK );
                }else{
                    $this->response(['status' => false,
                            'action' => $action,
                            'data' => 'Bad Request'
                    ], REST_Controller::HTTP_BAD_REQUEST );
                }
            } else if($action == 'DL'){
                // DL : Delete
                $npm = $this->post('npm');
                #var_dump( $id); die;
                if ($npm == '') {
                    $this->response(['status' => false,
                    'data' => 'key missed'
                    ], REST_Controller::HTTP_BAD_REQUEST );
                } else {
                    $this->db->where('npm', $id);
                    $mahasiswa = $this->db->delete('mahasiswa');
                }
        
                if($mahasiswa){
                    $this->response(['status' => true,
                            'data' => 'Data Deleted',
                            'action' => $action
                    ], REST_Controller::HTTP_OK );
                }else{
                    $this->response(['status' => false,
                    'data' => 'data not found'
                     ], REST_Controller::HTTP_NOT_FOUND );
                }    
            }else{
                $this->response(['status' => false,
                'data' => 'Bad Request',
                'action' => $action
                ], REST_Controller::HTTP_BAD_REQUEST );
            }

        }
    }

}
?>
