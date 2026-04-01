// Chart.js bar chart keluhan per kategori
window.addEventListener('DOMContentLoaded', function() {
  var barEl = document.getElementById('category-bar-chart');
  if (barEl && window.Chart && window.categoryBarLabels && window.categoryBarData) {
    const total = window.categoryBarTotal || window.categoryBarData.reduce((a,b)=>a+b,0);
    // Generate color array
    const colorList = [
      'rgba(0,230,255,0.7)',
      'rgba(255,224,102,0.7)',
      'rgba(0,255,179,0.7)',
      'rgba(208,70,239,0.7)',
      'rgba(255,99,132,0.7)',
      'rgba(54,162,235,0.7)',
      'rgba(255,206,86,0.7)',
      'rgba(75,192,192,0.7)',
      'rgba(153,102,255,0.7)',
      'rgba(255,159,64,0.7)'
    ];
    const borderList = [
      '#00e6ff','#ffe066','#00ffb3','#d946ef','#ff6384','#36a2eb','#ffce56','#4bc0c0','#9966ff','#ff9f40'
    ];
    const bgColors = window.categoryBarLabels.map((_,i)=>colorList[i%colorList.length]);
    const borderColors = window.categoryBarLabels.map((_,i)=>borderList[i%borderList.length]);
    new Chart(barEl.getContext('2d'), {
      type: 'bar',
      data: {
        labels: window.categoryBarLabels,
        datasets: [{
          label: 'Jumlah Keluhan',
          data: window.categoryBarData,
          backgroundColor: bgColors,
          borderColor: borderColors,
          borderWidth: 2,
          borderRadius: 8,
        }]
      },
      options: {
        responsive: true,
        plugins: {
          legend: { display: false },
          tooltip: {
            callbacks: {
              label: function(ctx) {
                const val = ctx.parsed.y;
                const percent = total ? ((val/total)*100).toFixed(1) : 0;
                return val + ' keluhan (' + percent + '%)';
              }
            }
          }
        },
        scales: {
          y: {
            beginAtZero: true,
            ticks: {
              precision:0
            }
          }
        }
      }
    });
  }
});
