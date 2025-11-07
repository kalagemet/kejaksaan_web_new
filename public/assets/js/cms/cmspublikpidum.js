let baseUrl = "/dataCmsPidum";
let env = "0" ? "dev" : "prod";
let props = {
  baseApi: () => (env === "dev" ? "https://localhost:8080/" : "/dataCmsPidum"),
};
moment.locale("id");
env = "prod";
var routes = {
  perkaraByKejari: baseUrl + "api/pidum/perkara/bykejari",
  sdpPkInfo: props.baseApi() + "api/perkara/info/:tahun/:satker",
  // getFiltered: baseUrl + "api/pidum/filtered",
  getFiltered: "/dataCmsPidum",
};

var gparam = {
  satuanKerja: "09.01.00",
  isKejati: 0,
};

let locks = {
  submit: false,
};

let ch = {
  spdp: new KTC("ktcStatSpdp").init(),
  top20: new KTC("ktcTop20").setOptFormat("bar").init(),
};

let gl = {
  main: [],
};

$("#pilihKejari").prop("disabled", true);
$("#pilihKejati").on("change", function () {
  getKejari(this.value);
  $("#pilihKejari").prop("disabled", false);
});

function getKejari(kejati) {
  $.getJSON(
    "https://cms-publik.kejaksaan.go.id/main/getSatkerByKejati/" + kejati,
    function (json) {
      optionKejari = "";
      optionKejari += '<option value="">Pilih Kejaksaan Negeri</option>';
      $.each(json, function () {
        // optionKejari += `<option value=${this.encripted_url}>${this.inst_nama}</option>`
        optionKejari += `<option value=${this.ins_satkerkd}>${this.inst_nama}</option>`;
      });
      $("#pilihKejari").html(optionKejari);
    }
  );
}

locker.sets = function (t) {
  switch (t) {
    // locker.sets("db")
    case "dm":
      locks.submit = true;
      locker.lock("#mainContent");
      break;
    // locker.sets("eb")
    case "em":
      locks.submit = false;
      locker.unlock("#mainContent");
      break;
  }
};

ajaxer = ajaxer.setCallbacks({
  beforeRequest: function (arg) {
    locker.sets("dm");
    Blocker.blockEl("#mainContent");
  },
  afterRequest: function (arg) {
    locker.sets("em");
    Blocker.unblockEl("#mainContent");
  },
});

$.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings) {
  return {
    iStart: oSettings._iDisplayStart,
    iEnd: oSettings.fnDisplayEnd(),
    iLength: oSettings._iDisplayLength,
    iTotal: oSettings.fnRecordsTotal(),
    iFilteredTotal: oSettings.fnRecordsDisplay(),
    iPage: Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
    iTotalPages: Math.ceil(
      oSettings.fnRecordsDisplay() / oSettings._iDisplayLength
    ),
  };
};

// to do : block on loading
async function getPerkaraByKejari(
  tahun,
  id_kejati,
  id_kejari,
  id_cabjari = "00"
) {
  let u = new URLSearchParams({
    tahun: tahun,
    id_kejati: id_kejati,
    id_kejari: id_kejari,
    id_cabjari: id_cabjari,
  });
  return await ajaxer.get(routes.perkaraByKejari + "?" + u.toString());
}

async function getSdpPkInfo(tahun, id_kejati, id_kejari, id_cabjari = "00") {
  let u = new URLSearchParams({
    tahun: tahun,
    id_kejati: id_kejati,
    id_kejari: id_kejari,
    id_cabjari: id_cabjari,
  });
  let ks = `${id_kejati}.${id_kejari}.${id_cabjari}`;
  let res = await ajaxer.get(
    routes.sdpPkInfo.replace(":tahun", tahun).replace(":satker", ks) +
      "?" +
      u.toString()
  );
  // return res ? res.data : null
  return res;
}

async function getFiltered(tahun, satker = "00.00.00") {
  let u = new URLSearchParams({
    tahun: tahun,
    satker: satker,
  });
  let res = await ajaxer.get(routes.getFiltered + `?${u.toString()}`);
  // return res ? res.data : null
  return res;
}

function renderTd(arr) {
  let ret = "";
  let t = `<td>[e]</td>`;
  arr.forEach((e) => {
    ret += t.replace("[e]", e);
  });
  return ret;
}

var dtConf = {
  autoWidth: false,
  ordering: false,
  // order: [
  //     [3, 'desc'],
  // ],
  columnDefs: [
    {
      targets: [5],
      width: "250px",
    },
    {
      targets: [5],
      visible: false,
      searchable: false,
    },
  ],
  pageLength: 5,
  // "rowCallback": rcallback,
  dom:
    "<'row'" +
    "<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
    "<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
    ">" +
    "<'table-responsive'tr>" +
    "<'row'" +
    "<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
    "<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
    ">",
  language: {
    search: "Cari ",
    lengthMenu: "Tampilkan _MENU_ data per halaman",
    // "zeroRecords": "Data tidak ditemukan",
    info: "Halaman _PAGE_ dari _PAGES_",
    infoEmpty: "Tidak ada data yang ditampilkan",
    infoFiltered: "ditemukan dari _MAX_ total data",
  },
};

var dtPerkara = $("#dtPerkara").DataTable(dtConf);
// dtPerkara.order([
//     [7, 'desc']
// ]).draw()

async function setDtEvents() {
  // $(".bt-telusur").off().click(function() {
  $(document).on("click", ".bt-telusur", function () {
    // alert("Handler for `click` called.");
    let idx = $(this).attr("data-idx");
    let r = gl.main[idx];

    if (r) {
      //   $('#riwayat-modal').modal('show');
      $(".spdp_no").html(
        (r.no_surat ? r.no_surat : "-") +
          (r.tgl_surat ? " tgl " + r.tgl_surat : "")
      );
      $(".spdp_tgl").html(r.terima_spdp ? r.terima_spdp : "-");
      $(".spdp_kembali").html(r.spdp_kembali ? r.spdp_kembali : "-");
      $(".berkas_no").html(r.no_berkas ? r.no_berkas : "-");
      $(".tgl_p21").html(r.tgl_p21 ? r.tgl_p21 : "-");
      $(".tgl_tahap_2").html(r.tahap_2 ? r.tahap_2 : "-");
      $(".tgl_p31").html(r.tgl_p31 ? r.tgl_p31 : "-");
      $(".tgl_p42").html(r.tgl_p42 ? r.tgl_p42 : "-");
      $(".tgl_putus_pn").html(r.tgl_put_pertama ? r.tgl_put_pertama : "-");
      $(".tgl_putus_banding").html(r.tgl_put_banding ? r.tgl_put_banding : "-");
      $(".tgl_putus_kasasi").html(r.tgl_put_kasasi ? r.tgl_put_kasasi : "-");
      $(".tgl_p48").html(r.tgl_p48 ? r.tgl_p48 : "-");
    }
  });
}

async function setDatatable(t = "", arg = {}) {
  let arr;
  let tahun = $("select[name=tahun]").val();
  let sker = decomposeSatker(gparam.satuanKerja);
  let res = {};

  switch (t) {
    case "filter":
      res = await getSdpPkInfo(tahun, sker.kejati, sker.kejari, sker.cabjari);

      if (!res.data) {
        dtPerkara.clear().draw();
        return;
      }

      arr = res.data;
      break;
    case "filter-old":
      data = await getConditionalData(
        tahun,
        sker.kejati,
        sker.kejari,
        sker.cabjari
      );

      if (!data.data) {
        dtPerkara.clear().draw();
        return;
      }

      arr = data.data.data;
      break;
    case "n":
      arr = arg.data;
      break;
    default:
      arr = getPerkaraByKejari(2021, gparam.satuanKerja);
  }

  dtPerkara.clear();
  var status = "";
  var no = 1;

  arr.forEach(function (e, i) {
    var tdwTxt = "";

    if (e.tdw != null) {
      tdwTxt = `${e.tdw.replace(/\;/g, "<br/>")}`;
    } else {
      tdwTxt = `<span class="badge badge-light"> kosong </span>`;
    }

    status = "";

    if (e.tgl_p48 != null) {
      status = `<span class="badge badge-light-danger fs-5">Eksekusi</span><br/>${moment(
        e.tgl_p48
      ).format("DD-MM-YYYY")}`;
    } else if (e.tgl_put_pertama != null) {
      status = `<span class="badge badge-light-success fs-5">Putusan</span><br/>${moment(
        e.tgl_put_pertama
      ).format("DD-MM-YYYY")}`;
    } else if (e.tgl_p42 != null) {
      status = `<span class="badge badge-danger fs-5">Tuntutan</span><br/>${moment(
        e.tgl_p42
      ).format("DD-MM-YYYY")}`;
    } else if (e.tgl_p31 != null) {
      status = `<span class="badge badge-success fs-5">Dilimpahkan ke PN</span><br/>${moment(
        e.tgl_p31
      ).format("DD-MM-YYYY")}`;
    } else if (e.tahap_2 != null) {
      status = `<span class="badge badge-light-warning fs-5">Tahap II</span><br/>${moment(
        e.tahap_2
      ).format("DD-MM-YYYY")}`;
    } else if (e.tgl_p21 != null) {
      status = `<span class="badge badge-light-primary fs-5">Berkas Lengkap</span><br/>${moment(
        e.tgl_p21
      ).format("DD-MM-YYYY")}`;
    } else if (e.no_berkas != null) {
      status = `<span class="badge badge-warning fs-5">Penerimaan Berkas</span><br/>${e.no_berkas}`;
    } else {
      status = `<span class="badge badge-primary fs-5">Penerimaan SPDP</span><br/>${moment(
        e.terima_spdp
      ).format("DD-MM-YYYY")}`;
    }
    //masukan data perkara
    dtPerkara.row.add([
      no++,
      // `${e.no_surat} <br> ${moment(e.tgl_surat).format('DD-MM-YYYY')}`,
      // moment(e.terima_spdp).format('DD-MM-YYYY'),
      `<b>${tdwTxt}</b>`,
      e.ur_ipp,
      e.undang_pasal,
      `${status} <br><a href="javascript:void(0)" 
                class="bt-telusur"
                data-bs-toggle="modal" 
                data-bs-target="#riwayat-modal"
                data-idx="${i}"
                >
                Telusuri
            </a>`,
      e.terima_spdp,
    ]);
  });
  gl.main = arr;
  dtPerkara.draw();

  setDtEvents();
}

function setModal(t = "", data = {}) {
  switch (t) {
    case "":
    default:
  }
}

async function applyFilter() {
  let t = "2025";
  let kt = "11";
  let kn = "11.27";
  let kn_nama = "KN. BANJARNEGARA";

  if (kn != "") {
    $(".satker-txt").text(kn_nama);
  }
  //   $("#kt_app_container").block({
  //     message: "Loading...",
  //     css: {
  //       zIndex: "1011",
  //       position: "absolute",
  //       padding: "15px",
  //       margin: "0px",
  //       width: "30%",
  //       top: "280px",
  //       left: "462px",
  //       textAlign: "center",
  //       color: "rgb(255, 255, 255)",
  //       border: "none",
  //       backgroundColor: "rgb(0, 0, 0)",
  //       cursor: "wait",
  //       borderRadius: "10px",
  //       opacity: "0.8",
  //     },
  //     overlayCSS: {
  //       zIndex: "1000",
  //       border: "none",
  //       margin: "0px",
  //       padding: "0px",
  //       width: "100%",
  //       height: "100%",
  //       top: "0px",
  //       left: "0px",
  //       backgroundColor: "rgb(0, 0, 0)",
  //       opacity: "0.6",
  //       cursor: "wait",
  //       position: "absolute",
  //     },
  //   });

  // console.log(kt)
  let satker = Kjk.padKodeInstansi(`${kt}`);

  if (kn) {
    if (kn == kt + ".00") {
      satker = kt + ".00";
    } else {
      satker = Kjk.padKodeInstansi(`${kn}`);
    }
    // console.log(kn)
  }

  // let satker = `${kt}.${kn}.00`
  // console.log(satker)
  let res = await getFiltered(t, satker);

  if (res.success) {
    let mdata = res.data.main;
    mdata = mdata ? mdata.data : [];

    setDatatable("n", {
      data: mdata,
    });
    // set stat

    let stat = res.data.statSPDP;
    stat = stat ? stat.data : [];

    let top20 = res.data.top20;
    top20 = top20 ? top20.data : [];

    let statJumlah = stat.map((e) => {
      return e.jumlah;
    });

    ch.spdp
      .setXaxis({
        categories: stat.map((e) => {
          return Kjk.months[parseInt(e.label)];
        }),
        stepSize: 1,
        tickAmount: undefined,
        labels: {
          // rotate: 0,
          // rotateAlways: true,--*
          style: {
            colors: ch.spdp.labelColor,
            // fontSize: '12px',
            // class: "m-5",
          },
          offsetX: 5,
        },
      })
      .setSeries([
        {
          name: "Penerimaan",
          data: statJumlah,
        },
      ])
      .setOptFormat("area")
      .appendOptions({
        grid: {
          borderColor: ch.spdp.borderColor,
          strokeDashArray: 4,
          yaxis: {
            lines: {
              show: true,
            },
          },
          padding: {
            left: 30,
          },
        },
      })
      .init();

    // wip
    ch.top20
      .setXaxis({
        categories: top20.map((e) => e.nama),
        labels: {
          formatter: function (val) {
            // Batasi teks menjadi 10 karakter, tambahkan ellipsis jika lebih panjang
            return val.length > 18 ? val.substring(0, 10) + "..." : val;
          },
          style: {
            colors: "#000", // Sesuaikan dengan kebutuhan
            fontSize: "12px",
          },
        },
      })
      .setSeries([
        {
          name: "Perkara",
          data: top20.map((e) => {
            return e.jumlah;
          }),
        },
      ])
      .setOptFormat("bar")
      .appendOptions({
        tooltip: {
          style: {
            fontSize: "12px",
          },
          y: {
            formatter: function (val) {
              // return "$" + val + " thousands"
              return `${val} perkara`;
            },
          },
          x: {
            formatter: function (val, opts) {
              // Mengembalikan teks asli dari kategori
              return opts.w.globals.labels[opts.dataPointIndex];
            },
          },
        },
      })
      .init();

    // set top 20
    $("#kt_app_container").unblock();
  }
}

function setupFilter() {
  // setDatatable("filter")
  $("#btFilter")
    .off()
    .click(async function () {
      await applyFilter();
    });
}

$("select[name=tahun]").on("change", setupFilter);

function createYearOpt() {
  let ret = renderOpt(jrange(2019, 2026), "arr");
  $("select[name=tahun]").html(ret);
}
createYearOpt();
$("select[name=tahun]").val(2025);

$(document).ready(function () {
  setupFilter();

  applyFilter();
});
