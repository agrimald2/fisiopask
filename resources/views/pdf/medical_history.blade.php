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
        padding-left: 0.7cm
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
        <img src="{{ URL::to('/') }}/img/HC_header.png" alt="" srcset="">
    </header>
    <div class="hc_number">
        000001
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
                                COLONIA
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                APELLIDO MATERNO
                            </div>
                            <div class="inputs text-center">
                                BARETTO
                            </div>
                        </div>
                        <div class="col-50 ">
                            <div class="fields text-center">
                                NOMBRE COMPLETO
                            </div>
                            <div class="inputs text-center">
                                MARCELO GABRIEL
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
                                    M
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    EDAD
                                </div>
                                <div class="inputs text-center">
                                    21
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    FECHA NAC.
                                </div>
                                <div class="inputs text-center">
                                    26/06/2001
                                </div>
                            </div>
                        </div>
                        <div class="col-50 flex ">
                            <div class="col-33">
                                <div class="fields text-center">
                                    EST. CIVIL
                                </div>
                                <div class="inputs text-center">
                                    VIUDO
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    TIPO
                                </div>
                                <div class="inputs text-center">
                                    SALVOCONDUCTO
                                </div>
                            </div>
                            <div class="col-33">
                                <div class="fields text-center">
                                    NUMERO
                                </div>
                                <div class="inputs text-center">
                                    99988878
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
                                FISIOSALUD EPS
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                GRADO DE INSTRUCCIÓN
                            </div>
                            <div class="inputs text-center">
                                TIU COAGUILA
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                OCUPACIÓN
                            </div>
                            <div class="inputs text-center">
                                DOTERO PROFESIONAL
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                RELIGIÓN
                            </div>
                            <div class="inputs text-center">
                                SEGUIDOR DE IGOR
                            </div>
                        </div>
                    </div>
                    <div class="row_data">
                        <div class="col-25 ">
                            <div class="fields text-center">
                                DISTRITO
                            </div>
                            <div class="inputs text-center">
                                CALLAO HUNDIDO
                            </div>
                        </div>
                        <div class="col-50 ">
                            <div class="fields text-center">
                                DOMICILIO
                            </div>
                            <div class="inputs text-center">
                                Am Wriezener bhf, 10243
                            </div>
                        </div>
                        <div class="col-25 ">
                            <div class="fields text-center">
                                CELULAR
                            </div>
                            <div class="inputs text-center">
                                954123456
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

        <!-- @TODO LOPEAR ACÁ CON DATOS DEL BACK -->
        <div class="other_rows">
            <h3>
                MOTIVO DE CONSULTA:
            </h3>
            <p>
                Se jodió la rodilla el inútil este por estar jugando fuchibol y se sacó la entreputa
            </p>
        </div>
        <div class="other_rows">
            <h3>
                MOTIVO DE CONSULTA:
            </h3>
            <p>
                Se jodió la rodilla el inútil este por estar jugando fuchibol y se sacó la entreputa
            </p>
        </div>
    </main>
    <footer>

    </footer>
  </body>
</html>
