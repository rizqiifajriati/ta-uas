<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Keranjang_model extends CI_Model
{
    public function get_produk_all()
    {
        $query = $this->db->get('products');
        return $query->result_array();
    }
    public function get_produk_kategori($kategori)
    {
        if ($kategori > 0) {
            $this->db->where('kategori', $kategori);
        }
        $query = $this->db->get('products');
        return $query->result_array();
    }
    public function get_kategori_all()
    {
        $query = $this->db->get('kategori');
        return $query->result_array();
    }
    public function get_produk_id($id)
    {
        $this->db->select('products.*,nama_kategori');
        $this->db->from('products');
        $this->db->join('kategori', 'kategori=kategori.id_kategori', 'left');
        $this->db->where('id_produk', $id);
        return $this->db->get();
    }
    public function tambah_pelanggan($data)
    {
        $this->db->insert('pelanggan', $data);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
    }
    public function tambah_order($data)
    {
        $this->db->insert('order', $data);
        $id = $this->db->insert_id();
        return (isset($id)) ? $id : FALSE;
    }
    public function tambah_detail_order($data)
    {
        $this->db->insert('detail_order', $data);
    }

    public function find($id_produk)
    {
        $result = $this->db->where('id_produk', $id_produk)->limit(1)->get('products');
        if ($result->num_rows() > 0) {
            return $result->row();
        } else {
            return array();
        }
    }

    public function getMaxIdOrder()
    {
        $query = "select max(id_order)+1 as id_order from `order`;";
        $result = $this->db->query($query);
        if ($result->num_rows() == 1) {
            return $result->row_array();
        }
        return FALSE;
    }

    public function getAllPayment()
    {
        $query = "select * from payment;";
        $result = $this->db->query($query);
        if ($result->num_rows() > 0) {
            return $result->result_array();
        }
        return FALSE;
    }
}
