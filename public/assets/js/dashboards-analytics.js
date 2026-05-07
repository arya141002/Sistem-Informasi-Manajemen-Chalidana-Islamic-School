/**
 * Dashboard Analytics
 */

'use strict';

(function () {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  cardColor = config.colors.white;
  headingColor = config.colors.headingColor;
  axisColor = config.colors.axisColor;
  borderColor = config.colors.borderColor;

  // Dynamic Greeting + Weather
  // --------------------------------------------------------------------
  const greetingTitleEl = document.querySelector('#dashboardGreetingTitle');
  const greetingSubtitleEl = document.querySelector('#dashboardGreetingSubtitle');
  const weatherInfoEl = document.querySelector('#dashboardWeatherInfo');

  if (greetingTitleEl && greetingSubtitleEl && weatherInfoEl) {
    const userName = greetingTitleEl.dataset.userName || 'Admin';
    const now = new Date();
    const hour = now.getHours();

    let waktu = 'night';
    if (hour >= 5 && hour < 11) waktu = 'morning';
    else if (hour >= 11 && hour < 15) waktu = 'afternoon';
    else if (hour >= 15 && hour < 18) waktu = 'evening';

    const tanggalFormat = new Intl.DateTimeFormat('id-ID', {
      weekday: 'long',
      day: '2-digit',
      month: 'long',
      year: 'numeric'
    }).format(now);

    greetingTitleEl.textContent = `Good ${waktu}, ${userName}`;
    greetingSubtitleEl.textContent = `Today is ${tanggalFormat}. Wishing you a productive day.`;

    const weatherCodeToText = code => {
      const map = {
        0: 'Clear',
        1: 'Mainly Clear',
        2: 'Partly Cloudy',
        3: 'Overcast',
        45: 'Fog',
        48: 'Dense Fog',
        51: 'Light Drizzle',
        53: 'Drizzle',
        55: 'Heavy Drizzle',
        61: 'Light Rain',
        63: 'Rain',
        65: 'Heavy Rain',
        71: 'Light Snow',
        73: 'Snow',
        75: 'Heavy Snow',
        80: 'Light Rain Showers',
        81: 'Rain Showers',
        82: 'Heavy Rain Showers',
        95: 'Thunderstorm',
        96: 'Thunderstorm with Hail',
        99: 'Severe Thunderstorm with Hail'
      };
      return map[code] || 'Unknown Weather';
    };

    const setWeatherFallback = () => {
      weatherInfoEl.textContent = `Weather information is unavailable right now. It is currently ${waktu}.`;
    };

    if ('geolocation' in navigator) {
      navigator.geolocation.getCurrentPosition(
        async position => {
          try {
            const { latitude, longitude } = position.coords;
            const weatherUrl = `https://api.open-meteo.com/v1/forecast?latitude=${latitude}&longitude=${longitude}&current=temperature_2m,weather_code&timezone=auto`;
            const res = await fetch(weatherUrl);
            if (!res.ok) {
              setWeatherFallback();
              return;
            }

            const data = await res.json();
            const current = data.current || {};
            const temp = Number(current.temperature_2m);
            const weatherText = weatherCodeToText(current.weather_code);

            if (Number.isFinite(temp)) {
              weatherInfoEl.textContent = `Current weather: ${weatherText}, ${temp.toFixed(1)}°C.`;
            } else {
              weatherInfoEl.textContent = `Current weather: ${weatherText}.`;
            }
          } catch (error) {
            setWeatherFallback();
          }
        },
        () => {
          setWeatherFallback();
        },
        { enableHighAccuracy: false, timeout: 7000, maximumAge: 600000 }
      );
    } else {
      setWeatherFallback();
    }
  }

  // Total Visitor Chart
  // --------------------------------------------------------------------
  const totalRevenueChartEl = document.querySelector('#totalRevenueChart');
  if (typeof totalRevenueChartEl !== undefined && totalRevenueChartEl !== null) {
    const visitorPeriodEl = document.querySelector('#visitorPeriod');
    const visitorStartDateEl = document.querySelector('#visitorStartDate');
    const visitorEndDateEl = document.querySelector('#visitorEndDate');
    const visitorApplyBtnEl = document.querySelector('#visitorApplyBtn');
    const visitorCustomRangeEl = document.querySelector('#visitorCustomRange');
    const visitorTotalCountEl = document.querySelector('#visitorTotalCount');
    const statsUrl = totalRevenueChartEl.dataset.visitorUrl;

    const totalRevenueChart = new ApexCharts(totalRevenueChartEl, {
      series: [{ name: 'Visitor', data: [] }],
      chart: {
        height: 300,
        type: 'bar',
        toolbar: { show: false }
      },
      plotOptions: {
        bar: {
          horizontal: false,
          borderRadius: 8,
          columnWidth: '45%'
        }
      },
      colors: [config.colors.primary],
      dataLabels: { enabled: false },
      grid: {
        borderColor: borderColor,
        padding: {
          top: 0,
          bottom: -8,
          left: 20,
          right: 20
        }
      },
      xaxis: {
        categories: [],
        labels: {
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        },
        axisTicks: { show: false },
        axisBorder: { show: false }
      },
      yaxis: {
        min: 0,
        forceNiceScale: true,
        labels: {
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        }
      },
      legend: { show: false }
    });
    totalRevenueChart.render();

    const toggleCustomRange = () => {
      if (!visitorPeriodEl || !visitorCustomRangeEl) return;
      if (visitorPeriodEl.value === 'custom') {
        visitorCustomRangeEl.classList.remove('d-none');
      } else {
        visitorCustomRangeEl.classList.add('d-none');
      }
    };

    const loadVisitorChart = async () => {
      if (!statsUrl || !visitorPeriodEl) return;

      const period = visitorPeriodEl.value || 'daily';
      const params = new URLSearchParams({ period: period });
      if (period === 'custom') {
        if (visitorStartDateEl && visitorStartDateEl.value) params.append('start_date', visitorStartDateEl.value);
        if (visitorEndDateEl && visitorEndDateEl.value) params.append('end_date', visitorEndDateEl.value);
      }

      const response = await fetch(`${statsUrl}?${params.toString()}`);
      if (!response.ok) return;

      const payload = await response.json();
      totalRevenueChart.updateOptions({
        xaxis: { categories: payload.labels || [] }
      });
      totalRevenueChart.updateSeries([
        {
          name: 'Visitor',
          data: payload.data || []
        }
      ]);

      if (visitorTotalCountEl) {
        visitorTotalCountEl.textContent = Number(payload.total || 0).toLocaleString('id-ID');
      }
    };

    if (visitorPeriodEl) {
      visitorPeriodEl.addEventListener('change', () => {
        toggleCustomRange();
        if (visitorPeriodEl.value !== 'custom') {
          loadVisitorChart();
        }
      });
    }

    if (visitorApplyBtnEl) {
      visitorApplyBtnEl.addEventListener('click', () => {
        loadVisitorChart();
      });
    }

    toggleCustomRange();
    loadVisitorChart();
  }

  // Growth Chart - Radial Bar Chart
  // --------------------------------------------------------------------
  const growthChartEl = document.querySelector('#growthChart'),
    growthChartOptions = {
      series: [78],
      labels: ['Growth'],
      chart: {
        height: 240,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          size: 150,
          offsetY: 10,
          startAngle: -150,
          endAngle: 150,
          hollow: {
            size: '55%'
          },
          track: {
            background: cardColor,
            strokeWidth: '100%'
          },
          dataLabels: {
            name: {
              offsetY: 15,
              color: headingColor,
              fontSize: '15px',
              fontWeight: '600',
              fontFamily: 'Public Sans'
            },
            value: {
              offsetY: -25,
              color: headingColor,
              fontSize: '22px',
              fontWeight: '500',
              fontFamily: 'Public Sans'
            }
          }
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: 'dark',
          shadeIntensity: 0.5,
          gradientToColors: [config.colors.primary],
          inverseColors: true,
          opacityFrom: 1,
          opacityTo: 0.6,
          stops: [30, 70, 100]
        }
      },
      stroke: {
        dashArray: 5
      },
      grid: {
        padding: {
          top: -35,
          bottom: -10
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      }
    };
  if (typeof growthChartEl !== undefined && growthChartEl !== null) {
    const growthChart = new ApexCharts(growthChartEl, growthChartOptions);
    growthChart.render();
  }

  // Profit Report Line Chart
  // --------------------------------------------------------------------
  const profileReportChartEl = document.querySelector('#profileReportChart'),
    profileReportChartConfig = {
      chart: {
        height: 80,
        // width: 175,
        type: 'line',
        toolbar: {
          show: false
        },
        dropShadow: {
          enabled: true,
          top: 10,
          left: 5,
          blur: 3,
          color: config.colors.warning,
          opacity: 0.15
        },
        sparkline: {
          enabled: true
        }
      },
      grid: {
        show: false,
        padding: {
          right: 8
        }
      },
      colors: [config.colors.warning],
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 5,
        curve: 'smooth'
      },
      series: [
        {
          data: [110, 270, 145, 245, 205, 285]
        }
      ],
      xaxis: {
        show: false,
        lines: {
          show: false
        },
        labels: {
          show: false
        },
        axisBorder: {
          show: false
        }
      },
      yaxis: {
        show: false
      }
    };
  if (typeof profileReportChartEl !== undefined && profileReportChartEl !== null) {
    const profileReportChart = new ApexCharts(profileReportChartEl, profileReportChartConfig);
    profileReportChart.render();
  }

  // Order Statistics Chart
  // --------------------------------------------------------------------
  const chartOrderStatistics = document.querySelector('#orderStatisticsChart'),
    orderChartConfig = {
      chart: {
        height: 165,
        width: 130,
        type: 'donut'
      },
      labels: ['Electronic', 'Sports', 'Decor', 'Fashion'],
      series: [85, 15, 50, 50],
      colors: [config.colors.primary, config.colors.secondary, config.colors.info, config.colors.success],
      stroke: {
        width: 5,
        colors: cardColor
      },
      dataLabels: {
        enabled: false,
        formatter: function (val, opt) {
          return parseInt(val) + '%';
        }
      },
      legend: {
        show: false
      },
      grid: {
        padding: {
          top: 0,
          bottom: 0,
          right: 15
        }
      },
      plotOptions: {
        pie: {
          donut: {
            size: '75%',
            labels: {
              show: true,
              value: {
                fontSize: '1.5rem',
                fontFamily: 'Public Sans',
                color: headingColor,
                offsetY: -15,
                formatter: function (val) {
                  return parseInt(val) + '%';
                }
              },
              name: {
                offsetY: 20,
                fontFamily: 'Public Sans'
              },
              total: {
                show: true,
                fontSize: '0.8125rem',
                color: axisColor,
                label: 'Weekly',
                formatter: function (w) {
                  return '38%';
                }
              }
            }
          }
        }
      }
    };
  if (typeof chartOrderStatistics !== undefined && chartOrderStatistics !== null) {
    const statisticsChart = new ApexCharts(chartOrderStatistics, orderChartConfig);
    statisticsChart.render();
  }

  // Income Chart - Area chart
  // --------------------------------------------------------------------
  const incomeChartEl = document.querySelector('#incomeChart'),
    incomeChartConfig = {
      series: [
        {
          data: [24, 21, 30, 22, 42, 26, 35, 29]
        }
      ],
      chart: {
        height: 215,
        parentHeightOffset: 0,
        parentWidthOffset: 0,
        toolbar: {
          show: false
        },
        type: 'area'
      },
      dataLabels: {
        enabled: false
      },
      stroke: {
        width: 2,
        curve: 'smooth'
      },
      legend: {
        show: false
      },
      markers: {
        size: 6,
        colors: 'transparent',
        strokeColors: 'transparent',
        strokeWidth: 4,
        discrete: [
          {
            fillColor: config.colors.white,
            seriesIndex: 0,
            dataPointIndex: 7,
            strokeColor: config.colors.primary,
            strokeWidth: 2,
            size: 6,
            radius: 8
          }
        ],
        hover: {
          size: 7
        }
      },
      colors: [config.colors.primary],
      fill: {
        type: 'gradient',
        gradient: {
          shade: shadeColor,
          shadeIntensity: 0.6,
          opacityFrom: 0.5,
          opacityTo: 0.25,
          stops: [0, 95, 100]
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 3,
        padding: {
          top: -20,
          bottom: -8,
          left: -10,
          right: 8
        }
      },
      xaxis: {
        categories: ['', 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul'],
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        },
        labels: {
          show: true,
          style: {
            fontSize: '13px',
            colors: axisColor
          }
        }
      },
      yaxis: {
        labels: {
          show: false
        },
        min: 10,
        max: 50,
        tickAmount: 4
      }
    };
  if (typeof incomeChartEl !== undefined && incomeChartEl !== null) {
    const incomeChart = new ApexCharts(incomeChartEl, incomeChartConfig);
    incomeChart.render();
  }

  // Expenses Mini Chart - Radial Chart
  // --------------------------------------------------------------------
  const weeklyExpensesEl = document.querySelector('#expensesOfWeek'),
    weeklyExpensesConfig = {
      series: [65],
      chart: {
        width: 60,
        height: 60,
        type: 'radialBar'
      },
      plotOptions: {
        radialBar: {
          startAngle: 0,
          endAngle: 360,
          strokeWidth: '8',
          hollow: {
            margin: 2,
            size: '45%'
          },
          track: {
            strokeWidth: '50%',
            background: borderColor
          },
          dataLabels: {
            show: true,
            name: {
              show: false
            },
            value: {
              formatter: function (val) {
                return '$' + parseInt(val);
              },
              offsetY: 5,
              color: '#697a8d',
              fontSize: '13px',
              show: true
            }
          }
        }
      },
      fill: {
        type: 'solid',
        colors: config.colors.primary
      },
      stroke: {
        lineCap: 'round'
      },
      grid: {
        padding: {
          top: -10,
          bottom: -15,
          left: -10,
          right: -10
        }
      },
      states: {
        hover: {
          filter: {
            type: 'none'
          }
        },
        active: {
          filter: {
            type: 'none'
          }
        }
      }
    };
  if (typeof weeklyExpensesEl !== undefined && weeklyExpensesEl !== null) {
    const weeklyExpenses = new ApexCharts(weeklyExpensesEl, weeklyExpensesConfig);
    weeklyExpenses.render();
  }
})();
