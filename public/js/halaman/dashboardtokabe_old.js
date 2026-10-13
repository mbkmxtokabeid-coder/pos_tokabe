window.onload = function () {
  timeFrame('year');
  timeFramePO('month');
  timeRange('year');
  
}
function getChartColorsArray(e) {
  if (null !== document.getElementById(e)) {
    var o = document.getElementById(e).getAttribute("data-colors");
    if (o)
      return (o = JSON.parse(o)).map(function (e) {
        var o = e.replace(" ", "");
        return -1 === o.indexOf(",")
          ? getComputedStyle(
            document.documentElement
          ).getPropertyValue(o) || o
          : 2 == (e = e.split(",")).length
            ? "rgba(" +
            getComputedStyle(
              document.documentElement
            ).getPropertyValue(e[0]) +
            "," +
            e[1] +
            ")"
            : o;
      });

  }
}


// CHART BAR UNTUK UMUM (DILUAR PO)
var options,
  chart,
  linechartBasicColors = getChartColorsArray("stacked_column_chart"),
  barchartColors =
    (linechartBasicColors &&
      ((options = {
        chart: {
          height: 365,
          type: "bar",
          toolbar: { show: false },
          zoom: { enabled: true },
        },
        plotOptions: {
          bar: {
            horizontal: false,
            columnWidth: "60%",
            endingShape: "rounded",
          },
        },
        dataLabels: { enabled: false },
        labels: hourArrayJS,
        series: [
          {
            name: "Income",
            data: totalJamArrayJS,
          },
        ],
        xaxis: {
          categories: hourArrayJS,
        },
        yaxis: {
          min: 1000000, // Nilai minimum di sumbu Y
          max: 2000000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
          // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
          tickAmount: 10, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: {
            show: true,
            formatter: function (val) {
              // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
              return "Rp " + val.toLocaleString();
            },
          },
        },
        grid: {
          xaxis: { lines: { show: false } },
          yaxis: { lines: { show: false } },
        },
         colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
        legend: { show: false },
        fill: { opacity: 1 },
      }),
      (chart = new ApexCharts(
        document.querySelector("#stacked_column_chart"),
        options
      )).render()));
var options, charts,
  donutchartColors = getChartColorsArray("structure_widget");

window.onload = function () {
  if (typeof datasYearJS !== "undefined" && datasYearJS.length > 0) {
    initializeChart(datasYearJS);
    timeRange("year"); // Set default ke Yearly
    document.getElementById("periode").innerText = "Yearly";
  } else {
    console.error("Data Yearly masih kosong atau belum dimuat.");
  }
};

// Fungsi untuk inisialisasi chart
function initializeChart(data) {
  options = {
    chart: { height: 300, type: "donut" },
    series: [data[0], data[1]], // Gunakan data Yearly secara default
    labels: ["Terbayar", "Belum Bayar"],
    colors: ["#28a745", "#dc3545"],
    plotOptions: { pie: { startAngle: 10, donut: { size: "78%" } } },
    legend: { show: !1 },
    dataLabels: {
      style: {
        fontSize: "11px",
        fontFamily: "DM Sans,sans-serif",
        colors: void 0,
      },
      background: {
        enabled: !0,
        foreColor: "#fff",
        padding: 4,
        borderRadius: 2,
        borderWidth: 1,
        borderColor: "#fff",
        opacity: 1,
      },
    },
    responsive: [
      {
        breakpoint: 600,
        options: { chart: { height: 240 }, legend: { show: !1 } },
      },
    ],
  };

  charts = new ApexCharts(document.querySelector("#structure_widget"), options);
  charts.render();
}


// TimeFrame untuk button
function timeFrame(period) {
  const elementHarga = document.getElementById('harga');
  const buttons = document.getElementsByClassName('tombol');
  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('btn-info');
    buttons[i].classList.add('btn-soft-info');
  }
  const selectedButton = document.getElementById('btn-' + period);
  selectedButton.classList.remove('btn-soft-info');
  selectedButton.classList.add('btn-info');

  const now = new Date();
  const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 15, 0); //start pukul 8 karena indonesia gmt+7 maka 8+7
  const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24, 0); //end pukul 17 karena Indonesia gmt+7 maka 17+7
  const startOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  startOfWeek.setDate(now.getDate() - now.getDay() + 1);
  const endOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate());
  endOfWeek.setDate(now.getDate() - now.getDay() + 6);
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1, 0, 1, 0, 0);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 10, 31, 23, 59, 59);
  if (period === 'day') {
    chart.updateOptions({
      dataLabels: { enabled: !1 },
      labels: hourArrayJS,
      series: [
        {
          name: "Income",
          data: totalJamArrayJS,
        },

      ],
      xaxis: {
        type: 'datetime',
        min: startOfDay.getTime(),
        max: endOfDay.getTime(),
        labels: {
          format: 'HH:mm',
        },
       },
       colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
        yaxis: {
        min: 0, // Nilai minimum di sumbu Y
        max: 5000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
        // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
        tickAmount: 5, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          show: true,
          formatter: function (val) {
            // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
            return "Rp " + val.toLocaleString();
          },
        },
      },
        plotOptions: {
        bar: {
          distributed: true, // Membuat tiap bar punya warna berbeda
          borderRadius: 2, // Menambahkan border radius di ujung bar
          columnWidth: "50%", // Ukuran lebar bar
          endingShape: "rounded", // Ujung bar terlihat lebih smooth
        },
      },
    });
    elementHarga.innerHTML = `Rp. ${totalPerHari}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
  } else if (period === 'week') {
    chart.updateOptions({
      dataLabels: { enabled: !1 },
      labels: weekArrayJS,
      series: [
        {
          name: "Income",
          data: totalMingguanArrayJS,
        },

      ],
      xaxis: {
        type: 'datetime',
        min: startOfWeek.getTime(),
        max: endOfWeek.getTime(),
        labels: {
          format: 'dd MMM',
        },
      },
      colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
      yaxis: {
        min: 0, // Nilai minimum di sumbu Y
        max: 10000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
        // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
        tickAmount: 5, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          show: true,
          formatter: function (val) {
            // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
            return "Rp " + val.toLocaleString();
          },
        },
      },
        plotOptions: {
        bar: {
          distributed: true, // Membuat tiap bar punya warna berbeda
          borderRadius: 2, // Menambahkan border radius di ujung bar
          columnWidth: "50%", // Ukuran lebar bar
          endingShape: "rounded", // Ujung bar terlihat lebih smooth
        },
      },
    });
    elementHarga.innerHTML = `Rp. ${totalPerMinggu}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
  } else if (period === 'month') {
    chart.updateOptions({
      dataLabels: { enabled: !1 },
      labels: dateArrayJS,
      series: [
        {
          name: "Income",
          data: totalHarianArrayJS,
        },

      ],
      xaxis: {
        type: 'datetime',
        min: startOfMonth.getTime(),
        max: endOfMonth.getTime(),
        labels: {
          format: 'dd',
        },
      },
       colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
      yaxis: {
          min: 0, // Nilai minimum di sumbu Y
          max: 5000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
          // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
          tickAmount: 5, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: {
            show: true,
            formatter: function (val) {
              // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
              return "Rp " + val.toLocaleString();
            },
          },
      },
        plotOptions: {
        bar: {
          distributed: true, // Membuat tiap bar punya warna berbeda
          borderRadius: 2, // Menambahkan border radius di ujung bar
          columnWidth: "50%", // Ukuran lebar bar
          endingShape: "rounded", // Ujung bar terlihat lebih smooth
        },
      },
    });
    if (elementHarga) {
      elementHarga.innerHTML = `Rp. ${totalPerBulan}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
    } 
  } else if (period === 'year') {
    chart.updateOptions({
      dataLabels: { enabled: !1 },
      labels: monthArrayJS,
      series: [
        {
          name: "Income",
          data: totalBulananArrayJS,
        },

      ],
      xaxis: {
        type: 'datetime',
        min: startOfYear.getTime(),
        max: endOfYear.getTime(),
        labels: {
          format: 'MMM',
        },
      },
       colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
      yaxis: {
        min: 0, // Nilai minimum di sumbu Y
        max: 100000000, // Nilai maksimum di sumbu Y (Rp 5.000.000)
        // forceNiceScale: true, // Paksa agar skala terlihat lebih rapi 
        tickAmount: 8, // Dibagi menjadi 5 bagian (setiap 1.000.000) 
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          show: true,
          formatter: function (val) {
            // let ticks = [500000, 1000000, 2000000, 3000000, 4000000, 5000000, 10000000, 20000000];
            return "Rp " + val.toLocaleString();
          },
        },
      },
      plotOptions: {
        bar: {
          distributed: true, // Membuat tiap bar punya warna berbeda
          borderRadius: 2, // Menambahkan border radius di ujung bar
          columnWidth: "50%", // Ukuran lebar bar
          endingShape: "rounded", // Ujung bar terlihat lebih smooth
        },
      },
    });
    elementHarga.innerHTML = `Rp. ${totalPerTahun}<span class="text-muted d-inline-block fs-17 align-middle ms-0 ms-sm-2">Incomes</span>`;
  }
}


function timeRange(period) {
  const periodeElement = document.getElementById("periode");
  const terbayarElement = document.getElementById("terbayar");
  const belumTerbayarElement = document.getElementById("belumTerbayar");
  const batalElement = document.getElementById("batal");
  const persenTerbayarElement = document.getElementById("persenTerbayar");
  const persenBlmTerbayarElement = document.getElementById("persenBlmTerbayar");
  const persenBatalElement = document.getElementById("persenBatal");

  let data, percentPaid, percentNotPaid, percentCanceled, label;

  switch (period) {
    case "year":
      data = datasYearJS;
      percentPaid = percentInYearJS;
      percentNotPaid = percentNotPaidYearJS;
      percentCanceled = percentCanceledYearJS;
      label = "Yearly";
      break;
    case "month":
      data = datasMonthJS;
      percentPaid = percentInMonthJS;
      percentNotPaid = percentNotPaidMonthJS;
      percentCanceled = percentCanceledMonthJS;
      label = "Monthly";
      break;
    default:
      data = datasWeekJS;
      percentPaid = percentInWeekJS;
      percentNotPaid = percentNotPaidWeekJS;
      percentCanceled = percentCanceledWeekJS;
      label = "Weekly";
      break;
  }

  // Cek apakah data ada sebelum diperbarui
  if (!data || data.length < 3) {
    console.error("Data untuk periode " + period + " masih kosong.");
    return;
  }

  charts.updateOptions({
    series: [data[0], data[1]],
  });

  periodeElement.innerText = label;
  terbayarElement.innerText = "Rp." + data[0].toLocaleString();
  belumTerbayarElement.innerText = "Rp." + data[1].toLocaleString();
  batalElement.innerText = "Rp." + data[2].toLocaleString();
  persenTerbayarElement.innerText = formatPersentaseTerbayar(percentPaid) + "%";
  persenBlmTerbayarElement.innerText = formatPersentase(percentNotPaid) + "%";
  persenBatalElement.innerText = formatPersentaseBatal(percentCanceled) + "%";
}

function formatPersentaseTerbayar(persen) {
  const persenTerbayarElement = document.getElementById('persenTerbayar');
  if (persen < 0) {
    persenTerbayarElement.classList.remove('badge', 'bg-primary');
    persenTerbayarElement.classList.add('badge', 'bg-danger');
    return persen;
  } else {
    persenTerbayarElement.classList.remove('badge', 'bg-danger');
    persenTerbayarElement.classList.add('badge', 'bg-primary');
    return '+' + persen;
  }
}

function formatPersentase(persen) {
  const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayar');

  if (persen > 0) {
    persenBlmTerbayarElement.classList.remove('badge', 'bg-primary');
    persenBlmTerbayarElement.classList.add('badge', 'bg-danger');
    return '+' + persen;
  } else {
    persenBlmTerbayarElement.classList.remove('badge', 'bg-danger');
    persenBlmTerbayarElement.classList.add('badge', 'bg-primary');
    return persen;
  }
}

function formatPersentaseBatal(persen) {
  const persenBatalElement = document.getElementById('persenBatal');
  if (persen > 0) {
    persenBatalElement.classList.remove('badge', 'bg-primary');
    persenBatalElement.classList.add('badge', 'bg-danger');
    return '+' + persen;
  }
  persenBatalElement.classList.remove('badge', 'bg-danger');
  persenBatalElement.classList.add('badge', 'bg-primary');

  return persen
}

// CHART PO

var options,
  chartPO,
  linechartBasicColorsPO = getChartColorsArray("stacked_column_chart_po"),
  barchartColors =
    (linechartBasicColorsPO &&
      ((options = {
        chart: {
          height: 362,
          type: "bar",
          toolbar: { show: !1 },
          zoom: { enabled: !0 },
        },
        plotOptions: {
          bar: {
            horizontal: !1,
            columnWidth: "35%",
            endingShape: "rounded",
          },
        },
        dataLabels: { enabled: !1 },
        labels: hourArrayJSPO,
        series: [
          {
            name: "Income",
            data: totalJamArrayJSPO,
          },
        ],
        colors: ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'],
        legend: { show: !1 },
        fill: { opacity: 1 },
      }),
        (chartPO = new ApexCharts(
          document.querySelector("#stacked_column_chart_po"),
          options
        )).render())
    );

var options, chartsPO,
  donutchartColorsPO = getChartColorsArray("structure_widget_po"),
  chartColors =
    (donutchartColorsPO &&
      ((options = {
        chart: { height: 300, type: "donut" },
        series: datasMonthJSPO,
        labels: ["Terbayar", "Belum Bayar", "Batal"],
        colors: donutchartColorsPO,
        plotOptions: { pie: { startAngle: 25, donut: { size: "78%" } } },
        legend: { show: !1 },
        dataLabels: {
          style: {
            fontSize: "11px",
            fontFamily: "DM Sans,sans-serif",
            colors: void 0,
          },
          background: {
            enabled: !0,
            foreColor: "#fff",
            padding: 4,
            borderRadius: 2,
            borderWidth: 1,
            borderColor: "#fff",
            opacity: 1,
          },
        },
        responsive: [
          {
            breakpoint: 600,
            options: { chart: { height: 240 }, legend: { show: !1 } },
          },
        ],
      }),
        (chartsPO = new ApexCharts(
          document.querySelector("#structure_widget_po"),
          options
        )).render())
    );

function timeFramePO(period) {
  const buttons = document.getElementsByClassName('tombolPO');
  for (let i = 0; i < buttons.length; i++) {
    buttons[i].classList.remove('btn-info');
    buttons[i].classList.add('btn-soft-info');
  }
  const selectedButton = document.getElementById('btn_' + period);
  selectedButton.classList.remove('btn-soft-info');
  selectedButton.classList.add('btn-info');

  const now = new Date();
  const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 15, 0);
  const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24, 0);
  const startOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate() - now.getDay() + 1);
  const endOfWeek = new Date(now.getFullYear(), now.getMonth(), now.getDate() - now.getDay() + 6);
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 11, 31);

  const customColors = ['#FF5733', '#33FF57', '#3357FF', '#FF33A8', '#33FFF5', '#F5FF33', '#FF8C33', '#33A8FF', '#A833FF', '#57FF33', '#FF3333', '#33FF8C'];

  let labelsArray, dataArray, minDate, maxDate, labelFormat;

  switch (period) {
    case 'day':
      labelsArray = hourArrayJSPO;
      dataArray = totalJamArrayJSPO;
      minDate = startOfDay.getTime();
      maxDate = endOfDay.getTime();
      labelFormat = 'HH:mm';
      break;
    case 'week':
      labelsArray = weekArrayJSPO;
      dataArray = totalMingguanArrayJSPO;
      minDate = startOfWeek.getTime();
      maxDate = endOfWeek.getTime();
      labelFormat = 'dd MMM';
      break;
    case 'month':
      labelsArray = dateArrayJSPO;
      dataArray = totalHarianArrayJSPO;
      minDate = startOfMonth.getTime();
      maxDate = endOfMonth.getTime();
      labelFormat = 'dd';
      break;
    case 'year':
      labelsArray = monthArrayJSPO;
      dataArray = totalBulananArrayJSPO;
      minDate = startOfYear.getTime();
      maxDate = endOfYear.getTime();
      labelFormat = 'MMM';
      break;
    default:
      alert('error');
      return;
  }

  chartPO.updateOptions({
    dataLabels: { enabled: false },
    labels: labelsArray,
    series: [{
      name: "Income",
      data: dataArray,
    }],
    colors: customColors,
    plotOptions: {
      bar: {
        distributed: true,
        borderRadius: 2,
        columnWidth: "50%",
        endingShape: "rounded",
      },
    },
    xaxis: {
      type: 'datetime',
      min: minDate,
      max: maxDate,
      labels: { format: labelFormat },
    },
  });

  setTimeout(() => {
    chartPO.render();
  }, 100);
}


// Chart Outstanding Barang

var options = {
  series: [{
    name: 'Jumlah Sisa',
    data: totalSisa
  }],
  chart: {
    height: 350,
    type: 'bar',
    toolbar: { show: !1 },
  },
  plotOptions: {
    bar: {
      horizontal: !1,
      borderRadius: 0,
      columnWidth: "35%",
      endingShape: "rounded",
      dataLabels: {
        position: 'top', // top, center, bottom
      },
    }
  },
  dataLabels: { enabled: !1 },


  xaxis: {
    categories: namaVendor,
    position: 'bottom',
    axisBorder: {
      show: true
    },
    axisTicks: {
      show: false
    },
    crosshairs: {
      fill: {
        type: 'gradient',
        gradient: {
          colorFrom: '#D8E3F0',
          colorTo: '#BED1E6',
          stops: [0, 100],
          opacityFrom: 0.4,
          opacityTo: 0.5,
        }
      }
    },
    tooltip: {
      enabled: false,
    }
  },
  yaxis: {
    axisBorder: {
      show: false
    },
    axisTicks: {
      show: false,
    },
    labels: {
      show: true,
      formatter: function (val) {
        return "Rp " + val.toLocaleString();
      }
    }

  },

  colors: ['#438a7a'],
};
var chartOut = new ApexCharts(document.querySelector("#vendorChart"), options);
chartOut.render();

