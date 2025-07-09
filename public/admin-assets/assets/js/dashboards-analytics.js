/**
 * Dashboard Analytics - Lebayo Admin
 */

'use strict';

(function () {
  let cardColor, headingColor, axisColor, shadeColor, borderColor;

  // Couleurs du thème Lebayo
  cardColor = '#ffffff';
  headingColor = '#566a7f';
  axisColor = '#a1acb8';
  borderColor = '#eceef1';

  // Couleurs personnalisées Lebayo
  const lebayoColors = {
    primary: '#003049',
    secondary: '#D62828',
    accent: '#F77F00',
    warning: '#FCBF49',
    light: '#EAE2B7',
    success: '#28a745',
    info: '#17a2b8',
    danger: '#dc3545'
  };

  // Configuration générale des graphiques
  const chartConfig = {
    chart: {
      fontFamily: 'Public Sans, sans-serif',
      toolbar: { show: false },
      animations: {
        enabled: true,
        easing: 'easeinout',
        speed: 800,
        animateGradually: {
          enabled: true,
          delay: 150
        }
      }
    },
    colors: [lebayoColors.primary, lebayoColors.accent, lebayoColors.secondary, lebayoColors.warning],
    grid: {
      borderColor: borderColor,
      padding: {
        top: 0,
        bottom: -8,
        left: 20,
        right: 20
      }
    },
    legend: {
      labels: {
        colors: axisColor
      }
    },
    stroke: {
      curve: 'smooth'
    },
    dataLabels: {
      enabled: false
    }
  };

  // Graphique des commerces (donut chart)
  const commerceChartEl = document.querySelector('#commerceChart');
  if (commerceChartEl) {
    const commerceChart = new ApexCharts(commerceChartEl, {
      series: window.commerceData || [10, 1], // Données par défaut
      chart: {
        type: 'donut',
        height: 65,
        sparkline: {
          enabled: true
        }
      },
      colors: [lebayoColors.primary, '#e3f2fd'],
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false
      },
      plotOptions: {
        pie: {
          donut: {
            size: '70%'
          }
        }
      }
    });
    commerceChart.render();
  }

  // Graphique des produits (donut chart)
  const productChartEl = document.querySelector('#productChart');
  if (productChartEl) {
    const productChart = new ApexCharts(productChartEl, {
      series: window.productData || [3, 8], // Données par défaut
      chart: {
        type: 'donut',
        height: 65,
        sparkline: {
          enabled: true
        }
      },
      colors: [lebayoColors.accent, '#e3f2fd'],
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false
      },
      plotOptions: {
        pie: {
          donut: {
            size: '70%'
          }
        }
      }
    });
    productChart.render();
  }

  // Graphique des statistiques (bar chart)
  const statsChartEl = document.querySelector('#statsChart');
  if (statsChartEl) {
    const statsChart = new ApexCharts(statsChartEl, {
      series: [{
        name: 'Statistiques',
        data: window.statsData || [11, 11, 25, 23] // Données par défaut
      }],
      chart: {
        type: 'bar',
        height: 200,
        toolbar: {
          show: false
        }
      },
      plotOptions: {
        bar: {
          borderRadius: 4,
          columnWidth: '60%',
          distributed: true
        }
      },
      colors: [lebayoColors.primary, lebayoColors.accent, lebayoColors.warning, lebayoColors.secondary],
      xaxis: {
        categories: ['Commerces', 'Produits', 'Catégories', 'Utilisateurs'],
        labels: {
          style: {
            fontSize: '12px',
            colors: axisColor
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        labels: {
          style: {
            fontSize: '12px',
            colors: axisColor
          }
        }
      },
      grid: {
        borderColor: borderColor,
        yaxis: {
          lines: {
            show: false
          }
        }
      },
      legend: {
        show: false
      },
      dataLabels: {
        enabled: false
      }
    });
    statsChart.render();
  }

  // Graphique d'évolution temporelle (line chart)
  const evolutionChartEl = document.querySelector('#evolutionChart');
  if (evolutionChartEl) {
    const evolutionChart = new ApexCharts(evolutionChartEl, {
      series: [{
        name: 'Commerces',
        data: [5, 7, 8, 10, 11]
      }, {
        name: 'Produits',
        data: [3, 5, 6, 8, 11]
      }],
      chart: {
        type: 'line',
        height: 250,
        toolbar: {
          show: false
        }
      },
      colors: [lebayoColors.primary, lebayoColors.accent],
      stroke: {
        curve: 'smooth',
        width: 3
      },
      xaxis: {
        categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai'],
        labels: {
          style: {
            fontSize: '12px',
            colors: axisColor
          }
        },
        axisBorder: {
          show: false
        },
        axisTicks: {
          show: false
        }
      },
      yaxis: {
        labels: {
          style: {
            fontSize: '12px',
            colors: axisColor
          }
        }
      },
      grid: {
        borderColor: borderColor,
        strokeDashArray: 5
      },
      legend: {
        show: true,
        position: 'top',
        horizontalAlign: 'left',
        labels: {
          colors: axisColor
        }
      },
      markers: {
        size: 5,
        colors: [lebayoColors.primary, lebayoColors.accent],
        strokeColors: cardColor,
        strokeWidth: 2,
        hover: {
          size: 7
        }
      }
    });
    evolutionChart.render();
  }

  // Graphique en secteurs pour les types de commerce
  const commerceTypesChartEl = document.querySelector('#commerceTypesChart');
  if (commerceTypesChartEl) {
    const commerceTypesChart = new ApexCharts(commerceTypesChartEl, {
      series: window.commerceTypesData || [40, 30, 20, 10], // Données par défaut
      chart: {
        type: 'pie',
        height: 300
      },
      labels: window.commerceTypesLabels || ['Restaurants', 'Pharmacies', 'Supermarchés', 'Autres'],
      colors: [lebayoColors.primary, lebayoColors.accent, lebayoColors.secondary, lebayoColors.warning],
      legend: {
        show: true,
        position: 'bottom',
        labels: {
          colors: axisColor
        }
      },
      responsive: [{
        breakpoint: 480,
        options: {
          chart: {
            width: 200
          },
          legend: {
            position: 'bottom'
          }
        }
      }],
      plotOptions: {
        pie: {
          donut: {
            labels: {
              show: true,
              name: {
                show: true,
                fontSize: '16px',
                fontWeight: 600,
                color: headingColor
              },
              value: {
                show: true,
                fontSize: '14px',
                fontWeight: 400,
                color: axisColor
              }
            }
          }
        }
      }
    });
    commerceTypesChart.render();
  }

  // Graphique radial pour les performances
  const performanceChartEl = document.querySelector('#performanceChart');
  if (performanceChartEl) {
    const performanceChart = new ApexCharts(performanceChartEl, {
      series: [75, 60, 85, 90],
      chart: {
        height: 300,
        type: 'radialBar',
      },
      plotOptions: {
        radialBar: {
          dataLabels: {
            name: {
              fontSize: '22px',
            },
            value: {
              fontSize: '16px',
            },
            total: {
              show: true,
              label: 'Performance',
              formatter: function (w) {
                return '77%'
              }
            }
          }
        }
      },
      labels: ['Commerces', 'Produits', 'Satisfaction', 'Livraisons'],
      colors: [lebayoColors.primary, lebayoColors.accent, lebayoColors.success, lebayoColors.info]
    });
    performanceChart.render();
  }

  // Animation des cartes de statistiques
  const statCards = document.querySelectorAll('.stat-card');
  statCards.forEach((card, index) => {
    card.style.animationDelay = `${index * 0.1}s`;
    card.classList.add('animate-fade-in');
  });

  // Animation des éléments de la liste d'activités
  const activityItems = document.querySelectorAll('.activity-item');
  activityItems.forEach((item, index) => {
    item.style.animationDelay = `${index * 0.05}s`;
    item.classList.add('animate-slide-in-right');
  });

  // Mise à jour des graphiques avec les données réelles (si disponibles)
  if (window.dashboardData) {
    // Mettre à jour les graphiques avec les données du serveur
    console.log('Données du dashboard chargées:', window.dashboardData);
  }

  // Gestion du redimensionnement des graphiques
  window.addEventListener('resize', function() {
    // Redimensionner les graphiques si nécessaire
    if (typeof ApexCharts !== 'undefined') {
      setTimeout(() => {
        window.dispatchEvent(new Event('resize'));
      }, 100);
    }
  });
})();

// Fonction utilitaire pour formater les nombres
function formatNumber(num) {
  if (num >= 1000000) {
    return (num / 1000000).toFixed(1) + 'M';
  } else if (num >= 1000) {
    return (num / 1000).toFixed(1) + 'K';
  }
  return num.toString();
}

// Fonction utilitaire pour animer les compteurs
function animateCounter(element, target, duration = 1000) {
  const start = parseInt(element.textContent) || 0;
  const increment = (target - start) / (duration / 16);
  let current = start;
  
  const timer = setInterval(() => {
    current += increment;
    if (current >= target) {
      element.textContent = target;
      clearInterval(timer);
    } else {
      element.textContent = Math.floor(current);
    }
  }, 16);
}

// Initialisation des compteurs animés
document.addEventListener('DOMContentLoaded', function() {
  const counters = document.querySelectorAll('.stat-card-value');
  counters.forEach(counter => {
    const target = parseInt(counter.textContent);
    if (target > 0) {
      counter.textContent = '0';
      setTimeout(() => {
        animateCounter(counter, target, 1500);
      }, 500);
    }
  });
});
