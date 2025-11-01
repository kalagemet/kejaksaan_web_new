<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model
{
    protected $table            = 'dt_menu';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['id', 'judul' ,'lokasi' ,'tipe' ,'link' ,'urutan' ,'grup_menu_id', 'relasi', 'content', 'is_child'];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    // protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];


    public function getMenuWithGrup($filter)
    {
        if ($filter == "footer") {
            $query = $this->select("
                            dt_menu.*, 
                            IF(dt_menu.lokasi = 'footer', 'footer', IF(dt_grup_menu.grup_menu IS NULL, '', dt_grup_menu.grup_menu)) as grup_menu,
                            IF(dt_menu.lokasi = 'footer', 'Menu Footer', dt_grup_menu.navbar) as navbar,
                            dt_grup_menu.urutan as urutan_grup,
                            (SELECT MAX(urutan) 
                             FROM dt_menu d 
                             WHERE (d.grup_menu_id = dt_menu.grup_menu_id OR (dt_menu.grup_menu_id IS NULL AND d.grup_menu_id IS NULL))) as last_urutan,
                             (select MAX(dt_grup_menu.urutan) from dt_grup_menu join dt_menu on dt_menu.grup_menu_id = dt_grup_menu.id where navbar = '".$filter."') as last_urutan_grup")
                  ->join('dt_grup_menu', 'dt_menu.grup_menu_id = dt_grup_menu.id', 'left')
                  ->where('dt_menu.lokasi','footer')
                  ->orderBy('dt_grup_menu.urutan')
                  ->orderBy('dt_menu.urutan')
                  ->findAll();
        } else {
            $query = $this->select("
                            dt_menu.*, 
                            IF(dt_menu.lokasi = 'footer', 'footer', IF(dt_grup_menu.grup_menu IS NULL, '', dt_grup_menu.grup_menu)) as grup_menu,
                            IF(dt_menu.lokasi = 'footer', 'Menu Footer', dt_grup_menu.navbar) as navbar,
                            dt_grup_menu.urutan as urutan_grup,
                            (SELECT MAX(urutan) 
                             FROM dt_menu d 
                             WHERE (d.grup_menu_id = dt_menu.grup_menu_id OR (dt_menu.grup_menu_id IS NULL AND d.grup_menu_id IS NULL))) as last_urutan,
                             (select MAX(dt_grup_menu.urutan) from dt_grup_menu join dt_menu on dt_menu.grup_menu_id = dt_grup_menu.id where navbar = '".$filter."') as last_urutan_grup")
                  ->join('dt_grup_menu', 'dt_menu.grup_menu_id = dt_grup_menu.id', 'left')
                  ->where('dt_grup_menu.navbar',$filter)
                  ->orderBy('dt_grup_menu.urutan')
                  ->orderBy('dt_menu.urutan')
                  ->findAll();
        }

        $db = \Config\Database::connect();
        foreach ($query as $key => &$value) {
            $text = '';
            if ($value['tipe'] == 'internal') {
                if ($value['relasi'] == 'halaman') {
                    $relasi = $db->query("SELECT judul FROM dt_pages WHERE slug = ?", [$value['content']])->getRow();     
                    $text = $relasi->judul ?? '';
                } else if ($value['relasi'] == 'berita') {
                    $relasi = $db->query("SELECT judul FROM dt_posts WHERE slug = ?", [$value['content']])->getRow();   
                    $text = $relasi->judul ?? '';
                } 
            }
            $value['relasi_judul'] = $text;
        }
        return $query;
    }

    public function getMenuGroupingWithGrup()
    {
        $list_menu = $this->select("
                        dt_menu.*, 
                        IF(dt_grup_menu.grup_menu IS NULL, '', dt_grup_menu.grup_menu) as grup_menu,
                        IF(dt_grup_menu.navbar IS NULL, 'Menu Footer', dt_grup_menu.navbar) as navbar,
                        dt_grup_menu.is_child as is_child_grup,
                        (SELECT MAX(urutan) 
                         FROM dt_menu d 
                         WHERE (d.grup_menu_id = dt_menu.grup_menu_id OR (dt_menu.grup_menu_id IS NULL AND d.grup_menu_id IS NULL))) as last_urutan")
                    ->join('dt_grup_menu', 'dt_menu.grup_menu_id = dt_grup_menu.id','left')
                    ->orderBy('dt_grup_menu.urutan')
                    ->orderBy('dt_menu.urutan')
                    ->findAll();
                    
        $result = [];

        
        foreach ($list_menu as $item) {
            $navbar = $item['navbar'];
            $groupId = $item['grup_menu_id'];
            $groupMenu = $item['grup_menu'];
            $isChild = $item['is_child'];

            if (!isset($result[$navbar])) {
                $result[$navbar] = [];
            }

            if (!isset($result[$navbar][$groupId])) {
                $result[$navbar][$groupId] = [
                    "grup_menu_id" => $groupId,
                    "grup_menu" => $groupMenu,
                    "is_child" => $isChild,
                    "sub_menu" => []
                ];
            }

            $result[$navbar][$groupId]["sub_menu"][] = $item;
        }

        return $result;
    }

    public function getLastUrutan($id)
    {
        $urutan = $this->select('urutan')
                    ->where('grup_menu_id',$id)
                    ->orderBy('urutan','desc')
                    ->get()
                    ->getRow();
        return $urutan !== null ? ((int)$urutan->urutan + 1) : 1;
    }

   
}
