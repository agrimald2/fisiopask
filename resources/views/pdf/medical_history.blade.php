<!DOCTYPE html>
<html lang="es">
  <head>
    <meta charset="utf-8">
    <title>INS PERU / OMS</title>
    <style type="text/css">
      @font-face {
        font-family: SourceSansPro;
        src: url(SourceSansPro-Regular.ttf);
      }
      @page
        {
            size:  auto;   /* auto es el valor inicial */
            margin: 0mm;  /* afecta el margen en la configuración de impresión */
        }

      .clearfix:after {
        content: "";
        display: table;
        clear: both;
      }

      a {
        color: #0087C3;
        text-decoration: none;
      }

      body {
        position: relative;
        width: 21cm;
        height: 27.94cm;
        margin: 0 auto;
        color: #000000;
        border: 1px solid #046f80;
        border-bottom: none;
        background: #FFFFFF;
        font-family: Arial, sans-serif;
        font-size: 15px;
      }

      header {
        padding: 10px 0;
        height: 87px;
      }

      header img {
        width: 100%;
      }
      .hc_number{
        text-align: right;
        padding-right: 1cm;
        padding-top: 5px;
        padding-bottom: 5px;
        background: #046f80;
        color: white;
        width: 10%;
        margin-left: auto;
        margin-top: 15px;
        font-weight: 700;
        font-size: unset;
        margin-bottom: 5px;
      }
      .patient_data_container{
        width: 100%;
        height: 9cm;
        border: 1px solid #046f80;
        display: flex;
        }
      .section_title{
        background-color: #046f80;
        color: white;
        font-weight: 700;
        height: 100%;
        width: 0.7cm;
        writing-mode: vertical-rl;
        text-align: center;
        align-items: center;
        display: flex;
        justify-content: center
    }
    .section_content{
    width: 20.3cm;
    }
    .content_div{
        height: 50%;
        padding-left: 10px;
        padding-top: 10px;
    }
    .row_data{
        height: 50%;
        display: flex;
    }
    .col-25{
        width: 25%
    }
    .col-50{
        width: 50%
    }
    .col-33{
        width: 33.33%;
    }
    .flex{
        display: flex;
    }
    .border_test{
        border: 1px solid black;
    }
    .fields{
        height: 50%;
        color: #046f80;
        font-size: 0.95rem;
        font-weight: 600
    }
    .inputs{
        height: 50%;
        color: black;
        font-size: 0.85rem;
        text-transform: uppercase;
    }
    .text-center{
        text-align: left;
        justify-content: center;
        align-items: center;
    }
    .separator_title{
        padding-left: 0.7cm;
        color: #046f80;
        text-align: center
    }
    hr{
        border: 1px solid #046f80;
        width: 80%;
    }
    .other_rows{
        padding-left: 0.7cm;
    }
    .other_rows h3{
        color: #48757cd1;
    }
    .other_rows p{
        width: 95%;
        background-color: #046f8099;
        padding: 6px;
    }
    </style>
  </head>
  <body>
    <header class="clearfix">
        <img src="{{ URL::to('/') }}/assets/HC_header.png" alt="" srcset="">
    </header>
    <div class="hc_number">
        {{ $code }}
    </div>
    <main>
        <!--INFORMACIÓN DEL PACIENTE-->
        <div class="patient_data_container">
            <div class="section_title">
                DATOS DEL PACIENTE
            </div>
            <div class="section_content">
                <div class="content_div" style="border-bottom: 0.5px solid #046f80;">
                    <div class="row_data">
                        <div class="col-25 ">
                            <div class="fields text-center">
                                APELLIDO PATERNO
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->lastname1 }}
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                APELLIDO MATERNO
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->lastname2 }}
                            </div>
                        </div>
                        <div class="col-50 ">
                            <div class="fields text-center">
                                NOMBRE COMPLETO
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->name }}
                            </div>
                        </div>
                    </div>
                    <div class="row_data">
                        <div class="col-50 flex ">
                            <div class="col-33">
                                <div class="fields text-center">
                                    SEX
                                </div>
                                <div class="inputs text-center">
                                    {{ $patient->sex }}
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    EDAD
                                </div>
                                <div class="inputs text-center">
                                    {{ $age }}
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    FECHA NAC.
                                </div>
                                <div class="inputs text-center">
                                    {{ $patient->birth_date }}
                                </div>
                            </div>
                        </div>
                        <div class="col-50 flex ">
                            <div class="col-33">
                                <div class="fields text-center">
                                    EST. CIVIL
                                </div>
                                <div class="inputs text-center">
                                    {{ $patient->status }}
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    TIPO
                                </div>
                                <div class="inputs text-center">
                                    {{ $patient->document_type }}
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    NUMERO
                                </div>
                                <div class="inputs text-center">
                                    {{ $patient->dni }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content_div">
                    <div class="row_data">
                        <div class="col-25 ">
                            <div class="fields text-center">
                                SEGURO DE SALUD
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->insurance }}
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                GRADO DE INSTRUCCIÓN
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->education }}
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                OCUPACIÓN
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->ocupation }}
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                RELIGIÓN
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->religion }}
                            </div>
                        </div>
                    </div>
                    <div class="row_data">
                        <div class="col-25 ">
                            <div class="fields text-center">
                                DISTRITO
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->district }}
                            </div>
                        </div>
                        <div class="col-50 ">
                            <div class="fields text-center">
                                DOMICILIO
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->address }}
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                CELULAR
                            </div>
                            <div class="inputs text-center">
                                {{ $patient->phone }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!--CAMPOS EDITABLES-->
        <div class="separator_title">
            <h3>
                INFORMACIÓN MÉDICA
            </h3>
        </div>
        <hr>

        @for ($i = 0; $i < count($data); $i++)
        @if ($data[$i]["type"] == 2)
        <div class="flex">
            @for ($j = 0; $j < 2; $j++)
            <div class="other_rows" style="width: 50%">
                <h3>
                    {{ $data[$i + $j]["name"] }}:
                </h3>
                <p style="width: 90%">
                    {{ $data[$i + $j]["value"] }}
                </p>
            </div>  
            @endfor
            @php
                $i++
            @endphp
        </div>
        @else
        <div class="other_rows">
            <h3>
                {{ $data[$i]["name"] }}:
            </h3>
            <p>
                {{ $data[$i]["value"] }}
            </p>
        </div>            
        @endif
        @endfor
    </main>
    <footer>

    </footer>
  </body>
</html>
