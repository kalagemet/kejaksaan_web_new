// sys global util

let datatableConfs = {
	default: {
			// ajax: routes.datatable   ,
		// "autoWidth": false,
		"scrollX": "1700px",
		// "scrollY": "600px",
		"pageLength": 200,
		"scrollCollapse": true,
		"filter": true,
		"info": true,
		processing: true,
		order: [1, 'asc'],
		"rowCallback": function(row, data, iDisplayIndex) {
			var index = iDisplayIndex + 1;
			$('td:eq(0)', row).html(index);
			return row;
		},
		"columns": [],
		dom: '<"datatable-header"><"datatable-scroll"t><"datatable-footer">',
		language: {
			search: '<span>Filter:</span> _INPUT_',
			searchPlaceholder: 'Type to filter...',
			lengthMenu: '<span>Show:</span> _MENU_',
			paginate: {
				'first': 'Awal',
				'last': 'Akhir',
				'next': '&rarr;',
				'previous': '&larr;'
			}
		},
		preDrawCallback: function() {
			$(this).find('tbody tr').slice(-3).find('.dropdown, .btn-group').removeClass('dropup');
			toastr.clear()

			// prevent auto load ajax (return false)
			// return false
		}
	},
	ajaxdeferLoading:{
		deferLoading: 0,
	},
	// https://datatables.net/reference/option/ajax.dataSrc
	ajaxDataStat:{
		ajax: {
			data:{
				data:[],
				stat:[],
			},
			dataSrc: function ( json ) {
				console.log(json.data)
				if(json.data.data){
					console.log(json.data.stat)
					return json.data.data;
				}
				return json.data;
			}
		},
	}
}

function capitalizeFirstLetter(string) {
	if (string != null) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	} else {
		return '-'
	}
}

function numberWithCommas(x) {
	if (x == null) {
		return 0;
	} else {
		var parts = x.toString().split(".");
		parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
		return parts.join(".");
	}
}

function isNull(x) {
	if (x == null) {
		return 0;
	} else {
		return parseInt(x)
	}
}

function formatPidanaBadan(t,b,h){
    let ret = ""
    if(t){
        ret += `${t} tahun `
    }
    if(b){
        ret += `${b} bulan `
    }
    if(h){
        ret += `${h} hari `
    }

    return ret
}

function printElement(elem) {
    var domClone = elem.cloneNode(true);

    var $printSection = document.getElementById("printSection");

    if (!$printSection) {
        var $printSection = document.createElement("div");
        $printSection.id = "printSection";
        document.body.appendChild($printSection);
    }

    $printSection.innerHTML = "";
    $printSection.appendChild(domClone);

    var css = '@page { size: landscape; }',
        head = document.head || document.getElementsByTagName('head')[0],
        style = document.createElement('style');

    style.type = 'text/css';
    style.media = 'print';

    if (style.styleSheet) {
        style.styleSheet.cssText = css;
    } else {
        style.appendChild(document.createTextNode(css));
    }

    head.appendChild(style);

    window.print();
	$printSection.innerHTML = "";
}

var range = function(start, end, step) {
	var range = [];
	var typeofStart = typeof start;
	var typeofEnd = typeof end;

	if (step === 0) {
		throw TypeError("Step cannot be zero.");
	}

	if (typeofStart == "undefined" || typeofEnd == "undefined") {
		throw TypeError("Must pass start and end arguments.");
	} else if (typeofStart != typeofEnd) {
		throw TypeError("Start and end arguments must be of same type.");
	}

	typeof step == "undefined" && (step = 1);

	if (end < start) {
		step = -step;
	}

	if (typeofStart == "number") {

		while (step > 0 ? end >= start : end <= start) {
			range.push(start);
			start += step;
		}

	} else if (typeofStart == "string") {

		if (start.length != 1 || end.length != 1) {
			throw TypeError("Only strings with one character are supported.");
		}

		start = start.charCodeAt(0);
		end = end.charCodeAt(0);

		while (step > 0 ? end >= start : end <= start) {
			range.push(String.fromCharCode(start));
			start += step;
		}

	} else {
		throw TypeError("Only string and number types are supported");
	}

	return range;
}

let components = {
	selectBidang: () => `<select class="form-control" name="bidang" id="pilihBidang">
		<option value="0">Semua Bidang</option>
		<option value="1">Orang dan Harta Benda</option>
		<option value="2">Kamnegtimbum dan TPUL</option>
		<option value="3">Narkotika dan Zat Adiktif Lainnya</option>
		<option value="4">Terorisme dan Lintas Negara</option>
	</select>`,
	// components.selectTahun()
	selectYear: () => {
		let cy = 2023
		try{
			if(datas){
				if(datas.currentYear){
					cy = datas.currentYear
				}
			}
		}catch(e){

		}
		let years = range(2018, cy)
		let opts = years.reverse().map((e)=>{ return `<option value="${e}">${e}</option>`; }).join("")

		// let opts = `<option value=""><?=$y?></option>`
		return `<div class="input-group">
			<span class="input-group-addon"><i class="icon-calendar22"></i></span>
			<select class="form-control" name="tahun" id="pilihTahun">
				${opts}
			</select>
		</div>`
	},
	selectMonth: function () {
		return `<select class="form-control pilih-tanggal" name="bulan">
			<option value="1">Januari</option>
			<option value="2">Februari</option>
			<option value="3">Maret</option>
			<option value="4">April</option>
			<option value="5">Mei</option>
			<option value="6">Juni</option>
			<option value="7">Juli</option>
			<option value="8">Agustus</option>
			<option value="9">September</option>
			<option value="10">Oktober</option>
			<option value="11">November</option>
			<option value="12">Desember</option>
		</select>`
	}
}

function initFilterComp(){
	$("#compFilterBidang").html(components.selectBidang())
	$("#compFilterYear").html(components.selectYear())
	$("#compFilterMonth").html(components.selectMonth())
}

initFilterComp()

function getFilterParam() {
	let namaBulan = $("select[name=bulan] option:selected").text();
	let namaBulan2 = $("select[name=bulanEnd] option:selected").text();
	let namaBidang = $("select[name=bidang] option:selected").text();
	let tahun = $("select[name=tahun] option:selected").text();
	let ins = $('select[name=kn]').val();
	let tp = $('select[name=bidang] option:selected').text();

	$('.bulan-dipilih').html(`${namaBulan} - ${namaBulan2}`);
	$('.tahun-dipilih').html(tahun);
	$('#spTpidana').html(tp);

	// todo fix add month globally
	let obj = {
		ins: ins,
		y: $("select[name=tahun]").val(),
		m: $("select[name=bulan]").val(),
		m2: $("select[name=bulanEnd]").val(),
		bidang: $("select[name=bidang]").val(),
	}

	return obj
}

// services
// ./services
async function getKejari(kejati) {
	$.getJSON(baseUrl+"api/publik/getSatkerByKejati/" + kejati, function(json) {
		let optionKejari = `<option value="${kejati}.al.al">Seluruh satker</option>`;
		$.each(json, function() {
			let skd = this.inst_satkerkd || ''
			optionKejari += `<option value="${skd}" >${this.inst_nama}</option>`
		});
		$('#pilihKejari').html(optionKejari);
	});
}

function getData() {
    toastr.info('... sedang mengambil data', 'Tunggu', {
        timeOut: 0,
        extendedTimeOut: 0
    })
    $('.nama-satker').html($("select[name=kn] option:selected").text());

    let obj = getFilterParam()

    let usp = new URLSearchParams(obj)


    tabel.ajax.url(routes.datatable + "?" + usp.toString()).load()
}

// global default print
function gPrint() {
    toastr.info('... sedang mengambil data', 'Tunggu', {
        timeOut: 0,
        extendedTimeOut: 0
    })
    $('.nama-satker').html($("select[name=kn] option:selected").text());

    let obj = getFilterParam()

    let usp = new URLSearchParams(obj)

	if(!routes.print){
		alert("work in progress 🙏")
		return
	}
	
    // window.location.href = routes.print + "?" + usp.toString()
    window.open(routes.print + "?" + usp.toString())
}

function setupRegisterStat(o){
	$("#masuk_bulan_ini").html(o.masuk_bulan_ini)
	$("#selesai_bulan_ini").html(o.selesai_bulan_ini)
	$("#sisa_bulan_ini").html(o.sisa_bulan_ini)
}

async function setupUI(){
	// idSatker = '<?=$this->session->userdata('id')?>';
	// alert(idSatker)
	// gdata._inst_satkerkd
	let scode = gdata._inst_satkerkd

	if(gdata._inst_satkerkd == '00'){
		// trigger setup pidum/pidsus
		let uiskd = gdata._user.inst_satkerkd || 'al.al.al'
		if(gdata._role == "pidum"){
			$('#pilihKejari').html(`<option value="${uiskd}" >Seluruh satker</option>`);
		}else if(gdata._role == "pidsus"){
			$('#pilihKejari').html(`<option value="${uiskd}" >Jaksa Agung Muda Tindak Pidana Khusus</option>`);
		}

		return
	}
	
	if (scode.length == 2) {
		$('#pilihKejati').val(scode);
		$("#pilihKejati").prop("disabled", true);
	} else {
		$('#pilihKejati').val(scode.substring(0, 2));
		$("#pilihKejati").prop("disabled", true);
		$('#pilihKejari').val(scode);
	}
	await getKejari(scode);
}


// set global events
function setGlobalEvt(){
}

function decomposeSatker(satker) {
    let splits = satker.split(".")
    let kjt = "00"
    let kjr = "00"
    let cjr = "00"

    if(splits.length <= 0 || splits.length == 1){
        return {
            kejati: satker,
            kejari: kjr,
            cabjari: cjr,
        }
    }

    if(splits.length == 2){
        kjt = splits[0]
        kjr = splits[1]
    }

    if(splits.length == 3){
        kjt = splits[0]
        kjr = splits[1]
        cjr = splits[2]
    }

    return {
        kejati: kjt,
        kejari: kjr,
        cabjari: cjr,
    }
}

function renderOpt(arr, mapping={}, initVal = "Pilih"){
  let usingArr = mapping === "arr"
  if(!mapping){
      mapping = {
          value: "x",
          label: "x",
      }
  }

  let ret = `<option value="">${initVal}</option>`
  let t = `<option value="[v]">[label]</option>`
  arr.forEach((e) => {
      if(usingArr){
          ret += t.replace("[v]", e)
              .replace("[label]", e)
      }else{
          ret += t.replace("[v]", e[mapping.value])
              .replace("[label]", e[mapping.label])
      }
  })
  return ret
}

function jrange(start, end=1, step = 1){
  return Array.from(Array.from(Array(Math.ceil((end-start)/step)).keys()), x => start+ x*step);
}

function setupModal(title, content){
  $(".modal-title").html(title)
  $(".modal-body").html(content)
}

function alertModal(type = "", title = "", msg = ""){
  title = title || title !="" ? title : "Pemberitahuan"
  switch(type){
      case "-":
          setupModal(title, msg)
      break;
      case "res":
          setupModal("Hasil", msg)
      break;
      case "err":
          setupModal("Error", msg)
      break;
      default:
          setupModal(title, msg)
  }
  $("#base-modal").modal()
}
// setupModal("Pemberitahuan", "")

function rcallback(row, data, iDisplayIndex) {
  var info = this.fnPagingInfo();
  var page = info.iPage;
  var length = info.iLength;
  var index = page * length + (iDisplayIndex + 1);
  $('td:eq(0)', row).html(index);
}

function decomposeSatker(satker) {
  let splits = satker.split(".")
  let kjt = "00"
  let kjr = "00"
  let cjr = "00"

  if(splits.length <= 0 || splits.length == 1){
      return {
          kejati: satker,
          kejari: kjr,
          cabjari: cjr,
      }
  }

  if(splits.length == 2){
      kjt = splits[0]
      kjr = splits[1]
  }

  if(splits.length == 3){
      kjt = splits[0]
      kjr = splits[1]
      cjr = splits[2]
  }

  return {
      kejati: kjt,
      kejari: kjr,
      cabjari: cjr,
  }
}

class Kjk{
	static months = {
		1:"Januari",
		2:"Februari",
		3:"Maret",
		4:"April",
		5:"Mei",
		6:"Juni",
		7:"Juli",
		8:"Agustus",
		9:"September",
		10:"Oktober",
		11:"November",
		12:"Desember",
	}
	static padKodeInstansi(kode) {
		const mainPad = "00.00.00";
		const mpLen = mainPad.length;
		const kLen = kode.length;

		if (kLen > 0 && kLen <= 8) {
			return kode.padEnd(8, mainPad.substring(kLen, mpLen));
		}

		return mainPad;
	}
}

// collection-eloquent-pluck like
Array.prototype.pluck = function(attr){ return this.map((e) => { return e[attr] }) }
Array.prototype.conditionalPluck = function(attr, alt=[]){
	if(this.length == 0){
		return alt
	}
	return this.map((e) => { return e[attr] }) 
}

setTimeout(()=>{
	setGlobalEvt()
}, 200)

function cl(d){
  console.log(d)
}

function pretty(json){
  return JSON.stringify(json, undefined, 2);
}

function preson(json){
  json = pretty(json)
  return `<pre>${json}<pre>`
}

var perkaras = {}


// return parseFloat(num).toLocaleString("id-ID");
Number.prototype.floatId = function(){
	return parseFloat(this).toLocaleString("id-ID");
}

Number.prototype.separate = function(){
	return this.floatId(this)
}

Array.prototype.countEmpty = function() {
	let count = 0;
	for (let i = 0; i < this.length; i++) {
		if (this[i] === null || this[i] === undefined) {
			count++;
		}
	}
	return count;
};
