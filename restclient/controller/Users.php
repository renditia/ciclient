<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Users extends CI_Controller
{

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     *	- or -
     * 		http://example.com/index.php/welcome/index
     *	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */

    //base url, restserver 
    var $API ="http://localhost:81/AAwebprogsemesterdua/silabus5_advancephp3/rest-api-server/cisbadmin2/";

    public function __construct()
    {
        //row_array
        //call parent construct
        parent::__construct();
        //load lybrary form validation | 
        $this->load->library('form_validation');
        $this->load->model('M_users', 'musers');
    }

    public function index()
    {
        $key =   $this->input->post('keyword');
        
        //post data send to API
        $datapost = [
            'action' => 'QR',
            'keyword' => $key
        ];

        //Before : ini mendapatkan list mahasiswa menggunakan direct connect ke db
        // $data['mahasiswa'] = $this->musers->getDataMhs($key);
        //after : untuk mendapatkan list mahasiswa menggunakan API dari Rest API CI
        $dataarr = json_decode($this->curl->simple_post($this->API.'mahasiswars', $datapost),true);

        //assign data from API
        $data['mahasiswa'] = $dataarr['data'];

        //lov jurusan (langsung connect ke database), ini juga bisa diganti menggunakan API
        $data['jurusan'] = $this->musers->getListJurusan();

        $this->load->view('users/header');
        $this->load->view('users/home', $data);
        $this->load->view('users/footer');
    }

    public function adddata()
    {

        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required|trim'
        );
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim');
        $this->form_validation->set_rules('npm', 'NPM', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');


        if ($this->form_validation->run() == false) {
            $data['jurusan'] = $this->musers->getListJurusan();
            $data['title'] = 'Manage Mahasiswa';
            $sql =  $this->db->get('mahasiswa');
            $data['mahasiswa'] = $sql->result_array(); #result_array() : output array, result_object
            $this->load->view('users/header');
            $this->load->view('users/home', $data);
            $this->load->view('users/footer');
        } else {

            //insert account
            $data = [
                'action' => 'IN', // action insert
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'jurusan' => $this->input->post('jurusan'),
                'npm' => $this->input->post('npm'),
                'email' => $this->input->post('email')
            ];

            //Before : insert database (Query Builder)
            //$this->db->insert('mahasiswa', $data);

            //After :insert using API
            $this->curl->simple_post($this->API.'mahasiswars', $data);


            // set_flashdata
            $this->session->set_flashdata('message', 'Di Tambahkan');
            redirect('users');
        }
    }

    public function deletedata()
    {
        echo $this->input->get('npm');
        if ($this->input->get('npm')) {
            $npm = $this->input->get('npm');
            //Before : delete data usgin build query
            //  $this->db->delete('mahasiswa', array('npm' =>  $npm));

            $data = [
                'action' => 'DL', // action insert
                'npm' => $npm
            ];

            // After : delete using API
            $this->curl->simple_post($this->API.'mahasiswars', $data);

            redirect('users');
        }
    }

    public function updatedata()
    {
        /*Select using where clause*/
        // this->db->get_where('mytable', array('id' => $id), $limit, $offset);

        $this->form_validation->set_rules(
            'nama',
            'Nama',
            'required|trim'
        );
        $this->form_validation->set_rules('email', 'Email', 'required|trim|valid_email');
        $this->form_validation->set_rules('jurusan', 'Jurusan', 'required|trim');
        $this->form_validation->set_rules('npm', 'NPM', 'required|trim');
        $this->form_validation->set_rules('alamat', 'Alamat', 'required|trim');

        $npm =  $this->input->get('npm', true);

        if ($this->form_validation->run() == false) {

            // htmlspecialchars($this->input->get('npm'));
            $data['jurusan'] = $this->musers->getListJurusan();
            $data['mahasiswa'] = $this->db->get_where('mahasiswa', array('npm' => $npm))->row_array();
            $this->load->view('users/header');
            $this->load->view('users/updatedata', $data);
            $this->load->view('users/footer');
        } else {
            //update account
            $npm = $this->input->post('npm');
            $data = [
                'action' => 'UP',
                'nama' => $this->input->post('nama'),
                'alamat' => $this->input->post('alamat'),
                'jurusan' => $this->input->post('jurusan'),
                'email' => $this->input->post('email'),
                'npm' => $npm
            ];

            //Before : update database (Query Builder)
            // $this->db->where('npm', $npm);
            // $this->db->update('mahasiswa', $data);

            //After : update data using API
            $this->curl->simple_post($this->API.'mahasiswars', $data);
            
            // set_flashdata
            $this->session->set_flashdata('message', 'Mahasiswa Has Been Updated !');
            redirect('users');
         

        }
    }
}
