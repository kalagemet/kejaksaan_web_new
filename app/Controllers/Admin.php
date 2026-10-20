<?php

namespace App\Controllers;
use App\Models\SlideshowModel;
use App\Models\GrupMenuModel;
use App\Models\LayananModel;
use App\Models\SettingModel;
use App\Models\VideoModel;
use App\Models\AgenModel;

class Admin extends BaseController
{
    public function __construct() {
        $this->m_grup_menu = new GrupMenuModel();
        $this->m_slideshow = new SlideshowModel();
        $this->m_layanan = new LayananModel();
        $this->m_video = new VideoModel();
        $this->m_agen = new AgenModel();
        $this->m_setting = new SettingModel();
        $this->db = \Config\Database::connect();
    }

    public function dashboard(){
        $ready = $this->db->tableExists('dt_masyarakat') && $this->db->tableExists('dt_pengaduan');
        return view('admin/dashboard_layanan', [
            'schemaReady' => $ready, 'showHeaderSearch' => false,
            'totalUsers' => $ready ? $this->db->table('dt_masyarakat')->countAllResults() : 0,
            'totalReports' => $ready ? $this->db->table('dt_pengaduan')->countAllResults() : 0,
            'waiting' => $ready ? $this->db->table('dt_pengaduan')->where('status','diajukan')->countAllResults() : 0,
        ]);
    }

    public function slideshow(){
      
        $data['slide'] = $this->m_slideshow->findAll();
        return view('admin/slide_show', $data);
    }

    public function video(){
        $data['video'] = $this->m_video->findAll();
        return view('admin/video', $data);
    }

    public function agen(){
        $data['agen'] = $this->m_agen->findAll();
        return view('admin/agen', $data);
    }

    public function layanan(){
      
        $data['layanan'] = $this->m_layanan->findAll();
        return view('admin/layanan', $data);
    }

    public function navbar(){
        $query = $this->db->query("SHOW COLUMNS FROM dt_grup_menu WHERE Field = 'navbar'");
        $row = $query->getRow();
        $enum = [];
        if ($row && isset($row->Type)) {
            // Extract enum values from the Type field
            preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                // Convert enum values to an array
                $enum = str_getcsv($matches[1], ',', "'");
            }
        }
        $data['navbar'] = $enum;
        return view('admin/navbar',$data);
    }
    
    public function grup_menu(){
        $query = $this->db->query("SHOW COLUMNS FROM dt_grup_menu WHERE Field = 'navbar'");
        $row = $query->getRow();
        $enum = [];
        if ($row && isset($row->Type)) {
            // Extract enum values from the Type field
            preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                // Convert enum values to an array
                $enum = str_getcsv($matches[1], ',', "'");
            }
        }
        $data['navbar'] = $enum;
        return view('admin/grup_menu',$data);
    }

    public function menu(){
        $data['grup_menu'] = $this->m_grup_menu->getChildMenu();
        $data['navbar'] = $this->getNavbar();
        
        return view('admin/menu',$data);
    }

    public function setting(){
        $data['data'] = $this->m_setting->find(1);
        return view('admin/setting',$data);
    }

    public function pages(){
      
        return view('admin/pages');
    }

    public function berita(){
        $data['jenis'] = 'berita';
        return view('admin/berita',$data);
    }

    public function pengumuman(){
        $data['jenis'] = 'pengumuman';
        return view('admin/pengumuman',$data);
    }

    public function siaran_pers(){
        $data['jenis'] = 'siaran_pers';
        return view('admin/siaran_pers',$data);
    }

    public function dokumen(){
      
        return view('admin/dokumen');
    }

    public function profil(){
      
        return view('admin/profil');
    }

    public function ulasan()
    {
        $model = new \App\Models\UlasanModel();
        $data['ulasan'] = $model->orderBy('created_at', 'DESC')->orderBy('id', 'DESC')->paginate(20, 'ulasan');
        $data['pager'] = $model->pager;
        return view('admin/ulasan', $data);
    }

    public function pegawai()
    {
        return view('admin/pegawai', ['pegawai' => (new \App\Models\PegawaiModel())->getPegawaiWithJenis()]);
    }

    public function pegawaiForm($id)
    {
        $pegawai = $id === 'new' ? [] : (new \App\Models\PegawaiModel())->find($id);
        if ($id !== 'new' && !$pegawai) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        return view('admin/pegawaiForm', [
            'id' => $id, 'pegawai' => $pegawai,
            'jenisPegawai' => $this->db->table('ms_jenis_pegawai')->orderBy('id_jenis')->get()->getResultArray(),
        ]);
    }

    private function getNavbar() {
        $query = $this->db->query("SHOW COLUMNS FROM dt_grup_menu WHERE Field = 'navbar'");
        $row = $query->getRow();
        $enum = [];
        if ($row && isset($row->Type)) {
            // Extract enum values from the Type field
            preg_match("/^enum\((.*)\)$/", $row->Type, $matches);
            if (isset($matches[1])) {
                // Convert enum values to an array
                $enum = str_getcsv($matches[1], ',', "'");
            }
        }
        return $enum;
    }

  
    
}

