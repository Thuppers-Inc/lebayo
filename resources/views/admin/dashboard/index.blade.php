@extends('admin.layouts.master')

@section('title', 'Tableau de bord - Analytics')

@section('content')
              <div class="row">
                <div class="col-lg-8 mb-4 order-0">
                  <div class="card">
                    <div class="d-flex align-items-end row">
                      <div class="col-sm-7">
                        <div class="card-body">
            <h5 class="card-title text-primary">Bienvenue sur Lebayo! 🎉</h5>
                          <p class="mb-4">
              Vous avez <span class="fw-bold">72%</span> de ventes en plus aujourd'hui. Consultez votre nouveau badge dans 
              votre profil.
                          </p>
            <a href="javascript:;" class="btn btn-sm btn-outline-primary">Voir les badges</a>
                        </div>
                      </div>
                      <div class="col-sm-5 text-center text-sm-left">
                        <div class="card-body pb-0 px-0 px-md-4">
                          <img
              src="{{ asset('admin-assets/assets/img/illustrations/man-with-laptop-light.png') }}"
                            height="140"
                            alt="View Badge User"
                            data-app-dark-img="illustrations/man-with-laptop-dark.png"
                            data-app-light-img="illustrations/man-with-laptop-light.png"
                          />
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="col-lg-4 col-md-4 order-1">
                  <div class="row">
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                  src="{{ asset('admin-assets/assets/img/icons/unicons/chart-success.png') }}"
                                alt="chart success"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt3"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt3">
                  <a class="dropdown-item" href="javascript:void(0);">Voir plus</a>
                  <a class="dropdown-item" href="javascript:void(0);">Supprimer</a>
                              </div>
                            </div>
                          </div>
            <span class="fw-semibold d-block mb-1">Bénéfices</span>
            <h3 class="card-title mb-2">12 628 €</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +72.80%</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                              <img
                  src="{{ asset('admin-assets/assets/img/icons/unicons/wallet-info.png') }}"
                                alt="Credit Card"
                                class="rounded"
                              />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt6"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt6">
                  <a class="dropdown-item" href="javascript:void(0);">Voir plus</a>
                  <a class="dropdown-item" href="javascript:void(0);">Supprimer</a>
                              </div>
                            </div>
                          </div>
            <span>Ventes</span>
            <h3 class="card-title text-nowrap mb-1">4 679 €</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.42%</small>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!-- Total Revenue -->
                <div class="col-12 col-lg-8 order-2 order-md-3 order-lg-2 mb-4">
                  <div class="card">
                    <div class="row row-bordered g-0">
                      <div class="col-md-8">
          <h5 class="card-header m-0 me-2 pb-3">Revenus Totaux</h5>
                        <div id="totalRevenueChart" class="px-2"></div>
                      </div>
                      <div class="col-md-4">
                        <div class="card-body">
                          <div class="text-center">
                            <div class="dropdown">
                              <button
                                class="btn btn-sm btn-outline-primary dropdown-toggle"
                                type="button"
                                id="growthReportId"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                  2024
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                  <a class="dropdown-item" href="javascript:void(0);">2023</a>
                  <a class="dropdown-item" href="javascript:void(0);">2022</a>
                                <a class="dropdown-item" href="javascript:void(0);">2021</a>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div id="growthChart"></div>
          <div class="text-center fw-semibold pt-3 mb-2">62% Croissance de l'entreprise</div>

                        <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                <small>2024</small>
                <h6 class="mb-0">32.5k €</h6>
                            </div>
                          </div>
                          <div class="d-flex">
                            <div class="me-2">
                              <span class="badge bg-label-info p-2"><i class="bx bx-wallet text-info"></i></span>
                            </div>
                            <div class="d-flex flex-column">
                <small>2023</small>
                <h6 class="mb-0">41.2k €</h6>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Total Revenue -->
                <div class="col-12 col-md-8 col-lg-4 order-3 order-md-2">
                  <div class="row">
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                <img src="{{ asset('admin-assets/assets/img/icons/unicons/paypal.png') }}" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt4"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                  <a class="dropdown-item" href="javascript:void(0);">Voir plus</a>
                  <a class="dropdown-item" href="javascript:void(0);">Supprimer</a>
                              </div>
                            </div>
                          </div>
            <span class="d-block mb-1">Paiements</span>
            <h3 class="card-title text-nowrap mb-2">2 456 €</h3>
                          <small class="text-danger fw-semibold"><i class="bx bx-down-arrow-alt"></i> -14.82%</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-6 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="card-title d-flex align-items-start justify-content-between">
                            <div class="avatar flex-shrink-0">
                <img src="{{ asset('admin-assets/assets/img/icons/unicons/cc-primary.png') }}" alt="Credit Card" class="rounded" />
                            </div>
                            <div class="dropdown">
                              <button
                                class="btn p-0"
                                type="button"
                                id="cardOpt1"
                                data-bs-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false"
                              >
                                <i class="bx bx-dots-vertical-rounded"></i>
                              </button>
                              <div class="dropdown-menu" aria-labelledby="cardOpt1">
                  <a class="dropdown-item" href="javascript:void(0);">Voir plus</a>
                  <a class="dropdown-item" href="javascript:void(0);">Supprimer</a>
                              </div>
                            </div>
                          </div>
                          <span class="fw-semibold d-block mb-1">Transactions</span>
            <h3 class="card-title mb-2">14 857 €</h3>
                          <small class="text-success fw-semibold"><i class="bx bx-up-arrow-alt"></i> +28.14%</small>
                        </div>
                      </div>
                    </div>
                    <div class="col-12 mb-4">
                      <div class="card">
                        <div class="card-body">
                          <div class="d-flex justify-content-between flex-sm-row flex-column gap-3">
                            <div class="d-flex flex-sm-column flex-row align-items-start justify-content-between">
                              <div class="card-title">
                  <h5 class="text-nowrap mb-2">Rapport de Profil</h5>
                  <span class="badge bg-label-warning rounded-pill">Année 2024</span>
                              </div>
                              <div class="mt-sm-auto">
                                <small class="text-success text-nowrap fw-semibold"
                                  ><i class="bx bx-chevron-up"></i> 68.2%</small
                                >
                  <h3 class="mb-0">84 686k €</h3>
                              </div>
                            </div>
                            <div id="profileReportChart"></div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <div class="row">
                <!-- Order Statistics -->
                <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                      <div class="card-title mb-0">
          <h5 class="m-0 me-2">Statistiques des Commandes</h5>
          <small class="text-muted">42.82k Ventes Totales</small>
                      </div>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="orederStatistics"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="orederStatistics">
            <a class="dropdown-item" href="javascript:void(0);">Tout sélectionner</a>
            <a class="dropdown-item" href="javascript:void(0);">Actualiser</a>
            <a class="dropdown-item" href="javascript:void(0);">Partager</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="d-flex flex-column align-items-center gap-1">
                          <h2 class="mb-2">8,258</h2>
            <span>Total Commandes</span>
                        </div>
                        <div id="orderStatisticsChart"></div>
                      </div>
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-primary"
                              ><i class="bx bx-mobile-alt"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <h6 class="mb-0">Électronique</h6>
                <small class="text-muted">Mobile, Écouteurs, TV</small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">82.5k</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-success"><i class="bx bx-closet"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <h6 class="mb-0">Mode</h6>
                <small class="text-muted">T-shirt, Jeans, Chaussures</small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">23.8k</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-info"><i class="bx bx-home-alt"></i></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <h6 class="mb-0">Décoration</h6>
                <small class="text-muted">Art, Salle à manger</small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">849k</small>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-3">
                            <span class="avatar-initial rounded bg-label-secondary"
                              ><i class="bx bx-football"></i
                            ></span>
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <h6 class="mb-0">Sports</h6>
                <small class="text-muted">Football, Kit Cricket</small>
                            </div>
                            <div class="user-progress">
                              <small class="fw-semibold">99</small>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Order Statistics -->

                <!-- Expense Overview -->
                <div class="col-md-6 col-lg-4 order-1 mb-4">
                  <div class="card h-100">
                    <div class="card-header">
                      <ul class="nav nav-pills" role="tablist">
                        <li class="nav-item">
                          <button
                            type="button"
                            class="nav-link active"
                            role="tab"
                            data-bs-toggle="tab"
                            data-bs-target="#navs-tabs-line-card-income"
                            aria-controls="navs-tabs-line-card-income"
                            aria-selected="true"
                          >
              Revenus
                          </button>
                        </li>
                        <li class="nav-item">
            <button type="button" class="nav-link" role="tab">Dépenses</button>
                        </li>
                        <li class="nav-item">
            <button type="button" class="nav-link" role="tab">Bénéfices</button>
                        </li>
                      </ul>
                    </div>
                    <div class="card-body px-0">
                      <div class="tab-content p-0">
                        <div class="tab-pane fade show active" id="navs-tabs-line-card-income" role="tabpanel">
                          <div class="d-flex p-4 pt-3">
                            <div class="avatar flex-shrink-0 me-3">
                <img src="{{ asset('admin-assets/assets/img/icons/unicons/wallet.png') }}" alt="User" />
                            </div>
                            <div>
                <small class="text-muted d-block">Solde Total</small>
                              <div class="d-flex align-items-center">
                  <h6 class="mb-0 me-1">459.10 €</h6>
                                <small class="text-success fw-semibold">
                                  <i class="bx bx-chevron-up"></i>
                                  42.9%
                                </small>
                              </div>
                            </div>
                          </div>
                          <div id="incomeChart"></div>
                          <div class="d-flex justify-content-center pt-4 gap-2">
                            <div class="flex-shrink-0">
                              <div id="expensesOfWeek"></div>
                            </div>
                            <div>
                <p class="mb-n1 mt-1">Dépenses Cette Semaine</p>
                <small class="text-muted">39 € de moins que la semaine dernière</small>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
                <!--/ Expense Overview -->

                <!-- Transactions -->
                <div class="col-md-6 col-lg-4 order-2 mb-4">
                  <div class="card h-100">
                    <div class="card-header d-flex align-items-center justify-content-between">
                      <h5 class="card-title m-0 me-2">Transactions</h5>
                      <div class="dropdown">
                        <button
                          class="btn p-0"
                          type="button"
                          id="transactionID"
                          data-bs-toggle="dropdown"
                          aria-haspopup="true"
                          aria-expanded="false"
                        >
                          <i class="bx bx-dots-vertical-rounded"></i>
                        </button>
                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="transactionID">
            <a class="dropdown-item" href="javascript:void(0);">28 derniers jours</a>
            <a class="dropdown-item" href="javascript:void(0);">Mois dernier</a>
            <a class="dropdown-item" href="javascript:void(0);">Année dernière</a>
                        </div>
                      </div>
                    </div>
                    <div class="card-body">
                      <ul class="p-0 m-0">
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/paypal.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Paypal</small>
                <h6 class="mb-0">Envoi d'argent</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+82.6</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/wallet.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <small class="text-muted d-block mb-1">Portefeuille</small>
                              <h6 class="mb-0">Mac'D</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+270.69</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/chart.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <small class="text-muted d-block mb-1">Transfert</small>
                <h6 class="mb-0">Remboursement</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+637.91</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/cc-success.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <small class="text-muted d-block mb-1">Carte de Crédit</small>
                <h6 class="mb-0">Commande de nourriture</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">-838.71</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex mb-4 pb-1">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/wallet.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                <small class="text-muted d-block mb-1">Portefeuille</small>
                              <h6 class="mb-0">Starbucks</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">+203.33</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                        <li class="d-flex">
                          <div class="avatar flex-shrink-0 me-3">
              <img src="{{ asset('admin-assets/assets/img/icons/unicons/cc-warning.png') }}" alt="User" class="rounded" />
                          </div>
                          <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                            <div class="me-2">
                              <small class="text-muted d-block mb-1">Mastercard</small>
                <h6 class="mb-0">Commande de nourriture</h6>
                            </div>
                            <div class="user-progress d-flex align-items-center gap-1">
                              <h6 class="mb-0">-92.45</h6>
                <span class="text-muted">EUR</span>
                            </div>
                          </div>
                        </li>
                      </ul>
                    </div>
                  </div>
                </div>
                <!--/ Transactions -->
              </div>
@endsection

@push('scripts')
                  <script>
  // Initialisation des graphiques ApexCharts
  document.addEventListener('DOMContentLoaded', function() {
    // Chart Revenue
    if (document.querySelector('#totalRevenueChart')) {
      const totalRevenueChart = new ApexCharts(document.querySelector('#totalRevenueChart'), {
        series: [{
          name: 'Revenus',
          data: [31, 40, 28, 51, 42, 109, 100]
        }],
        chart: {
          height: 350,
          type: 'line',
          toolbar: { show: false }
        },
        colors: ['#003049'],
        stroke: { curve: 'smooth', width: 3 },
        xaxis: {
          categories: ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Jun', 'Jul']
        }
      });
      totalRevenueChart.render();
    }

    // Chart Growth
    if (document.querySelector('#growthChart')) {
      const growthChart = new ApexCharts(document.querySelector('#growthChart'), {
        series: [62],
        chart: {
          height: 240,
          type: 'radialBar',
        },
        colors: ['#003049'],
        plotOptions: {
          radialBar: {
            hollow: { size: '70%' },
            dataLabels: {
              name: { show: false },
              value: {
                fontSize: '24px',
                fontWeight: 600,
                color: '#003049',
                formatter: function (val) {
                  return val + '%';
                }
              }
            }
          }
        }
      });
      growthChart.render();
    }

    // Chart Income
    if (document.querySelector('#incomeChart')) {
      const incomeChart = new ApexCharts(document.querySelector('#incomeChart'), {
        series: [{
          name: 'Revenus',
          data: [31, 40, 28, 51, 42, 109, 100]
        }],
        chart: {
          height: 215,
          type: 'area',
          toolbar: { show: false }
        },
        colors: ['#003049'],
        fill: {
          type: 'gradient',
          gradient: {
            shade: 'light',
            type: 'vertical',
            opacityFrom: 0.4,
            opacityTo: 0.1,
          }
        }
      });
      incomeChart.render();
    }

    // Chart Order Statistics
    if (document.querySelector('#orderStatisticsChart')) {
      const orderChart = new ApexCharts(document.querySelector('#orderStatisticsChart'), {
        series: [85],
        chart: {
          height: 165,
          type: 'donut',
        },
        colors: ['#003049', '#F77F00', '#FCBF49', '#EAE2B7'],
        plotOptions: {
          pie: {
            donut: {
              size: '75%',
              labels: {
                show: true,
                name: { show: false },
                value: {
                  show: true,
                  fontSize: '18px',
                  fontWeight: 600,
                  color: '#566a7f',
                  formatter: function (val) {
                    return parseInt(val) + '%';
                  }
                }
              }
            }
          }
        },
        legend: { show: false }
      });
      orderChart.render();
    }

    // Chart Profile Report
    if (document.querySelector('#profileReportChart')) {
      const profileChart = new ApexCharts(document.querySelector('#profileReportChart'), {
        series: [{
          name: 'Profile',
          data: [20, 30, 25, 35, 30, 40, 35]
        }],
        chart: {
          height: 80,
          type: 'line',
          toolbar: { show: false },
          sparkline: { enabled: true }
        },
        colors: ['#003049'],
        stroke: { curve: 'smooth', width: 2 }
      });
      profileChart.render();
    }

    // Chart Expenses of Week
    if (document.querySelector('#expensesOfWeek')) {
      const expensesChart = new ApexCharts(document.querySelector('#expensesOfWeek'), {
        series: [65],
        chart: {
          width: 60,
          height: 60,
          type: 'radialBar'
        },
        colors: ['#003049'],
        plotOptions: {
          radialBar: {
            hollow: { size: '50%' },
            dataLabels: { show: false }
          }
        }
      });
      expensesChart.render();
    }
  });
</script>
@endpush
