<!DOCTYPE html>
<html lang="id">

<head>

    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>Detail Rectifier - PLN Icon Plus</title>


    <!-- =====================================================
         BOOTSTRAP ICONS
    ====================================================== -->

    <link
        rel="stylesheet"
        href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css"
    >


    <!-- =====================================================
         GOOGLE FONT
    ====================================================== -->

    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
        rel="stylesheet"
    >


    <!-- =====================================================
         CSS
    ====================================================== -->

    @vite([
        'resources/css/sidebar.css',
        'resources/css/rectifier-card.css',
        'resources/css/rectifier-detail.css'
    ])

</head>


<body>

<div class="app-container">


    <!-- =====================================================
         SIDEBAR
    ====================================================== -->

    <aside
        class="sidebar"
        id="sidebar"
    >

        <!-- LOGO -->

        <div class="sidebar-logo">

            <img
                src="{{ asset('images/logo-iconplus.png') }}"
                alt="PLN Icon Plus"
            >

        </div>


        <!-- =================================================
             DASHBOARD
        ================================================== -->

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


        <!-- =================================================
             GENERAL
        ================================================== -->

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


        <!-- =================================================
             AKUN
        ================================================== -->

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
             DETAIL CONTENT
        ================================================== -->

        <div class="rectifier-detail-content">


            <!-- =================================================
                 TOP ACTION
            ================================================== -->

            <div class="detail-top">


                <!-- BACK -->

                <a
                    href="{{ url('/rectifier-card') }}"
                    class="detail-back"
                    title="Kembali"
                >

                    <i class="bi bi-arrow-left"></i>

                </a>



                <!-- EDIT -->

                <button
                    type="button"
                    class="btn-edit"
                    onclick="editRectifier()"
                >

                    <i class="bi bi-pencil-fill"></i>

                    Edit Form

                </button>

            </div>



            <!-- =================================================
                 INFORMATION + MODULE + PHOTO
                 
                 STRUKTUR:
                 
                 LEFT  = Information + Module
                 RIGHT = Photo
                 
                 Photo akan sejajar dengan dua card kiri.
            ================================================== -->

            <div class="detail-top-grid">


                <!-- =================================================
                     LEFT COLUMN
                ================================================== -->

                <div class="detail-left-column">


                    <!-- =============================================
                         INFORMATION RECTIFIER
                    ============================================== -->

                    <div class="detail-card information-card">


                        <div class="detail-card-title">
                            Information Rectifier
                        </div>


                        <div class="information-grid">


                            <!-- LEFT INFORMATION -->

                            <div class="information-left">


                                <div class="info-row">

                                    <span class="info-label">
                                        Merk
                                    </span>

                                    <span class="info-value">
                                        Emerson NetSure 531
                                    </span>

                                </div>


                                <div class="info-row">

                                    <span class="info-label">
                                        Type
                                    </span>

                                    <span class="info-value">
                                        531 A91-S1
                                    </span>

                                </div>


                                <div class="info-row">

                                    <span class="info-label">
                                        Type POP
                                    </span>

                                    <span class="info-value">
                                        POP-SB
                                    </span>

                                </div>


                                <!-- SERIAL NUMBER -->

                                <div class="serial-box">

                                    <span class="serial-label">
                                        Serial Number (SN)
                                    </span>


                                    <div class="serial-value">

                                        <span>
                                            210107074221987000E8
                                        </span>


                                        <button
                                            type="button"
                                            onclick="copySerial()"
                                            title="Copy Serial Number"
                                        >

                                            <i class="bi bi-clipboard"></i>

                                        </button>

                                    </div>

                                </div>

                            </div>



                            <!-- RIGHT INFORMATION -->

                            <div class="information-right">


                                <div class="info-small-row">

                                    <span class="info-label">
                                        Jumlah Module
                                    </span>

                                    <span class="info-value">
                                        5
                                    </span>

                                </div>


                                <div class="info-small-row">

                                    <span class="info-label">
                                        Kapasitas / Module
                                    </span>

                                    <span class="info-value">
                                        40 A DC / 13 A AC
                                    </span>

                                </div>


                                <div class="info-small-row">

                                    <span class="info-label">
                                        Building
                                    </span>

                                    <span class="info-value">
                                        Shelter CKC
                                    </span>

                                </div>

                            </div>

                        </div>

                    </div>



                    <!-- =============================================
                         SERIAL NUMBER MODULE
                    ============================================== -->

                    <div class="detail-card module-card">


                        <div class="section-heading">

                            <span>
                                Serial Number Module
                            </span>


                            <small>
                                5 dari 9 module terpasang
                            </small>

                        </div>


                        <div class="module-table">


                            <!-- MODULE 1 -->

                            <div class="module-row">

                                <div>
                                    Module 1
                                </div>

                                <div>
                                    ENES 03180302527
                                </div>

                            </div>


                            <!-- MODULE 2 -->

                            <div class="module-row">

                                <div>
                                    Module 2
                                </div>

                                <div>
                                    ENES 03180302527
                                </div>

                            </div>


                            <!-- MODULE 3 -->

                            <div class="module-row">

                                <div>
                                    Module 3
                                </div>

                                <div>
                                    ENES 03180302527
                                </div>

                            </div>


                            <!-- MODULE 4 -->

                            <div class="module-row">

                                <div>
                                    Module 4
                                </div>

                                <div>
                                    ENES 03180302527
                                </div>

                            </div>


                            <!-- MODULE 5 -->

                            <div class="module-row">

                                <div>
                                    Module 5
                                </div>

                                <div>
                                    ENES 03180302527
                                </div>

                            </div>

                        </div>

                    </div>

                </div>



                <!-- =================================================
                     PHOTO RECTIFIER
                ================================================== -->

                <div class="detail-card photo-card">


                    <div class="photo-header">


                        <div class="photo-icon">

                            <i class="bi bi-camera-fill"></i>

                        </div>


                        <span>
                            Photo Rectifier
                        </span>

                    </div>



                    <div class="photo-wrapper">


                        <img
                            src="{{ asset('images/rectifier.png') }}"
                            alt="Photo Rectifier"
                            onerror="
                                this.style.display='none';
                                document.getElementById('photoPlaceholder').style.display='flex';
                            "
                        >


                        <div
                            id="photoPlaceholder"
                            class="photo-placeholder"
                            style="display:none;"
                        >

                            <i class="bi bi-image"></i>

                            <span>
                                Foto Rectifier
                            </span>

                        </div>

                    </div>

                </div>

            </div>



            <!-- =====================================================
                 OUTPUT
            ====================================================== -->

            <div class="detail-card output-card">


                <div class="section-heading">

                    <span>
                        Output
                    </span>

                </div>



                <div class="output-grid">


                    <!-- =================================================
                         OUTPUT KIRI
                    ================================================== -->

                    <table class="output-table">


                        <thead>

                            <tr>

                                <th>
                                    MCB
                                </th>

                                <th>
                                    Merk
                                </th>

                                <th>
                                    Kapasitas
                                </th>

                                <th>
                                    Peruntukan
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <tr>

                                <td>
                                    MCB1
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    BAT I
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB2
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    BAT II
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB3
                                </td>

                                <td>
                                    CHNT
                                </td>

                                <td>
                                    63 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB4
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB5
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB6
                                </td>

                                <td>
                                    APL
                                </td>

                                <td>
                                    63 A
                                </td>

                                <td>
                                    BMS
                                </td>

                            </tr>


                        </tbody>

                    </table>



                    <!-- =================================================
                         OUTPUT KANAN
                    ================================================== -->

                    <table class="output-table">


                        <thead>

                            <tr>

                                <th>
                                    MCB
                                </th>

                                <th>
                                    Merk
                                </th>

                                <th>
                                    Kapasitas
                                </th>

                                <th>
                                    Peruntukan
                                </th>

                            </tr>

                        </thead>


                        <tbody>


                            <tr>

                                <td>
                                    MCB7
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    BAT I
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB8
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    BAT II
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB9
                                </td>

                                <td>
                                    CHNT
                                </td>

                                <td>
                                    63 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB10
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB11
                                </td>

                                <td>
                                    Nader
                                </td>

                                <td>
                                    64 A
                                </td>

                                <td>
                                    SPARE
                                </td>

                            </tr>


                            <tr>

                                <td>
                                    MCB12
                                </td>

                                <td>
                                    APL
                                </td>

                                <td>
                                    63 A
                                </td>

                                <td>
                                    BMS
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>



            <!-- =====================================================
                 CHECKLIST RECTIFIER
            ====================================================== -->

            <div class="detail-card checklist-card">


                <!-- TITLE -->

                <div class="section-heading checklist-heading">

                    Checklist Rectifier

                </div>



                <!-- =================================================
                     POP + TANGGAL
                ================================================== -->

                <div class="checklist-row">


                    <div class="checklist-label">
                        POP
                    </div>


                    <div class="checklist-value">
                        : Payo Selincah Bawah
                    </div>


                    <div class="checklist-label">
                        Tanggal
                    </div>


                    <div class="checklist-value">
                        : <strong>17 Februari 2026</strong>
                    </div>

                </div>



                <!-- =================================================
                     PIC
                ================================================== -->

                <div class="checklist-row">


                    <div class="checklist-label">
                        PIC
                    </div>


                    <div class="checklist-value">
                        : Bherian
                    </div>


                    <div class="checklist-label">
                        &nbsp;
                    </div>


                    <div class="checklist-value">
                        &nbsp;
                    </div>

                </div>



                <!-- =================================================
                     RECTIFIER 1
                ================================================== -->

                <div class="checklist-section-title">

                    Rectifier 1

                </div>



                <!-- =================================================
                     SPECIFICATION TABLE
                ================================================== -->

                <div class="spec-table-wrapper">


                    <table class="spec-table">


                        <tbody>


                            <!-- ROW 1 -->

                            <tr>

                                <th>
                                    Merk
                                </th>

                                <td>
                                    : Emerson
                                </td>

                                <th>
                                    Type modul power
                                </th>

                                <td>
                                    : R48-2000e3
                                </td>

                            </tr>


                            <!-- ROW 2 -->

                            <tr>

                                <th>
                                    Type
                                </th>

                                <td>
                                    : VERTIF NETSURE 531 A91
                                </td>

                                <th>
                                    Kapasitas recti
                                </th>

                                <td>
                                    : 200 A
                                </td>

                            </tr>


                            <!-- ROW 3 -->

                            <tr>

                                <th>
                                    SN Rectifier
                                </th>

                                <td>
                                    : 210107074221987000E8
                                </td>

                                <th>
                                    Beban (A)
                                </th>

                                <td>
                                    : 36,5 A
                                </td>

                            </tr>


                            <!-- ROW 4 -->

                            <tr>

                                <th>
                                    Couple / tidak
                                </th>

                                <td>
                                    : COUPLE
                                </td>

                                <th>
                                    Status utilisasi
                                </th>

                                <td>
                                    : 18,2 %<br>
                                    (Beban : Kapasitas Modul x 100)
                                </td>

                            </tr>


                            <!-- ROW 5 -->

                            <tr>

                                <th>
                                    Type modul controller
                                </th>

                                <td>
                                    : MCU M800D
                                </td>

                                <th>
                                    Status pengecekan
                                </th>

                                <td>
                                    : OK
                                </td>

                            </tr>


                            <!-- ROW 6 -->

                            <tr>

                                <th>
                                    Jumlah slot modul
                                </th>

                                <td>
                                    : 9
                                </td>

                                <th>
                                    Status pelaksana
                                </th>

                                <td>
                                    : OK
                                </td>

                            </tr>


                            <!-- ROW 7 -->

                            <tr>

                                <th>
                                    Kapasitas modul terpasang
                                </th>

                                <td>
                                    : 40 A dc (13A ac)
                                </td>

                                <th>
                                    Utilitas recti
                                </th>

                                <td>
                                    :
                                </td>

                            </tr>


                            <!-- ROW 8 -->

                            <tr>

                                <th>
                                    Jumlah modul power terpasang
                                </th>

                                <td>
                                    : 5
                                </td>

                                <th>
                                    &nbsp;
                                </th>

                                <td>
                                    &nbsp;
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>


        </div>

    </main>

</div>



<!-- =====================================================
     JAVASCRIPT
====================================================== -->

<script>


/* =====================================================
   SIDEBAR TOGGLE
===================================================== */

document.addEventListener(
    'DOMContentLoaded',
    function () {


        const sidebarToggle =
            document.getElementById('sidebarToggle');


        const sidebarIcon =
            sidebarToggle?.querySelector('i');


        if (sidebarToggle) {


            sidebarToggle.addEventListener(
                'click',
                function () {


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

                }
            );

        }

    }
);



/* =====================================================
   COPY SERIAL NUMBER
===================================================== */

function copySerial() {


    const serial =
        '210107074221987000E8';


    if (
        navigator.clipboard &&
        navigator.clipboard.writeText
    ) {


        navigator.clipboard
            .writeText(serial)
            .then(function () {

                alert(
                    'Serial number berhasil disalin.'
                );

            })
            .catch(function () {

                alert(
                    'Serial number: ' + serial
                );

            });


    } else {


        alert(
            'Serial number: ' + serial
        );

    }

}



/* =====================================================
   EDIT FORM
===================================================== */

function editRectifier() {

    alert(
        'Fitur Edit Form akan tersedia pada tahap berikutnya.'
    );

}

</script>


</body>

</html>