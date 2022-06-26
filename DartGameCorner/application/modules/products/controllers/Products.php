<?php

defined('BASEPATH') or exit('No direct script access allowed');


class Products extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('Products_model');
        $this->load->library('form_validation');
        $this->load->helper(array('form', 'url'));
        $this->simple_login->cek_login();
        $this->simple_login->cek_akses();
    }

    public function index()
    {
        $nama = $this->session->userdata('name');
        $data['nama'] = $nama;
        $data['judul'] = 'List Produk';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['kategori'] = $this->Products_model->getAllKategori();
        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = 'http://localhost:8073/ecomm2022/DartGameCorner/products/index/';
        $config['total_rows'] = $this->Products_model->getTotalProducts();
        $config['per_page'] = 10;

        $this->pagination->initialize($config);
        $data['start'] = $this->uri->segment(3);
        $data['products'] = $this->Products_model->getListProducts($config['per_page'], $data['start']);

        //Search
        if ($this->input->post('keyword')) {
            $data['products'] = $this->Products_model->cariDataProducts();
            $this->session->set_userdata('keyword', $this->input->post('keyword'));
            //$this->session->set_userdata('keyword', $data['keyword']);
        } else {
            $data['keyword'] = $this->session->userdata('keyword');
        }
        $this->load->view('templates/user_header', $data);
        $this->load->view('templates/user_sidebar', $data);
        $this->load->view('products/list', $data);
        $this->load->view('templates/user_footer');
    }

    public function list()
    {
        $nama = $this->session->userdata('name');
        $data['nama'] = $nama;
        $data['judul'] = 'List Produk';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        // Pagination
        $this->load->library('pagination');
        $config['base_url'] = 'http://localhost:8073/ecomm2022/DartGameCorner/products/list/';
        $config['total_rows'] = $this->Products_model->getTotalProducts();
        $config['per_page'] = 5;
        $config['num_links'] = 2;

        $this->pagination->initialize($config);
        $data['start'] = $this->uri->segment(3);
        $data['products'] = $this->Products_model->getListProducts($config['per_page'], $data['start']);
        //Search
        if ($this->input->post('keyword')) {
            $data['products'] = $this->Products_model->cariDataProducts();
            $this->session->set_userdata('keyword', $this->input->post('keyword'));
            //$this->session->set_userdata('keyword', $data['keyword']);
        } else {
            $data['keyword'] = $this->session->userdata('keyword');
        }

        $this->load->view('templates/user_header', $data);
        $this->load->view('templates/user_sidebar', $data);
        $this->load->view('products/list', $data);
        $this->load->view('templates/user_footer');
    }

    public function tambahkategori()
    {
        $data['judul'] = 'Tambah Kategori';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['kategori'] = $this->Products_model->getAllKategori();
        $this->form_validation->set_rules('nama_kategori', 'Nama_kategori', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/user_header', $data);
            $this->load->view('templates/user_sidebar', $data);
            $this->load->view('products/tambah_kategori');
            $this->load->view('templates/user_footer');
        } else {
            $this->Products_model->tambahDataKategori();
            $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">Kategori Baru Ditambahkan!</div>');
            redirect('products/tambahkategori');
        }
    }

    public function tambahstok()
    {
        $data['judul'] = 'Tambah Stok Produk';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['products'] = $this->Products_model->getAllProducts();
        $data['tambahstok'] = $this->Products_model->getAllTambahStok();
        $this->form_validation->set_rules('id_produk', 'Nama Produk', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/user_header', $data);
            $this->load->view('templates/user_sidebar', $data);
            $this->load->view('products/tambah_stok', $data);
            $this->load->view('templates/user_footer');
        } else {
            $this->Products_model->tambahDataStok();
            $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">Stok Produk Ditambahkan!</div>');
            redirect('products/tambahstok');
        }
    }

    public function tambah()
    {
        $data['judul'] = 'Tambah Data';
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['kategori'] = $this->Products_model->getAllKategori();
        $this->form_validation->set_rules('gambar', 'Gambar');
        $this->form_validation->set_rules('nama_produk', 'Nama_produk', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/user_header', $data);
            $this->load->view('templates/user_sidebar', $data);
            $this->load->view('products/tambah');
            $this->load->view('templates/user_footer');
        } else {
            $this->Products_model->tambahDataProducts();
            $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">Produk Baru Ditambahkan!</div>');
            redirect('products/list');
        }
    }

    public function hapus($id_produk)
    {
        $this->Products_model->hapusDataProducts($id_produk);
        $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">Produk Dihapus!</div>');
        redirect('products/list');
    }

    public function hapuskategori($id_kategori)
    {
        $this->Products_model->hapusDataKategori($id_kategori);
        $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">Kategori Dihapus!</div>');
        redirect('products/tambahkategori');
    }

    public function detail($id_produk)
    {
        $data['judul'] = 'Detail Data';
        $data['kategori'] = $this->Products_model->getAllKategori();
        $data['products'] = $this->Products_model->getProductsById($id_produk);
        $this->load->view('templates/header', $data);
        $this->load->view('products/detail', $data);
        $this->load->view('templates/footer');
    }

    public function edit($id_produk)
    {
        $nama = $this->session->userdata('name');
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['products'] = $this->db->get_where('products')->row_array();
        $data['judul'] = 'Edit Produk';
        $data['nama'] = $nama;
        $data['products'] = $this->Products_model->getProductsById($id_produk);
        $data['id_produk'] = $id_produk;
        $data['kategori'] = $this->Products_model->getAllKategori();

        $this->form_validation->set_rules('nama_produk', 'Nama_produk', 'required');
        $this->form_validation->set_rules('stok', 'Stok', 'required|numeric');
        $this->form_validation->set_rules('harga', 'Harga', 'required|numeric');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'required');
        $this->form_validation->set_rules('kategori', 'Kategori', 'required');

        if ($this->form_validation->run() == false) {
            $this->load->view('templates/user_header', $data);
            $this->load->view('templates/user_sidebar', $data);
            $this->load->view('products/edit', $data);
            $this->load->view('templates/user_footer');
        } else {
            // cek jika ada gambar yang akan diupload
            $upload_gambar = $_FILES['gambar']['name'];
            if ($upload_gambar) {
                $config['allowed_types'] = 'gif|jpg|png|jpeg';
                $config['max_size']     = '2048';
                $config['upload_path'] = './assets/images/produk/';

                $this->load->library('upload', $config);

                if ($this->upload->do_upload('gambar')) {
                    $old_gambar = $data['products']['gambar'];
                    if ($old_gambar != 'default.jpg') {
                        unlink(FCPATH . 'assets/images/produk/' . $old_gambar);
                    }
                    $new_gambar = $this->upload->data('file_name');
                    $this->db->set('gambar', $new_gambar);
                } else {
                    echo $this->upload->display_errors();
                }
            }

            $this->db->set('nama_produk', $this->input->post('nama_produk'));
            $this->db->set('harga', $this->input->post('harga'));
            $this->db->set('stok', $this->input->post('stok'));
            $this->db->set('keterangan', $this->input->post('keterangan'));
            $this->db->set('kategori', $this->input->post('kategori'));
            $this->db->where('id_produk', $id_produk);
            $this->db->update('products');
            $this->session->set_flashdata('flash', '<div class="alert alert-success" role="alert">
            Your product has been updated!</div>');
            redirect('products/list');
        }
    }

    public function kategori($id_kategori)
    {
        $id_kategori = $this->uri->segment(3);
        $nama = $this->session->userdata('name');
        $data['nama'] = $nama;
        $data['user'] = $this->db->get_where('user', ['email' => $this->session->userdata('email')])->row_array();
        $data['judul'] = 'Kategori';
        $data['judulh1'] = $this->db->get_where('kategori', ['id_kategori' => $this->uri->segment(3)])->row_array();
        $data['kategori'] = $this->Products_model->getAllKategori();
        $data['products'] = $this->Products_model->getProductsByKategori($id_kategori);
        $this->load->view('templates/user_header', $data);
        $this->load->view('templates/user_sidebar', $data);
        $this->load->view('kategori', $data);
        $this->load->view('templates/user_footer');
    }
}
