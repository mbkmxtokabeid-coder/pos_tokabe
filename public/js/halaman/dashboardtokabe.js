// ==========================================================================
// GLOBAL INDONESIAN NUMBER & CURRENCY FORMATTERS (Intl.NumberFormat id-ID)
// ==========================================================================
window.idNumberFormatter = window.idNumberFormatter || new Intl.NumberFormat('id-ID');
var idNumberFormatter = window.idNumberFormatter;

if (!window.formatRupiah) {
  window.formatRupiah = function (val) {
    if (val === null || val === undefined || isNaN(val)) return 'Rp 0';
    const num = Number(val);
    const abs = Math.abs(num);
    const formatted = idNumberFormatter.format(abs);
    return num < 0 ? `\u2212Rp ${formatted}` : `Rp ${formatted}`;
  };
}
var formatRupiah = window.formatRupiah;

if (!window.formatRupiahCompact) {
  window.formatRupiahCompact = function (val) {
    if (val === null || val === undefined || isNaN(val)) return 'Rp 0';
    const num = Number(val);
    const abs = Math.abs(num);
    const prefix = num < 0 ? '\u2212Rp ' : 'Rp ';
    if (abs >= 1000000000) {
      const v = (abs / 1000000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
      return `${prefix}${v} M`;
    }
    if (abs >= 1000000) {
      const v = (abs / 1000000).toLocaleString('id-ID', { minimumFractionDigits: 1, maximumFractionDigits: 1 });
      return `${prefix}${v} jt`;
    }
    if (abs >= 1000) {
      const v = (abs / 1000).toLocaleString('id-ID', { maximumFractionDigits: 0 });
      return `${prefix}${v} rb`;
    }
    return `${prefix}${idNumberFormatter.format(abs)}`;
  };
}
var formatRupiahCompact = window.formatRupiahCompact;

if (!window.animateCountUp) {
  window.animateCountUp = function (element, endVal, duration = 650, isCurrency = true) {
    if (!element) return;
    const prefersReducedMotion = (typeof window !== 'undefined' && typeof window.matchMedia === 'function')
      ? window.matchMedia('(prefers-reduced-motion: reduce)').matches
      : false;
    if (prefersReducedMotion) {
      element.textContent = isCurrency ? formatRupiah(endVal) : idNumberFormatter.format(endVal);
      return;
    }
    const currentText = element.textContent || '0';
    const startVal = parseFloat(currentText.replace(/[^0-9.-]/g, '')) || 0;
    const startTime = performance.now();
    function frame(now) {
      const elapsed = now - startTime;
      const progress = Math.min(elapsed / duration, 1);
      const ease = progress === 1 ? 1 : 1 - Math.pow(2, -10 * progress);
      const current = Math.round(startVal + (endVal - startVal) * ease);
      element.textContent = isCurrency ? formatRupiah(current) : idNumberFormatter.format(current);
      if (progress < 1) {
        if (typeof requestAnimationFrame === 'function') {
          requestAnimationFrame(frame);
        } else {
          setTimeout(frame, 16);
        }
      } else {
        element.textContent = isCurrency ? formatRupiah(endVal) : idNumberFormatter.format(endVal);
      }
    }
    if (typeof requestAnimationFrame === 'function') {
      requestAnimationFrame(frame);
    } else {
      setTimeout(frame, 16);
    }
  };
}
var animateCountUp = window.animateCountUp;

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
  linechartBasicColors = ['#6B73F0', '#34C28B'],
  barchartColors =
    ((options = {
      chart: {
        height: 365,
        type: "bar",
        toolbar: { show: false },
        animations: { enabled: true, speed: 350 },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "48%",
          borderRadius: 4,
        },
      },
      dataLabels: { enabled: false },
      labels: (typeof hourArrayJS !== 'undefined') ? hourArrayJS : [],
      series: [
        {
          name: "Pendapatan",
          data: (typeof totalJamArrayJS !== 'undefined') ? totalJamArrayJS : [],
        },
      ],
      xaxis: {
        categories: (typeof hourArrayJS !== 'undefined') ? hourArrayJS : [],
        labels: {
          style: { colors: '#9AA4B2', fontSize: '11px' }
        },
        axisBorder: { show: false },
        axisTicks: { show: false }
      },
      yaxis: {
        min: 0,
        max: 5000000,
        tickAmount: 5,
        axisBorder: { show: false },
        axisTicks: { show: false },
        labels: {
          show: true,
          formatter: function (val) {
            return formatRupiahCompact(val);
          },
          style: { colors: '#6B7686', fontSize: '11px' }
        },
      },
      grid: {
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true } },
        borderColor: 'rgba(255, 255, 255, 0.05)',
        strokeDashArray: 3
      },
      colors: ['#6B73F0'],
      legend: { show: false },
      tooltip: {
        theme: 'dark',
        y: {
          formatter: function (val) {
            return formatRupiah(val);
          }
        }
      }
    }),
    (chart = new ApexCharts(
      document.querySelector("#stacked_column_chart"),
      options
    )).render());

// DONUT CHART STRUKTUR STATUS PEMBAYARAN
var options, charts,
  chartColors =
    ((options = {
      chart: { height: 260, type: "donut", animations: { enabled: true, speed: 450 } },
      series: (typeof datasLastYearJS !== 'undefined' && Array.isArray(datasLastYearJS))
        ? [datasLastYearJS[0] || 0, datasLastYearJS[1] || 0, datasLastYearJS[2] || 0]
        : (typeof datasMonthJS !== 'undefined' && Array.isArray(datasMonthJS)
          ? [datasMonthJS[0] || 0, datasMonthJS[1] || 0, datasMonthJS[2] || 0]
          : [0, 0, 0]),
      labels: ["Terbayar", "Belum Bayar", "Batal"],
      colors: ["#34C28B", "#E3A33B", "#6B7686"],
      stroke: {
        width: 2,
        colors: ['#12161D']
      },
      plotOptions: { 
        pie: { 
          donut: { 
            size: "82%",
            background: 'transparent',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '11px',
                fontFamily: 'inherit',
                color: '#9AA4B2',
                offsetY: -4
              },
              value: {
                show: true,
                fontSize: '16px',
                fontWeight: '700',
                fontFamily: 'inherit',
                color: '#E8EBF0',
                offsetY: 4,
                formatter: function (val) {
                  return formatRupiahCompact(val);
                }
              },
              total: {
                show: true,
                showAlways: true,
                label: 'Total Tagihan',
                color: '#9AA4B2',
                fontSize: '11px',
                fontFamily: 'inherit',
                formatter: function (w) {
                  const total = w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                  return formatRupiahCompact(total);
                }
              }
            }
          } 
        } 
      },
      legend: { show: false },
      dataLabels: { enabled: false },
      tooltip: {
        theme: 'dark',
        y: {
          formatter: function (val, opts) {
            const total = opts.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
            const pct = total > 0 ? ((val / total) * 100).toFixed(1) + '%' : '0%';
            return formatRupiah(val) + ' (' + pct + ')';
          }
        }
      },
      responsive: [
        {
          breakpoint: 600,
          options: { chart: { height: 230 }, legend: { show: false } },
        },
      ],
    }),
      (charts = new ApexCharts(
        document.querySelector("#structure_widget"),
        options
      )).render());

// TimeFrame untuk button
function timeFrame(period) {
  if (!chart && document.querySelector("#stacked_column_chart") && typeof ApexCharts !== 'undefined') {
    chart = new ApexCharts(document.querySelector("#stacked_column_chart"), options);
    chart.render();
  }
  const elementHarga = document.getElementById('harga');
  const buttons = document.querySelectorAll('#card-aktivitas-pembayaran .segmented-item');
  buttons.forEach(b => b.classList.remove('active'));
  const selectedButton = document.getElementById('btn-' + period);
  if (selectedButton) {
    selectedButton.classList.add('active');
  }
  const btnCustomToggle = document.getElementById('btn-payment-custom-toggle');
  if (btnCustomToggle) {
    btnCustomToggle.classList.remove('active');
  }

  // Sinkronisasi otomatis ke widget Donut Chart (Structure Status)
  if (typeof timeRange === 'function') {
    timeRange(period);
  }

  updatePaymentPeriodBadgeText(period);

  const now = new Date();
  const startOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 15, 0);
  const endOfDay = new Date(now.getFullYear(), now.getMonth(), now.getDate(), 24, 0);
  const startOfMonth = new Date(now.getFullYear(), now.getMonth(), 1, 0, 1, 0, 0);
  const endOfMonth = new Date(now.getFullYear(), now.getMonth() + 1, 0, 23, 59, 59);
  const startOfYear = new Date(now.getFullYear(), 0, 1);
  const endOfYear = new Date(now.getFullYear(), 10, 31, 23, 59, 59);
  const currentYear = now.getFullYear();
  const currentMonth = ("0" + (now.getMonth() + 1)).slice(-2);

// Kondisi Hari Ini
if (period === 'day') {
  chart.updateOptions({
    dataLabels: { enabled: false },
    labels: hourArrayJS,
    series: [
      {
        name: "Pendapatan",
        data: totalJamArrayJS.map((value, index) => {
          let utcTime = new Date(hourArrayJS[index]);
          let localTime = new Date(utcTime.setHours(utcTime.getHours() + 7));
          return { x: localTime.toISOString().slice(11, 16), y: value };
        }),
        color: '#6B73F0',
      },
    ],
    xaxis: {
      type: 'category',
      categories: hourArrayJS.map(time => {
        let utcTime = new Date(time);
        let localTime = new Date(utcTime.setHours(utcTime.getHours()));
        return localTime.toISOString().slice(11, 16);
      }),
      labels: {
        formatter: function (val) {
          return val;
        },
        style: { colors: '#9AA4B2', fontSize: '11px' }
      },
    },
    yaxis: {
      min: 0,
      max: 5000000,
      tickAmount: 5,
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
        show: true,
        formatter: function (val) {
          return formatRupiahCompact(val);
        },
        style: { colors: '#6B7686', fontSize: '11px' }
      },
    },
    plotOptions: {
      bar: {
        distributed: true,
        borderRadius: 4,
        columnWidth: "48%",
      },
    },
    legend: { show: false },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function (val) {
          return formatRupiah(val);
        }
      }
    }
  });

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalPerHari || 0);
  }

  if (elementHarga) {
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Pendapatan (Hari Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalPerHari || 0)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Beban (Hari Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">Rp 0</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
          <span class="text-muted fs-13">Pendapatan Bersih (Hari Ini)</span>
          <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(totalPerHari || 0)}</span>
        </div>
      </div>
    `;
  }
}

// Kondisi Minggu Ini
else if (period === 'week') {
  let today = new Date();
  let dayOfWeek = today.getDay();

  let startOfWeek = new Date(today);
  startOfWeek.setDate(today.getDate() - (dayOfWeek === 0 ? 6 : dayOfWeek - 1));
  startOfWeek.setHours(0, 0, 0, 0);
  startOfWeek.setTime(startOfWeek.getTime() + (7 * 60 * 60 * 1000));

  let endOfWeek = new Date(today);
  endOfWeek.setHours(23, 59, 59, 999);
  endOfWeek.setTime(endOfWeek.getTime() + (7 * 60 * 60 * 1000));

  let fullWeekDates = [];
  let currentDate = new Date(startOfWeek);

  while (currentDate <= endOfWeek) {
    let formattedDate = currentDate.toISOString().split("T")[0];
    fullWeekDates.push(formattedDate);
    currentDate.setDate(currentDate.getDate() + 1);
  }

  let dataMap = new Map(weekArrayJS.map((date, index) => [date, Number(totalMingguanArrayJS[index])]));

  let totalTransaksiAsli = fullWeekDates.reduce((total, date) => {
    return total + (dataMap.get(date) ?? 0);
  }, 0);

  let finalWeeklyData = fullWeekDates.map(date => {
    const transaksi = dataMap.get(date);
    return transaksi !== undefined ? transaksi - 1000000 : -1000000;
  });

  let totalPositif = finalWeeklyData.filter(val => val > 0).reduce((acc, val) => acc + val, 0);
  let totalNegatif = finalWeeklyData.filter(val => val < 0).reduce((acc, val) => acc + val, 0);
  let netIncome = totalPositif + totalNegatif;

  chart.updateOptions({
    dataLabels: { enabled: false },
    labels: fullWeekDates,
    series: [{
      name: "Pendapatan Bersih",
      data: finalWeeklyData,
    }],
    xaxis: {
      type: 'category',
      categories: fullWeekDates,
      labels: {
        format: 'dd MMM',
        style: { colors: '#9AA4B2', fontSize: '11px' }
      },
    },
    yaxis: {
      min: -1000000,
      max: 5000000,
      tickAmount: 5,
      axisBorder: { show: false },
      axisTicks: { show: false },
      labels: {
        formatter: function (val) {
          return formatRupiahCompact(val);
        },
        style: { colors: '#6B7686', fontSize: '11px' }
      },
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: "48%",
        colors: {
          ranges: [
            { from: -Infinity, to: 0, color: '#E5586B' },
            { from: 0.001, to: Infinity, color: '#6B73F0' }
          ]
        }
      }
    },
    legend: { show: false },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function (val) {
          return formatRupiah(val);
        }
      }
    }
  });

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalTransaksiAsli);
  }

  if (elementHarga) {
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Pendapatan (Minggu Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalTransaksiAsli)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Beban (Minggu Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(Math.abs(totalNegatif))}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
          <span class="text-muted fs-13">Pendapatan Bersih (Minggu Ini)</span>
          <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(netIncome)}</span>
        </div>
      </div>
    `;
  }
}

// Kondisi Bulan Ini
else if (period === 'month') {
  let now = new Date();
  let currentYear = now.getFullYear();
  let currentMonth = ("0" + (now.getMonth() + 1)).slice(-2);

  let fullDateArray = Array.from({ length: now.getDate() }, (_, i) =>
    `${currentYear}-${currentMonth}-${("0" + (i + 1)).slice(-2)}`
  );

  let dataMap = new Map(dateArrayJS.map((date, index) => [date, Number(totalHarianArrayJS[index])]));

  let totalTransaksiAsli = fullDateArray.reduce((total, date) => {
    return total + (dataMap.get(date) ?? 0);
  }, 0);

  let filteredTotalHarianArray = fullDateArray.map(date => {
    const transaksi = dataMap.get(date);
    return transaksi !== undefined ? (transaksi - 1000000) : -1000000;
  });

  let totalPositif = filteredTotalHarianArray
    .filter(val => val > 0)
    .reduce((acc, val) => acc + val, 0);

  let totalNegatif = filteredTotalHarianArray
    .filter(val => val < 0)
    .reduce((acc, val) => acc + val, 0);

  let totalIncome = totalPositif + totalNegatif;

  chart.updateOptions({
    series: [{
      name: "Pendapatan Bersih",
      data: filteredTotalHarianArray
    }],
    xaxis: {
      type: 'category',
      categories: fullDateArray,
      labels: {
        formatter: function (value) {
          return value.split("-")[2];
        },
        style: { colors: '#9AA4B2', fontSize: '11px' }
      }
    },
    yaxis: {
      min: -1000000,
      max: 5000000,
      tickAmount: 6,
      forceNiceScale: true,
      labels: {
        formatter: function (val) {
          return formatRupiahCompact(val);
        },
        style: { colors: '#6B7686', fontSize: '11px' }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: "48%",
        colors: {
          ranges: [
            { from: -Infinity, to: 0, color: '#E5586B' },
            { from: 0.001, to: Infinity, color: '#6B73F0' }
          ]
        }
      }
    },
    legend: { show: false },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function (val) {
          return formatRupiah(val);
        }
      }
    },
    dataLabels: { enabled: false }
  });

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalTransaksiAsli);
  }

  if (elementHarga) {
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Pendapatan (Bulan Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalTransaksiAsli)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Beban (Bulan Ini)</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(Math.abs(totalNegatif))}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
          <span class="text-muted fs-13">Pendapatan Bersih (Bulan Ini)</span>
          <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(totalIncome)}</span>
        </div>
      </div>
    `;
  }
}

// Kondisi Tahun Ini
else if (period === 'year') {
  let now = new Date();
  let currentYear = now.getFullYear();
  let currentMonth = now.getMonth() + 1;
  let currentDate = now.getDate();

  let fullMonthArray = Array.from({ length: currentMonth }, (_, i) =>
    `${currentYear}-${("0" + (i + 1)).slice(-2)}`
  );

  let monthlyTotalMap = new Map();

  monthArrayJS.forEach((month, index) => {
    let year = month.slice(0, 4);
    let income = Number(totalBulananArrayJS[index]);

    if (isNaN(income) || !/\d{4}-\d{2}/.test(month)) return;

    if (year === String(currentYear)) {
      if (monthlyTotalMap.has(month)) {
        monthlyTotalMap.set(month, monthlyTotalMap.get(month) + income);
      } else {
        monthlyTotalMap.set(month, income);
      }
    }
  });

  let totalBulananArray = fullMonthArray.map(month => {
    return monthlyTotalMap.has(month) ? monthlyTotalMap.get(month) : 0;
  });

  let totalNegatifPerBulan = Array.from({ length: currentMonth }, (_, month) => {
    let daysInMonth = (month + 1 === currentMonth)
      ? currentDate
      : new Date(currentYear, month + 1, 0).getDate();

    let totalNegatif = daysInMonth * -1000000;
    return totalNegatif;
  });

  let totalWithFormulaArray = totalBulananArray.map((value, index) =>
    value + totalNegatifPerBulan[index]
  );

  const bulanIndoList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

  chart.updateOptions({
    series: [
      {
        name: "Pendapatan Bersih",
        data: totalWithFormulaArray,
        color: '#6B73F0',
      },
      {
        name: "Pendapatan Asli",
        data: totalBulananArray,
        color: '#34C28B',
      }
    ],
    xaxis: {
      type: 'category',
      categories: fullMonthArray,
      labels: {
        formatter: function (value) {
          const mIdx = parseInt(value.split("-")[1], 10) - 1;
          return bulanIndoList[mIdx] || value;
        },
        style: { colors: '#9AA4B2', fontSize: '11px' }
      }
    },
    yaxis: {
      min: Math.min(...totalWithFormulaArray, 0),
      max: Math.max(...totalBulananArray, ...totalWithFormulaArray) + 1000000,
      tickAmount: 6,
      forceNiceScale: true,
      labels: {
        formatter: function (val) {
          return formatRupiahCompact(val);
        },
        style: { colors: '#6B7686', fontSize: '11px' }
      }
    },
    plotOptions: {
      bar: {
        borderRadius: 4,
        columnWidth: "48%",
        colors: {
          ranges: [
            { from: -Infinity, to: -1, color: '#E5586B' }
          ]
        }
      }
    },
    legend: {
      show: true,
      position: 'top',
      horizontalAlign: 'right',
      fontSize: '12px',
      fontFamily: 'inherit',
      labels: { colors: '#9AA4B2' },
      markers: { radius: 12, width: 8, height: 8 },
      itemMargin: { horizontal: 8, vertical: 0 }
    },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function (val) {
          return formatRupiah(val);
        }
      }
    },
    dataLabels: { enabled: false }
  });

  const totalOriginalTahunIni = totalBulananArray.reduce((acc, val) => acc + val, 0);
  const totalNegatifTahunIni = totalNegatifPerBulan.reduce((acc, val) => acc + val, 0);
  const totalWithFormulaTahunIni = totalOriginalTahunIni + totalNegatifTahunIni;

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalOriginalTahunIni);
  }

  if (elementHarga) {
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Pendapatan (${currentYear})</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalOriginalTahunIni)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="text-muted fs-13">Total Beban (${currentYear})</span>
          <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(Math.abs(totalNegatifTahunIni))}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
          <span class="text-muted fs-13">Pendapatan Bersih (${currentYear})</span>
          <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(totalWithFormulaTahunIni)}</span>
        </div>
      </div>
    `;
  }
}

// Kondisi Tahun Lalu (Default Dashboard)
else if (period === 'lastYear') {
  let now = new Date();
  let lastYear = now.getFullYear() - 1;

  let fullMonthArrayLastYear = Array.from({ length: 12 }, (_, i) =>
    `${lastYear}-${("0" + (i + 1)).slice(-2)}`
  );

  let monthlyTotalMapLastYear = new Map();
  monthArrayLastYearJS.forEach((month, index) => {
    if (month.startsWith(`${lastYear}-`)) {
      let income = Number(totalBulananArrayLastYearJS[index]);
      if (monthlyTotalMapLastYear.has(month)) {
        monthlyTotalMapLastYear.set(month, monthlyTotalMapLastYear.get(month) + income);
      } else {
        monthlyTotalMapLastYear.set(month, income);
      }
    }
  });

  let totalBulananArrayLastYear = fullMonthArrayLastYear.map(month => {
    return monthlyTotalMapLastYear.get(month) || 0;
  });

  let totalNegatifPerBulanLastYear = Array.from({ length: 12 }, (_, month) => {
    let daysInMonth = new Date(lastYear, month + 1, 0).getDate();
    let totalNegatif = daysInMonth * -500000;
    return totalNegatif;
  });

  let totalWithFormulaArrayLastYear = totalBulananArrayLastYear.map((val, index) => 
    val + totalNegatifPerBulanLastYear[index]
  );

  const bulanIndoList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];

  try {
    chart.updateOptions({
      series: [
        {
          name: "Pendapatan Bersih",
          data: totalWithFormulaArrayLastYear,
          color: '#6B73F0',
        },
        {
          name: "Pendapatan Asli",
          data: totalBulananArrayLastYear,
          color: '#34C28B',
        }
      ],
      xaxis: {
        categories: fullMonthArrayLastYear,
        labels: {
          formatter: value => {
            const mIdx = parseInt(value.split("-")[1], 10) - 1;
            return bulanIndoList[mIdx] || value;
          },
          style: { colors: '#CBD5E1', fontSize: '11px' }
        }
      },
      yaxis: {
        min: Math.min(...totalWithFormulaArrayLastYear, 0),
        max: Math.max(...totalBulananArrayLastYear, ...totalWithFormulaArrayLastYear) + 10000000,
        tickAmount: 6,
        forceNiceScale: true,
        labels: {
          formatter: val => formatRupiahCompact(val),
          style: { colors: '#94A3B8', fontSize: '11px' }
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: "48%",
          colors: {
            ranges: [{ from: -Infinity, to: -1, color: '#E5586B' }]
          }
        }
      },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'right',
        fontSize: '12px',
        fontFamily: 'inherit',
        labels: { colors: '#CBD5E1' },
        markers: { radius: 12, width: 8, height: 8 },
        itemMargin: { horizontal: 8, vertical: 0 }
      },
      tooltip: {
        theme: 'dark',
        y: {
          formatter: function (val) {
            return formatRupiah(val);
          }
        }
      },
      annotations: { points: [], xaxis: [], yaxis: [] },
      dataLabels: { enabled: false }
    });
  } catch (chartErr) {
    console.error("Gagal memperbarui opsi chart lastYear:", chartErr);
  }

  const totalOriginalTahunLalu = totalBulananArrayLastYear.reduce((acc, val) => acc + val, 0);
  const totalNegatifTahunLalu = totalNegatifPerBulanLastYear.reduce((acc, val) => acc + val, 0);
  const totalWithFormulaTahunLalu = totalOriginalTahunLalu + totalNegatifTahunLalu;

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalOriginalTahunLalu);
  }

  if (elementHarga) {
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span style="color: #94A3B8; font-size: 13px;">Total Pendapatan (${lastYear})</span>
          <span style="color: #F8FAFC; font-weight: 600; font-size: 13px; font-variant-numeric: tabular-nums;">${formatRupiah(totalOriginalTahunLalu)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span style="color: #94A3B8; font-size: 13px;">Total Beban (${lastYear})</span>
          <span style="color: #F8FAFC; font-weight: 600; font-size: 13px; font-variant-numeric: tabular-nums;">${formatRupiah(Math.abs(totalNegatifTahunLalu))}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: rgba(255, 255, 255, 0.08) !important;">
          <span style="color: #94A3B8; font-size: 13px;">Pendapatan Bersih (${lastYear})</span>
          <span style="color: #6B73F0; font-weight: 700; font-size: 14px; font-variant-numeric: tabular-nums;">${formatRupiah(totalWithFormulaTahunLalu)}</span>
        </div>
      </div>
    `;
  }
}
}

// ====================================================================
// PAYMENT ACTIVITY CUSTOM FILTER (TANGGAL, BULAN, TAHUN)
// ====================================================================
var currentPaymentCustomMode = 'date'; // 'date', 'month', 'year'

function updatePaymentPeriodBadgeText(period, customLabel) {
  const badgeText = document.getElementById('badge-payment-period-text');
  if (!badgeText) return;

  const now = new Date();
  if (period === 'day') {
    const formatted = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'long', year: 'numeric' });
    badgeText.innerText = 'Hari Ini (' + formatted + ')';
  } else if (period === 'week') {
    badgeText.innerText = 'Minggu Ini';
  } else if (period === 'month') {
    const formatted = now.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
    badgeText.innerText = 'Bulan Ini (' + formatted + ')';
  } else if (period === 'year') {
    badgeText.innerText = 'Tahun Ini (' + now.getFullYear() + ')';
  } else if (period === 'lastYear') {
    badgeText.innerText = 'Tahun Lalu (' + (now.getFullYear() - 1) + ')';
  } else if (period === 'custom') {
    badgeText.innerText = customLabel || 'Filter Khusus';
  }
}

function togglePaymentCustomFilter() {
  const filterBox = document.getElementById('payment-custom-filter-box');
  if (!filterBox) return;

  if (filterBox.style.display === 'none' || filterBox.style.display === '') {
    filterBox.style.display = 'block';
  } else {
    filterBox.style.display = 'none';
  }
}

function setPaymentCustomFilterMode(mode) {
  currentPaymentCustomMode = mode;

  const modes = ['date', 'month', 'year', 'range'];
  modes.forEach(function(m) {
    const btn = document.getElementById('btn-payment-mode-' + m);
    const wrapper = document.getElementById('wrapper-payment-filter-' + m);
    if (btn) {
      if (m === mode) {
        btn.classList.remove('btn-soft-primary');
        btn.classList.add('btn-primary');
      } else {
        btn.classList.remove('btn-primary');
        btn.classList.add('btn-soft-primary');
      }
    }
    if (wrapper) {
      if (m === mode) {
        wrapper.classList.remove('d-none');
        wrapper.classList.add('d-inline-flex');
      } else {
        wrapper.classList.remove('d-inline-flex', 'd-flex');
        wrapper.classList.add('d-none');
      }
    }
  });
}

function applyPaymentCustomFilter() {
  const params = {
    filter_type: currentPaymentCustomMode
  };

  if (currentPaymentCustomMode === 'date') {
    const inputDate = document.getElementById('input-payment-filter-date');
    if (!inputDate || !inputDate.value) {
      alert('Silakan pilih tanggal terlebih dahulu.');
      return;
    }
    params.date = inputDate.value;
  } else if (currentPaymentCustomMode === 'month') {
    const selectMonth = document.getElementById('select-payment-filter-month');
    const selectYear = document.getElementById('select-payment-filter-month-year');
    if (!selectMonth || !selectYear) return;
    params.month = selectMonth.value;
    params.year = selectYear.value;
  } else if (currentPaymentCustomMode === 'year') {
    const selectYear = document.getElementById('select-payment-filter-year');
    if (!selectYear) return;
    params.year = selectYear.value;
  } else if (currentPaymentCustomMode === 'range') {
    const start = document.getElementById('input-payment-filter-start');
    const end = document.getElementById('input-payment-filter-end');
    if (!start || !end || !start.value || !end.value || start.value > end.value) {
      alert('Silakan pilih rentang tanggal yang valid.');
      return;
    }
    params.start_date = start.value;
    params.end_date = end.value;
  }

  // Loading state
  const loadingEl = document.getElementById('payment-chart-loading');
  const chartEl = document.getElementById('stacked_column_chart');
  const btnApply = document.getElementById('btn-apply-payment-custom-filter');
  const elementHarga = document.getElementById('harga');

  if (loadingEl) loadingEl.classList.remove('d-none');
  if (chartEl) chartEl.style.display = 'none';
  if (btnApply) {
    btnApply.disabled = true;
    btnApply.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Memuat...';
  }

  const queryStr = new URLSearchParams(params).toString();
  fetch('/dashboard-tkb/payment-chart-data?' + queryStr, {
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(function(res) {
    if (!res.ok) throw new Error('Network error saat memuat data payment chart');
    return res.json();
  })
  .then(function(result) {
    if (result.status === 'success') {
      // Update button visual states
      const buttons = document.getElementsByClassName('tombol');
      for (let i = 0; i < buttons.length; i++) {
        buttons[i].classList.remove('btn-info');
        buttons[i].classList.add('btn-soft-info');
      }
      const btnCustom = document.getElementById('btn-payment-custom-toggle');
      if (btnCustom) {
        btnCustom.classList.remove('btn-soft-warning');
        btnCustom.classList.add('btn-warning');
      }

      updatePaymentPeriodBadgeText('custom', result.period_label);

      // Render chart according to filter_type
      if (result.filter_type === 'range') {
        const d = result.data;
        const categories = d.dates || [];
        const totals = d.totals || [];
        chart.updateOptions({
          dataLabels: { enabled: false },
          series: [{ name: 'Pendapatan', data: totals, color: '#6B73F0' }],
          xaxis: { type: 'category', categories: categories, labels: { style: { colors: '#9AA4B2', fontSize: '11px' } } },
          yaxis: { labels: { formatter: function (val) { return formatRupiahCompact(val); }, style: { colors: '#6B7686', fontSize: '11px' } } },
          tooltip: { theme: 'dark', y: { formatter: function(val) { return formatRupiah(val); } } }
        });
        if (elementHarga) {
          elementHarga.innerHTML = `
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Transaksi Rentang Tanggal</span>
                <span class="text-white fw-bold tabular-nums fs-14">${formatRupiah(d.total_transaksi || 0)}</span>
              </div>
            </div>
          `;
        }
      } else if (result.filter_type === 'date') {
        const d = result.data;
        const hours = d.hours && d.hours.length > 0 ? d.hours : ['08:00 - 17:00'];
        const totals = d.totals && d.totals.length > 0 ? d.totals : [0];
        const totalTransaksi = d.total_transaksi || 0;
        const totalPositif = Math.max(0, totalTransaksi - 1000000);
        const totalNegatif = totalTransaksi < 1000000 ? (1000000 - totalTransaksi) : 0;
        const netIncome = totalTransaksi - 1000000;

        chart.updateOptions({
          dataLabels: { enabled: false },
          labels: hours,
          series: [
            {
              name: "Pendapatan",
              data: totals,
              color: '#6B73F0',
            }
          ],
          xaxis: {
            type: 'category',
            categories: hours,
            labels: {
              formatter: function(val) { return val; },
              style: { colors: '#9AA4B2', fontSize: '11px' }
            }
          },
          yaxis: {
            min: 0,
            max: Math.max(5000000, ...totals) + 1000000,
            tickAmount: 5,
            axisBorder: { show: false },
            axisTicks: { show: false },
            labels: {
              show: true,
              formatter: function (val) {
                return formatRupiahCompact(val);
              },
              style: { colors: '#6B7686', fontSize: '11px' }
            }
          },
          plotOptions: {
            bar: {
              distributed: false,
              borderRadius: 4,
              columnWidth: "40%",
            }
          },
          tooltip: {
            theme: 'dark',
            y: { formatter: function(val) { return formatRupiah(val); } }
          }
        });

        if (elementHarga) {
          elementHarga.innerHTML = `
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Transaksi</span>
                <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalTransaksi)}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Beban Tetap</span>
                <span class="text-white fw-semibold tabular-nums fs-13">Rp 1.000.000</span>
              </div>
              <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
                <span class="text-muted fs-13">Pendapatan Bersih</span>
                <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(netIncome)}</span>
              </div>
            </div>
          `;
        }
      } else if (result.filter_type === 'month') {
        const d = result.data;
        const year = d.year;
        const month = d.month;
        const daysInMonth = d.days_in_month;
        const totalsByDate = d.totals_by_date || {};

        const fullDateArray = Array.from({ length: daysInMonth }, (_, i) => {
          const dayStr = ("0" + (i + 1)).slice(-2);
          return `${year}-${month}-${dayStr}`;
        });

        const totalTransaksiAsli = fullDateArray.reduce((total, date) => {
          return total + (totalsByDate[date] || 0);
        }, 0);

        const filteredTotalHarianArray = fullDateArray.map(date => {
          const transaksi = totalsByDate[date];
          return transaksi !== undefined ? (transaksi - 1000000) : -1000000;
        });

        const totalPositif = filteredTotalHarianArray
          .filter(val => val > 0)
          .reduce((acc, val) => acc + val, 0);

        const totalNegatif = filteredTotalHarianArray
          .filter(val => val < 0)
          .reduce((acc, val) => acc + val, 0);

        const totalIncome = totalPositif + totalNegatif;

        chart.updateOptions({
          series: [{
            name: "Pendapatan Bersih",
            data: filteredTotalHarianArray
          }],
          xaxis: {
            type: 'category',
            categories: fullDateArray,
            labels: {
              formatter: function (value) {
                return value ? value.split("-")[2] : '';
              },
              style: { colors: '#9AA4B2', fontSize: '11px' }
            }
          },
          yaxis: {
            min: Math.min(-1000000, ...filteredTotalHarianArray),
            max: Math.max(5000000, ...filteredTotalHarianArray) + 1000000,
            tickAmount: 6,
            forceNiceScale: true,
            labels: {
              formatter: function (val) {
                return formatRupiahCompact(val);
              },
              style: { colors: '#6B7686', fontSize: '11px' }
            }
          },
          plotOptions: {
            bar: {
              borderRadius: 4,
              columnWidth: "48%",
              colors: {
                ranges: [
                  { from: -Infinity, to: 0, color: '#E5586B' },
                  { from: 0.0001, to: Infinity, color: '#6B73F0' }
                ]
              }
            }
          },
          tooltip: {
            theme: 'dark',
            y: { formatter: function(val) { return formatRupiah(val); } }
          },
          dataLabels: { enabled: false }
        });

        if (elementHarga) {
          elementHarga.innerHTML = `
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Pendapatan</span>
                <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalTransaksiAsli)}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Beban</span>
                <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(Math.abs(totalNegatif))}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
                <span class="text-muted fs-13">Pendapatan Bersih</span>
                <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(totalIncome)}</span>
              </div>
            </div>
          `;
        }
      } else if (result.filter_type === 'year') {
        const d = result.data;
        const targetYear = d.year;
        const isCurrentYear = d.is_current_year;
        const currentMonth = d.current_month;
        const currentDate = d.current_date;
        const totalsByMonth = d.totals_by_month || {};

        const monthsToDisplay = isCurrentYear ? currentMonth : 12;
        const fullMonthArray = Array.from({ length: monthsToDisplay }, (_, i) => {
          return `${targetYear}-${("0" + (i + 1)).slice(-2)}`;
        });

        const totalBulananArray = fullMonthArray.map((_, i) => {
          return totalsByMonth[i + 1] || 0;
        });

        const totalNegatifPerBulan = Array.from({ length: monthsToDisplay }, (_, monthIndex) => {
          const daysInMonth = (isCurrentYear && monthIndex + 1 === currentMonth)
            ? currentDate
            : new Date(targetYear, monthIndex + 1, 0).getDate();
          return daysInMonth * -1000000;
        });

        const totalWithFormulaArray = totalBulananArray.map((value, index) =>
          value + totalNegatifPerBulan[index]
        );

        const totalOriginalTahunIni = totalBulananArray.reduce((acc, val) => acc + val, 0);
        const totalNegatifTahunIni = totalNegatifPerBulan.reduce((acc, val) => acc + val, 0);
        const totalWithFormulaTahunIni = totalOriginalTahunIni + totalNegatifTahunIni;

        chart.updateOptions({
          series: [
            {
              name: `Pendapatan Bersih (${targetYear})`,
              data: totalWithFormulaArray,
              color: '#6B73F0',
            },
            {
              name: `Pendapatan Asli (${targetYear})`,
              data: totalBulananArray,
              color: '#34C28B',
            }
          ],
          xaxis: {
            type: 'category',
            categories: fullMonthArray,
            labels: {
              formatter: function (value) {
                return new Date(value + "-01").toLocaleString('id-ID', { month: 'short' });
              },
              style: { colors: '#9AA4B2', fontSize: '11px' }
            }
          },
          yaxis: {
            min: Math.min(...totalWithFormulaArray, 0),
            max: Math.max(...totalWithFormulaArray, ...totalBulananArray, 5000000) + 1000000,
            tickAmount: 6,
            forceNiceScale: true,
            labels: {
              formatter: function (val) {
                return formatRupiahCompact(val);
              },
              style: { colors: '#6B7686', fontSize: '11px' }
            }
          },
          plotOptions: {
            bar: {
              borderRadius: 4,
              columnWidth: "48%",
              colors: {
                ranges: [
                  { from: -Infinity, to: -1, color: '#E5586B' }
                ]
              }
            }
          },
          legend: {
            show: true,
            position: 'top',
            horizontalAlign: 'right',
            fontSize: '12px',
            fontFamily: 'inherit',
            labels: { colors: '#9AA4B2' }
          },
          tooltip: {
            theme: 'dark',
            y: { formatter: function(val) { return formatRupiah(val); } }
          },
          dataLabels: { enabled: false }
        });

        if (elementHarga) {
          elementHarga.innerHTML = `
            <div class="d-flex flex-column gap-2">
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Pendapatan (${targetYear})</span>
                <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(totalOriginalTahunIni)}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center">
                <span class="text-muted fs-13">Total Beban (${targetYear})</span>
                <span class="text-white fw-semibold tabular-nums fs-13">${formatRupiah(Math.abs(totalNegatifTahunIni))}</span>
              </div>
              <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
                <span class="text-muted fs-13">Pendapatan Bersih (${targetYear})</span>
                <span class="fw-bold tabular-nums fs-14" style="color: var(--brand);">${formatRupiah(totalWithFormulaTahunIni)}</span>
              </div>
            </div>
          `;
        }
      }
    }
  })
  .catch(function(err) {
    console.error('Error filter payment activity chart:', err);
    alert('Gagal mengambil data payment chart untuk filter yang dipilih.');
  })
  .finally(function() {
    if (loadingEl) loadingEl.classList.add('d-none');
    if (chartEl) chartEl.style.display = 'block';
    if (btnApply) {
      btnApply.disabled = false;
      btnApply.innerHTML = '<i class="ri-search-line me-1"></i>Tampilkan';
    }
  });
}

function timeRange(period) {
  if (!charts && document.querySelector("#structure_widget") && typeof ApexCharts !== 'undefined') {
    charts = new ApexCharts(document.querySelector("#structure_widget"), options);
    charts.render();
  }
  const periodeElement = document.getElementById('periode');
  const terbayarElement = document.getElementById('terbayar');
  const belumTerbayarElement = document.getElementById('belumTerbayar');
  const batalElement = document.getElementById('batal');
  const persenTerbayarElement = document.getElementById('persenTerbayar');
  const persenBlmTerbayarElement = document.getElementById('persenBlmTerbayar');
  const persenBatalElement = document.getElementById('persenBatal');

  let activeData = [0, 0, 0];
  let pTerbayar = '0.00';
  let pBlm = '0.00';
  let pBatal = '0.00';
  let labelPeriod = 'Tahun Lalu';

  switch (period) {
    case 'lastYear':
      activeData = (typeof datasLastYearJS !== 'undefined') ? datasLastYearJS : [0, 0, 0];
      pTerbayar = (typeof percentInLastYearJS !== 'undefined') ? percentInLastYearJS : '0.00';
      pBlm = (typeof percentNotPaidLastYearJS !== 'undefined') ? percentNotPaidLastYearJS : '0.00';
      pBatal = (typeof percentCanceledLastYearJS !== 'undefined') ? percentCanceledLastYearJS : '0.00';
      labelPeriod = 'Tahun Lalu';
      break;

    case 'year':
      activeData = (typeof datasYearJS !== 'undefined') ? datasYearJS : [0, 0, 0];
      pTerbayar = (typeof percentInYearJS !== 'undefined') ? percentInYearJS : '0.00';
      pBlm = (typeof percentNotPaidYearJS !== 'undefined') ? percentNotPaidYearJS : '0.00';
      pBatal = (typeof percentCanceledYearJS !== 'undefined') ? percentCanceledYearJS : '0.00';
      labelPeriod = 'Tahun Ini';
      break;

    case 'month':
      activeData = (typeof datasMonthJS !== 'undefined') ? datasMonthJS : [0, 0, 0];
      pTerbayar = (typeof percentInMonthJS !== 'undefined') ? percentInMonthJS : '0.00';
      pBlm = (typeof percentNotPaidMonthJS !== 'undefined') ? percentNotPaidMonthJS : '0.00';
      pBatal = (typeof percentCanceledMonthJS !== 'undefined') ? percentCanceledMonthJS : '0.00';
      labelPeriod = 'Bulan Ini';
      break;

    case 'week':
      activeData = (typeof datasWeekJS !== 'undefined') ? datasWeekJS : [0, 0, 0];
      pTerbayar = (typeof percentInWeekJS !== 'undefined') ? percentInWeekJS : '0.00';
      pBlm = (typeof percentNotPaidWeekJS !== 'undefined') ? percentNotPaidWeekJS : '0.00';
      pBatal = (typeof percentCanceledWeekJS !== 'undefined') ? percentCanceledWeekJS : '0.00';
      labelPeriod = 'Minggu Ini';
      break;

    default: // day / specific
      activeData = (typeof datasWeekJS !== 'undefined') ? datasWeekJS : [0, 0, 0];
      pTerbayar = (typeof percentInWeekJS !== 'undefined') ? percentInWeekJS : '0.00';
      pBlm = (typeof percentNotPaidWeekJS !== 'undefined') ? percentNotPaidWeekJS : '0.00';
      pBatal = (typeof percentCanceledWeekJS !== 'undefined') ? percentCanceledWeekJS : '0.00';
      labelPeriod = 'Hari Ini';
      break;
  }

  if (charts) {
    charts.updateOptions({
      series: [activeData[0] || 0, activeData[1] || 0, activeData[2] || 0],
    });
  }

  if (periodeElement) periodeElement.innerText = labelPeriod;
  if (terbayarElement) terbayarElement.innerText = formatRupiah(activeData[0] || 0);
  if (belumTerbayarElement) belumTerbayarElement.innerText = formatRupiah(activeData[1] || 0);
  if (batalElement) batalElement.innerText = formatRupiah(activeData[2] || 0);

  if (persenTerbayarElement) {
    formatPersentaseTerbayar(pTerbayar);
  }
  if (persenBlmTerbayarElement) {
    formatPersentase(pBlm);
  }
  if (persenBatalElement) {
    formatPersentaseBatal(pBatal);
  }
}

function formatPersentaseTerbayar(persen) {
  const el = document.getElementById('persenTerbayar');
  const num = parseFloat(persen) || 0;
  const absFormatted = Math.abs(num).toFixed(2).replace('.', ',') + '%';
  if (el) {
    if (num > 0) {
      el.className = 'badge-tint badge-tint-success';
      el.innerHTML = `<i class="ri-arrow-up-s-line"></i>+${absFormatted}`;
    } else if (num < 0) {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = `<i class="ri-arrow-down-s-line"></i>−${absFormatted}`;
    } else {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = '0,00%';
    }
  }
  return num > 0 ? '+' + persen : persen;
}

function formatPersentase(persen) {
  const el = document.getElementById('persenBlmTerbayar');
  const num = parseFloat(persen) || 0;
  const absFormatted = Math.abs(num).toFixed(2).replace('.', ',') + '%';
  if (el) {
    if (num > 0) {
      el.className = 'badge-tint badge-tint-warning';
      el.innerHTML = `<i class="ri-arrow-up-s-line"></i>+${absFormatted}`;
    } else if (num < 0) {
      el.className = 'badge-tint badge-tint-success';
      el.innerHTML = `<i class="ri-arrow-down-s-line"></i>−${absFormatted}`;
    } else {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = '0,00%';
    }
  }
  return num > 0 ? '+' + persen : persen;
}

function formatPersentaseBatal(persen) {
  const el = document.getElementById('persenBatal');
  const num = parseFloat(persen) || 0;
  const absFormatted = Math.abs(num).toFixed(2).replace('.', ',') + '%';
  if (el) {
    if (num > 0) {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = `<i class="ri-arrow-up-s-line"></i>+${absFormatted}`;
    } else if (num < 0) {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = `<i class="ri-arrow-down-s-line"></i>−${absFormatted}`;
    } else {
      el.className = 'badge-tint badge-tint-neutral';
      el.innerHTML = '0,00%';
    }
  }
  return num > 0 ? '+' + persen : persen;
}

// ====================================================================
// GRAFIK PENJUALAN ITEM & PILIHAN PAJAK DARI ITEM
// ====================================================================
var currentItemMetric = 'nominal';     // 'nominal' (default Rp) atau 'qty'
var currentItemTimeframe = 'year';     // 'today', 'week', 'month', 'year', 'last_year', 'custom'
var currentItemTaxFilter = 'all';      // 'all', 'ppn', 'pph', 'ppn_pph', 'tanpa_pajak'
var currentItemCustomMode = 'date';    // 'date', 'month', 'year', 'range'
var customItemPeriodLabel = '';
var chartPenjualanItem = null;
var chartPilihanPajakDonut = null;

const ITEM_PALETTE = [
  '#6B73F0', '#7E85F4', '#5A62DB', '#8B92FF', '#4E55C7',
  '#A0A6FF', '#3D44A6', '#6B7686', '#9AA4B2', '#4A5361'
];

const TAX_COLOR_MAP = {
  'ppn': '#6B73F0',         // Single Accent Indigo
  'pph': '#E3A33B',         // Warm Amber Warning
  'ppn_pph': '#5B9DF0',     // Info Blue
  'tanpa_pajak': '#6B7686', // Slate Neutral
};

const TAX_LABEL_MAP = {
  'ppn': 'PPN',
  'pph': 'PPh',
  'ppn_pph': 'PPN & PPh',
  'tanpa_pajak': 'Tanpa Pajak',
};

const TAX_BADGE_CLASS = {
  'ppn': 'badge-tax-ppn',
  'pph': 'badge-tax-pph',
  'ppn_pph': 'badge-tax-ppn_pph',
  'tanpa_pajak': 'badge-tax-tanpa_pajak',
};

function initItemSalesChart() {
  if (typeof dataPembelianBarangJS === 'undefined' || !dataPembelianBarangJS) {
    return;
  }
  updateItemPeriodBadge();
  renderItemSalesSection();
}

function updateItemPeriodBadge() {
  const badgePeriod = document.getElementById('badge-item-period-text');
  const badgeTax = document.getElementById('badge-item-tax-text');
  const now = new Date();

  if (badgePeriod) {
    if (currentItemTimeframe === 'today') {
      const formatted = now.toLocaleDateString('id-ID', { day: 'numeric', month: 'short', year: 'numeric' });
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>Hari Ini (' + formatted + ')';
    } else if (currentItemTimeframe === 'week') {
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>Minggu Ini';
    } else if (currentItemTimeframe === 'month') {
      const formatted = now.toLocaleDateString('id-ID', { month: 'long', year: 'numeric' });
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>Bulan Ini (' + formatted + ')';
    } else if (currentItemTimeframe === 'year') {
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>Tahun Ini (' + now.getFullYear() + ')';
    } else if (currentItemTimeframe === 'last_year') {
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>Tahun Lalu (' + (now.getFullYear() - 1) + ')';
    } else if (currentItemTimeframe === 'custom') {
      badgePeriod.innerHTML = '<i class="ri-calendar-line me-1"></i>' + (customItemPeriodLabel || 'Filter Kustom');
    }
  }

  if (badgeTax) {
    if (currentItemTaxFilter === 'all') {
      badgeTax.innerHTML = '<i class="ri-shield-check-line me-1"></i>Semua Pilihan Pajak';
    } else {
      const label = TAX_LABEL_MAP[currentItemTaxFilter] || currentItemTaxFilter.toUpperCase();
      badgeTax.innerHTML = '<i class="ri-shield-check-line me-1"></i>Pajak: ' + label;
    }
  }
}

function switchItemTimeframe(timeframe) {
  currentItemTimeframe = timeframe;

  const buttons = ['today', 'week', 'month', 'year', 'last_year'];
  buttons.forEach(function (tf) {
    const btn = document.getElementById('btn-item-' + tf);
    if (btn) {
      if (tf === timeframe) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    }
  });

  const btnCustom = document.getElementById('btn-item-custom-toggle');
  if (btnCustom) {
    btnCustom.classList.remove('active');
  }

  updateItemPeriodBadge();

  if (currentItemTaxFilter !== 'all' || !dataPembelianBarangJS.jenis || !dataPembelianBarangJS.jenis[timeframe]) {
    fetchItemDataAjax();
  } else {
    renderItemSalesSection();
  }
}

function onTaxFilterChanged(taxValue) {
  currentItemTaxFilter = taxValue;
  updateItemPeriodBadge();

  if (currentItemTimeframe === 'custom' || currentItemTaxFilter !== 'all') {
    fetchItemDataAjax();
  } else {
    renderItemSalesSection();
  }
}

function switchItemMetric(metric) {
  currentItemMetric = metric;

  const btnNominal = document.getElementById('btn-item-metric-nominal');
  const btnQty = document.getElementById('btn-item-metric-qty');
  if (btnNominal && btnQty) {
    if (metric === 'nominal') {
      btnNominal.classList.add('active');
      btnQty.classList.remove('active');
    } else {
      btnQty.classList.add('active');
      btnNominal.classList.remove('active');
    }
  }

  renderItemChart();
}

function toggleCustomFilterItem() {
  const box = document.getElementById('item-custom-filter-box');
  const icon = document.getElementById('icon-toggle-item-custom');
  if (!box) return;

  if (box.style.display === 'none' || box.style.display === '') {
    box.style.display = 'block';
    if (icon) {
      icon.style.transform = 'rotate(180deg)';
    }
  } else {
    box.style.display = 'none';
    if (icon) {
      icon.style.transform = 'rotate(0deg)';
    }
  }
}

function setItemCustomMode(mode) {
  currentItemCustomMode = mode;

  const modes = ['date', 'month', 'year', 'range'];
  modes.forEach(function (m) {
    const btn = document.getElementById('btn-item-mode-' + m);
    const wrapper = document.getElementById('wrapper-item-filter-' + m);
    if (btn) {
      if (m === mode) {
        btn.classList.add('active');
      } else {
        btn.classList.remove('active');
      }
    }
    if (wrapper) {
      if (m === mode) {
        wrapper.classList.remove('d-none');
        wrapper.classList.add('d-inline-flex');
      } else {
        wrapper.classList.remove('d-inline-flex', 'd-flex');
        wrapper.classList.add('d-none');
      }
    }
  });
}

function applyCustomFilterItem() {
  const params = {
    timeframe: 'custom',
    filter_type: currentItemCustomMode,
    tax_filter: currentItemTaxFilter
  };

  if (currentItemCustomMode === 'date') {
    const inputDate = document.getElementById('input-item-filter-date');
    if (!inputDate || !inputDate.value) {
      alert('Silakan pilih tanggal terlebih dahulu.');
      return;
    }
    params.date = inputDate.value;
  } else if (currentItemCustomMode === 'month') {
    const selectMonth = document.getElementById('select-item-filter-month');
    const selectYear = document.getElementById('select-item-filter-month-year');
    if (!selectMonth || !selectYear) return;
    params.month = selectMonth.value;
    params.year = selectYear.value;
  } else if (currentItemCustomMode === 'year') {
    const selectYear = document.getElementById('select-item-filter-year');
    if (!selectYear) return;
    params.year = selectYear.value;
  } else if (currentItemCustomMode === 'range') {
    const start = document.getElementById('input-item-filter-start');
    const end = document.getElementById('input-item-filter-end');
    if (!start || !end || !start.value || !end.value || start.value > end.value) {
      alert('Silakan pilih rentang tanggal yang valid.');
      return;
    }
    params.start_date = start.value;
    params.end_date = end.value;
  }

  fetchItemDataAjax(params, true);
}

function fetchItemDataAjax(customParams, isCustomSubmit) {
  const loadingEl = document.getElementById('item-chart-loading');
  const chartEl = document.getElementById('chart_penjualan_item');
  const emptyEl = document.getElementById('item-chart-empty');
  const btnApply = document.getElementById('btn-apply-item-filter');

  if (loadingEl) loadingEl.classList.remove('d-none');
  if (chartEl) chartEl.style.display = 'none';
  if (emptyEl) emptyEl.classList.add('d-none');
  if (btnApply) {
    btnApply.disabled = true;
    btnApply.innerHTML = '<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span>Memuat...';
  }

  let params = customParams;
  if (!params) {
    params = {
      timeframe: currentItemTimeframe,
      tax_filter: currentItemTaxFilter
    };
  }

  const queryStr = new URLSearchParams(params).toString();
  fetch('/dashboard-tkb/barang-chart-data?' + queryStr, {
    headers: {
      'Accept': 'application/json',
      'X-Requested-With': 'XMLHttpRequest'
    }
  })
  .then(function (res) {
    if (!res.ok) throw new Error('Gagal mengambil data dari server');
    return res.json();
  })
  .then(function (result) {
    if (result.status === 'success') {
      if (typeof dataPembelianBarangJS === 'undefined' || !dataPembelianBarangJS) {
        dataPembelianBarangJS = {};
      }
      if (!dataPembelianBarangJS.jenis) dataPembelianBarangJS.jenis = {};
      if (!dataPembelianBarangJS.pajak) dataPembelianBarangJS.pajak = {};

      const tfKey = result.timeframe || currentItemTimeframe;
      dataPembelianBarangJS.jenis[tfKey] = result.data.jenis;
      dataPembelianBarangJS.pajak[tfKey] = result.data.pajak;

      if (isCustomSubmit) {
        customItemPeriodLabel = result.period_label || 'Filter Kustom';
        currentItemTimeframe = 'custom';

        const buttons = ['today', 'week', 'month', 'year', 'last_year'];
        buttons.forEach(function (tf) {
          const btn = document.getElementById('btn-item-' + tf);
          if (btn) btn.classList.remove('active');
        });
        const btnCustom = document.getElementById('btn-item-custom-toggle');
        if (btnCustom) btnCustom.classList.add('active');
      }

      updateItemPeriodBadge();
      renderItemSalesSection();
    }
  })
  .catch(function (err) {
    console.error('Error memuat data penjualan item:', err);
    if (isCustomSubmit) {
      alert('Gagal mengambil data untuk filter yang dipilih.');
    }
  })
  .finally(function () {
    if (loadingEl) loadingEl.classList.add('d-none');
    if (btnApply) {
      btnApply.disabled = false;
      btnApply.innerHTML = '<i class="ri-search-line me-1"></i>Tampilkan';
    }
  });
}

function getFilteredItems() {
  if (typeof dataPembelianBarangJS === 'undefined' || !dataPembelianBarangJS) return [];

  const jenisObj = (dataPembelianBarangJS.jenis && dataPembelianBarangJS.jenis[currentItemTimeframe]) || { items: [] };
  let items = jenisObj.items || [];

  if (currentItemTaxFilter !== 'all') {
    items = items.filter(function (it) {
      return (it.jenis_pajak || '') === currentItemTaxFilter;
    });
  }

  return items;
}

function getPajakDistribution() {
  if (typeof dataPembelianBarangJS === 'undefined' || !dataPembelianBarangJS) return [];

  const pajakObj = (dataPembelianBarangJS.pajak && dataPembelianBarangJS.pajak[currentItemTimeframe]) || { items: [] };
  let items = pajakObj.items || [];

  if (currentItemTaxFilter !== 'all') {
    items = items.filter(function (it) {
      return (it.raw_tax || '') === currentItemTaxFilter;
    });
  }

  return items;
}

function renderItemSalesSection() {
  const items = getFilteredItems();
  const pajakItems = getPajakDistribution();

  renderItemStats(items, pajakItems);
  renderItemChart(items);
  renderPajakDonutChart(pajakItems);
  renderItemDetailTable(items);
}

function renderItemStats(items, pajakItems) {
  const statQtyEl = document.getElementById('stat-item-qty');
  const statNominalEl = document.getElementById('stat-item-nominal');
  const statTopEl = document.getElementById('stat-item-top');
  const statTaxEl = document.getElementById('stat-item-dominant-tax');
  const badgeCountEl = document.getElementById('badge-item-count-text');
  const totalItemShownEl = document.getElementById('badge-total-item-shown');

  const totalQty = items.reduce(function (sum, it) { return sum + (it.total_qty || 0); }, 0);
  const totalNominal = items.reduce(function (sum, it) { return sum + (it.total_nominal || 0); }, 0);

  if (statQtyEl) {
    statQtyEl.innerText = new Intl.NumberFormat('id-ID').format(totalQty) + ' Unit';
  }
  if (statNominalEl) {
    animateCountUp(statNominalEl, totalNominal, true);
  }

  // Top Item by sales
  if (items.length > 0) {
    const sorted = [...items].sort(function (a, b) {
      return b.total_nominal - a.total_nominal;
    });
    const top = sorted[0];
    const topItemDesc = top.label + ' (' + (top.jenis_pajak_label || 'Pajak') + ') - ' + formatRupiah(top.total_nominal);
    if (statTopEl) {
      statTopEl.innerText = top.label;
      statTopEl.title = topItemDesc;
    }
  } else {
    if (statTopEl) {
      statTopEl.innerText = '−';
      statTopEl.title = '−';
    }
  }

  // Dominant Tax Option
  if (pajakItems.length > 0) {
    const sortedTax = [...pajakItems].sort(function (a, b) {
      return b.total_nominal - a.total_nominal;
    });
    const dominant = sortedTax[0];
    const totalTaxNominal = pajakItems.reduce(function (s, x) { return s + (x.total_nominal || 0); }, 0);
    const pct = totalTaxNominal > 0 ? ((dominant.total_nominal / totalTaxNominal) * 100).toFixed(1).replace('.', ',') + '%' : '0%';
    if (statTaxEl) {
      statTaxEl.innerText = dominant.label + ' (' + pct + ')';
      statTaxEl.title = dominant.label + ' − ' + formatRupiah(dominant.total_nominal);
    }
  } else {
    if (statTaxEl) {
      statTaxEl.innerText = '−';
      statTaxEl.title = '−';
    }
  }

  if (badgeCountEl) {
    badgeCountEl.innerText = 'Menampilkan ' + items.length + ' Item Terjual';
  }
  if (totalItemShownEl) {
    totalItemShownEl.innerText = items.length > 10 ? 'Top 10 dari ' + items.length + ' Item' : items.length + ' Item';
  }
}

function renderItemChart(items) {
  if (!items) items = getFilteredItems();

  const chartEl = document.getElementById('chart_penjualan_item');
  const emptyEl = document.getElementById('item-chart-empty');

  if (!items || items.length === 0) {
    if (chartEl) chartEl.style.display = 'none';
    if (emptyEl) emptyEl.classList.remove('d-none');
    if (chartPenjualanItem) {
      chartPenjualanItem.destroy();
      chartPenjualanItem = null;
    }
    return;
  }

  if (chartEl) chartEl.style.display = 'block';
  if (emptyEl) emptyEl.classList.add('d-none');

  // Sort descending by metric and take top 10
  const sorted = [...items].sort(function (a, b) {
    return currentItemMetric === 'nominal'
      ? (b.total_nominal - a.total_nominal)
      : (b.total_qty - a.total_qty);
  });
  const top10 = sorted.slice(0, 10);

  const categories = top10.map(function (it) {
    return it.label;
  });
  const seriesValues = top10.map(function (it) {
    return currentItemMetric === 'nominal' ? it.total_nominal : it.total_qty;
  });

  // Requirement: dynamic height matching item count, bar height ~28-32px
  const barCount = top10.length;
  const dynamicHeight = Math.max(160, barCount * 42 + 64);
  const calculatedBarHeight = barCount <= 2 ? '28px' : (barCount <= 4 ? '30px' : '65%');

  const options = {
    chart: {
      type: 'bar',
      height: dynamicHeight,
      toolbar: { show: false },
      animations: {
        enabled: true,
        speed: 350,
        easing: 'easeout'
      }
    },
    plotOptions: {
      bar: {
        horizontal: true,
        borderRadius: 4,
        barHeight: calculatedBarHeight,
        distributed: false,
        dataLabels: {
          position: 'top'
        }
      }
    },
    dataLabels: {
      enabled: true,
      textAnchor: 'start',
      offsetX: 10,
      formatter: function (val) {
        if (currentItemMetric === 'nominal') {
          return formatRupiahCompact(val);
        } else {
          return new Intl.NumberFormat('id-ID').format(val) + ' Unit';
        }
      },
      style: {
        fontSize: '12px',
        fontWeight: 600,
        colors: ['#9AA4B2']
      }
    },
    series: [{
      name: currentItemMetric === 'nominal' ? 'Total Penjualan' : 'Jumlah Unit Terjual',
      data: seriesValues
    }],
    xaxis: {
      categories: categories,
      labels: {
        formatter: function (val) {
          if (currentItemMetric === 'nominal') {
            return formatRupiahCompact(val);
          } else {
            return new Intl.NumberFormat('id-ID').format(val);
          }
        },
        style: { colors: '#6B7686', fontSize: '11px' }
      },
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: {
        maxWidth: 220,
        style: {
          colors: '#E8EBF0',
          fontSize: '12px',
          fontWeight: 500
        }
      }
    },
    colors: ['#6B73F0'], // Single Accent Indigo
    legend: { show: false },
    tooltip: {
      theme: 'dark',
      custom: function (opts) {
        const item = top10[opts.dataPointIndex];
        if (!item) return '';

        const taxBadgeText = item.jenis_pajak_label || item.jenis_pajak || 'Pajak';
        const qtyFormatted = new Intl.NumberFormat('id-ID').format(item.total_qty) + ' Unit';
        const nominalFormatted = formatRupiah(item.total_nominal);
        const trxFormatted = item.total_transaksi ? new Intl.NumberFormat('id-ID').format(item.total_transaksi) + ' transaksi' : '';

        return `
          <div style="background: #181D26; border: 1px solid #232A35; border-radius: 8px; padding: 10px 14px; font-family: inherit; min-width: 210px; box-shadow: 0 10px 25px rgba(0,0,0,0.45);">
            <div style="font-weight: 600; font-size: 13px; color: #E8EBF0; margin-bottom: 4px;">${item.label}</div>
            <div style="margin-bottom: 8px;">
              <span class="badge-tint badge-tint-neutral" style="font-size: 11px;">
                ${taxBadgeText}
              </span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; color: #9AA4B2;">
              <span>Total Penjualan:</span>
              <span style="font-weight: 600; color: #6B73F0; font-variant-numeric: tabular-nums;">${nominalFormatted}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: #9AA4B2;">
              <span>Kuantitas:</span>
              <span style="font-weight: 500; color: #E8EBF0; font-variant-numeric: tabular-nums;">${qtyFormatted}</span>
            </div>
            ${trxFormatted ? `<div style="font-size: 11px; color: #6B7686; margin-top: 6px; text-align: right;">${trxFormatted}</div>` : ''}
          </div>
        `;
      }
    },
    grid: {
      borderColor: '#232A35',
      strokeDashArray: 3,
      xaxis: { lines: { show: true } },
      yaxis: { lines: { show: false } }
    }
  };

  if (chartPenjualanItem) {
    chartPenjualanItem.destroy();
  }
  chartPenjualanItem = new ApexCharts(document.querySelector('#chart_penjualan_item'), options);
  chartPenjualanItem.render();
}

function renderPajakDonutChart(pajakItems) {
  if (!pajakItems) pajakItems = getPajakDistribution();

  const totalNominal = pajakItems.reduce(function (sum, it) { return sum + (it.total_nominal || 0); }, 0);
  const totalQty = pajakItems.reduce(function (sum, it) { return sum + (it.total_qty || 0); }, 0);

  const badgeTotalPajak = document.getElementById('badge-total-pajak-count');
  if (badgeTotalPajak) {
    badgeTotalPajak.innerText = pajakItems.length + ' Pilihan Pajak';
  }

  const chartContainer = document.querySelector('#chart_pilihan_pajak_donut');
  if (!chartContainer) return;

  if (pajakItems.length === 0) {
    if (chartPilihanPajakDonut) {
      chartPilihanPajakDonut.destroy();
      chartPilihanPajakDonut = null;
    }
    chartContainer.innerHTML = '<div class="text-center py-5 text-muted"><p class="mt-2 mb-0" style="color: #6B7686; font-size: 13px;">Tidak ada data transaksi</p></div>';
    return;
  }

  const series = pajakItems.map(function (it) {
    return currentItemMetric === 'nominal' ? it.total_nominal : it.total_qty;
  });
  const labels = pajakItems.map(function (it) { return it.label; });
  const sliceColors = pajakItems.map(function (it) {
    return TAX_COLOR_MAP[it.raw_tax] || '#6B73F0';
  });

  const options = {
    chart: {
      type: 'donut',
      height: 280,
      animations: { enabled: true, speed: 350 }
    },
    series: series,
    labels: labels,
    colors: sliceColors,
    stroke: {
      width: 2,
      colors: ['#12161D']
    },
    plotOptions: {
      pie: {
        startAngle: 0,
        donut: {
          size: '82%',
          background: 'transparent',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '11px',
              fontFamily: 'inherit',
              color: '#6B7686',
              offsetY: -4
            },
            value: {
              show: true,
              fontSize: '16px',
              fontWeight: 700,
              fontFamily: 'inherit',
              color: '#E8EBF0',
              offsetY: 4,
              formatter: function (val) {
                if (currentItemMetric === 'nominal') {
                  return formatRupiahCompact(val);
                } else {
                  return new Intl.NumberFormat('id-ID').format(val) + ' Unit';
                }
              }
            },
            total: {
              show: true,
              showAlways: true,
              label: currentItemMetric === 'nominal' ? 'Total Penjualan' : 'Total Unit',
              color: '#6B7686',
              fontSize: '11px',
              fontFamily: 'inherit',
              formatter: function (w) {
                const total = w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                if (currentItemMetric === 'nominal') {
                  return formatRupiahCompact(total);
                } else {
                  return new Intl.NumberFormat('id-ID').format(total) + ' Unit';
                }
              }
            }
          }
        }
      }
    },
    dataLabels: { enabled: false },
    legend: {
      show: true,
      position: 'bottom',
      horizontalAlign: 'center',
      fontSize: '12px',
      fontFamily: 'inherit',
      labels: { colors: '#9AA4B2' },
      markers: {
        radius: 4,
        width: 8,
        height: 8
      },
      itemMargin: { horizontal: 8, vertical: 4 }
    },
    tooltip: {
      theme: 'dark',
      y: {
        formatter: function (val) {
          const grandTotal = currentItemMetric === 'nominal' ? totalNominal : totalQty;
          const pct = grandTotal > 0 ? ((val / grandTotal) * 100).toFixed(1).replace('.', ',') + '%' : '0%';
          if (currentItemMetric === 'nominal') {
            return formatRupiah(val) + ' (' + pct + ')';
          } else {
            return new Intl.NumberFormat('id-ID').format(val) + ' Unit (' + pct + ')';
          }
        }
      }
    }
  };

  if (chartPilihanPajakDonut) {
    chartPilihanPajakDonut.destroy();
  }
  chartPilihanPajakDonut = new ApexCharts(chartContainer, options);
  chartPilihanPajakDonut.render();
}

function renderItemDetailTable(items) {
  if (!items) items = getFilteredItems();

  const tbody = document.getElementById('tbody-item-detail-list');
  const badgeTotalItem = document.getElementById('badge-table-total-item');
  const badgeGrandTotal = document.getElementById('badge-table-grand-total');
  const tfootTrx = document.getElementById('tfoot-total-transaksi');
  const tfootQty = document.getElementById('tfoot-total-qty');
  const tfootNominal = document.getElementById('tfoot-total-nominal');
  const tfoot = document.getElementById('tfoot-item-detail');

  if (!tbody) return;

  const grandNominal = items.reduce(function (sum, it) { return sum + (it.total_nominal || 0); }, 0);
  const grandQty = items.reduce(function (sum, it) { return sum + (it.total_qty || 0); }, 0);
  const grandTrx = items.reduce(function (sum, it) { return sum + (it.total_transaksi || 0); }, 0);

  if (badgeTotalItem) {
    badgeTotalItem.innerText = items.length + ' Item';
  }
  if (badgeGrandTotal) {
    badgeGrandTotal.innerText = formatRupiah(grandNominal);
  }

  tbody.innerHTML = '';
  if (items.length === 0) {
    tbody.innerHTML = '<tr><td colspan="7" class="text-center py-5" style="color: #6B7686; font-size: 13px;"><i class="ri-inbox-line fs-28 d-block mb-1"></i>Tidak ada data item untuk periode dan filter pajak ini</td></tr>';
    if (tfoot) tfoot.style.display = 'none';
    return;
  }

  if (tfoot) tfoot.style.display = '';
  if (tfootTrx) tfootTrx.innerText = new Intl.NumberFormat('id-ID').format(grandTrx) + ' Transaksi';
  if (tfootQty) tfootQty.innerText = new Intl.NumberFormat('id-ID').format(grandQty) + ' Unit';
  if (tfootNominal) tfootNominal.innerText = formatRupiah(grandNominal);

  // Sort descending by nominal
  const sorted = [...items].sort(function (a, b) {
    return b.total_nominal - a.total_nominal;
  });

  sorted.forEach(function (item, index) {
    const taxLabel = item.jenis_pajak_label || item.jenis_pajak || 'Tanpa Pajak';
    const pct = grandNominal > 0 ? ((item.total_nominal / grandNominal) * 100).toFixed(1).replace('.', ',') + '%' : '0,0%';
    const pctNum = grandNominal > 0 ? ((item.total_nominal / grandNominal) * 100).toFixed(1) : 0;
    const nominalFormatted = formatRupiah(item.total_nominal);
    const totalTrx = item.total_transaksi ? new Intl.NumberFormat('id-ID').format(item.total_transaksi) : '0';

    const tr = document.createElement('tr');
    tr.innerHTML = `
      <td class="text-center" style="color: #94A3B8; font-size: 12px; font-variant-numeric: tabular-nums;">
        ${index + 1}
      </td>
      <td>
        <span style="color: #F8FAFC; font-weight: 500; font-size: 13px;">${item.label}</span>
      </td>
      <td class="text-center">
        <span class="badge-tint badge-tint-neutral">${taxLabel}</span>
      </td>
      <td class="text-end" style="color: #CBD5E1; font-size: 13px; font-variant-numeric: tabular-nums;">
        ${totalTrx}
      </td>
      <td class="text-end" style="color: #F1F5F9; font-weight: 500; font-size: 13px; font-variant-numeric: tabular-nums;">
        ${new Intl.NumberFormat('id-ID').format(item.total_qty)}
      </td>
      <td class="text-end" style="color: #FFFFFF; font-weight: 600; font-size: 13px; font-variant-numeric: tabular-nums;">
        ${nominalFormatted}
      </td>
      <td class="text-end">
        <div class="d-flex align-items-center justify-content-end gap-2">
          <div style="width: 50px; height: 4px; background: rgba(255,255,255,0.06); border-radius: 2px; overflow: hidden;">
            <div style="width: ${pctNum}%; height: 100%; background: #6B73F0; border-radius: 2px;"></div>
          </div>
          <span style="min-width: 44px; color: #CBD5E1; font-size: 12px; font-variant-numeric: tabular-nums;">${pct}</span>
        </div>
      </td>
    `;
    tbody.appendChild(tr);
  });
}

function initDashboardCharts() {
  try {
    if (typeof timeFrame === 'function') {
      timeFrame('lastYear');
    }
  } catch (e1) {
    console.error('Error saat inisialisasi timeFrame:', e1);
  }

  try {
    if (typeof initItemSalesChart === 'function') {
      initItemSalesChart();
    }
  } catch (e2) {
    console.error('Error saat inisialisasi initItemSalesChart:', e2);
  }
}

// Expose functions globally to window for HTML onclick handlers
window.timeFrame = timeFrame;
window.timeRange = timeRange;
window.togglePaymentCustomFilter = togglePaymentCustomFilter;
window.setPaymentCustomFilterMode = setPaymentCustomFilterMode;
window.applyPaymentCustomFilter = applyPaymentCustomFilter;
window.updatePaymentPeriodBadgeText = updatePaymentPeriodBadgeText;

window.switchItemTimeframe = switchItemTimeframe;
window.onTaxFilterChanged = onTaxFilterChanged;
window.switchItemMetric = switchItemMetric;
window.toggleCustomFilterItem = toggleCustomFilterItem;
window.setItemCustomMode = setItemCustomMode;
window.applyCustomFilterItem = applyCustomFilterItem;
window.initItemSalesChart = initItemSalesChart;
window.renderItemSalesSection = renderItemSalesSection;
window.initDashboardCharts = initDashboardCharts;

if (document.readyState === 'loading') {
  document.addEventListener('DOMContentLoaded', initDashboardCharts);
} else {
  initDashboardCharts();
}
