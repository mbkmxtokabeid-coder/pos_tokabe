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

function getTokabeChartPalette() {
  const isLight = document.documentElement.getAttribute('data-theme') === 'light';
  return {
    isLight: isLight,
    brand: isLight ? '#0F9B60' : '#22C58B',
    brandDark: isLight ? '#0A7A4B' : '#0D6E48',
    brandSoft: isLight ? '#7ED9B0' : '#10B981',
    cyan: isLight ? '#0B7A94' : '#38BDF8',
    cyanSoft: isLight ? '#5BB7CC' : '#0E7490',
    danger: isLight ? '#E11D48' : '#FB7185',
    warning: isLight ? '#D97706' : '#F59E0B',
    neutral: isLight ? '#647A70' : '#6E887C',
    textPrimary: isLight ? '#0F1F18' : '#F2F8F5',
    textSecondary: isLight ? '#4D655A' : '#9CB3A8',
    textMuted: isLight ? '#718A7E' : '#6E887C',
    surface: isLight ? '#FFFFFF' : '#0D1713',
    surface2: isLight ? '#F4F9F6' : '#111F19',
    border: isLight ? '#DCE8E1' : 'rgba(255, 255, 255, 0.08)',
    grid: isLight ? 'rgba(15, 45, 32, 0.08)' : 'rgba(255, 255, 255, 0.06)',
    tooltipTheme: isLight ? 'light' : 'dark',
  };
}

var currentActivePaymentPeriod = 'lastYear';
var currentLastPaymentRenderArgs = null;

// CHART BAR UNTUK UMUM (DILUAR PO)
var _tkbInitPal = getTokabeChartPalette();
var options,
  chart,
  linechartBasicColors = [_tkbInitPal.brand, _tkbInitPal.cyan],
  barchartColors =
    ((options = {
      chart: {
        height: 350,
        type: "bar",
        toolbar: { show: false },
        animations: { enabled: true, speed: 350 },
      },
      plotOptions: {
        bar: {
          horizontal: false,
          columnWidth: "46%",
          borderRadius: 5,
          borderRadiusApplication: 'end',
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
          style: { colors: _tkbInitPal.textMuted, fontSize: '11px' }
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
          style: { colors: _tkbInitPal.textMuted, fontSize: '11px' }
        },
      },
      grid: {
        xaxis: { lines: { show: false } },
        yaxis: { lines: { show: true } },
        borderColor: _tkbInitPal.grid,
        strokeDashArray: 4
      },
      colors: [_tkbInitPal.brand],
      legend: { show: false },
      tooltip: {
        theme: _tkbInitPal.tooltipTheme,
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
      chart: { height: 265, type: "donut", animations: { enabled: true, speed: 450 } },
      series: (typeof datasLastYearJS !== 'undefined' && Array.isArray(datasLastYearJS))
        ? [datasLastYearJS[0] || 0, datasLastYearJS[1] || 0, datasLastYearJS[2] || 0]
        : (typeof datasMonthJS !== 'undefined' && Array.isArray(datasMonthJS)
          ? [datasMonthJS[0] || 0, datasMonthJS[1] || 0, datasMonthJS[2] || 0]
          : [0, 0, 0]),
      labels: ["Terbayar", "Belum bayar", "Batal"],
      colors: [_tkbInitPal.brand, _tkbInitPal.warning, _tkbInitPal.neutral],
      stroke: {
        width: 3,
        colors: [_tkbInitPal.surface]
      },
      plotOptions: { 
        pie: { 
          donut: { 
            size: "78%",
            background: 'transparent',
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '11.5px',
                fontFamily: 'inherit',
                color: _tkbInitPal.textMuted,
                offsetY: -4
              },
              value: {
                show: true,
                fontSize: '20px',
                fontWeight: '800',
                fontFamily: 'inherit',
                color: _tkbInitPal.textPrimary,
                offsetY: 6,
                formatter: function (val) {
                  return formatRupiahCompact(val);
                }
              },
              total: {
                show: true,
                showAlways: true,
                label: 'Total tagihan',
                color: _tkbInitPal.textMuted,
                fontSize: '11.5px',
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
        theme: _tkbInitPal.tooltipTheme,
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


// Helper untuk merender Grafik Perbandingan Pendapatan, Pengeluaran, & Pendapatan Bersih + Ringkasan Nominal
function renderNetIncomeChartAndSummary(dataset, periodLabelText, axisMode) {
  currentLastPaymentRenderArgs = [dataset, periodLabelText, axisMode];
  const p = getTokabeChartPalette();
  const safeData = dataset || {};
  const categories = Array.isArray(safeData.categories) ? safeData.categories : [];
  const pendapatanArr = Array.isArray(safeData.pendapatan) ? safeData.pendapatan.map(Number) : [];
  const pengeluaranArr = Array.isArray(safeData.pengeluaran) ? safeData.pengeluaran.map(Number) : [];
  const bersihArr = Array.isArray(safeData.pendapatan_bersih) ? safeData.pendapatan_bersih.map(Number) : [];

  const summary = safeData.summary || {};
  const totalPendapatan = Number(summary.total_pendapatan ?? pendapatanArr.reduce((a, b) => a + b, 0));
  const totalBebanBelanja = Number(summary.total_beban_belanja ?? 0);
  const totalBebanSpt = Number(summary.total_beban_spt ?? 0);
  const totalPengeluaran = Number(summary.total_pengeluaran ?? (totalBebanBelanja + totalBebanSpt));
  const pendapatanBersih = Number(summary.pendapatan_bersih ?? (totalPendapatan - totalPengeluaran));

  const bulanIndoList = ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Agu', 'Sep', 'Okt', 'Nov', 'Des'];
  const allVals = [...pendapatanArr, ...pengeluaranArr, ...bersihArr, 0];
  const minVal = Math.min(...allVals, 0);
  const maxVal = Math.max(...allVals, 1000000);

  const isMobileCash = window.innerWidth < 576;

  if (chart) {
    try {
      chart.updateOptions({
        chart: {
          height: isMobileCash ? 280 : 350
        },
        dataLabels: { enabled: false },
        series: [
          {
            name: 'Pendapatan',
            data: pendapatanArr,
            color: p.brand,
          },
          {
            name: 'Pengeluaran (belanja + SPT)',
            data: pengeluaranArr,
            color: p.danger,
          },
          {
            name: 'Pendapatan bersih',
            data: bersihArr,
            color: p.cyan,
          }
        ],
        fill: {
          type: 'gradient',
          gradient: {
            shade: p.isLight ? 'light' : 'dark',
            type: 'vertical',
            shadeIntensity: 0.2,
            opacityFrom: 1,
            opacityTo: 0.78,
            stops: [0, 100]
          }
        },
        xaxis: {
          type: 'category',
          categories: categories,
          labels: {
            hideOverlappingLabels: true,
            rotate: isMobileCash && categories.length > 6 ? -35 : 0,
            formatter: function (value) {
              if (!value || typeof value !== 'string') return value || '';
              if (axisMode === 'month_year' && /^\d{4}-\d{2}$/.test(value)) {
                const mIdx = parseInt(value.split('-')[1], 10) - 1;
                return bulanIndoList[mIdx] || value;
              }
              if (axisMode === 'day_of_month' && /^\d{4}-\d{2}-\d{2}$/.test(value)) {
                return value.split('-')[2];
              }
              if (axisMode === 'short_date' && /^\d{4}-\d{2}-\d{2}$/.test(value)) {
                const parts = value.split('-');
                const mIdx = parseInt(parts[1], 10) - 1;
                return `${parts[2]} ${bulanIndoList[mIdx] || parts[1]}`;
              }
              return value;
            },
            style: { colors: p.textMuted, fontSize: isMobileCash ? '10px' : '11px' }
          }
        },
        yaxis: {
          min: minVal < 0 ? minVal * 1.1 : 0,
          max: maxVal * 1.15,
          tickAmount: isMobileCash ? 4 : 5,
          forceNiceScale: true,
          axisBorder: { show: false },
          axisTicks: { show: false },
          labels: {
            show: true,
            formatter: function (val) {
              return formatRupiahCompact(val);
            },
            style: { colors: p.textMuted, fontSize: isMobileCash ? '10px' : '11px' }
          }
        },
        grid: {
          borderColor: p.grid,
          strokeDashArray: 4,
          xaxis: { lines: { show: false } },
          yaxis: { lines: { show: true } }
        },
        plotOptions: {
          bar: {
            distributed: false,
            borderRadius: 5,
            borderRadiusApplication: 'end',
            columnWidth: categories.length <= 2 ? '32%' : '56%',
            colors: {
              ranges: []
            }
          }
        },
        legend: {
          show: true,
          position: 'top',
          horizontalAlign: 'left',
          fontSize: isMobileCash ? '11px' : '12px',
          fontFamily: 'inherit',
          labels: { colors: p.textSecondary },
          markers: { radius: 12, width: 8, height: 8 },
          itemMargin: { horizontal: isMobileCash ? 6 : 10, vertical: 3 }
        },
        tooltip: {
          theme: p.tooltipTheme,
          shared: true,
          intersect: false,
          y: {
            formatter: function (val) {
              return formatRupiah(val);
            }
          }
        },
        annotations: { points: [], xaxis: [], yaxis: [] }
      });
    } catch (chartErr) {
      console.error('Gagal memperbarui grafik pendapatan bersih:', chartErr);
    }
  }

  const heroEl = document.getElementById('hero-pendapatan-akumulasi');
  if (heroEl) {
    animateCountUp(heroEl, totalPendapatan);
  }

  const heroPill = document.getElementById('hero-period-pill');
  if (heroPill) {
    heroPill.textContent = /^\d{4}$/.test(String(periodLabelText))
      ? ((Number(periodLabelText) === new Date().getFullYear() - 1) ? ('Tahun lalu ' + periodLabelText) : ('Tahun ' + periodLabelText))
      : periodLabelText;
  }

  const elementHarga = document.getElementById('harga');
  if (elementHarga) {
    const netColor = pendapatanBersih < 0 ? 'var(--danger-text)' : 'var(--accent-cyan)';
    elementHarga.innerHTML = `
      <div class="d-flex flex-column gap-2">
        <div class="d-flex justify-content-between align-items-center">
          <span class="fw-medium fs-13" style="color: var(--text-primary);">Total pendapatan (${periodLabelText})</span>
          <span class="fw-bold tabular-nums fs-13" style="color: var(--success-text);">${formatRupiah(totalPendapatan)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="fs-12 ps-2" style="color: var(--text-tertiary);">Beban belanja</span>
          <span class="tabular-nums fs-12" style="color: var(--text-secondary);">${formatRupiah(totalBebanBelanja)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center">
          <span class="fs-12 ps-2" style="color: var(--text-tertiary);">Beban SPT masa (lunas)</span>
          <span class="tabular-nums fs-12" style="color: var(--text-secondary);">${formatRupiah(totalBebanSpt)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1 border-top" style="border-color: var(--border-subtle) !important;">
          <span class="fw-semibold fs-13" style="color: var(--text-primary);">Total pengeluaran (${periodLabelText})</span>
          <span class="fw-bold tabular-nums fs-13" style="color: var(--danger-text);">${formatRupiah(totalPengeluaran)}</span>
        </div>
        <div class="d-flex justify-content-between align-items-center pt-1">
          <span class="fw-semibold fs-13" style="color: var(--text-primary);">Pendapatan bersih (${periodLabelText})</span>
          <span class="fw-bold tabular-nums fs-13.5" style="color: ${netColor};">${formatRupiah(pendapatanBersih)}</span>
        </div>
      </div>
    `;
  }
}

// TimeFrame untuk button
function timeFrame(period) {
  if (!chart && document.querySelector('#stacked_column_chart') && typeof ApexCharts !== 'undefined') {
    chart = new ApexCharts(document.querySelector('#stacked_column_chart'), options);
    chart.render();
  }

  const buttons = document.querySelectorAll('#card-aktivitas-pembayaran .segmented-item.tombol');
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
  const currentYear = now.getFullYear();
  const lastYear = currentYear - 1;
  const netStore = window.netIncomeDataJS || {};

  if (period === 'day') {
    renderNetIncomeChartAndSummary(netStore.day, 'Hari Ini', 'short_date');
  } else if (period === 'week') {
    renderNetIncomeChartAndSummary(netStore.week, 'Minggu Ini', 'short_date');
  } else if (period === 'month') {
    renderNetIncomeChartAndSummary(netStore.month, 'Bulan Ini', 'day_of_month');
  } else if (period === 'year') {
    renderNetIncomeChartAndSummary(netStore.year, String(currentYear), 'month_year');
  } else if (period === 'lastYear') {
    renderNetIncomeChartAndSummary(netStore.lastYear, String(lastYear), 'month_year');
  }
}

// ====================================================================
// PAYMENT ACTIVITY CUSTOM FILTER (TANGGAL, BULAN, TAHUN)
// ====================================================================
var currentPaymentCustomMode = 'date'; // 'date', 'month', 'year', 'range'

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
      const buttons = document.querySelectorAll('#card-aktivitas-pembayaran .segmented-item.tombol');
      buttons.forEach(b => b.classList.remove('active'));
      const btnCustom = document.getElementById('btn-payment-custom-toggle');
      if (btnCustom) {
        btnCustom.classList.add('active');
      }

      updatePaymentPeriodBadgeText('custom', result.period_label);

      const d = result.data || {};
      const label = result.period_label || 'Filter Khusus';
      if (result.filter_type === 'range' || result.filter_type === 'date') {
        renderNetIncomeChartAndSummary(d, label, 'short_date');
      } else if (result.filter_type === 'month') {
        renderNetIncomeChartAndSummary(d, label, 'day_of_month');
      } else if (result.filter_type === 'year') {
        renderNetIncomeChartAndSummary(d, String(d.year || label), 'month_year');
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

var ITEM_PALETTE = [
  '#6B73F0', '#7E85F4', '#5A62DB', '#8B92FF', '#4E55C7',
  '#A0A6FF', '#3D44A6', '#6B7686', '#9AA4B2', '#4A5361'
];

var TAX_COLOR_MAP = {
  'ppn': '#6B73F0',         // Single Accent Indigo
  'pph': '#E3A33B',         // Warm Amber Warning
  'ppn_pph': '#5B9DF0',     // Info Blue
  'tanpa_pajak': '#6B7686', // Slate Neutral
};

var TAX_LABEL_MAP = {
  'ppn': 'PPN',
  'pph': 'PPh',
  'ppn_pph': 'PPN & PPh',
  'tanpa_pajak': 'Tanpa Pajak',
};

var TAX_BADGE_CLASS = {
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

  const p = getTokabeChartPalette();
  const isMobileScreen = window.innerWidth < 576;
  const barCount = top10.length;
  const dynamicHeight = Math.max(170, barCount * 48 + 64);
  const calculatedBarHeight = barCount <= 2 ? (isMobileScreen ? '30px' : '36px') : (barCount <= 4 ? '30px' : '65%');

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
        borderRadius: 6,
        borderRadiusApplication: 'end',
        barHeight: calculatedBarHeight,
        distributed: false,
        dataLabels: {
          position: 'top'
        }
      }
    },
    fill: {
      type: 'gradient',
      gradient: {
        shade: p.isLight ? 'light' : 'dark',
        type: 'horizontal',
        shadeIntensity: 0.25,
        gradientToColors: [p.brand],
        inverseColors: false,
        opacityFrom: 0.9,
        opacityTo: 1,
        stops: [0, 100]
      }
    },
    dataLabels: {
      enabled: true,
      textAnchor: 'end',
      offsetX: isMobileScreen ? -6 : -12,
      formatter: function (val) {
        if (currentItemMetric === 'nominal') {
          return formatRupiahCompact(val);
        } else {
          return new Intl.NumberFormat('id-ID').format(val) + ' unit';
        }
      },
      style: {
        fontSize: isMobileScreen ? '10.5px' : '12px',
        fontWeight: 700,
        colors: ['#FFFFFF']
      }
    },
    series: [{
      name: currentItemMetric === 'nominal' ? 'Total Penjualan' : 'Jumlah Unit Terjual',
      data: seriesValues
    }],
    xaxis: {
      categories: categories,
      tickAmount: isMobileScreen ? 3 : 5,
      labels: {
        hideOverlappingLabels: true,
        formatter: function (val) {
          if (currentItemMetric === 'nominal') {
            return formatRupiahCompact(val);
          } else {
            return new Intl.NumberFormat('id-ID').format(val);
          }
        },
        style: { colors: p.textMuted, fontSize: isMobileScreen ? '10px' : '11px' }
      },
      axisBorder: { show: false },
      axisTicks: { show: false }
    },
    yaxis: {
      labels: {
        maxWidth: isMobileScreen ? 95 : 220,
        style: {
          colors: p.textSecondary,
          fontSize: isMobileScreen ? '11px' : '12.5px',
          fontWeight: 500
        }
      }
    },
    responsive: [
      {
        breakpoint: 576,
        options: {
          xaxis: { tickAmount: 3, labels: { hideOverlappingLabels: true, style: { fontSize: '10px' } } },
          yaxis: { labels: { maxWidth: 95, style: { fontSize: '11px' } } },
          dataLabels: { offsetX: -6, style: { fontSize: '10.5px' } }
        }
      }
    ],
    colors: [p.brandDark],
    legend: { show: false },
    tooltip: {
      theme: p.tooltipTheme,
      custom: function (opts) {
        const item = top10[opts.dataPointIndex];
        if (!item) return '';

        const taxBadgeText = item.jenis_pajak_label || item.jenis_pajak || 'Pajak';
        const qtyFormatted = new Intl.NumberFormat('id-ID').format(item.total_qty) + ' unit';
        const nominalFormatted = formatRupiah(item.total_nominal);
        const trxFormatted = item.total_transaksi ? new Intl.NumberFormat('id-ID').format(item.total_transaksi) + ' transaksi' : '';

        return `
          <div style="background: ${p.surface}; border: 1px solid ${p.border}; border-radius: 10px; padding: 10px 14px; font-family: inherit; min-width: 210px; box-shadow: 0 10px 25px rgba(0,0,0,0.22);">
            <div style="font-weight: 700; font-size: 13px; color: ${p.textPrimary}; margin-bottom: 4px;">${item.label}</div>
            <div style="margin-bottom: 8px;">
              <span class="badge-tint badge-tint-neutral" style="font-size: 11px;">
                ${taxBadgeText}
              </span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 4px; color: ${p.textSecondary};">
              <span>Total Penjualan:</span>
              <span style="font-weight: 700; color: ${p.brand}; font-variant-numeric: tabular-nums;">${nominalFormatted}</span>
            </div>
            <div style="display: flex; justify-content: space-between; font-size: 12px; color: ${p.textSecondary};">
              <span>Kuantitas:</span>
              <span style="font-weight: 600; color: ${p.textPrimary}; font-variant-numeric: tabular-nums;">${qtyFormatted}</span>
            </div>
            ${trxFormatted ? `<div style="font-size: 11px; color: ${p.textMuted}; margin-top: 6px; text-align: right;">${trxFormatted}</div>` : ''}
          </div>
        `;
      }
    },
    grid: {
      borderColor: p.grid,
      strokeDashArray: 4,
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

  const p = getTokabeChartPalette();
  const dynamicTaxColors = {
    'ppn': p.brand,
    'pph': p.warning,
    'ppn_pph': p.cyan,
    'tanpa_pajak': p.neutral,
  };
  const series = pajakItems.map(function (it) {
    return currentItemMetric === 'nominal' ? it.total_nominal : it.total_qty;
  });
  const labels = pajakItems.map(function (it) { return it.label; });
  const sliceColors = pajakItems.map(function (it) {
    return dynamicTaxColors[it.raw_tax] || p.cyan;
  });

  const options = {
    chart: {
      type: 'donut',
      height: 270,
      animations: { enabled: true, speed: 350 }
    },
    series: series,
    labels: labels,
    colors: sliceColors,
    stroke: {
      width: 3,
      colors: [p.surface2]
    },
    plotOptions: {
      pie: {
        startAngle: 0,
        donut: {
          size: '78%',
          background: 'transparent',
          labels: {
            show: true,
            name: {
              show: true,
              fontSize: '11.5px',
              fontFamily: 'inherit',
              color: p.textMuted,
              offsetY: -4
            },
            value: {
              show: true,
              fontSize: '18px',
              fontWeight: 800,
              fontFamily: 'inherit',
              color: p.textPrimary,
              offsetY: 6,
              formatter: function (val) {
                if (currentItemMetric === 'nominal') {
                  return formatRupiahCompact(val);
                } else {
                  return new Intl.NumberFormat('id-ID').format(val) + ' unit';
                }
              }
            },
            total: {
              show: true,
              showAlways: true,
              label: currentItemMetric === 'nominal' ? 'Total penjualan' : 'Total unit',
              color: p.textMuted,
              fontSize: '11.5px',
              fontFamily: 'inherit',
              formatter: function (w) {
                const total = w.globals.seriesTotals.reduce(function (a, b) { return a + b; }, 0);
                if (currentItemMetric === 'nominal') {
                  return formatRupiahCompact(total);
                } else {
                  return new Intl.NumberFormat('id-ID').format(total) + ' unit';
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
      labels: { colors: p.textSecondary },
      markers: {
        radius: 12,
        width: 8,
        height: 8
      },
      itemMargin: { horizontal: 8, vertical: 4 }
    },
    tooltip: {
      theme: p.tooltipTheme,
      y: {
        formatter: function (val) {
          const grandTotal = currentItemMetric === 'nominal' ? totalNominal : totalQty;
          const pct = grandTotal > 0 ? ((val / grandTotal) * 100).toFixed(1).replace('.', ',') + '%' : '0%';
          if (currentItemMetric === 'nominal') {
            return formatRupiah(val) + ' (' + pct + ')';
          } else {
            return new Intl.NumberFormat('id-ID').format(val) + ' unit (' + pct + ')';
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
      <td class="text-center" style="color: var(--text-tertiary); font-size: 12.5px; font-variant-numeric: tabular-nums;">
        ${index + 1}
      </td>
      <td>
        <span style="color: var(--text-primary); font-weight: 700; font-size: 13px;">${item.label}</span>
      </td>
      <td class="text-center">
        <span class="badge-tint badge-tint-neutral">${taxLabel}</span>
      </td>
      <td class="text-center" style="color: var(--text-secondary); font-size: 13px; font-variant-numeric: tabular-nums;">
        ${totalTrx}
      </td>
      <td class="text-center" style="color: var(--text-primary); font-weight: 500; font-size: 13px; font-variant-numeric: tabular-nums;">
        ${new Intl.NumberFormat('id-ID').format(item.total_qty)}
      </td>
      <td class="text-end" style="color: var(--text-primary); font-weight: 700; font-size: 13px; font-variant-numeric: tabular-nums;">
        ${nominalFormatted}
      </td>
      <td class="text-end" style="padding-right: 18px;">
        <div class="d-flex align-items-center justify-content-end gap-2">
          <div style="width: 84px; height: 6px; background: var(--bg-surface-3); border-radius: 9999px; overflow: hidden;">
            <div style="width: ${pctNum}%; height: 100%; background: linear-gradient(90deg, var(--brand-pressed), var(--brand)); border-radius: 9999px;"></div>
          </div>
          <span style="min-width: 44px; color: var(--text-secondary); font-size: 12px; font-weight: 600; font-variant-numeric: tabular-nums;">${pct}</span>
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
