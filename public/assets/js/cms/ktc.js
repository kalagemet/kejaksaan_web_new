// var KTChartsWidget36 = function () {
/*
let ktcStatSpdp = new KTC("#ktcStatSpdp")
*/
class KTC{
	elId = "kt_charts_widget_36"
	dom = null
	chart = {
        self: null,
        rendered: false
    };
	options = {}
	series = []
	xaxis = {}

	constructor(elId){
		this.elId = elId
		this.dom = document.getElementById(this.elId)

		this.setSeries([{
			name: 'x',
			data: [0]
		},])

		this.setOptFormat()
		.setDefStyles()
	}

	setSeries(s){
		this.series = s

		return this
	}
	
	setXaxis(xa){
		this.xaxis = xa

		return this
	}

	setDefStyles(){
		this.labelColor = KTUtil.getCssVariableValue('--bs-gray-500');
		this.borderColor = KTUtil.getCssVariableValue('--bs-border-dashed-color');
		this.baseprimaryColor = KTUtil.getCssVariableValue('--bs-primary');
		this.lightprimaryColor = KTUtil.getCssVariableValue('--bs-primary');
		this.basesuccessColor = KTUtil.getCssVariableValue('--bs-success');
		this.lightsuccessColor = KTUtil.getCssVariableValue('--bs-success');
		this.baseColor = KTUtil.getCssVariableValue('--bs-primary');
		this.secondaryColor = KTUtil.getCssVariableValue('--bs-gray-300');

		return this
	}

	setOptFormat(t){
		var height = parseInt(
			KTUtil.css(this.dom, 'height')
		);
        
		var labelColor = this.labelColor
        var borderColor = this.borderColor
        var baseprimaryColor = this.baseprimaryColor
        var lightprimaryColor = this.lightprimaryColor
        var basesuccessColor = this.basesuccessColor
        var lightsuccessColor = this.lightsuccessColor
		var baseColor = this.baseColor
		var secondaryColor = this.secondaryColor

		switch(t){
			case "bar":
				this.setOptions({
					/* series: [{
						name: 'Net Profit',
						data: [44, 55, 57, 56, 61, 58]
					}, {
						name: 'Revenue',
						data: [76, 85, 101, 98, 87, 105]
					}], */
					series: this.series,
					chart: {
						fontFamily: 'inherit',
						type: 'bar',
						height: height,
						toolbar: {
							show: false
						},
						zoom: {
							enabled: false
						},
					},
					plotOptions: {
						bar: {
							horizontal: false,
							columnWidth: ['30%'],
							borderRadius: [6]
						},
					},
					legend: {
						show: false
					},
					dataLabels: {
						enabled: true,
					},
					plotOptions: {
						bar: {
							dataLabels: {
								position: 'top'
							}
						},
						dataLabels: {
							enabled: true,
							style: {
								colors: ['#333']
							},
							offsetY: 100
						},
					},
					stroke: {
						show: true,
						width: 2,
						colors: ['transparent']
					},
					xaxis: {
						categories: [1],
						axisBorder: {
							show: false,
						},
						axisTicks: {
							show: false
						},
						labels: {
							style: {
								colors: labelColor,
								fontSize: '12px',
								class: "m-5",
							}
						},
						...this.xaxis,
					},
					yaxis: {
						labels: {
							style: {
								colors: labelColor,
								fontSize: '12px'
							}
						}
					},
					fill: {
						opacity: 1
					},
					states: {
						normal: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						hover: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						active: {
							allowMultipleDataPointsSelection: false,
							filter: {
								type: 'none',
								value: 0
							}
						}
					},
					tooltip: {
						style: {
							fontSize: '12px'
						},
						y: {
							formatter: function (val) {
								// return "$" + val + " thousands"
								return `${val} perkara`
							}
						}
					},
					colors: [baseColor, secondaryColor],
					grid: {
						borderColor: borderColor,
						strokeDashArray: 4,
						yaxis: {
							lines: {
								show: true
							}
						}
					}
				})
			break;
			case "l":
			case "line":
			break
			case "a":
			case "area":
			default:
				this.setOptions({
					series: this.series,
					chart: {
						fontFamily: 'inherit',
						type: 'area',
						height: height,
						toolbar: {
							show: false
						},
						zoom: {
							enabled: false
						},
					},
					
					plotOptions: {
		
					},
					legend: {
						show: false
					},
					/* dataLabels: {
						enabled: false
					}, */
					fill: {
						type: "gradient",
						gradient: {
							shadeIntensity: 1,
							opacityFrom: 0.4,
							opacityTo: 0.2,
							stops: [15, 120, 100]
						}
					},
					stroke: {
						curve: 'smooth',
						show: true,
						width: 3,
						colors: [baseprimaryColor, basesuccessColor]
					},
					xaxis: {
						categories: [
							''
						],
						axisBorder: {
							show: false,
						},
						axisTicks: {
							show: false
						},
						tickAmount: 6,
						labels: {
							rotate: 0,
							rotateAlways: true,
							style: {
								colors: labelColor,
								fontSize: '12px',
								// class: "m-5",
							}
						},
						crosshairs: {
							position: 'front',
							stroke: {
								color: [baseprimaryColor, basesuccessColor],
								width: 1,
								dashArray: 3
							}
						},
						tooltip: {
							enabled: true,
							formatter: undefined,
							offsetY: 0,
							style: {
								fontSize: '12px'
							}
						},
						...this.xaxis,
					},
					yaxis: {
						// max: 120,
						// min: 30,
						tickAmount: 6,
						labels: {
							style: {
								colors: labelColor,
								fontSize: '12px'
							}
						}
					},
					states: {
						normal: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						hover: {
							filter: {
								type: 'none',
								value: 0
							}
						},
						active: {
							allowMultipleDataPointsSelection: false,
							filter: {
								type: 'none',
								value: 0
							}
						}
					},
					tooltip: {
						style: {
							fontSize: '12px'
						} 
					},
					colors: [lightprimaryColor, lightsuccessColor],
					grid: {
						borderColor: borderColor,
						strokeDashArray: 4,
						yaxis: {
							lines: {
								show: true
							}
						},
						padding: {
							// top: 0,
							// right: 0,
							// bottom: 0,
							left: 20
						},
					},
					markers: {
						strokeColor: [baseprimaryColor, basesuccessColor],
						strokeWidth: 3
					}
				})
		}

		return this
	}

	setOptions(opt){
		this.options = opt

		return this
	}
	
	appendOptions(opt){
		this.options = {
			...this.options,
			...opt,
		}

		return this
	}

	// Private methods
	initChart(reset=true) {
		// let chart = this.chart
		var element = this.dom

		// cl(element)

		if (!element) {
			return;
		}

		// flag
		var options = this.options;

		if(reset && this.chart.self){
			this.chart.self.destroy()
		}
		this.chart.self = new ApexCharts(element, options);
		const ctx = this

		// Set timeout to properly get the parent elements width
		setTimeout(function() {
			ctx.chart.self.render();
			ctx.chart.rendered = true;
		}, 200);
	}

    // Public methods
    init() {
		let ctx = this
		this.initChart(
			this.chart
		);

		// Update chart on theme mode change
		/*
		KTThemeMode.on("kt.thememode.change", function() {                
			if (ctx.chart.rendered) {
				ctx.chart.self.destroy();
			}

			ctx.initChart(chart);
		});
		*/

		return this
	}
}