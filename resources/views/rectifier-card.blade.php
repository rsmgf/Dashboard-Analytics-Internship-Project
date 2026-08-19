<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Card Rectifier - PLN Icon Plus</title>

    <!-- BOOTSTRAP ICONS -->
    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >

    <!-- GOOGLE FONT - POPPINS -->
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >

    <!-- CSS -->
    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-card.css'
    ])

</head>


<body>

<div class="app-container">

    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside class="sidebar" id="sidebar">

        <!-- LOGO -->
        <div class="sidebar-logo">

            <img
                src="{{ asset('images/logo-iconplus.png') }}"
                alt="PLN Icon Plus"
            >

        </div>


        <!-- DASHBOARD -->
        <div class="sidebar-section">

            <div class="section-title">
                Dashboard
            </div>

            <a
                href="/dashboard"
                class="sidebar-menu"
            >

                <i class="bi bi-grid-fill"></i>

                <span>
                    Dashboard
                </span>

            </a>

        </div>


        <!-- GENERAL -->
        <div class="sidebar-section">

            <div class="section-title">
                General
            </div>


            <a
                href="/pops"
                class="sidebar-menu active"
            >

                <i class="bi bi-shield-fill"></i>

                <span>
                    POP
                </span>

            </a>


            <a
                href="/rma"
                class="sidebar-menu"
            >

                <i class="bi bi-file-earmark-text-fill"></i>

                <span>
                    Form RMA
                </span>

            </a>

        </div>


        <!-- AKUN -->
        <div class="sidebar-section">

            <div class="section-title">
                Akun
            </div>


            <a
                href="#"
                class="sidebar-menu"
            >

                <i class="bi bi-box-arrow-right"></i>

                <span>
                    Log Out
                </span>

            </a>

        </div>

    </aside>


    <!-- =====================================================
         MAIN CONTENT
    ====================================================== -->

    <main class="main-content">


        <!-- =================================================
             TOPBAR
        ================================================== -->

        <header
            class="topbar"
            style="
                display: flex;
                justify-content: flex-start;
                align-items: center;
                gap: 12px;
                width: 100%;
                padding-right: 20px;
            "
        >

            <!-- SIDEBAR TOGGLE -->
            <button
                type="button"
                class="sidebar-toggle"
                id="sidebarToggle"
                title="Buka / Tutup Sidebar"
            >

                <i class="bi bi-list"></i>

            </button>


            <!-- USER PROFILE -->
            <div
                class="user-profile"
                style="
                    margin-left: auto;
                    display: flex;
                    align-items: center;
                    gap: 12px;
                    cursor: pointer;
                "
            >

                <!-- NAMA USER -->
                <span
                    style="
                        font-weight: 600;
                        font-size: 14px;
                        color: #333;
                    "
                >
                    {{ Auth::check() ? Auth::user()->name : 'Nama User' }}
                </span>


                <!-- AVATAR -->
                <div
                    style="
                        width: 38px;
                        height: 38px;
                        background-color: #007bff;
                        color: white;
                        border-radius: 50%;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                    "
                >

                    <i
                        class="bi bi-person-fill"
                        style="font-size: 20px;"
                    ></i>

                </div>

            </div>

        </header>


        <!-- =================================================
             CONTENT
        ================================================== -->

        <div class="rectifier-content">


            <!-- =================================================
                 LAST UPDATED
            ================================================== -->

            <div class="last-updated">

                <a
                    href="{{ url('/pops') }}"
                    class="back-button"
                    title="Kembali ke List POP"
                >

                    <i class="bi bi-arrow-left"></i>

                </a>


                <span>
                    Last updated by Liqa - 07 Aug 2026, 08:00 WIB
                </span>

            </div>


            <!-- =================================================
                 DUMMY DATA
            ================================================== -->

            @php

                $rectifiers = [

                    [
                        'number' => 1,
                        'type' => 'EMERSON NetSure 531',
                        'model' => '531 A91-S1',
                        'serial' => '210107074221987000E8',
                    ],

                    [
                        'number' => 2,
                        'type' => 'EMERSON NetSure 701 A51',
                        'model' => 'PS48300-3A/3200',
                        'serial' => '21024011292098000005',
                    ],

                    [
                        'number' => 3,
                        'type' => 'EMERSON NetSure 531',
                        'model' => '531 A91-S1',
                        'serial' => '210107074221987000E8',
                    ],

                    [
                        'number' => 4,
                        'type' => 'EMERSON NetSure 701 A51',
                        'model' => 'PS48300-3A/3200',
                        'serial' => '21024011292098000005',
                    ],

                    [
                        'number' => 5,
                        'type' => 'EMERSON NetSure 531',
                        'model' => '531 A91-S1',
                        'serial' => '210107074221987000E8',
                    ],

                    [
                        'number' => 6,
                        'type' => 'EMERSON NetSure 701 A51',
                        'model' => 'PS48300-3A/3200',
                        'serial' => '21024011292098000005',
                    ],

                ];

            @endphp


            <!-- =================================================
                 RECTIFIER GRID
            ================================================== -->

            <div class="rectifier-grid">

                @foreach ($rectifiers as $rectifier)

                    <div class="rectifier-card">


                        <!-- HEADER -->
                        <div class="rectifier-card-header">

                            <div class="checklist-icon">

                                <i class="bi bi-file-earmark-text-fill"></i>

                            </div>


                            <div class="checklist-title">

                                <h3>
                                    Checklist Rectifier
                                </h3>

                                <p>
                                    Data Rectifier
                                </p>

                            </div>


                            <span class="rectifier-number">
                                Rectifier #{{ $rectifier['number'] }}
                            </span>

                        </div>


                        <!-- INFORMATION -->
                        <div class="rectifier-information">

                            <div class="equipment-info">
                                {{ $rectifier['type'] }}
                            </div>

                            <div class="equipment-info">
                                {{ $rectifier['model'] }}
                            </div>

                            <div class="equipment-info serial">
                                {{ $rectifier['serial'] }}
                            </div>

                        </div>


                        <!-- META -->
                        <div class="rectifier-meta">

                            <div class="meta-item">

                                <i class="bi bi-calendar-fill"></i>

                                <span>
                                    07 Aug 2026
                                </span>

                            </div>


                            <div class="meta-item">

                                <i class="bi bi-geo-alt-fill"></i>

                                <span>
                                    Jambi
                                </span>

                            </div>

                        </div>


                        <!-- DETAIL -->
                        <div class="rectifier-card-footer">

                            <a
                                href="{{ url('/rectifier-detail')}}"
                                class="detail-button"
                            >

                                <span>
                                    Detail
                                </span>

                                <i class="bi bi-chevron-right"></i>

                            </a>

                        </div>


                    </div>

                @endforeach

            </div>

        </div>

    </main>

</div>


<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>

document.addEventListener('DOMContentLoaded', function () {

    const sidebarToggle =
        document.getElementById('sidebarToggle');

    const sidebarIcon =
        sidebarToggle?.querySelector('i');


    if (sidebarToggle) {

        sidebarToggle.addEventListener('click', function () {

            document.body.classList.toggle(
                'sidebar-collapsed'
            );


            if (
                document.body.classList.contains(
                    'sidebar-collapsed'
                )
            ) {

                sidebarIcon.classList.replace(
                    'bi-list',
                    'bi-chevron-right'
                );

            } else {

                sidebarIcon.classList.replace(
                    'bi-chevron-right',
                    'bi-list'
                );

            }

        });

    }

});

</script>


</body>

</html>