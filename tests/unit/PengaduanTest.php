<?php

use App\Libraries\MasyarakatFiles;
use App\Libraries\PengaduanService;
use App\Models\PengaduanModel;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;
use Config\Services;
use Tests\Support\Libraries\FixtureUploadedFile;

final class PengaduanTest extends CIUnitTestCase
{
    protected $db;
    private string $directory;
    private array $temporary = [];
    private MasyarakatFiles $storage;
    private PengaduanService $service;
    private array $user;

    protected function setUp(): void
    {
        parent::setUp();
        require_once SUPPORTPATH . 'Libraries/FixtureUploadedFile.php';
        require_once APPPATH . 'Database/Migrations/2026-10-20-000003_CreateLayananMasyarakat.php';
        $this->db=Database::connect('tests');
        if (!$this->db->tableExists('dt_masyarakat')) { (new \App\Database\Migrations\CreateLayananMasyarakat(Database::forge('tests')))->up(); }
        foreach (['dt_pengaduan_riwayat','dt_pengaduan_berkas','dt_pengaduan','dt_masyarakat_otp','dt_masyarakat'] as $table) { $this->db->table($table)->emptyTable(); }
        $this->db->table('dt_masyarakat')->insert(['nama'=>'Pelapor asli','nik'=>'3300000000000001','email'=>'pelapor@example.com','no_wa'=>'6281234567890','password'=>'unused','aktif'=>1,'session_version'=>1,'created_at'=>gmdate('Y-m-d H:i:s')]);
        $id=$this->db->insertID();
        $this->user=$this->db->table('dt_masyarakat')->where('id',$id)->get()->getRowArray();
        $this->directory=WRITEPATH.'cache/pengaduan-test-'.bin2hex(random_bytes(6));
        mkdir($this->directory,0700,true);
        $this->storage=new MasyarakatFiles($this->directory);
        $this->service=new PengaduanService($this->db,$this->storage);
        Services::resetSingle('request'); Services::resetSingle('response'); Services::resetSingle('renderer');
    }

    protected function tearDown(): void
    {
        foreach (glob($this->directory.'/*') ?: [] as $file) { if (is_file($file)) { unlink($file); } }
        if (is_dir($this->directory)) { rmdir($this->directory); }
        foreach ($this->temporary as $file) { if (is_file($file)) { unlink($file); } }
        Services::resetSingle('pengaduan');
        parent::tearDown();
    }

    private function file(string $name='laporan.pdf', int $padding=0): FixtureUploadedFile
    {
        $path=tempnam(WRITEPATH.'cache','adu-');
        file_put_contents($path,"%PDF-1.4\n1 0 obj\n<<>>\nendobj\ntrailer\n<<>>\n%%EOF" . str_repeat(' ',$padding));
        $this->temporary[]=$path;
        return new FixtureUploadedFile($path,$name,'application/pdf',filesize($path),UPLOAD_ERR_OK);
    }

    private function create(): int
    {
        return $this->service->create($this->user,['nama'=>'Nama palsu','nik'=>'9999999999999999','alamat'=>'Alamat lengkap pelapor','uraian'=>'Uraian singkat aduan yang disampaikan oleh masyarakat.'], $this->file('identitas.pdf'),$this->file('aduan.pdf'));
    }

    public function testComplaintStoresPrivateFilesAndInitialHistory(): void
    {
        $id=$this->create(); $data=$this->service->detail($id,(int)$this->user['id']);
        $this->assertSame('Pelapor asli',$data['laporan']['nama']);
        $this->assertSame($this->user['nik'],$data['laporan']['nik']);
        $this->assertSame('diajukan',$data['laporan']['status']);
        $this->assertCount(2,$data['berkas']); $this->assertCount(1,$data['riwayat']);
        foreach ($data['berkas'] as $file) {
            $this->assertMatchesRegularExpression('/^[a-f0-9]{40}\.pdf$/',$file['nama_file']);
            $this->assertStringStartsWith(realpath($this->directory),$this->storage->path($file['nama_file']));
        }
    }

    public function testOtherCitizenCannotReadComplaint(): void
    {
        $id=$this->create();
        $this->expectException(\CodeIgniter\Exceptions\PageNotFoundException::class);
        $this->service->detail($id,999999);
    }

    public function testIdentityDocumentAboveTwoMbIsRejected(): void
    {
        $this->expectException(DomainException::class);
        $this->service->create($this->user,['alamat'=>'Alamat','uraian'=>str_repeat('A',30)],$this->file('id.pdf',2*1024*1024),$this->file());
    }

    public function testExecutableExtensionCannotMasqueradeAsPdf(): void
    {
        $this->expectException(DomainException::class);
        $this->storage->save($this->file('exploit.php'),'identitas');
    }

    public function testPartialUploadFailureCleansUpFilesAndDoesNotCreateReport(): void
    {
        try {
            $this->service->create($this->user,['alamat'=>'Alamat','uraian'=>str_repeat('A',30)],$this->file('id.pdf'),$this->file('bad.php'));
            $this->fail('Invalid document was accepted.');
        } catch (DomainException $e) {
            $this->assertSame(0,$this->db->table('dt_pengaduan')->countAllResults());
            $this->assertCount(0,glob($this->directory.'/*') ?: []);
        }
    }

    public function testMissingRequiredDocumentIsRejected(): void
    {
        $this->expectException(DomainException::class);
        $this->service->create($this->user,['alamat'=>'Alamat','uraian'=>str_repeat('A',30)],null,$this->file());
    }

    public function testStepProgressAndHistoryRemainConsistent(): void
    {
        $id=$this->create(); $version=1;
        foreach (['penelaahan','puldata_buket','tindak_lanjut','penyelidikan'] as $status) {
            $this->service->transition($id,$version++,$status,'Tanggapan resmi untuk pelapor.','',99,'Admin');
        }
        $data=$this->service->detail($id);
        $this->assertSame('penyelidikan',$data['laporan']['status']);
        $this->assertSame(5,(int)$data['laporan']['version']);
        $this->assertCount(5,$data['riwayat']);
        $this->assertSame('penyelidikan',$data['riwayat'][4]['status']);
    }

    public function testSkippingDirectlyToInvestigationIsRejected(): void
    {
        $id=$this->create();
        $this->expectException(DomainException::class);
        $this->service->transition($id,1,'penyelidikan','Tidak boleh melompati tahapan.','',99,'Admin');
    }

    public function testStaleVersionDoesNotAppendHistory(): void
    {
        $id=$this->create();
        $this->service->transition($id,1,'penelaahan','Penelaahan telah dimulai.','',99,'Admin');
        try { $this->service->transition($id,1,'puldata_buket','Pengumpulan data telah dimulai.','',99,'Admin'); $this->fail('Stale update accepted.'); }
        catch (DomainException $e) { $this->assertCount(2,$this->service->detail($id)['riwayat']); }
    }

    public function testRejectionRequiresReviewAndReasonAndBecomesTerminal(): void
    {
        $id=$this->create();
        $this->service->transition($id,1,'penelaahan','Dilakukan penelaahan laporan.','',99,'Admin');
        $this->service->transition($id,2,'ditolak','Laporan ditolak dengan alasan yang dijelaskan.','',99,'Admin');
        $this->expectException(DomainException::class);
        $this->service->transition($id,3,'penelaahan','Tidak dapat membuka kembali.','',99,'Admin');
    }

    public function testTransferRequiresDestination(): void
    {
        $id=$this->create();
        $this->service->transition($id,1,'penelaahan','Dilakukan penelaahan laporan.','',99,'Admin');
        $this->expectException(DomainException::class);
        $this->service->transition($id,2,'dilimpahkan','Laporan dilimpahkan ke instansi terkait.','',99,'Admin');
    }

    public function testTransferDestinationIsVisibleInHistory(): void
    {
        $id=$this->create();
        $this->service->transition($id,1,'penelaahan','Dilakukan penelaahan laporan.','',99,'Admin');
        $this->service->transition($id,2,'dilimpahkan','Laporan dilimpahkan ke instansi terkait.','Instansi tujuan',99,'Admin');
        $this->assertSame('Instansi tujuan',$this->service->detail($id)['riwayat'][2]['tujuan_pelimpahan']);
    }

    public function testTimelineEscapesAdminAndCitizenText(): void
    {
        $id=$this->create();
        $this->service->transition($id,1,'penelaahan','<script>alert("x")</script>','',99,'<b>Admin</b>');
        $html=view('masyarakat/partials/pengaduan_detail',array_merge($this->service->detail($id),['isAdmin'=>false]));
        $this->assertStringNotContainsString('<script>alert("x")</script>',$html);
        $this->assertStringContainsString('&lt;b&gt;Admin&lt;/b&gt;',$html);
        $this->assertStringContainsString('aria-current="step"',$html);
    }

    public function testPrivateStorageRejectsPathTraversal(): void
    {
        $this->expectException(DomainException::class);
        $this->storage->path('../../.env');
    }

    public function testCitizenListingOnlyShowsOwnedReports(): void
    {
        $id=$this->create();
        $this->db->table('dt_masyarakat')->insert(['nama'=>'Pelapor lain','nik'=>'3300000000000002','email'=>'other@example.com','no_wa'=>'628199999999','password'=>'unused','aktif'=>1]);
        $otherId=(int)$this->db->insertID();
        $this->db->table('dt_pengaduan')->insert(['masyarakat_id'=>$otherId,'nomor'=>'SECRET-OTHER','nama'=>'Pelapor lain','nik'=>'3300000000000002','alamat'=>'Rahasia','uraian'=>'Tidak boleh terlihat','status'=>'diajukan']);
        session()->set(['masyarakat_id'=>$this->user['id'],'masyarakat_version'=>1]);
        $controller=new \App\Controllers\Masyarakat\Portal();
        $controller->initController(Services::request(),Services::response(),Services::logger());
        $html=$controller->complaints();
        $this->assertStringContainsString($this->service->detail($id)['laporan']['nomor'],$html);
        $this->assertStringNotContainsString('SECRET-OTHER',$html);
    }

    public function testMigrationDownOnIsolatedDatabase(): void
    {
        $connection=Database::connect(array_merge(config('Database')->tests,['DBPrefix'=>'citizen_isolated_']),false);
        $migration=new \App\Database\Migrations\CreateLayananMasyarakat(Database::forge($connection));
        $migration->up();
        $this->assertTrue($connection->tableExists('dt_pengaduan_riwayat'));
        $migration->down();
        $this->assertFalse($connection->tableExists('dt_masyarakat'));
        $connection->close();
    }
}
